<?php
// elimina la password nel db
resetPassword($db,$chatID); 
$data['text'] = 'Reset Effettuato';

// invia la risposta callback
inviaMsg($data,$url,true);   

// invia un messaggio normale           
$url = API_URL . 'sendMessage'; 
$text = 'Inserisci una nuova password';
$data = creaMsg($chatID,$text,null);
inviaMsg($data,$url,true);