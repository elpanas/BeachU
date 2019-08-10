<?php 
// invia la risposta al callback	
inviaMsg($data,$url,true);

// Estrae i dati dello stabilimento
$dati_stab = estraeDisp($db,$id_stabilimento); 
$indirizzo = urlencode($dati_stab['indirizzo']);

// controlla se è già presente tra i Preferiti
if (!controllaPreferito($db,$id_stabilimento,$username) && controllaSessione($db,$chatID))
    {
        // crea il bottone inline
        $inline_keyboard = array('inline_keyboard' => array(array(array('text' => 'Aggiungi ai preferiti',
                                                                                  'callback_data' => '/p'.$id_stabilimento))));		
        $encodedMarkup = json_encode($inline_keyboard);
    }
else
    $encodedMarkup = creaMenuKeyboard();

// testo del messaggio
$text = 'Lo stabilimento '.$dati_stab['nome'].' a '.$dati_stab['localita'].' ha '.$dati_stab['posti'].' ombrelloni disponibili'.PHP_EOL;    
$text .= '<a href="'.RICERCA_URL.$indirizzo.'">Vai alle indicazioni</a>';
$url = API_URL . 'sendMessage'; // url del bot telegram
// crea il messaggio
$data = creaMsg($chatID,$text,$encodedMarkup);	
// invia il messaggio
inviaMsg($data,$url,true);
