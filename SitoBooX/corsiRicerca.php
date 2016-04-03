<?php
/*
file utilizzato dallo script per riempire l'option a seconda dell'università scelta nella ricerca
*/
  include ("funzioni.php");
  $q = $_GET['q'];
  $conn=connectDB();
  $sql="select * from CorsoDiLaurea where universita='".mysql_real_escape_string(html_entity_decode($q,ENT_QUOTES,ini_get("UTF-8")))."'";
  $result = mysql_query($sql,$conn);
  echo "<option id='optionCorsi' value='na'>Corso Di Laurea</option>";
  while($riga=mysql_fetch_array($result))
  	echo "<option id='optionCorsi' value='".htmlentities(($riga['nome']),ENT_QUOTES ,"UTF-8")."'".">".$riga['nome']."</option>\n";
  mysql_close();
?>