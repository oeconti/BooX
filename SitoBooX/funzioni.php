<?php
/*
file contenente funzioni di utilizzo generale
*/

//-------------CONNESSIONE AL DATABASE
function connectDB(){
	$server_name="basidati.studenti.math.unipd.it";
	$user="oconti";
	$password="insertPasswordHhere";
	$db_name="oconti-PR";
	//crea connessione al databse
	$connessione=mysql_connect($server_name,$user,$password)or die("Error= ".mysql_error());
	mysql_set_charset("UTF8",$connessione);
	//seleziona il database
	$database=mysql_select_db($db_name,$connessione)or die("Error= ".mysql_error());
	return $connessione;
}

//-------------------------------------

//--------------CREA IL BANNER DEL LOGIN
function loginbanner(){
	session_start();
	if(isset($_POST['login']))
		verifica_login($_POST["email"],$_POST["password"]);	
	if(isset($_POST['logout']))
		logout();	
	if(empty($_SESSION["email"])){
		echo <<< END
			<form name="login" id="login" action="" method="POST">
			<input id="login" type="text" name="email" placeholder="email"/>
			<input id="login" type="password" name="password" placeholder="password"/>
			<input id="login" type="submit" name="login" value="accedi"/>	
			</form>
			<a id="login" href="registrazione.php">REGISTRATI</a>
END;
	}
	else {
		echo "Accesso come: <font color='white'>".$_SESSION["email"]."</font>";
		echo <<< END
			<form name="logout" id="login" action="" method="POST">
			<input id="login" type="submit" name="logout" value="logout"/>	
			</form>
END;

	}
}

//----------------------------------------

//-------------CONTROLLLO LOGIN

function verifica_login($email,$pwd){
	$conn=connectDB();
	$pwd=hash('sha256', $pwd);
	$query="select email,password from Utente where email = '$email' and password = '$pwd'";
	$risultato=mysql_query($query) or die (mysql_error());
	$riga=mysql_fetch_array($risultato);
	$identificativo=$riga["email"];
	if($identificativo!=null){
		$_SESSION["email"]=$identificativo;
		return true;
	}
	else{
		echo "Utente o password errata. Riprova";
		return false;
	}
}

//-----------------------------

function restore_session(){
	$email=$_SESSION['email'];
	session_destroy();
	session_start();
	$_SESSION['email']=$email;
}

//-------------LOGUOUT

function logout(){
	unset($_SESSION["email"]);
	echo "Logout effetuato con successo";
}
//------------------------------

//-------------LIMITA L'ACCESSO SENZA LOGIN
function controlla_permessi(){
	if(empty($_SESSION["email"])){
		include("restrizioni.php");
		die;
	}		
}

//-----------CONTROLLO DATA
function controllo_data($giorno,$mese,$anno){
	if($giorno=="na"||$mese=="na"||$anno=="na")
		return 0;
	else if($giorno<=28)
		return 1;
	else if($mese==2 and $giorno>29)
		return 0;
	else if($mese==2 and $giorno==29 and $anno%4!=0)
		return 0;
	else if(($mese==4 || $mese==6 || $mese==9 || $mese==11) && $giorno==31)
		return 0;
	else
		return 1;
}

function script_corsi_laurea(){
	echo <<< END
		<script charset="UTF-8">
			function showUser(str) {
				if (str == "") {
					document.getElementById("txtHint").innerHTML = "";
					return;
				}
				else {
					if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} 
					else {
					// code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
						}
					}
					xmlhttp.open("GET","corsi.php?q="+str,true);
					xmlhttp.send();
				}
			}
		</script>
END;
}

function dynamic_options(){
	script_corsi_laurea();
	$conn=connectDB();
	$uni=mysql_query("select distinct universita from CorsoDiLaurea");
	echo "Inserisci l'universit&agrave; <br/> <select name='universita' onchange='showUser(this.value)'>";
	echo " <option value='na'>Universit&agrave;</option>";
	while($riga=mysql_fetch_array($uni))
		echo "<option value='".htmlentities(($riga['universita']),ENT_QUOTES ,"UTF-8")."'".">".$riga['universita']."</option>\n";
	echo "</select>";
	echo " <div id='txtHint'></div><br/><br/>";
}
//----------------------------------------

