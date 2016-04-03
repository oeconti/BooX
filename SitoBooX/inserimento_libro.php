<?php
/*
pagina per controllo e inserimento di nuovi libri
*/
	include ("funzioni.php");
	include ("funzioni_stile_pagina.php");
	include ("funzioni_pubblicazione_annuncio.php");
	start_page("Inserimento nuovo libro");
	if(!isset($_SESSION['isbn'])){
		header('Location:index.php');
					exit();
	}

		else{
	if(!isset($_POST['insertlibro']))
		first_inseriscilibroform();
	else{
		if(empty($_POST['titolo']) || empty($_POST['AutoreNome']) || empty($_POST['AutoreCognome']) || empty($_POST['editore']))
			inseriscilibroform();
		else{
				$conn=connectDB();
				$universita="NULL";
				$corso="NULL";
				$insegnamento="NULL";
				if (!empty($_SESSION['universita']) and $_SESSION['universita']!='na')
					$universita=$_SESSION['universita'];
				if (!empty($_SESSION['corso']) and $_SESSION['corso']!='na')
					$corso=$_SESSION['corso'];
				if (!empty($_SESSION['insegnamento']))
					$insegnamento=$_SESSION['insegnamento'];
				$query="CALL inserimento_libro('".mysql_real_escape_string($_POST['AutoreNome'])."','".mysql_real_escape_string($_POST['AutoreCognome'])."','".mysql_real_escape_string($_POST['editore'])
					."','".mysql_real_escape_string($_SESSION['isbn'])."','".mysql_real_escape_string($_POST['titolo'])."','".$_POST['prezzo']."','".$_POST['anno']."','".mysql_real_escape_string($insegnamento)."','".mysql_real_escape_string($corso)."','".mysql_real_escape_string($universita)."')";
				mysql_query($query,$conn);
				if ($_SESSION['type']=="Ricerca")
					$query="insert into Ricerca(testo,utente,libro) values ('".mysql_real_escape_string($_SESSION['testo'])."','".mysql_real_escape_string($_SESSION['email'])."','".$_SESSION['isbn']."')";
				else
					$query="insert into Vendita(testo,prezzo,utente,libro) values ('".mysql_real_escape_string($_SESSION['testo'])."','".$_SESSION['prezzo']."','".mysql_real_escape_string($_SESSION['email'])."','".$_SESSION['isbn']."')";
				mysql_query($query,$conn);
				echo "<h1 style='align:center;'>Grazie per l'inserimento del Libro<br/>Annuncio inserito con successo</h1>";
				restore_session();
			}
		}
	}
	end_page();
?>
