<?php
// restituisce la disponibilità di un singolo stabilimento
function estraeDisp($db,$id) {
	
	$query = "SELECT * FROM stabilimenti 
              WHERE id = $id";

    $dati = null; // inizializza la variabile
        
    if($result = $db->query($query)) // effettua la query
        if($result->num_rows > 0) // verifica che esistano record nel db		 
            while($row = $result->fetch_assoc()) // converte in un array associativo
                $dati = array('posti' => $row['posti'],
			      'localita' => $row['localita'],
			      'nome' => $row['nome'],
                              'indirizzo' => $row['civico']." ".$row['indirizzo']." ".$row['cap']." ".$row['localita'],
                              'id' => $row['id']);
    
    // libera la memoria
    $result->free();

    return $dati;
}

// restituisce una lista degli stabilimenti disponibili in una località
function estraeElenco($db,$localita) {
	$query = "SELECT * FROM stabilimenti 
              WHERE localita = '$localita' AND
                    posti > 0";
	
    $elenco = null; // inizializza la variabile     
    $i = 0;
	
    if($result = $db->query($query)) // effettua la query
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	    while($row = $result->fetch_assoc())  // converte in un array associativo	    
		    $elenco[$i++] = array('localita' => $row['localita'],
				      	          'stabilimento' => $row['nome'],
				      	          'posti' => $row['posti'],
				      	          'id' => $row['id']);
    
    // libera la memoria
    $result->free();
	
    return $elenco;
}

function controllaPreferito($db,$ids,$user) {

    $esito = false;
    $user = $db->real_escape_string($user);
    $query = "SELECT id
              FROM preferiti
              WHERE idstab = $ids AND
                    idutente = (SELECT id FROM utenti WHERE username = '$user')";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            $esito = true;
 
    $result->free();
	
    return $esito;
}

function inseriscePreferito($db,$user,$idp) {

    $esito = true;
    $user = $db->real_escape_string($user);
    $query1 = "INSERT INTO utenti SET username = '$user'";
    $query2 = "INSERT INTO preferiti (idstab,idutente)
               VALUES ($idp,(SELECT id FROM utenti WHERE username = '$user'))";

    $db->query($query1);
    if(!$db->query($query2));
        $esito = false;

    $db->close();
	
    return $esito;
}

function estraePreferiti($db,$user){

    $elenco = null;
    $i = 0;    
    $user = $db->real_escape_string($user);
    $query = "SELECT distinct s.nome as nome,
    			      s.id as id,
			      s.localita as localita,
			      s.posti as posti
              FROM preferiti as p JOIN 
                   utenti as u JOIN
                   stabilimenti as s
              WHERE u.username = '$user' AND
                    p.idutente = u.id AND
                    p.idstab = s.id";

    if($result = $db->query($query)) // effettua la query
        if($result->num_rows > 0) // verifica che esistano record nel db				
            while($row = $result->fetch_assoc())  // converte in un array associativo						
                $elenco[$i++] = array('stabilimento' => $row['nome'],
                                      'localita' => $row['localita'],
				      'posti' => $row['posti'],
				      'id' => $row['id']);


    // libera la memoria
    $result->free();

    return $elenco;
    }