<?php 
require 'settings/config.php';
require 'db.php';
require 'functions.php';
require 'functions_db.php';
require 'mapbox.php';

$provamail = '{"personalizations": [{"to": [{"email": "test@example.com"}]}],"from": {"email": "test@example.com"},"subject": "Sending with SendGrid is Fun","content": [{"type": "text/plain", "value": "and easy to do anywhere, even with cURL"}]}';

echo '<pre>';
print_r(json_decode($provamail));
echo '</pre>';

$encodedMarkup = null; // inizializza la variabile per i menu

$inputhttp = file_get_contents("php://input"); // legge le info in input

$content = json_decode($inputhttp,true); // converte il formato json in array associativo

if (isset($content['message'])) // è stato ricevuto un messaggio normale
    {
    // memorizza i valori che interessano
    $chatID = $content["message"]["chat"]["id"];
    $username = $content["message"]["chat"]["username"];
    $longitudine = isset($content["message"]["location"]["longitude"]) ? $content["message"]["location"]["longitude"] : '';
    $latitudine = isset($content["message"]["location"]["latitude"]) ? $content["message"]["location"]["latitude"] : '';
    $messaggio = isset($content["message"]["text"]) ? $content["message"]["text"] : '';
    $url = API_URL . 'sendMessage'; // url del bot telegram
    $encodedMarkup = creaMenuKeyboard(); // crea il menu a tastiera

    $loggato = controllaSessione($db,$chatID); // Verifica che esista una sessione

    if (!$loggato)
    {
        $dati_reg = controllaReg($db,$username); // verifica se l'utente ha completato la registrazione

        if ($dati_reg != NULL) // registrazione in attesa di password
            $flag_psw = $dati_reg['attesa_psw']; // flag che indica che il msg è una password
    }
    else
        $dati_reg = $flag_psw = NULL;   
	
    switch(true) {
    	case ($messaggio == '/start'):
	    include 'includes/istruzioni.php';
	    break;
	
	    case ($longitudine != NULL && $latitudine != NULL):
	    include 'includes/geoloc.php';
	    break;

    	case ($messaggio == '/preferiti'):
	    include 'includes/infopreferiti.php';
	    break;	
	
	    case ($dati_reg != NULL && $flag_psw): // l'utente non ha ancora completato la reg     
        include 'includes/gestionelogin.php';	
        break;    

        default: // ha inserito la località  
        include 'includes/infolocalita.php'; 
    	}
    }
elseif(isset($content['callback_query'])) // è stato ricevuto un messaggio proveniente da un bottone inline
    {
    $callback = $content['callback_query']['data'];
    $id_query = $content['callback_query']['id'];
    $chatID = $content['callback_query']['message']['chat']['id']; 
    $username = $content['callback_query']['message']['chat']['username'];
    $id_preferito = str_replace('/p','',$callback,$count_p);    // elimina la parte extra che identifica il tipo di valore
    $id_stabilimento = str_replace('/s','',$callback,$count_s); // elimina la parte extra che identifica il tipo di valore 
    $url = API_URL . 'answerCallbackQuery'; // url del bot telegram
    $data = array('callback_query_id' => $id_query,
                  'text' => '');
	
    switch(true) {	
	    case ($count_p > 0): // inserisce lo stabilimento nella lista dell'utente     
	    $data['text'] = (inseriscePreferito($db,$username,$id_preferito)) ? 'Preferito aggiunto' : 'Errore';        
	    inviaMsg($data,$url,true); // invia il messaggio
	    break;
		
	    case ($count_s > 0): // info dello stabilimento prescelto
	    include 'includes/infostabilimento.php';
	    break;
	
	    default: // ha inserito la località
	    include 'includes/infolocalita.php';
	    }
    }

$db->close();
