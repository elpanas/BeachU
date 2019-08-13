<?php
// controlla se l'utente è loggato ed elimina le sessioni scadute
function aggiornaSessione($db,       // input: oggetto per comunicare col database
                          $user){    // input: id della chat
    $user = $db->real_escape_string($user); // elimina caratteri extra dal parametro
    $db->query("UPDATE utenti
                SET sessione = 0
                WHERE TIMEDIFF(NOW(),sessione) > '24:00:00' AND
                      username = '$user'");
}

// modifica il flag che indica l'attesa di una password
function cambiaFlagAttesa($db,      // input: oggetto per comunicare col database 
                          $idu) {   // input: id utente
    return $db->query("UPDATE utenti SET attesa_psw = IF(attesa_psw = 1,0,1) WHERE id = $idu");
}

// controlla se lo stabilimento è nella lista preferiti dell'utente
function controllaPreferito($db,        // input: oggetto per comunicare col database
                            $ids,       // input: id dello stabilimento nel db
                            $user) {    // input: username telegram

    $esito = false;
    $query = "SELECT id FROM preferiti 
              WHERE idstab = $ids AND 
                    idutente = (SELECT id FROM utenti WHERE username = '$user')";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            $esito = true;
 
    $result->free(); // libera la memoria
	
    return $esito; // output: indica se i parametri esistono nel db
}

// restituisce la disponibilità di un singolo stabilimento
function estraeDisp($db,$id) {
	
	$query = "SELECT * FROM stabilimenti WHERE id = $id";

    $dati = null; // inizializza la variabile
        
    if($result = $db->query($query)) // effettua la query
        if($result->num_rows > 0) // verifica che esistano record nel db		 
            while($row = $result->fetch_assoc()) // converte in un array associativo
                $dati = array('posti' => $row['posti'],
			                  'localita' => $row['localita'],
			                  'nome' => $row['nome'],
                              'indirizzo' => $row['civico']." ".$row['indirizzo']." ".$row['cap']." ".$row['localita'],
                              'id' => $row['id']);
    
    $result->free(); // libera la memoria

    return $dati;
}

// restituisce una lista degli stabilimenti disponibili in una data località
function estraeElenco($db,          // input: oggetto per comunicare col database
                      $localita) {  // input: luogo dove cercare gli stabilimenti
	$query = "SELECT * FROM stabilimenti WHERE localita = '$localita' AND posti > 0";
	
    $elenco = null; // inizializza la variabile     
    $i = 0;
	
    if($result = $db->query($query)) // effettua la query
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	    while($row = $result->fetch_assoc())  // converte in un array associativo	    
		    $elenco[$i++] = array('localita' => $row['localita'],
				      	          'stabilimento' => $row['nome'],
				      	          'posti' => $row['posti'],
				      	          'id' => $row['id']);
    
    $result->free(); // libera la memoria
	
    return $elenco; // array 
}

// estrae i preferiti di un utente dal database
function estraePreferiti($db,       // input: oggetto per comunicare col database
                         $user){    // input: username telegram

    $elenco = null;
    $i = 0;    
    $user = $db->real_escape_string($user); // elimina caratteri extra dal parametro
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

    $result->free(); // libera la memoria

    return $elenco; // output: elenco con i preferiti
}

// controlla se l'utente è registrato ma deve inserire la password
function estraeUtente($db,      // input: oggetto per comunicare col database
                      $user) {  // input: username telegram                     

    $dati = null;
    $user = $db->real_escape_string($user); // elimina caratteri extra dal parametro
    $query = "SELECT id,
                     password,
                     attesa_psw,
                     IF(TIMEDIFF(NOW(),sessione) > '24:00:00',0,1) as loggato
              FROM utenti
              WHERE username = '$user'";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            while($row = $result->fetch_assoc())
                $dati = array('idu' => $row['id'],
                              'password' => $row['password'],
                              'attesa_psw' => $row['attesa_psw'],
                              'loggato' => $row['loggato']);
 
    $result->free(); // libera la memoria

    return $dati; // output: array associativo con i dati
}

// codifica e inserisce la password nella colonna omonima
function inseriscePassword($db,         // input: oggetto per comunicare col database 
                           $idu,        // input: id utente
                           $password) { // input: password dell'utente
    $psw = hash('sha1',str_replace('/','',$password));  
    $db->query("UPDATE utenti 
                SET password = '$psw', 
                    attesa_psw = 0,
                    sessione = NOW()
                    WHERE id = $idu");
}

// inserisce uno stabilimento tra i preferiti
function inseriscePreferito($db,    // input: oggetto per comunicare col database
                            $user,  // input: username telegram
                            $idp) { // input: id dello stabilimento preferito

    $user = $db->real_escape_string($user); // output: 
    $query = "INSERT INTO preferiti (idstab,idutente)
              VALUES ($idp,(SELECT id FROM utenti WHERE username = '$user'))";
	
    return $db->query($query); // output: indica il buon/cattivo esito della query
}

function inserisceSessione($db,$idu) {
    $db->query("UPDATE utenti SET sessione = NOW() WHERE id = $idu");
}

// inserisce un nuovo utente
function inserisceUtente($db,       // input: oggetto per comunicare col database
                         $user) {   // input: username telegram 
    $user = $db->real_escape_string($user);
    return $db->query("INSERT INTO utenti SET username = '$user'");
}

// resetta password
function resetPassword($db,$username) {	
	$db->query("UPDATE utenti SET password = NULL WHERE username = '$username'");
}