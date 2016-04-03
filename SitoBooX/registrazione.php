<?php
/*
Pagina per il controllo e la registrazione di nuovi utenti
*/
	include("funzioni_stile_pagina.php");
	include("funzioni.php");
	include("funzioni_registrazione.php");
	start_page("Registrati");
	if(!isset($_SESSION['email'])){
		if(!isset($_POST['registrazione']))
			first_form();
		else{
			if(!empty($_POST['email'])&&!empty($_POST['pwd'])&&!empty($_POST['pwd2'])&&!empty($_POST['nome'])&&
				!empty($_POST['cognome'])&&!empty($_POST['giorno'])&&!empty($_POST['mese'])&&!empty($_POST['anno'])){
				
				$diffpwd=($_POST['pwd']!=$_POST['pwd2']);
				$errdate=(!controllo_data($_POST['giorno'], $_POST['mese'], $_POST['anno']));	
				$conn=connectDB();
				$s=mysql_query("select * from Utente where email='".$_POST['email']."'", $conn);
				$presente=false;
				if(mysql_num_rows($s)>0)
					$presente=true;
				if($diffpwd||$errdate||$presente){
					echo $errdate."<br/>";
					error_form($diffpwd,$errdate,$presente,$_POST['email'],$_POST['pwd'],$_POST['pwd2'],$_POST['nome'],$_POST['cognome'],
						$_POST['giorno'],$_POST['mese'],$_POST['anno']);
				}
				else{
					$data_nascita=$_POST['anno']."-".$_POST['mese']."-".$_POST['giorno'];
					$pass=hash('sha256', $_POST['pwd']);
					if($_POST['universita']=='na'){
						$query="insert into Utente (email,nome,cognome,password,dataDiNascita) values (";
						$query.="'".$_POST['email']."','".$_POST['nome']."','".$_POST['cognome']."','".$pass."','".$data_nascita."')";
						mysql_query($query, $conn);
						if (verifica_login($_POST['email'], $_POST['pwd'])){
							header('Location:benvenuto.php');
							exit();
						}
					} else{
						$query="insert into Utente (email,nome,cognome,password,dataDiNascita,corso,universita) values (";
						if($_POST['corso']=='na')
							$query.="'".$_POST['email']."','".$_POST['nome']."','".$_POST['cognome']."','".$pass."','".$data_nascita."', NULL,'".mysql_real_escape_string(html_entity_decode($_POST['universita'],ENT_QUOTES,"UTF-8"))."')";
						else
							$query.="'".$_POST['email']."','".$_POST['nome']."','".$_POST['cognome']."','".$pass."','".$data_nascita."','".mysql_real_escape_string(html_entity_decode($_POST['corso'],ENT_QUOTES,"UTF-8"))."','".mysql_real_escape_string(html_entity_decode($_POST['universita'],ENT_QUOTES,"UTF-8"))."')";
						mysql_query($query,$conn);
						if (verifica_login($_POST['email'], $_POST['pwd'])){
							header('Location:benvenuto.php');
							exit();
						}
					}
				}
			}
			else
				campi_vuoti($_POST['email'],$_POST['pwd'],$_POST['pwd2'],$_POST['nome'],$_POST['cognome'],$_POST['giorno'], 
					$_POST['mese'],$_POST['anno']);
		}
	}
	else
		echo "<h1>".$_SESSION['email']." sei gi&agrave; un nostro utente!</h1>";
	mysql_close();
	end_page();
?>
