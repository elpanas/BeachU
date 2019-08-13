<?php
function gestioneLogin($db,$username,$dati_utente,$msg) {

    $output = array('loggato' => false,
                    'testo' => '');

    if ($dati_utente == NULL) // l'utente non è registrato
        { 
        inserisceUtente($db,$username);  // inserisce l'utente nel db     
        $output['testo'] = 'Crea una password';
        }
    elseif ($dati_utente['password'] == NULL) // l'utente c'è ma non la password
        {
        if (strpos($msg,'/') !== false) // l'utente ha inserito una password inacettabile "/"
            $output['testo'] = 'Non sei ancora registrato/a
                     Inserisci una password priva dello slash iniziale "/"';
        else
            {
            inseriscePassword($db,$dati_utente['idu'],$messaggio); // inserisce la psw nel db            
            $output['testo'] = 'Registrazione completata!';
            $output['loggato'] = true;
            }
        }
    elseif (!$dati_utente['attesa_psw'])
            {
            cambiaFlagAttesa($db,$dati_utente['idu']); // imposta il flag a 1
            $output['testo'] = 'Inserire la password'; // l'utente è registrato ma non loggato
            }
    elseif ($dati_utente['password'] != $messaggio) // la psw c'è, è un login ma la psw è errata
            $output['testo'] = 'Password errata';
    else       
        { 
        inserisceSessione($db,$dati_utente['idu']);  // l'utente è registrato e la psw è corretta, quindi crea la sessione
        cambiaFlagAttesa($db,$dati_utente['idu']); // imposta il flag a 0
        $output['testo'] = 'Login effettuato!';
        $output['loggato'] = true;
        }

    return $output;
    }