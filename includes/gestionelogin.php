<?php
/*
* Se la psw è NULL, la variabile restituita vale 0.
* Quindi questo messaggio (senza slash) deve essere
* la psw inserita dall'utente e non una località
*/
if (!$dati_reg['psw'])
    {
    inseriscePassword($db,$dati_reg['idu'],$messaggio); // inserisce la psw nel db
    inserisceSessione($db,$chatID);  // l'utente è registrato, quindi crea la sessione
    $text = 'Registrazione completata!';
    }
elseif ($flag_psw && !controllaUtente($db,$dati_reg['idu'],$messaggio)) // Se la psw non è NULL, è un login ma la psw è errata
        $text = 'Password errata';
else       
    { 
       inserisceSessione($db,$chatID);  // l'utente è registrato e la psw è corretta, quindi crea la sessione
       cambiaFlagAttesa($db,$dati_reg['idu']); // imposta il flag a 0
       $text = 'Login effettuato. 
                Inserire il comando desiderato o il nome di una località';
    }

$data = creaMsg($chatID,$text,$encodedMarkup);	
inviaMsg($data,$url,true);