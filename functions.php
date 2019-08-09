<?php
// compone il messaggio
function creaMsg($chatid,$text,$markup) {
	if($markup != null)
		$data = array(
				'text' => $text,
				'chat_id' => $chatid,
				'parse_mode' => 'html',
				'reply_markup' => $markup);
	else
		$data = array(
				'text' => $text,
				'chat_id' => $chatid,
				'parse_mode' => 'html');
	
	return $data;
}

function inviaMsg($data,$url,$post) {	
	
	//  inizializza l'oggetto connessione
	$ch = curl_init();
	//  imposta l'url
	curl_setopt($ch, CURLOPT_URL, $url);
	if($post) // in caso di chiamata post
	{
		//  imposta il metodo come POST
		curl_setopt($ch, CURLOPT_POST, count($data));
		//  campi della richiesta POST
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
	//  accetta la risposta
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//  esegue la richiesta POST
	$result = curl_exec($ch);
	//  chiude la connessione
	curl_close($ch);
	
	return $result;
}

function creaElenco($elenco) {
	
	$markup = null;
	
	if ($elenco != null)
	{
		$text = '<b>Stabilimenti disponibili:</b>';
		$i = 0;
		foreach ($elenco as $record)	
		{			
			$inline_keyboard['inline_keyboard'][$i][0]['text'] = $record['stabilimento'].': '.$record['posti'];
			$inline_keyboard['inline_keyboard'][$i][0]['callback_data'] = '/s'.$record['id'];
			$i++;
		}
    	$markup = json_encode($inline_keyboard);
	}
	else
		$text = 'Non ci sono stabilimenti disponibili';
	
	$output = array('inlinek' => $markup,
			'testo' => $text);
	
	return $output;
}

function creaElencoPreferiti($elenco) {
	
	$markup = null;
	
	if ($elenco != null)
	{
		$text = '<b>Stabilimenti preferiti:</b>';
		$i = 0;
		foreach ($elenco as $record)	
		{			
			$inline_keyboard['inline_keyboard'][$i][0]['text'] = $record['stabilimento'].' ('.$record['localita'].'): '.$record['posti'];
			$inline_keyboard['inline_keyboard'][$i][0]['callback_data'] = '/s'.$record['id'];
			$i++;
		}
    	$markup = json_encode($inline_keyboard);
	}
	else
		$text = 'Lista preferiti vuota';
	
	$output = array('inlinek' => $markup,
			        'testo' => $text);
	
	return $output;
}

function creaMenukeyboard() {
	// array contenente le voci di menu
	$replyMarkup = array('keyboard' => array(array(array('text' => 'Preferiti'),
												   array('text' => 'Invia posizione',
														 'request_location' => true))),
						 'resize_keyboard' => true,
						 'one_time_keyboard' => false
					);

	// codifica l'array in formato json
	return json_encode($replyMarkup);
}
