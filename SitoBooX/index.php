<?php
/*
Pagina in cui si apre il sito con presentazione e link alla pagina con le query e le funzioni
*/	
	include("funzioni.php");
	include("funzioni_stile_pagina.php");
	start_page("Home: BooX - Books eXchange project");
	echo "	<h1 style='align:center;'>Benvenuto in BooX</h1>Boox e&grave; il primo sito per lo scambio di libri universitari in Italia<br/>
		Pubblica il tuo annuncio oppure contatta gli altri utenti per discutere dei loro annunci, incontrati con gli altri utenti e il gioco è fatto!";
	echo "<br/><br/><a href=\"query_e_funzioni.php\">Vai alle query</a><br/><br/>";
	end_page()
?>
