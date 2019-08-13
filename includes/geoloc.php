<?php
// converte le coordinate nella località corrispondente
$localita = mapboxReverse($longitudine, // input: longitudine
                          $latitudine); // input: latitutdine

$elenco = estraeElenco($db,$localita); // estrae i disponibili dal db
$output = creaElenco($elenco,false); // crea un array con l'insieme degli stabilimenti forniti
$text = $output['testo'];

if ($output['inlinek'] != null) $encodedMarkup = $output['inlinek'];
