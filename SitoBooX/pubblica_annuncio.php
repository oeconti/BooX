<?php
/*
Pagina per contollo e inserimento di nuovi annunci
*/
	include("funzioni.php");
	include("funzioni_stile_pagina.php");
	include("funzioni_pubblicazione_annuncio.php");
	start_page("Pubblica il tuo annuncio");
	controlla_permessi();
	if(!isset($_POST['insertAnnuncio'])) {
		echo "<h1>Pubblica Annuncio</h1> <form action=pubblica_annuncio.php method=POST>
			Tipo Annuncio:<br/> <br/>
			Vendita <input type=radio name=type value=Vendita checked /> <br/><br/>
			Ricerca <input type=radio name=type value=Ricerca /> <br/><br/>
			<input type=submit name=insertAnnuncio value='Avanti >>' /></form>";
	}
	else if(!isset($_POST['isbn'])) 
			pubblicaform(0);
	else if (empty($_POST['isbn']) or $_POST['isbn']=='')
			pubblicaform(1);
	else if ((empty($_POST['prezzo']) or $_POST['prezzo']=='') && $_POST['type']=="Vendita")
			pubblicaform(2);
	else if ((empty($_POST['insegnamento']) or $_POST['insegnamento']=='') && $_POST['universita']!='na') 
			pubblicaform(3);
	else if ((!empty($_POST['insegnamento']) or $_POST['insegnamento']!='') && $_POST['universita']=='na')
			pubblicaform(3);
	else{
		//non ci sono stati errori. Posso pubblicare l'annuncio.
		$query="select * from Libro where ISBN=".$_POST['isbn'];
		$conn=connectDB();
		$ris=mysql_query($query, $conn);			
		//controllo libro se presente. Se non presente allora vado in inserimento libro creando una sessione con i dati dell'annuncio inserito
		if(mysql_num_rows($ris)==0){
			$_SESSION['isbn']=$_POST['isbn'];
			$_SESSION['type']=$_POST['type'];
			if($_POST['type']=="Vendita")
				$_SESSION['prezzo']=$_POST['prezzo'];
			$_SESSION['testo']=$_POST['testo'];
			$_SESSION['universita']=$_POST['universita'];
			$_SESSION['corso']=$_POST['corso'];
			$_SESSION['insegnamento']=$_POST['insegnamento'];
			header('Location:inserimento_libro.php');
			exit();
		}
			//se mi trovo qui il libro è presente, ed è possibile inserire l'annuncio
			//entro nell'if se l'utente ha inserito anche l'insegnamento altrimenti inserisco subito l'annuncio
		else if(isset($_POST['insegnamento']) and $_POST['insegnamento']!=''){
			$query="select id from insegnamento where nome='".$_POST['insegnamento']."' and corsoDiLaurea='".$_POST['corso']."' and universita='".$_POST['universita']."'";
			$ris=mysql_query($query,$conn);
				
			if($ris){
				$riga=mysql_fetch_array($ris);
				$query="insert into LibroInsegnamento(libro,insegnamento) values ('".$riga['id']."','".$_POST['isbn']."')";
				$ris=mysql_query($query,$conn);
			}
			else{
				$query="insert into Insegnamento(nome,corsoDiLaurea,universita) values ('".mysql_real_escape_string($_POST['insegnamento'])."','"
							.mysql_real_escape_string($_POST['corso'])."','".mysql_real_escape_string($_POST['universita'])."')";
				$ris=mysql_query($query,$conn);
				$query="select id from Insegnamento where nome='".mysql_real_escape_string($_POST['insegnamento'])."' and
						    corsoDiLaurea='".mysql_real_escape_string($_POST['corso'])."' and universita='".mysql_real_escape_string($_POST['universita'])."'";
				$ris=mysql_query($query,$conn);
				$riga=mysql_fetch_array($ris);
				$query="insert into LibroInsegnamento(insegnamento,libro) values ('".$riga['id']."','".$_POST['isbn']."')";
				$ris=mysql_query($query,$conn);
			}
		}
			if ($_POST['type']=="Ricerca")
				$query="insert into Ricerca(testo,utente,libro) values ('".$_POST['testo']."','".$_SESSION['email']."','".$_POST['isbn']."')";
			else
				$query="insert into Vendita(testo,utente,libro,prezzo) values ('".$_POST['testo']."','".$_SESSION['email']."','".$_POST['isbn']."','".$_POST['prezzo']."')";
			
			$ris=mysql_query($query,$conn);
			if($ris)
				echo "<h1 style='align:center;'>Annuncio inserito con successo</h1>";
			else
				echo "<h1 style='align:center;'>Error</h1>";
				
	}
    end_page();
?>