function annunciricerca($titolo, $autore, $universita){
	$query="select l.Titolo as tit, ann.testo as txt, ann.utente as ut from Ricerca as ann join Libro l on ann.libro=l.ISBN where";
	if($titolo==0&&$autore==0&&$universita==0)
		$query.=" 1;";
	$conn=connectDB();
	$ris=mysql_query($query, $conn);
	echo "<div > <table style=\"border-style:groove; width:100%; \"><th>Titolo</th><th>Testo</th><th>Utente</th>";
	while($riga=mysql_fetch_array($ris))
		echo "<option value='".htmlentities($riga['nome'],ENT_QUOTES)."'".">".$riga['nome']."</option>";		
	echo "</table></div>";
}



function dynamic_options_ricerca(){
	script_corsi_laurea_ricerca();
	$conn=connectDB();
	$uni=mysql_query("select nome from Universita where 1");
	echo "Inserisci l'universit&agrave <br/>";
	echo "<select id='selectUniversita' name='universita' onchange='showUser(this.value)'>";
	echo " <option value='na'>Universit&agrave</option>";
	while($riga=mysql_fetch_array($uni)){
		echo "<option value='".htmlentities(($riga['nome']),ENT_QUOTES ,"UTF-8")."'".">".$riga['nome']."</option>\n";
	}
	echo "</select>";
}

function script_corsi_laurea_ricerca(){
	echo <<< END
		<script charset="UTF-8">
			function showUser(str) {
				if (str == "") {
					document.getElementById("txtHint").innerHTML = "";
					return;
				}
				else {
					if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} 
					else {
					// code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
						}
					}
					xmlhttp.open("GET","corsiRicerca.php?q="+str,true);
					xmlhttp.send();
				}
			}
		</script>
END;
}

/*
tipoAnnuncio è true se è di vendita, false se di ricerca
*/

function cerca_libro($titolo,$universita,$corso,$tipoAnnuncio){
	if($tipoAnnuncio){
		$prezzoAnn="Vendita.prezzo AS prezzoAnn,";
		$annuncio="vendita";
	}
	else{
		$prezzoAnn="";
		$annuncio="ricerca";
	}
	if($titolo=="" && $universita=="na"){//non è stato selezionato nulla
		$query="select * from mostra_".$annuncio." where 1";
	}
	else if($titolo !="" && $universita=="na"){//solo titolo selezionato
		$query="select * from mostra_".$annuncio.",Libro where mostra_".$annuncio.".ISBN=Libro.ISBN and Libro.Titolo LIKE '%".$titolo."%'";
	}
	else if($titolo !="" && $universita!="na" && $corso=="na"){//titolo + universita
		$query="select * from mostra_".$annuncio.",Libro,Insegnamento,LibroInsegnamento where mostra_".$annuncio.".ISBN=Libro.ISBN and Libro.Titolo LIKE '%".$titolo."%' 
				and Libro.ISBN=LibroInsegnamento.libro and Insegnamento.id=LibroInsegnamento.insegnamento and Insegnamento.universita='".mysql_real_escape_string($universita)."'";
	}
	else if($titolo !="" && $universita!="na" && $corso!="na"){
		$query="select * from mostra_".$annuncio.",Libro,Insegnamento,LibroInsegnamento where mostra_".$annuncio.".ISBN=Libro.ISBN and Libro.Titolo LIKE '%".$titolo."%' 
				and Libro.ISBN=LibroInsegnamento.libro and Insegnamento.id=LibroInsegnamento.insegnamento and Insegnamento.universita='".mysql_real_escape_string($universita)."'
				and Insegnamento.corsoDiLaurea='".mysql_real_escape_string($corso)."' and Insegnamento.corsoDiLaurea='".mysql_real_escape_string($corso)."'";
	}
	else if($titolo=="" && $universita!="na" && $corso=="na"){
		$query="select * from mostra_".$annuncio.",Insegnamento,LibroInsegnamento 
				where mostra_".$annuncio.".ISBN=LibroInsegnamento.libro and Insegnamento.id=LibroInsegnamento.insegnamento 
				and Insegnamento.universita='".mysql_real_escape_string($universita)."'";
	}
	else{
		$query="select * from mostra_".$annuncio.",Insegnamento,LibroInsegnamento 
				where mostra_".$annuncio.".ISBN=LibroInsegnamento.libro and Insegnamento.id=LibroInsegnamento.insegnamento 
				and Insegnamento.universita='".mysql_real_escape_string($universita)."' and Insegnamento.corsoDiLaurea='".mysql_real_escape_string($corso)."'";
	}
	$conn=connectDB();
	$ris=mysql_query($query, $conn);
	return $ris;
}

