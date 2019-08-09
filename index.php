<?php 
require 'settings/config.php';
require 'db.php';
require 'functions.php';
require 'functions_db.php';
require 'mapbox.php';

// inizializza la variabile per i menu
$encodedMarkup = null;
// legge le info in input
$inputhttp = file_get_contents("php://input");
// converte il formato json in array associativo
$content = json_decode($inputhttp,true);

if (isset($content['message']))
    {
    // memorizza i valori che interessano
    $chatID = $content["message"]["chat"]["id"];
    $username = $content["message"]["chat"]["username"];
    $longitudine = isset($content["message"]["location"]["longitude"]) ? $content["message"]["location"]["longitude"] : '';
    $latitudine = isset($content["message"]["location"]["latitude"]) ? $content["message"]["location"]["latitude"] : '';
    $messaggio = isset($content["message"]["text"]) ? $content["message"]["text"] : '';
    $url = API_URL . 'sendMessage'; // url del bot telegram
	
    switch(true) {
    	case ($messaggio == '/start'):
	include 'includes/istruzioni.php';
	break;
	
	case ($longitudine != NULL && $latitudine != NULL):
	include 'includes/geoloc.php';
	break;

    	case ($messaggio == 'Lista Preferiti'):
	include 'includes/infopreferiti.php';
	break;	
	
	default: // ha inserito solo la località
	include 'includes/infolocalita.php';
    	}
    }
elseif(isset($content['callback_query']))
    {
    $callback = $content['callback_query']['data'];
    $id_query = $content['callback_query']['id'];
    $chatID = $content['callback_query']['message']['chat']['id']; 
    $username = $content['callback_query']['message']['chat']['username'];
    $id_preferito = str_replace('/p','',$callback,$count_p);
    $id_stabilimento = str_replace('/s','',$callback,$count_s);
    $url = API_URL . 'answerCallbackQuery'; // url del bot telegram
    $data = array('callback_query_id' => $id_query,
                  'text' => '');
	
    switch(true){ // ha inviato...	
	case ($count_p > 0): // inserisce il preferito nel db     	
	$data['text'] = (inseriscePreferito($db,$username,$id_preferito)) ? 'Preferito aggiunto' : 'Errore';
	inviaMsg($data,$url,true);
	break;
		
	case ($count_s > 0): // info dello stabilimento prescelto
	include 'includes/infostabilimento.php';
	break;
	
	default: // ha inserito solo la località
	include 'includes/infolocalita.php';
	}
    }

$db->close();