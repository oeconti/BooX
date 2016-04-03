USE `oconti-PR`;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS AutoreLibro;
DROP TABLE IF EXISTS LibroInsegnamento;
DROP TABLE IF EXISTS Utente;
DROP TABLE IF EXISTS Messaggio;
DROP TABLE IF EXISTS NonInEvidenza;
DROP TABLE IF EXISTS Vendita;
DROP TABLE IF EXISTS Ricerca;
DROP TABLE IF EXISTS Libro;
DROP TABLE IF EXISTS Descrizione;
DROP TABLE IF EXISTS Autore;
DROP TABLE IF EXISTS Editore;
DROP TABLE IF EXISTS CorsoDiLaurea;
DROP TABLE IF EXISTS Insegnamento;
DROP TABLE IF EXISTS Universita;

#creazione delle tabelle

/* Creazione della tabella Utente */

CREATE TABLE Utente(
	email VARCHAR(50),
	nome VARCHAR(50) NOT NULL,
	cognome VARCHAR(50) NOT NULL,
	password VARCHAR(100) NOT NULL,
	dataDiNascita DATE NOT NULL,
	corso VARCHAR(100),
	universita VARCHAR(50),
	PRIMARY KEY(email),
	FOREIGN KEY (corso,universita) REFERENCES CorsoDiLaurea(nome,universita) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella messaggio */

CREATE TABLE Messaggio(
	tmstmp TIMESTAMP,
	testo VARCHAR(320),
	mittente VARCHAR(50),
	destinatario VARCHAR(50),
	PRIMARY KEY (tmstmp, mittente, destinatario),
	FOREIGN KEY (mittente) REFERENCES Utente(email) ON UPDATE CASCADE,
	FOREIGN KEY (destinatario) REFERENCES Utente(email) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella annunci non in evidenza */

CREATE TABLE NonInEvidenza(
	id INTEGER AUTO_INCREMENT,
	testo VARCHAR(360) default null,
	tmstmp TIMESTAMP NOT NULL,
	motivazione BOOLEAN,
	utente VARCHAR(50) NOT NULL,
	libro BIGINT NOT NULL,
	prezzo DECIMAL(5,2) default null,
	PRIMARY KEY(id),
	FOREIGN KEY (utente) REFERENCES Utente(email) ON UPDATE CASCADE,
	FOREIGN KEY (libro) REFERENCES Libro(ISBN) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella annunci di vendita */

CREATE TABLE Vendita(
	id INTEGER AUTO_INCREMENT,
	testo VARCHAR(360) default null,
	tmstmp TIMESTAMP NOT NULL,
	prezzo DECIMAL(5,2),
	utente VARCHAR(50) NOT NULL,
	libro BIGINT NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY (utente) REFERENCES Utente(email) ON UPDATE CASCADE,
	FOREIGN KEY (libro) REFERENCES Libro(ISBN) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella annunci di ricerca */

CREATE TABLE Ricerca(
	id INTEGER AUTO_INCREMENT,
	testo VARCHAR(360) default null,
	tmstmp TIMESTAMP NOT NULL,
	utente VARCHAR(50) NOT NULL,
	libro BIGINT NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY (utente) REFERENCES Utente(email) ON UPDATE CASCADE,
	FOREIGN KEY (libro) REFERENCES Libro(ISBN) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella libro */

CREATE TABLE Libro(
	ISBN BIGINT,
	titolo VARCHAR(250) NOT NULL,
	editore INTEGER NOT NULL,
	prezzo DECIMAL(5,2),
	anno YEAR(4),
	PRIMARY KEY(ISBN),
	FOREIGN KEY (editore) REFERENCES Editore(id) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella descrizione, rappresentante la descrizione di un libro */

CREATE TABLE Descrizione(
	libro BIGINT,
	testo TEXT,
	copertina BLOB,
	PRIMARY KEY(libro),
	FOREIGN KEY (libro) REFERENCES Libro(ISBN) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella autore */

CREATE TABLE Autore(
	id INTEGER AUTO_INCREMENT,
	nome VARCHAR(50),
	cognome VARCHAR(50),
	PRIMARY KEY (id)
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella autore-libro */

CREATE TABLE AutoreLibro(
	autore INTEGER,
	libro BIGINT,
	PRIMARY KEY (autore, libro),
	FOREIGN KEY (autore) REFERENCES Autore(id) ON UPDATE CASCADE,
	FOREIGN KEY (libro) REFERENCES Libro(ISBN) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella editore */

CREATE TABLE Editore(
	id INTEGER AUTO_INCREMENT,
	nome VARCHAR(50),
	PRIMARY KEY(id)
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella rappresentante un corso di laurea */

CREATE TABLE CorsoDiLaurea(
	nome VARCHAR(200),
	universita VARCHAR(100),
	PRIMARY KEY (nome, universita),
	FOREIGN KEY (universita) REFERENCES Universita(nome) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella Università */

CREATE TABLE Universita(
	nome VARCHAR(100),
	indirizzo VARCHAR(150),
	telefono VARCHAR(13),
	PRIMARY KEY (nome)
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella Insegnamento, rappresentante gli insegnamenti di un corso di laurea */

CREATE TABLE Insegnamento(
	id INTEGER AUTO_INCREMENT,
	nome VARCHAR(100) NOT NULL,
	corsoDiLaurea VARCHAR(200),
	universita VARCHAR(100),
	anno ENUM('1','2','3','4','5','6','7'),
	PRIMARY KEY (id),
	FOREIGN KEY (corsoDiLaurea, universita) REFERENCES CorsoDiLaurea(nome, universita) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/* Creazione della tabella libro insegnamento che associa i libri ai loro insegnamenti */

CREATE TABLE LibroInsegnamento(
	insegnamento INTEGER,
	libro BIGINT,
	PRIMARY KEY(insegnamento, libro),
	FOREIGN KEY(insegnamento) REFERENCES Insegnamento(id) ON UPDATE CASCADE,
	FOREIGN KEY(libro) REFERENCES Libro(ISBN) ON UPDATE CASCADE
)ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

#fine creazione tabelle

SET FOREIGN_KEY_CHECKS=1;

#creazione view per ricerca

DROP VIEW IF EXISTS mostra_ricerca;
DROP VIEW IF EXISTS mostra_vendita;

CREATE VIEW mostra_ricerca(IDAnnuncio,ISBN,Titolo,Editore,PrezzoCopertina,Testo,Utente,Universita) AS
SELECT r.id,l.ISBN, l.titolo, e.nome, l.prezzo, r.testo, r.utente, u.universita
FROM Libro AS l JOIN Editore AS e ON l.editore=e.id
	 JOIN Ricerca AS r ON r.libro=l.ISBN
	 JOIN Utente AS u ON u.email=r.utente;

CREATE VIEW mostra_vendita(IDAnnuncio,ISBN,Titolo,Editore,PrezzoCopertina, PrezzoVendita,Testo,Utente,Universita) AS
SELECT v.id, l.ISBN, l.titolo, e.nome, l.prezzo, v.prezzo, v.testo, v.utente, u.universita
FROM Libro AS l JOIN Editore AS e ON l.editore=e.id
	 JOIN Vendita AS v ON v.libro=l.ISBN
	 JOIN Utente AS u ON u.email=v.utente;

#fine creazione view


#creazione procedure ed evento
DROP PROCEDURE IF EXISTS elimina_scaduti;
DROP PROCEDURE IF EXISTS inserimento_libro;
/*DROP EVENT IF EXISTS daily_expired_ads;*/

DELIMITER $$
CREATE PROCEDURE elimina_scaduti ()
BEGIN
	DELETE FROM Ricerca WHERE Ricerca.tmstmp < DATE_SUB(NOW(),INTERVAL 6 MONTH);
	DELETE FROM Vendita WHERE Vendita.tmstmp < DATE_SUB(NOW(),INTERVAL 6 MONTH);
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE inserimento_libro(nomeAutore VARCHAR(50),cognomeAutore VARCHAR(50),
nomeEditore VARCHAR(50), lisbn BIGINT, ltitolo VARCHAR(250), lprezzo decimal(5,2), lanno year(4),
linsegnamento VARCHAR(100), lcorso VARCHAR(200), luniversita VARCHAR(100))
BEGIN
	DECLARE c INTEGER;
	DECLARE eid INTEGER;
	DECLARE aid INTEGER;
	DECLARE iid INTEGER;
	select count(*) INTO c FROM Editore WHERE Editore.nome=nomeEditore;
	IF c=0 THEN
		INSERT INTO Editore(nome) VALUES (nomeEditore);
	END IF;
	select Editore.id INTO eid FROM Editore WHERE Editore.nome=nomeEditore;
	select count(*) INTO c FROM Autore WHERE Autore.nome=nomeAutore and Autore.cognome=cognomeAutore;
	IF c=0 THEN
		INSERT INTO Autore(nome,cognome) VALUES (nomeAutore,cognomeAutore);
	END IF;
	select Autore.id INTO aid FROM Autore WHERE Autore.nome=nomeAutore and Autore.cognome=cognomeAutore;
	INSERT INTO Libro(ISBN, titolo, editore, prezzo, anno) VALUES (lisbn, ltitolo, eid, lprezzo, lanno);
	INSERT INTO AutoreLibro(autore, libro) VALUES (aid,lisbn);
	select count(*) INTO c FROM Insegnamento WHERE Insegnamento.nome=linsegnamento and Insegnamento.corsoDiLaurea=lcorso and Insegnamento.universita=luniversita;
	IF c=0 THEN
		INSERT INTO Insegnamento(nome,corsoDiLaurea,universita) VALUES (linsegnamento,lcorso,luniversita);
	END IF;
	select Insegnamento.id INTO iid FROM Insegnamento WHERE Insegnamento.nome=linsegnamento and Insegnamento.corsoDiLaurea=lcorso and Insegnamento.universita=luniversita;
	INSERT INTO LibroInsegnamento(insegnamento, libro) VALUES (iid,lisbn);
END $$
DELIMITER ;

#L'evento commentato poiche' non funziona in laboratorio
/*DELIMITER $$
CREATE event daily_expired_ads
	on schedule every 1 day 
    starts now()
    DO
		call elimina_scaduti();
$$
DELIMITER ;*/
#fine creazione procedure e evento

#creazione trigger
DROP TRIGGER IF EXISTS elimina_annunci_ricerca;
DROP TRIGGER IF EXISTS elimina_annunci_vendita;
DROP TRIGGER IF EXISTS elimina_incoerenza_ricerca;
DROP TRIGGER IF EXISTS elimina_incoerenza_vendita;

DELIMITER $$
CREATE TRIGGER elimina_annunci_ricerca AFTER DELETE ON Ricerca FOR EACH ROW
BEGIN
	DECLARE m BOOLEAN;
	DECLARE scadenza date;
	SET scadenza=ADDDATE(DATE(old.tmstmp), interval 6 month);
	if scadenza<CURDATE() then
		SET m=false;
	else 
		SET m=true;
	end if;
	INSERT INTO NonInEvidenza(testo,tmstmp,motivazione,utente,libro) VALUES (OLD.testo, OLD.tmstmp,m,OLD.utente,OLD.libro); 
end $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER elimina_annunci_vendita AFTER DELETE ON Vendita FOR EACH ROW
BEGIN
	DECLARE m BOOLEAN;
	DECLARE scadenza date;
	SET scadenza=ADDDATE(DATE(old.tmstmp), interval 6 month);
	if scadenza<CURDATE() then
		SET m=false;
	else 
		SET m=true;
	end if;
	INSERT INTO NonInEvidenza(testo,tmstmp,motivazione,utente,libro,prezzo) VALUES (OLD.testo, OLD.tmstmp,m,OLD.utente,OLD.libro,OLD.prezzo); 
end $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER elimina_incoerenza_ricerca after insert on Ricerca for each row 
BEGIN
	declare idVendita INTEGER DEFAULT NULL;
	declare contaV INTEGER DEFAULT 0;
	SELECT count(*) INTO contaV
	FROM Vendita 
	WHERE Vendita.libro=new.libro AND Vendita.utente=new.Utente;

	WHILE contaV > 0 do
		SELECT Vendita.id INTO idVendita
		FROM Vendita 
		WHERE Vendita.libro=new.libro AND Vendita.utente=new.Utente LIMIT 1;

		DELETE FROM Vendita WHERE Vendita.id=idVendita;
		SET contaV= contaV - 1;
	end WHILE;

end $$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER elimina_incoerenza_vendita after insert on Vendita for each row 
BEGIN
	declare idRicerca INTEGER DEFAULT NULL;
	declare contaV INTEGER DEFAULT 0;
	SELECT count(*) INTO contaV
	FROM Ricerca 
	WHERE Ricerca.libro=new.libro AND Ricerca.utente=new.Utente;

	WHILE contaV > 0 do
		SELECT Ricerca.id INTO idRicerca
		FROM Ricerca 
		WHERE Ricerca.libro=new.libro AND Ricerca.utente=new.Utente LIMIT 1;

		DELETE FROM Ricerca WHERE Ricerca.id=idRicerca;
		SET contaV= contaV - 1;
	end WHILE;

end $$
DELIMITER ;

#fine creazione trigger

#creazione funzioni
DROP FUNCTION IF exists MediaAnnuaPV;
DROP FUNCTION IF exists LibriPerUtente;

DELIMITER $$
CREATE FUNCTION MediaAnnuaPV(anno year)
RETURNS DECIMAL(6,2)
begin
	DECLARE sumScad DECIMAL(8,2) DEFAULT 0;
	DECLARE sumVend DECIMAL(8,2) DEFAULT 0;
	DECLARE nScad INTEGER;
	DECLARE nVend INTEGER;
    DECLARE media DECIMAL(8,2) DEFAULT 0;
	SELECT SUM(ne.prezzo),COUNT(*) INTO sumScad, nScad 
	FROM NonInEvidenza AS ne WHERE ne.prezzo is not NULL AND year(ne.tmstmp) = anno;
	SELECT SUM(v.prezzo),COUNT(*) INTO sumVend, nVend 
	FROM Vendita AS v WHERE YEAR(v.tmstmp) = anno;
    
    IF nScad = 0 AND nVend = 0 THEN
		RETURN NULL;
	ELSEIF nScad = 0 THEN
		SET sumScad = 0;
	ELSEIF nVend = 0 THEN
		SET sumVend = 0;
	END IF;
	SET media = (sumScad + sumVend) / (nScad + nVend);
    
	RETURN media;

END $$
DELIMITER ;	

DELIMITER $$
CREATE FUNCTION LibriPerUtente(emailutente VARCHAR(50))
RETURNS TEXT
begin
	DECLARE uni VARCHAR(50);
	DECLARE ris TEXT;
	SELECT universita INTO uni FROM Utente WHERE Utente.email=emailutente;
	SELECT GROUP_CONCAT(DISTINCT '<tr><td>',l.ISBN,'</td><td>', l.titolo SEPARATOR '</td></tr>') INTO ris
		   FROM Libro AS l JOIN LibroInsegnamento AS li ON l.ISBN=li.libro
				JOIN Insegnamento AS i ON i.id=li.insegnamento
		   WHERE universita=uni;
	RETURN ris;
END $$

#fine crezione funzioni
