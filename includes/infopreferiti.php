<?php
// estrae i disponibili dal db
$elenco = estraePreferiti($db,$username);
$output = creaElencoPreferiti($elenco);
$text = $output['testo'];
$encodedMarkup = $output['inlinek'];
$data = creaMsg($chatID,$text,$encodedMarkup);	
inviaMsg($data,$url,true);
