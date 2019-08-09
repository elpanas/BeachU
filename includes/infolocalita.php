<?php 
$elenco = null;
// estrae i disponibili dal db
$elenco = estraeElenco($db,$messaggio);
$output = creaElenco($elenco);
$text = $output['testo'];
$encodedMarkup = ($output['inlinek'] != null) ? $output['inlinek'] : creaMenuKeyboard();
$data = creaMsg($chatID,$text,$encodedMarkup);	
inviaMsg($data,$url,true);
