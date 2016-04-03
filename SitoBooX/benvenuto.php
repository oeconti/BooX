<?php
/*
Pagina di benvenuto che viene viasualizzata quando un utente si registra
*/
	include("funzioni.php");
	include("funzioni_stile_pagina.php");
	start_page("Benvenuto!");
	$query="select nome,cognome from Utente where email='".$_SESSION['email']."'";
	$conn=connectDB();
	$ris=mysql_query($query,$conn);
	$r=mysql_fetch_array($ris);
	echo "<h1>Dati inseriti con successo<br/>Benvenuto ".$r['nome']." ".$r['cognome']."!</h1>";
	mysql_close();
	end_page();
?>