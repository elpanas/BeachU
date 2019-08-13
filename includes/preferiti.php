<?php
$elenco = estraePreferiti($db,$username); // estrae i record dal db e li inserisce in un array associativo
$output = creaElenco($elenco,true); // crea un menu inline con tutti i preferiti appena estratti
$text = $output['testo'];
if ($output['inlinek'] != null) $encodedMarkup = $output['inlinek'];