<?php
/*
funzioni per la creazione degli script utilizzati nella pubblicazione di annunci
*/

#------------form pubblicazione annunci-----------------
function pubblicaform($error){
	echo "<h1>Pubblica Annuncio di ".$_POST['type']."</h1> <form action=pubblica_annuncio.php method=POST>";
	if ($error==0)	
		echo "Inserisci l'ISBN del libro:<br/> <input type=text name=isbn /><br/><br/>";
	else if ($error==1){
		echo "Inserisci l'ISBN del libro:<br/> <input type=text name=isbn /><br/><br/>";
		echo "<font color=red>campo obbligatorio</font><br/><br/>";
	}
	else
		echo "Inserisci l'ISBN del libro:<br/> <input type=text name=isbn value=".$_POST['isbn']." /><br/><br/>";
	
	
	if ($error==0)
		echo "Inserisci il testo dell'annuncio:<br/> <textarea name=testo cols=27 rows=5></textarea><br/><br/>";
	else
		echo "Inserisci il testo dell'annuncio:<br/> <textarea name=testo value=".$_POST['testo']." cols=27 rows=5></textarea><br/><br/>";
	
	if ($_POST['type']=="Vendita"){
		if ($error==0)
			echo "Inserisci il prezzo di vendita:<br/><input type=number name=prezzo min=0 step=0.01 />";
		else if($error==2){
			echo "Inserisci il prezzo di vendita:<br/><input type=number name=prezzo min=0 step=0.01 />";
			echo "<font color=red>campo obbligatorio</font><br/><br/>";
		}
		else
			echo "Inserisci il prezzo di vendita:<br/><input type=number name=prezzo min=0 step=0.01 value=".$_POST['prezzo']." />";	
	}
	echo "<br/><br/><br/><br/>
		Corso a cui è associato questo libro:<br/><br/>";
	if($error==0)
	    echo "Nome corso<br/> <input type=text name=insegnamento /><br/>";
	else if ($error==3){
		echo "Nome corso<br/> <input type=text name=insegnamento /><br/>";
		echo "<font color=red>se riempito il campo corso devono essere compilato il campo università e viceversa</font><br/><br/>";
	}
	else
		echo "Nome corso<br/> <input type=text name=insegnamento value='".$_POST['insegnamento']."' /><br/>";
	dynamic_options();
	echo "<input type=hidden name=type value=".$_POST['type']." />
		<input type=hidden name=insertAnnuncio value=1 />
		<input type=submit name=pubblica value=Pubblica /></form>";
}

//-----form inserimanto libro----------
function first_inseriscilibroform(){
	echo "<h1>Libro non presente nel database!</h1><br/>
		 <h2 style=\"align=center;\">Aiutaci a migliorare!<br/> Compila il seguente form per inserire il libro nel database e completare l'annuncio</h3><br/>
		<form action=inserimento_libro.php method=POST>
		ISBN:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$_SESSION['isbn']."<br/><br/><br/>
		Titolo: &nbsp &nbsp <input type=text name=titolo placeholder=\"Titolo del libro\" style='width:240px;' /> </br><br/><br/>
		Autore:&nbsp  <input type=text name=AutoreNome placeholder=Nome style='width:240px;' /> &nbsp  
		&nbsp &nbsp  <input type=text name=AutoreCognome placeholder=Cognome style='width:240px;' /> </br><br/><br/>
		Editore: &nbsp <input type=text name=editore placeholder=\"Nome editore\" style='width:240px;' /> </br><br/><br/>
		Prezzo: &nbsp <input type=number name=prezzo min=0 step=0.01 placeholder=\"prezzo di copertina\" style='width:240px;'/> </br><br/><br/>
		Anno: &nbsp &nbsp &nbsp <input type=text name=anno placeholder=\"Anno di pubblicazione\" /><br/><br/><br/>
		<input type=submit name=insertlibro value=Inserisci style='width:200px;heigth:55px;' /></form>";
}

//------form inserimento libro in caso di errori nel primo inserimento----------
function inseriscilibroform(){
	$c="<font color=red>campo obbligatorio</font>";
	echo "<h1>Libro non presente nel database!</h1><br/>
		 <h2 style=\"align=center;\">Aiutaci a migliorare!<br/> Compila il seguente form per inserire il libro nel database e completare l'annuncio</h3><br/>
		<form action=inserimento_libro.php method=POST>
		ISBN:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$_SESSION['isbn']."<br/><br/><br/>";
		if(empty($_POST['titolo'])){
			echo "Titolo: &nbsp &nbsp <input type=text name=titolo placeholder=\"Titolo del libro\" style='width:240px;' /><br/>";
			echo $c." </br><br/><br/>";
		} else{
			echo "Titolo: &nbsp &nbsp <input type=text name=titolo value=".$_POST['titolo']." placeholder=\"Titolo del libro\" style='width:240px;' /> </br><br/><br/>";
		}
		if (!empty($_POST['AutoreNome']) && !empty($_POST['AutoreCognome'])){
			echo "Autore:
			&nbsp  <input type=text name=AutoreNome value=".$_POST['AutoreNome']." placeholder=Nome style='width:240px;' /> &nbsp  
			&nbsp &nbsp  <input type=text name=AutoreCognome value=".$_POST['AutoreCognome']." placeholder=Cognome style='width:240px;' /> </br><br/><br/>";
		} else {
			if (empty($_POST['AutoreNome']))
				echo "Autore: &nbsp  <input type=text name=AutoreNome placeholder=Nome style='width:240px;' /> &nbsp";
			else
				echo "Autore: &nbsp  <input type=text name=AutoreNome value=".$_POST['AutoreNome']." placeholder=Nome style='width:240px;' /> &nbsp";
			if (empty($_POST['AutoreCognome']))
				echo "&nbsp  <input type=text name=AutoreCognome placeholder=Cognome style='width:240px;' /> <br/>";
			else
				echo "&nbsp  <input type=text name=AutoreCognome value=".$_POST['AutoreCognome']." placeholder=Cognome style='width:240px;' /> <br/>";
			echo $c." </br><br/><br/>";
		}
		if (empty($_POST['editore'])){
			echo "Editore: &nbsp <input type=text name=editore placeholder=\"Nome editore\" style='width:240px;' /> </br>";
			echo $c." </br><br/><br/>";
		}
		else
			echo "Editore: &nbsp <input type=text name=editore value=".$_POST['editore']." placeholder=\"Nome editore\" style='width:240px;' /> </br><br/><br/>";
		if (empty($_POST['prezzo']))
			echo "Prezzo: &nbsp <input type=number name=prezzo min=0 step=0.01 placeholder=\"prezzo di copertina\" style='width:240px;'/> </br><br/><br/>";
		else
			echo "Prezzo: &nbsp <input type=number name=prezzo min=0 step=0.01 value=".$_POST['prezzo']." placeholder=\"prezzo di copertina\" style='width:240px;'/> </br><br/><br/>";			
		if (empty($_POST['anno']))
			echo "Anno: &nbsp &nbsp &nbsp <input type=text name=anno placeholder=\"Anno di pubblicazione\" /><br/><br/><br/>";
		else
			echo "Anno: &nbsp &nbsp &nbsp <input type=text name=anno value=".$_POST['anno']." placeholder=\"Anno di pubblicazione\" /><br/><br/><br/>";
		echo "<input type=submit name=insertlibro value=Inserisci style='width:200px;heigth:55px;' /></form>";
}
?>