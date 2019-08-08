<?php 
$elenco = null;
// estrae i disponibili dal db
$elenco = estraeElenco($db,$messaggio);
$output = creaElenco($elenco);
$text = $output['testo'];
$encodedMarkup = $output['inlinek'];
$data = creaMsg($chatID,$text,$encodedMarkup);	
inviaMsg($data,$url,true);
