<?php
/*
file contenenti funzioni per impostare lo stile della pagina
*/

// INIZIO PAGINA
function start_page($title="BooX"){ //parametro serve per impostare il titolo della pagina
	echo "<!DOCTYPE html>
			<html>
			<head>
				<title>$title</title>
				<link rel=\"stylesheet\" type=\"text/css\" href=\"stile/stile.css\" />
				<meta http-equiv=\"Content-Type\" content=\"text/html;\" charset=\"UTF-8\" >
			</head>
			<body>
		    	<div id='container'>
					<div id='header'>";
							loginbanner();		
			  echo "</div>
				 	<div id='navigation'>";
				 			menu();
			  echo "</div>
					<div id='content'>";
}

// INIZIO PAGINA CON RICERCA
function start_page_ricerca($title="BooX"){
	echo "<!DOCTYPE html>
			<html>
			<head>
				<title>$title</title>
				<link rel=\"stylesheet\" type=\"text/css\" href=\"stile/stile.css\" />
				<meta http-equiv=\"Content-Type\" content=\"text/html;\" charset=\"UTF-8\" >
			</head>
			<body>
		    	<div id='container'>
					<div id='header'>";
							loginbanner();		
			  echo "</div>
				 	<div id='navigation'>";
				 			menu();
			  echo "</div>";
}

//FINE PAGINA
function end_page(){
	echo "</div> <div id='footer'> Autore Marco e Oscar </div> </div> </body> </html>";
}
//---------------

//MENU
function menu(){
$pagine = array ('Home' =>'index.php',
                 'Annunci vendita' =>'annunci_vendita.php',
                 'Annunci ricerca' =>'annunci_ricerca.php',
                 'Pubblica annuncio' =>'pubblica_annuncio.php',
                 'Messaggi' =>'messaggi.php',
                 );
echo '<ul id="menu" class="activer">';
foreach ($pagine as $text => $link)
  {
  
  if (basename($_SERVER['PHP_SELF']) == basename($link))
       $out='<li id="active"><a href="'.$link.'">'.$text.'</a></li>';
  else 
      $out='<li><a href="'.$link.'">'.$text.'</a></li>';
  
  echo $out;
  }
echo "</ul>";

}


//CREA CAMPO DATA

function crea_form_data(){
	echo "<select name=\"giorno\">";
		echo " <option value=\"na\">Giorno</option>";
		for($g=1;$g<32;$g++){
			echo " <option value=\"".$g."\">".$g."</option>";		
		}
	echo "</select>";

	echo "<select name=\"mese\">";
		echo " <option value=\"na\">Mese</option>";
		for($g=1;$g<13;$g++){
			echo " <option value=\"".$g."\">".$g."</option>";		
		}
	echo "</select>";

	echo "<select name=\"anno\">";
		echo " <option value=\"na\">Anno</option>";
		for($g=2010;$g>=1930;$g--){
			echo " <option value=\"".$g."\">".$g."</option>";		
		}
	echo "</select>";
}

// CREA CAMPO DATA E SETTA UNA CERTA DATA
function create_and_set_form_data($giorno, $mese, $anno){
	echo "<select name=\"giorno\">";
		echo " <option value=\"na\">Giorno</option>";
		for($g=1;$g<32;$g++){
			if($g==$giorno)
				echo " <option value=\"".$g."\" selected=selected>".$g."</option>";
			else
				echo " <option value=\"".$g."\">".$g."</option>";

		}
	echo "</select>";

	echo "<select name=\"mese\">";
		echo " <option value=\"na\">Mese</option>";
		for($g=1;$g<13;$g++){
			if($g==$mese)
				echo " <option value=\"".$g."\" selected=selected>".$g."</option>";
			else
				echo " <option value=\"".$g."\">".$g."</option>";	
		}
	echo "</select>";

	echo "<select name=\"anno\">";
		echo " <option value=\"na\">Anno</option>";
		for($g=2010;$g>=1930;$g--){
			if($g==$anno)
				echo " <option value=\"".$g."\" selected=selected>".$g."</option>";
			else
				echo " <option value=\"".$g."\">".$g."</option>";		
		}
	echo "</select>";
}

// FORM PER LA RICERCA
function crea_ricerca_form($titolo,$universita,$corso,$tipo){
		if($tipo==TRUE)
			$pagina="annunci_vendita.php";
		else
			$pagina="annunci_ricerca.php";

		echo"<div id='ricerca'>";
			 echo"<form method=\"POST\" action=\"".$pagina."\">";
			 	echo"<div id=\"camporicerca\">";
			 		echo "Cerca un libro:<br/>";
			 		if($titolo!="")
			 			echo "<input type=\"text\" name=\"titolo\" style=\"width:400px;\" placeholder=\"Scrivi il titolo del libro che vuoi cercare\" value='".$titolo."'>";
			 		else
			 			echo "<input type=\"text\" name=\"titolo\" style=\"width:400px;\" placeholder=\"Scrivi il titolo del libro che vuoi cercare\">";	
			 	echo "</div>";
			 	echo "<div id=\"camporicerca\">";
			 			dynamic_options_ricerca();
			 	echo "</div>";
			 	
			 	echo "<div id=\"camporicerca\">";
			 		echo "Seleziona il corso:<br/>";
			 		echo "<select id=\"txtHint\" name=\"corso\">";
			 			echo "<option value=\"na\">Corso Di Laurea</option>";
			 		echo "</select>";	
			 	echo "</div>";
			 	echo "<div id=\"camporicerca\">";
			 		echo "<input id=\"cercaButton\" name=\"cerca\" type=\"submit\" value=\"Cerca\">";
			 	echo "</div>";

			 echo "</form>";
		echo "</div>";
		
		echo "<div id=\"content\">";
}
?>