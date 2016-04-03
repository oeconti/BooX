<?php
/*
funzione per mostrare gli annunci
*/
	function show_annunci($type){
		$query="select * from ";
		if ($type==1)
			$query.="Ricerca";
		else if($type==2)
			$query.="Vendita";
		else
			$query.="NonInEvidenza";
		$query.="join Libro on libro=ISBN";
		connessione_DB();
		$res=mysql_query($qury);
		if($res && mysql_num_rows($res)>0){
			if ($type==1)
				$heading=array("id", "Titolo", "Testo");
			else if($type==2)
				$heading=array("id", "Titolo", "Testo", "Prezzo");
			else
				$heading=array("id", "Titolo", "Testo", "Tipo", "Prezzo");
			echo "<table><tr>";
			foreach ($heading as $h)
				echo "<th>".$h."</th>";
			echo "</tr>";
		}
		else
			echo "Non sono presenti annunci di questo tipo";
	}
?>