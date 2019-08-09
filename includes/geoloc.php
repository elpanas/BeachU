<?php
// converte le coordinate nella località corrispondente
$localita = mapboxReverse($longitudine,$latitudine);
// estrae i disponibili dal db
$elenco = estraeElenco($db,$localita);
$output = creaElenco($elenco);
$text = $output['testo'];
$encodedMarkup = ($output['inlinek'] != null) ? $output['inlinek'] : creaMenuKeyboard();
$data = creaMsg($chatID,$text,$encodedMarkup);
// invia il messaggio POST
inviaMsg($data,$url,true);
