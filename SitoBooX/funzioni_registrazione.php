<?php
//----------form registrazione---------------
function first_form(){
	echo "<h1>Registrati</h1> <form action=registrazione.php method=POST>
		Inserisci la tua mail<br/> <input type=email name=email /><br/><br/>
		Inserisci la password<br/> <input type=password name=pwd /><br/><br/>
		Ripeti la password<br/> <input type=password name=pwd2 /><br/><br/>
		Inserisci nome<br/> <input type=text name=nome /><br/><br/>
		Inserisci cognome<br/> <input type=text name=cognome /><br/><br/>
		Inserisci data di nascita<br/>";
	crea_form_data();
	echo "<br/><br/>";
	dynamic_options();
	echo " <input type=submit name=registrazione value=Registrati /></form>";
}

//-------------form registrazione in caso di campi vuoti--------------------
function campi_vuoti($email, $pwd, $pwd2, $nome, $cognome, $giorno_n, $mese_n, $anno_n){
	$c="<font color=red>campo obbligatorio</font>";
	echo "<h1>Registrati</h1> <form action=registrazione.php method=POST>";
	if(!empty($email)){
		echo "Inserisci la tua mail<br/> <input type=email name=email value=".$email." /><br/><br/>";
	}
	else
		echo "Inserisci la tua mail<br/> <input type=email name=email /><br/>".$c."<br/><br/>";
	echo "Inserisci la password<br/> <input type=password name=pwd />";
	if(empty($pwd))
		echo "<br/>".$c;
	echo "<br/><br/>Ripeti la password<br/> <input type=password name=pwd2 />";
	if(empty($pwd2))
		echo "<br/>".$c;
	if($pwd!=$pwd2&&!empty($pwd)&&!empty($pwd2))
		echo "<br/><font color=red>password e password di conferma differenti</font></br>";
	if(!empty($nome))
		echo "<br/><br/>Inserisci nome<br/> <input type=text name=nome value=".$nome." /><br/>";
	else
		echo "<br/><br/>Inserisci nome<br/> <input type=text name=nome /><br/>".$c."<br/><br/>";
	if(!empty($cognome))
		echo "Inserisci cognome<br/> <input type=text name=cognome value=".$cognome." /><br/><br/>";
	else
		echo "Inserisci cognome<br/> <input type=text name=cognome /><br/>".$c."<br/><br/>";
		echo "Inserisci data di nascita<br/>"; create_and_set_form_data($giorno_n,$mese_n,$anno_n);
	if (controllo_data($giorno_n, $mese_n, $anno_n)==false)
		echo "<br/><font color=red>Data non corretta</font>";
		echo "<br/><br/>";
	dynamic_options();
	echo " <input type=submit name=registrazione value=Registrati /></form>";
}

//--------------from registrazione in caso di errori------------------------
function error_form($diffpwd,$errdate,$presente,$email, $pwd, $pwd2, $nome, $cognome, $giorno_n, $mese_n, $anno_n){
	echo "<h1>Registrati</h1> <form action=registrazione.php method=POST>";
	if($presente)
		echo "Inserisci la tua mail<br/> <input type=email name=email /><br/><font color=red>email gi&agrave presente</font><br/><br/>";
	else
		echo "Inserisci la tua mail<br/> <input type=email name=email value=".$email." /><br/><br/>";
	echo "Inserisci la password<br/> <input type=password name=pwd />";
	echo "<br/><br/>Ripeti la password<br/> <input type=password name=pwd2 />";
	if($diffpwd)
		echo "<br/><font color=red>password e password di conferma differenti</font></br>";
	echo "<br/><br/>Inserisci nome<br/> <input type=text name=nome value=".$nome." /><br/>";
	echo "Inserisci cognome<br/> <input type=text name=cognome value=".$cognome." /><br/><br/>";
	echo "Inserisci data di nascita<br/>"; create_and_set_form_data($giorno_n,$mese_n,$anno_n);
	if ($errdate)
		echo "<br/><font color=red>Data non corretta</font>";
	echo "<br/><br/>";
	dynamic_options();
	echo " <input type=submit name=registrazione value=Registrati /></form>";
}
?>