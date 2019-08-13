<?php
// elimina la password nel db
resetPassword($db,$chatID,$username); 
$data['text'] = 'Reset Effettuato';

// invia la risposta callback
inviaMsg($data,$url,true);   

// invia un messaggio normale           
$url = API_URL . 'sendMessage'; 
$text = 'Inserisci una nuova password';
$encodedMarkup = null;