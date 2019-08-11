<?php
// compone il messaggio
function creaMsg($chatid,   // input: id della chat
                 $text,     // input: testo del messaggio
                 $markup) { // input: menu allegato al messaggio
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
	
	return $data; // output: dati da allegare al messaggio
}

// invia il messaggio
function inviaMsg($data,        // input: dati allegati al messaggio
                  $url,         // input: indirizzo di destinazione (bot Telegram)
                  $post        // input: se è true usa il metodo POST altrimenti GET
                  ) {  

    $headers = array('Content-type: applocation/json');    
	
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

    // inserisce gli l'headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//  accetta la risposta
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//  esegue la richiesta POST
	$result = curl_exec($ch);
	//  chiude la connessione
	curl_close($ch);
	
	return $result; // output: risposta del destinatario
}

// crea un menu inline con gli stabilimenti sulla base dell'input fornito
function creaElenco($elenco,$pref) { // input: elenco degli stabilimenti in una località
	
	$markup = null;
	
	if ($elenco != null)
	{
        $text = '<b>Stabilimenti ';
		$text .= ($pref) ? 'preferiti:</b>' : 'disponibili:</b>';
		$i = 0;
		foreach ($elenco as $record)	
		{	
            if ($pref)		
                $inline_keyboard['inline_keyboard'][$i][0]['text'] = $record['stabilimento'].' ('.$record['localita'].'): '.$record['posti'];
            else
			    $inline_keyboard['inline_keyboard'][$i][0]['text'] = $record['stabilimento'].': '.$record['posti'];

			$inline_keyboard['inline_keyboard'][$i][0]['callback_data'] = '/s'.$record['id'];
			$i++;
		}
    	$markup = json_encode($inline_keyboard); // converte l'array in formato json
	}
	else
		$text = ($pref) ? 'Lista preferiti vuota' : 'Non sono stabilimenti disponibili';
	
	$output = array('inlinek' => $markup,
			        'testo' => $text);
	
	return $output; // array associativo con i dati che comporranno il messaggio
}

function creaMenukeyboard() {
	// array contenente le voci di menu
	$replyMarkup = array('keyboard' => array(array(array('text' => '/preferiti'),
												   array('text' => '/posizione',
														 'request_location' => true))),
						 'resize_keyboard' => true,
						 'one_time_keyboard' => false
					);

	// codifica l'array in formato json
	return json_encode($replyMarkup);
}
