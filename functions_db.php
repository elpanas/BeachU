<?php
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

// resetta password
function resetPassword($db,$username) {	
	$db->query("UPDATE utenti SET password = NULL, attesa_psw = 1 WHERE username = '$username'");
    $db->query("DELETE FROM sessioni WHERE username = '$username'");
}

// modifica il flag che indica l'attesa di una password
function cambiaFlagAttesa($db,      // input: oggetto per comunicare col database 
                          $idu) {   // input: id utente
    return $db->query("UPDATE utenti SET attesa_psw = IF(attesa_psw = 1,0,1) WHERE id = $idu");
}

// codifica e inserisce la password nella colonna omonima
function inseriscePassword($db,         // input: oggetto per comunicare col database 
                           $idu,        // input: id utente
                           $password) { // input: password dell'utente
    $psw = hash('sha1',str_replace('/','',$password));  
    return $db->query("UPDATE utenti SET password = '$psw', attesa_psw = 0 WHERE id = $idu");
}

// crea una nuova sessione e imposta il flag loggato a 1
function inserisceSessione($db,         // input: oggetto per comunicare col database 
                           $chatid) {   // input: id della chat
    return $db->query("INSERT INTO sessioni SET chatid = '$chatid', loggato = 1");
}

// inserisce un nuovo utente
function inserisceUtente($db,       // input: oggetto per comunicare col database
                         $user) {   // input: username telegram 
    $user = $db->real_escape_string($user);
    return $db->query("INSERT INTO utenti SET username = '$user'");
}

// controlla se l'utente è loggato ed elimina le sessioni scadute
function controllaSessione($db,         // input: oggetto per comunicare col database
                           $chatid){    // input: id della chat

    $loggato = 0;   

    $db->query("DELETE FROM sessioni WHERE TIMEDIFF(NOW(),scadenza) > '24:00:00'");
   
    $query = "SELECT loggato FROM sessioni WHERE chatid = $chatid";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            while($row = $result->fetch_assoc())
                $loggato = $row['loggato'];
 
    $result->free(); // libera la memoria

    return $loggato; // output: indica se l'utente è loggato
}

// controlla se l'utente è registrato ma deve inserire la password
function controllaReg($db,      // input: oggetto per comunicare col database
                      $user) {  // input: username telegram

    $dati = null;
    $user = $db->real_escape_string($user); // elimina caratteri extra dal parametro
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
 
    $result->free(); // libera la memoria

    return $dati; // output: array associativo con i dati
}

// controlla se l'utente esiste
function controllaUtente($db,           // input: oggetto per comunicare col database
                         $idu,          // input: id utente
                         $password) {   // input: password dell'utente

    $esito = false;
    $psw = hash('sha1',str_replace('/','',$password)); // codifica la password in sha1
    $query = "SELECT * FROM utenti WHERE id = $idu AND password = '$psw'";
   
    if($result = $db->query($query))
        if ($result->num_rows > 0)
            $esito = true;
 
    $result->free(); // libera la memoria

    return $esito;  // output: indica se i parametri esistono e sono corretti
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

// inserisce uno stabilimento tra i preferiti
function inseriscePreferito($db,    // input: oggetto per comunicare col database
                            $user,  // input: username telegram
                            $idp) { // input: id dello stabilimento preferito

    $user = $db->real_escape_string($user); // output: 
    $query = "INSERT INTO preferiti (idstab,idutente)
              VALUES ($idp,(SELECT id FROM utenti WHERE username = '$user'))";
	
    return $db->query($query); // output: indica il buon/cattivo esito della query
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