//$ricerca indica se la stampa degli annunci è stata chiamata dopo una ricerca

function stampa_annunci($ris,$tipoAnnuncio,$ricerca){
	if (mysql_num_rows($ris)){
		while($riga=mysql_fetch_array($ris)){
			echo "<div id=\"annuncio\">";
			$immagine="<img src=\"immagini/nocopertina.jpg\" class=\"imgcentered\" />";
			$query="select copertina from Descrizione where libro='".$riga['ISBN']."'";
			$conn=connectDB();
			$img=mysql_query($query,$conn);
			$im=mysql_fetch_array($img);
			if(!empty($im))
				$immagine="<img src=\"data:image/jpg;base64,". base64_encode($im['copertina']) ."\" class=\"imgcentered\" width=150 heigth=225 />";
			echo "<div id=\"immaginediv\">".$immagine."</div>";
			echo "<div id=\"annuncioContenuto\">";
			echo "<h2>ID annuncio: ".$riga["IDAnnuncio"]."</h2>";
			echo "<b>ISBN:</b> ".$riga["ISBN"]."<br/><br/>";
			echo "<b>Titolo:</b> ".$riga["Titolo"]."<br/><br/>";
			stampa_autore($riga["ISBN"]);
			echo "<b>Editore:</b> ".$riga["Editore"]."<br/><br/>";
			echo "<b>Prezzo di copertina:</b> ".$riga["PrezzoCopertina"]."<br/><br/>";
			if($tipoAnnuncio==1)
				echo "<b>Prezzo di vendita:</b> ".$riga["PrezzoVendita"]."<br/><br/>";
			echo "<b>Testo annuncio:</b> ".$riga["Testo"]."<br/><br/>";
			echo "<b>Utente:</b> ".$riga["Utente"]."<br/><br/>";
			echo "<b>Universit&agrave; dell'utente:</b> ".$riga["Universita"]."<br/><br/>";
			echo "<form id=\"annuncioForm\" action=\"messaggi.php\" method=\"POST\"><input type=\"submit\" value=\"Contatta l'utente\"></form>";
			echo "</div>";
			echo "</div>";	
			echo "<br/><br/>";
		}
	}
	else if(!$ricerca){
		echo "Al momento non sono presenti annunci.";
	}
	else{
		echo "La tua ricerca non ha portato ad alcun risultato. Prova a cercare cambiando la parola chiave.<br/>";
	}
}

function stampa_autore($isbn){
	$query="select Autore.nome, Autore.cognome from Autore,AutoreLibro where AutoreLibro.autore=Autore.id and AutoreLibro.libro='".$isbn."'";
	$conn=connectDB();
	$ris=mysql_query($query, $conn);
	$count=mysql_num_rows($ris);
	if ($count>1)
		echo "<b>Autori:</b> ";
	else
		echo "<b>Autore:</b> ";
	while($riga=mysql_fetch_array($ris)){
		if($count>1)
			echo "&nbsp;".$riga["nome"]."&nbsp;".$riga["cognome"].",&nbsp;";
		else
			echo "&nbsp;".$riga["nome"]."&nbsp;".$riga["cognome"];

		$count--;
	}

	echo "<br/><br/>";

}

?>
