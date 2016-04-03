<?php
/*
Pagina contenente il risultato della query scelta nella pagina delle query
*/
	include("funzioni.php");
	include("funzioni_stile_pagina.php");
	start_page("Risultati");
	if(!isset($_POST['query']))
		echo "<h1><font color=red>Nessuna query selezionata!</font></h1> <br/><br/><br/>";
	else{ //nel caso sia stata scelta una query o una funzione si effettua la query o la chiamata alla funzione
		$conn=connectDB();
		echo "<h1>Risultato</h1>";
		if ($_POST["query"]==1){
			$query="select l.ISBN, l.titolo from Libro as l 
					where l.ISBN in((select r.libro from Ricerca as r join Utente as u on r.utente=u.email 
									  where exists(select * from ((Libro as l1 join LibroInsegnamento as li on l1.ISBN=li.libro) 
															     join Insegnamento as i on li.insegnamento=i.id) join CorsoDiLaurea as cdl on i.corsoDiLaurea=cdl.nome 
																 where cdl.universita<>u.universita and l1.ISBN = r.libro) 
									union 
									(select v.libro from Vendita as v join Utente as u on v.utente=u.email 
									 where exists(select * from ((Libro as l1 join LibroInsegnamento as li on l1.ISBN=li.libro) 
															    join Insegnamento as i on li.insegnamento=i.id) 
																join CorsoDiLaurea as cdl on i.corsoDiLaurea=cdl.nome where cdl.universita<>u.universita and l1.ISBN = v.libro))));";
			$ris=mysql_query($query, $conn);
			echo "1)Visualizza la lista dei libri per cui sono presenti annunci e utilizzati in università differenti dal pubblicatore<br/><br/>";
			echo "<table border='dotted'>";
			echo "<tr><th>ISBN</th><th>Titolo</th></tr>";
			while($riga=mysql_fetch_array($ris)){
				echo "<tr>";
					echo "<td>".$riga["ISBN"]."</td>";
					echo "<td>".$riga["titolo"]."</td>";
				echo "</tr>";
			}
			echo "</table><br/><br/>";
		}
		else if($_POST["query"]==2){
			$query="DROP VIEW IF EXISTS UtenteNumeroAnnunciRicerca;";
			$ris=mysql_query($query, $conn);
			$query="DROP VIEW IF EXISTS UtenteNumeroAnnunciVendita;";
			$ris=mysql_query($query, $conn);
			$query="create view UtenteNumeroAnnunciRicerca(utente,nr) as
				 select u.email , count(r.id)
				 from Utente as u left join Ricerca as r on u.email=r.utente
				 group by u.email;";
			$ris=mysql_query($query, $conn);
			$query="create view UtenteNumeroAnnunciVendita(utente,nv) as
				 select u.email , count(v.id) 
				 from Utente as u left join Vendita as v on u.email=v.utente
				 group by u.email;";
			$ris=mysql_query($query, $conn);
			$query="select u.email,u.nome,u.cognome,unr.nr+unv.nv as totale
				  from (Utente as u join UtenteNumeroAnnunciRicerca as unr on u.email=unr.utente) 
				  join UtenteNumeroAnnunciVendita as unv on u.email=unv.utente
				  where unr.nr+unv.nv >= ALL (select distinct (unr1.nr+unv1.nv)
				  from UtenteNumeroAnnunciVendita as unv1 join UtenteNumeroAnnunciRicerca as unr1 on unv1.utente=unr1.utente);";
			$ris=mysql_query($query, $conn);
			echo "2)Visualizza l'insieme degli utenti che hanno pubblicato il numero massimo di annunci<br/><br/>";
			echo "<table border='dotted'>";
			echo "<tr><th>utente</th><th>Nome</th><th>Cognome</th><th>Totale</th></tr>";
			while($riga=mysql_fetch_array($ris)){
				echo "<tr>";
					echo "<td>".$riga["email"]."</td>";
					echo "<td>".$riga["nome"]."</td>";
					echo "<td>".$riga["cognome"]."</td>";
					echo "<td>".$riga["totale"]."</td>";
				echo "</tr>";
			}
			echo "</table><br/><br/>";
			$query="DROP VIEW IF EXISTS UtenteNumeroAnnunciRicerca;";
			$ris=mysql_query($query, $conn);
			$query="DROP VIEW IF EXISTS UtenteNumeroAnnunciVendita;";
			$ris=mysql_query($query, $conn);
		}
		else if($_POST["query"]==3){
			$query="SELECT *
FROM Universita WHERE Universita.nome IN(
	SELECT i.universita 
	FROM Insegnamento AS i JOIN LibroInsegnamento AS li ON i.id = li.insegnamento
		 JOIN Vendita AS v ON v.libro = li.libro
	GROUP BY i.universita 
	having COUNT(distinct v.libro) >= ALL (
		SELECT COUNT(distinct v1.libro)
		FROM Insegnamento AS i1 JOIN LibroInsegnamento AS li1 ON i1.id = li1.insegnamento
	 		JOIN Vendita AS v1 ON v1.libro = li1.libro
	 	GROUP BY i1.universita		 	
	 )
)
					";
			$ris=mysql_query($query, $conn);
			echo "3)Le università in cui sono utilizzati i libri che hanno il maggior numero di annunci di vendita associati<br/><br/>";
			echo "<table border='dotted'>";
			echo "<tr><th>Nome</th><th>Indirizzo</th><th>Telefono</th></tr>";
			while($riga=mysql_fetch_array($ris)){
				echo "<tr>";
					echo "<td>".$riga["nome"]."</td>";
					echo "<td>".$riga["indirizzo"]."</td>";
					echo "<td>".$riga["telefono"]."</td>";
				echo "</tr>";
			}
			echo "</table><br/><br/>";
		}
		else if($_POST["query"]==4){
			$query="select u.email, u.nome, u.cognome, avg(l.prezzo)-avg(v.prezzo) as scarto
					from Utente as u join Vendita as v on u.email=v.utente
						 join Libro as l on v.libro=l.ISBN
					group by u.email having (avg(l.prezzo)-avg(v.prezzo))>=all(
						select avg(l1.prezzo)-avg(v1.prezzo)
						from Vendita as v1 join Libro as l1 on v1.libro=l1.ISBN
					group by v1.utente)";
			$ris=mysql_query($query, $conn);
			echo "4)Visualizza l'insieme degli utenti con maggior scarto tra la media dei prezzi degli annunci di vendita e il prezzo di copertina del libro dell'annuncio<br/><br/>";
			echo "<table border='dotted'>";
			echo "<tr><th>Email</th><th>Nome</th><th>Cognome</th><th>Scarto</th></tr>";
			while($riga=mysql_fetch_array($ris)){
				echo "<tr>";
					echo "<td>".$riga["email"]."</td>";
					echo "<td>".$riga["nome"]."</td>";
					echo "<td>".$riga["cognome"]."</td>";
					echo "<td>".$riga["scarto"]."</td>";
				echo "</tr>";
			}
			echo "</table><br/><br/>";
		}
		else if($_POST["query"]==5){
			$query="select l.*
					from Libro as l
					where l.ISBN not in( 
						select li1.libro 
						from 
						(LibroInsegnamento as li1 JOIN Insegnamento as i1 on li1.insegnamento=i1.id) 
							join 
						(LibroInsegnamento as li2 JOIN Insegnamento as i2 on li2.insegnamento=i2.id)
					 	on li1.libro=li2.libro and i1.universita<>i2.universita and i2.corsoDilaurea<>i1.corsoDiLaurea) and l.ISBN in(
					 		select LibroInsegnamento.libro
					 		from LibroInsegnamento
					 	);";
			$ris=mysql_query($query, $conn);
			echo "5)Visualizza tutti i libri associati ad una ed una sola università<br/><br/>";
			echo "<table border='dotted'>";
			echo "<tr><th>ISBN</th><th>Titolo</th><th>Prezzo</th></tr>";
			while($riga=mysql_fetch_array($ris)){
				echo "<tr>";
					echo "<td>".$riga["ISBN"]."</td>";
					echo "<td>".$riga["titolo"]."</td>";
					echo "<td>".$riga["prezzo"]."</td>";
				echo "</tr>";
			}
			echo "</table><br/><br/>";
		}
		else if($_POST["query"]==6){
			$query="select * 
				from Utente as u
				where u.email not in(
					select utente
					from Vendita
					union
					select utente
					from Ricerca
					union
					select utente
					from NonInEvidenza
				)
				or u.email in
				(
					select utente
					from NonInEvidenza as n
					where n.utente not in(
						select utente
						from Vendita
						union
						select utente
						from Ricerca
						)
					and n.motivazione = true
					and not exists(select *
						       from NonInEvidenza as n1
						       where n1.utente=n.utente and
							     SUBDATE(NOW(),INTERVAL 1 YEAR) < ADDDATE(n1.tmstmp, INTERVAL 6 MONTH))
				)";
			$ris=mysql_query($query, $conn);
			echo "6)Visualizza gli utenti che non hanno mai pubblicato un annuncio o hanno pubblicato solamente annunci rimossi perché scaduti ed la data di 
			  <br/>&nbsp&nbsp
		      scadenza differisce dalla data odierna di almeno un anno<br/><br/><br/><br/>";
			echo "<table border='dotted'>";
			echo "<tr><th>Email</th><th>Nome</th><th>Cognome</th><tr/>";
			while($riga=mysql_fetch_array($ris)){
				echo "<tr>";
					echo "<td>".$riga["email"]."</td>";
					echo "<td>".$riga["nome"]."</td>";
					echo "<td>".$riga["cognome"]."</td>";
				echo "</tr>";
			}
			echo "</table><br/><br/>";
		}
		else if($_POST["query"]==7){
			$query="select LibriPerUtente('".$_POST['utente']."');";
			$ris=mysql_query($query, $conn);
			$riga=mysql_fetch_array($ris);
			echo "<table border=\"dotted\">";
				echo "<tr><th>ISBN</th><th>Titolo</th></tr>";
				echo $riga[0];
			echo "</table>";
		}

	
		else if($_POST["query"]==8){
				$query="select MediaAnnuaPV('".$_POST['anno']."');";
				$ris=mysql_query($query, $conn);
				$riga=mysql_fetch_array($ris);
				echo "La media per l'anno ".$_POST['anno']." è: €".$riga[0]."<br/><br/><br/>";
			}
		mysql_close();	
	}

	echo "<br/><br/><a href=\"query_e_funzioni.php\">Torna indietro</a>";
	end_page()
?>
