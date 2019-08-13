<?php
function gestioneLogin($db,$username,$dati_utente) {

    $loggato = false;

    if ($dati_utente == NULL) // l'utente non � registrato
        { 
        inserisceUtente($db,$username);  // inserisce l'utente nel db     
        $text = 'Crea una password';
        }
    elseif ($dati_utente['password'] == NULL) // l'utente c'� ma non la password
        {
        if (strpos($messaggio,'/') >= 0) // l'utente ha inserito una password inacettabile "/"
            $text = 'Non sei ancora registrato/a
                     Inserisci una password priva dello slash iniziale "/"';
        else
            {
            inseriscePassword($db,$dati_utente['idu'],$messaggio); // inserisce la psw nel db            
            $text = 'Registrazione completata!';
            $loggato = true;
            }
        }
    elseif (!$dati_utente['attesa_psw'])
            {
            cambiaFlagAttesa($db,$dati_utente['idu']); // imposta il flag a 1
            $text = 'Inserire la password'; // l'utente � registrato ma non loggato
            }
    elseif ($dati_utente['password'] != $messaggio) // la psw c'�, � un login ma la psw � errata
            $text = 'Password errata';
    else       
        { 
        inserisceSessione($db,$dati_utente['idu']);  // l'utente � registrato e la psw � corretta, quindi crea la sessione
        cambiaFlagAttesa($db,$dati_utente['idu']); // imposta il flag a 0
        $text = 'Login effettuato!';
        $loggato = true;
        }

    return $loggato;
    }