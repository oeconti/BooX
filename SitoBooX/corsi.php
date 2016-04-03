<?php
/*
file utilizzato dallo script per riempire l'option a seconda dell'università scelta nella registrazione
*/
  include ("funzioni.php");
  $q = $_GET['q'];
  $conn=connectDB();
  $sql="select * from CorsoDiLaurea where universita='".mysql_real_escape_string(html_entity_decode($q,ENT_QUOTES, "UTF-8"))."'";
  $result = mysql_query($sql,$conn);
  echo "<br/>Corso Di Laurea </br> <select name=corso>";
  while($riga=mysql_fetch_array($result))
    echo "<option value='".htmlentities(($riga['nome']),ENT_QUOTES ,"UTF-8")."'".">".$riga['nome']."</option>";
  echo "</select>";
  mysql_close();
?>