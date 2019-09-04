<?php 
inviaMsg($data,$url,true); // invia la risposta al callback

$dati_stab = estraeDisp($db,$id_stabilimento); // estrae i dati dello stabilimento
$coordinate = mapboxForward($dati_stab['indirizzo']); // codifica l'indirizzo per essere inserito in un url

$url = API_URL . 'sendMessage'; // url del bot telegram
$data['text'] = 'Nome: '.$dati_stab['nome'].'\n';
$data['text'] .= 'Indirizzo: '.$dati_stab['indirizzo'];

if ($dati_stab['telefono'] > 0)
    $data['text'] .= '\nTelefono: '.$dati_stab['telefono'];

inviaMsg($data,$url,true); // invia un messaggio testuale con le informazioni

// controlla se è già presente tra i preferiti e se l'utente è loggato
if (!controllaPreferito($db,$id_stabilimento,$username))
    {
        $inline_keyboard = array('inline_keyboard' => array(array(array('text' => 'Aggiungi ai preferiti',
                                                                                  'callback_data' => '/p'.$id_stabilimento))));
		
        $encodedMarkup = json_encode($inline_keyboard); // converte l'array in formato json
    }

$url = API_URL . 'sendLocation'; // url del bot telegram