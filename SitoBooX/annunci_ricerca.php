<?php
/*
Pagina per gli annunci di ricerca con form per la ricerca
*/
	include("funzioni.php");
	include("funzioni_stile_pagina.php");
	start_page_ricerca("Annunci di ricerca");
	if(isset($_POST['cerca']))
		crea_ricerca_form($_POST['titolo'],$_POST['universita'],$_POST['corso'],0);
	else
		crea_ricerca_form("","","",0);
	echo "<h1>Annunci di ricerca di libri</h1>";
	if(isset($_POST['cerca'])){
		$ris=cerca_libro($_POST['titolo'],$_POST['universita'],$_POST['corso'],0);
		stampa_annunci($ris,0,1);
	}
	else{
		$ris=cerca_libro("","na","na",0);
		stampa_annunci($ris,0,0);
	}
    end_page();
?>