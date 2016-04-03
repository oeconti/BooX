<?php
/*
Pagina per la scelta di query e funzioni
*/
	include("funzioni.php");
	include("funzioni_stile_pagina.php");
	start_page("Query e Funzioni");
	echo "<h1>Query e funzioni</h1>";
	echo "Seleziona la query o la funzione che vuoi eseguire che vuoi eseguire:<br/>";
	echo "<form action=\"results.php\" method=\"POST\">";

		echo "<input type=\"radio\" name=\"query\" value=\"1\">query 1)Visualizza la lista dei libri per cui sono presenti annunci e utilizzati in università differenti dal pubblicatore<br/><br/>";

		echo "<input type=\"radio\" name=\"query\" value=\"2\">query 2)Visualizza l'insieme degli utenti che hanno pubblicato il numero massimo di annunci<br/><br/>";

		echo "<input type=\"radio\" name=\"query\" value=\"3\">query 3)Le università in cui sono utilizzati i libri che hanno il maggior numero di annunci di vendita associati<br/><br/>";

		echo "<input type=\"radio\" name=\"query\" value=\"4\">query 4)Visualizza l'insieme degli utenti con maggior scarto tra la media dei prezzi degli annunci di vendita e il prezzo di copertina del libro dell'annuncio<br/><br/>";

		echo "<input type=\"radio\" name=\"query\" value=\"5\">query 5)Visualizza tutti i libri associati ad una ed una sola università<br/><br/>";

		echo "<input type=\"radio\" name=\"query\" value=\"6\">query 6)Visualizza gli utenti che non hanno mai pubblicato un annuncio o hanno pubblicato solamente annunci rimossi perché scaduti ed la data di 
			  <br/>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		      scadenza differisce dalla data odierna di almeno un anno<br/><br/>";
		
		echo "<input type=\"radio\" name=\"query\" value=\"7\">funzione 1)Visualizza per un utente tutti i libri associati alla sua università
			  <br/> &nbsp &nbsp &nbsp &nbsp Utente: &nbsp
			  <select name=utente>";
			  $query="select email from Utente where 1";
			  $conn=connectDB();
			  $ris=mysql_query($query,$conn);
			  while($riga=mysql_fetch_array($ris))
			  	echo "<option value='".$riga['email']."'> ".$riga['email']." </option>";
		echo "</select><br/><br/>";
			  mysql_close($conn);

		echo "<input type=\"radio\" name=\"query\" value=\"8\">funzione 2)Visualizza la media dei prezzi degli annunci di vendita per un determinato anno
			  <br/> &nbsp &nbsp &nbsp &nbsp Anno: &nbsp &nbsp 
			  <input type=\"input\" name=\"anno\" placeholder=\"e.g. 2015\" /><br/><br/>";

		echo "<input type=\"submit\" name=\"esegui\" value=\"Esegui\">";

	echo "</form>";
	end_page()
?>
