<?php
// connessione al database
$db = mysqli_init(); // inizializza l'oggetto db

if (!$db) die('Errore di inizializzazione');

// imposta i dati per una connessione SSL sicura
$db->ssl_set(PATH_TO_SSL_CLIENT_KEY_FILE, 
             PATH_TO_SSL_CLIENT_CERT_FILE,
             PATH_TO_CA_CERT_FILE, null, null); 

// effettua la connessione
if(!$db->real_connect(HOSTNAME,
                      USERNAME,
                      PASSWORD,
                      DATABASE_NAME,
                      3306,
                      null,
                      MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT))
        die('Errore di connessione ('.$db->connect_errno.') '.$db->connect_error);
