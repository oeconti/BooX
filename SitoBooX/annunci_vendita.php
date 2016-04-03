<?php
/*
Pagina per gli annunci di vendita con form per la ricerca
*/
	include("funzioni.php");
	include("funzioni_stile_pagina.php");
	include("show_annunci.php");
	start_page_ricerca("Annunci di vendita");
	if(isset($_POST['cerca']))
		crea_ricerca_form($_POST['titolo'],$_POST['universita'],$_POST['corso'],1);
	else
		crea_ricerca_form("","","",1);
	echo "<h1>Annunci di vendita di libri</h1>";
	if(isset($_POST['cerca'])){
		$ris=cerca_libro($_POST['titolo'],$_POST['universita'],$_POST['corso'],TRUE);
		stampa_annunci($ris,1,1);
	}
	else{
		$ris=cerca_libro("","na","na",TRUE);
		stampa_annunci($ris,1,0);
	}
	mysql_close();
    end_page();
?>