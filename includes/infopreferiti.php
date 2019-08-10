<?php
if ($loggato)
    {
     // estrae i disponibili dal db
     $elenco = estraePreferiti($db,$username);
     $output = creaElencoPreferiti($elenco);
     $text = $output['testo'];
     if ($output['inlinek'] != null) $encodedMarkup = $output['inlinek'];
    }
elseif ($dati_reg != NULL) 
    {
        if ($dati_reg['psw']) // l'utente deve ancora completare la registrazione ma ha inserito di nuovo il comando /preferiti
            $text = 'Non sei ancora registrato/a
                     Inserire una password priva dello slash iniziale "\"';
        else       
            {
            cambiaFlagAttesa($db,$dati_reg['idu']); // imposta il flag a 1
            $text = 'Inserire la password'; // l'utente è registrato e deve semplicemente inserire la sua psw  
            }          
    }
else // l'utente non è registrato
    {
        inserisceUtente($db,$username);        
        $text = 'Inserire una password';
    }

$data = creaMsg($chatID,$text,$encodedMarkup);	
inviaMsg($data,$url,true);
