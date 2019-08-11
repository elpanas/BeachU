<?php
if ($loggato) // l'utente ha effettuato il login
    {
     $elenco = estraePreferiti($db,$username); // estrae i record dal db e li inserisce in un array associativo
     $output = creaElenco($elenco,true); // crea un menu inline con tutti i preferiti appena estratti
     $text = $output['testo'];
     if ($output['inlinek'] != null) $encodedMarkup = $output['inlinek'];
    }
elseif ($dati_reg != NULL) // l'utente è presente ma...
    { 
        if ($dati_reg['psw']) // l'utente deve ancora completare la registrazione ma ha inserito di nuovo il comando /preferiti
            $text = 'Non sei ancora registrato/a
                     Inserire una password priva dello slash iniziale "\"';
        else // l'utente è registrato e deve semplicemente inserire la sua psw   
            { 
            cambiaFlagAttesa($db,$dati_reg['idu']); // indica che il prossimo messaggio sarà una password
            $text = 'Inserire la propria password';   
            }          
    }
else // l'utente non è registrato
    {
        inserisceUtente($db,$username);  // inserisce l'utente nel db     
        $text = 'Inserire una password';
    }

$data = creaMsg($chatID,$text,$encodedMarkup); // compone il messaggio
inviaMsg($data,$url,true); // invia il messaggio
