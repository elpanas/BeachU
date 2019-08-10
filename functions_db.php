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

function cambiaFlagAttesa($db,$idu) {
    return $db->query("UPDATE utenti SET attesa_psw = IF(attesa_psw = 1,0,1) WHERE id = $idu");
}

function inseriscePassword($db,$idu,$password) { 
    $psw = hash('sha1',str_replace('/','',$password));  
    return $db->query("UPDATE utenti SET password = '$psw', attesa_psw = 0 WHERE id = $idu");
}

function inserisceSessione($db,$chatid) {
    return $db->query("INSERT INTO sessioni SET chatid = '$chatid', loggato = 1");
}

function inserisceUtente($db,$user) {
    
    $user = $db->real_escape_string($user);

    return $db->query("INSERT INTO utenti SET username = '$user'");
}

// controlla se l'utente è loggato
function controllaSessione($db,$chatid){

    $loggato = 0;   

    $db->query("DELETE FROM sessioni WHERE TIMEDIFF(NOW(),scadenza) > '24:00:00'") or die($db->mysql_error);
   
    $query = "SELECT loggato FROM sessioni WHERE chatid = $chatid";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            while($row = $result->fetch_assoc())
                $loggato = $row['loggato'];
 
    $result->free();

    return $loggato;
}

// controlla se l'utente ha la password
function controllaReg($db,$user) {

    $dati = null;
    $user = $db->real_escape_string($user);
    $query = "SELECT id,
                     ISNULL(password) as psw,
                     attesa_psw
              FROM utenti
              WHERE username = '$user'";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            while($row = $result->fetch_assoc())
                $dati = array('idu' => $row['id'],
                              'psw' => $row['psw'],
                              'attesa_psw' => $row['attesa_psw']);
 
    $result->free();

    return $dati;
}

// controlla se l'utente ha la password
function controllaUtente($db,$idu,$password) {

    $esito = false;
    $psw = hash('sha1',str_replace('/','',$password));
    $query = "SELECT * FROM utenti WHERE id = $idu AND password = '$psw'";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            $esito = true;
 
    $result->free();

    return $esito;
}

// controlla se lo stabilimento è già in elenco
function controllaPreferito($db,$ids,$user) {

    $esito = false;
    $query = "SELECT id FROM preferiti 
              WHERE idstab = $ids AND 
                    idutente = (SELECT id FROM utenti WHERE username = '$user')";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            $esito = true;
 
    $result->free();
	
    return $esito;
}

function inseriscePreferito($db,$user,$idp) {

    $user = $db->real_escape_string($user);
    $query = "INSERT INTO preferiti (idstab,idutente)
              VALUES ($idp,(SELECT id FROM utenti WHERE username = '$user'))";
	
    return $db->query($query);
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
