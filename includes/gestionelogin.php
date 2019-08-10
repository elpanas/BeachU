<?php
/*
* Se la psw � NULL, la variabile restituita vale 0.
* Quindi questo messaggio (senza slash) deve essere
* la psw inserita dall'utente e non una localit�
*/
if (!$dati_reg['psw'])
    {
    inseriscePassword($db,$dati_reg['idu'],$messaggio); // inserisce la psw nel db
    inserisceSessione($db,$chatID);  // l'utente � registrato, quindi crea la sessione
    $text = 'Registrazione completata!';
    }
elseif ($flag_psw && !controllaUtente($db,$dati_reg['idu'],$messaggio)) // Se la psw non � NULL, � un login ma la psw � errata
        $text = 'Password errata';
else       
    { 
       inserisceSessione($db,$chatID);  // l'utente � registrato e la psw � corretta, quindi crea la sessione
       cambiaFlagAttesa($db,$dati_reg['idu']); // imposta il flag a 0
       $text = 'Login effettuato. 
                Inserire il comando desiderato o il nome di una localit�';
    }

$data = creaMsg($chatID,$text,$encodedMarkup);	
inviaMsg($data,$url,true);