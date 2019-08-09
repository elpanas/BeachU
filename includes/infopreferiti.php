<?php
// estrae i disponibili dal db
$elenco = estraePreferiti($db,$username);
$output = creaElencoPreferiti($elenco);
$text = $output['testo'];
$encodedMarkup = ($output['inlinek'] != null) ? $output['inlinek'] : creaMenuKeyboard();
$data = creaMsg($chatID,$text,$encodedMarkup);	
inviaMsg($data,$url,true);
