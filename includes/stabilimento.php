<?php 
inviaMsg($data,$url,true); // invia la risposta al callback

$dati_stab = estraeDisp($db,$id_stabilimento); // estrae i dati dello stabilimento
$coordinate = forwardMapbox($dati_stab['indirizzo']); // codifica l'indirizzo per essere inserito in un url

// controlla se è già presente tra i preferiti e se l'utente è loggato
if (!controllaPreferito($db,$id_stabilimento,$username))
    {
        $inline_keyboard = array('inline_keyboard' => array(array(array('text' => 'Aggiungi ai preferiti',
                                                                                  'callback_data' => '/p'.$id_stabilimento))));
		
        $encodedMarkup = json_encode($inline_keyboard); // converte l'array in formato json
    }

$text = 'Lo stabilimento '.$dati_stab['nome'].' a '.$dati_stab['localita'].' ha '.$dati_stab['posti'].' ombrelloni disponibili'.PHP_EOL;

$url = API_URL . 'sendLocation'; // url del bot telegram
