<?php
$text = "Continuando resetterai la password attuale. I tuoi dati NON saranno cancellati";

$inline_keyboard['inline_keyboard'][0][0]['text'] = 'Premi qui per confermare';
$inline_keyboard['inline_keyboard'][0][0]['callback_data'] = '/rY';

$encodedMarkup = json_encode($inline_keyboard);

$data = creaMsg($chatID,$text,$encodedMarkup); // crea il messaggio

inviaMsg($data,$url,true); // invia il messaggio
