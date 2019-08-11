<?php
$localita = mapboxReverse($longitudine,$latitudine); // converte le coordinate nella località corrispondente

$elenco = estraeElenco($db,$localita); // estrae i disponibili dal db
$output = creaElenco($elenco); // crea un array con l'insieme degli stabilimenti forniti
$text = $output['testo'];

if ($output['inlinek'] != null) $encodedMarkup = $output['inlinek'];

$data = creaMsg($chatID,$text,$encodedMarkup); // compone il messaggio
inviaMsg($data,$url,true); // invia il messaggio
