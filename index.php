<?php 
require 'includes/config.php';
require 'db.php';
require 'functions/f_database.php';
require 'functions/f_messaggio.php';
require 'functions/f_gestionelogin.php';
require 'functions/f_mapbox.php';

$encodedMarkup = creaMenuKeyboard(); // inizializza la variabile per i menu
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
    aggiornaSessione($db,$username);
    $dati_utente = estraeUtente($db,$username);

    if($dati_utente != NULL) // estrae i dati dell'utente
        {
        $loggato = $dati_utente['loggato'];
        if ($flag_psw = $dati_utente['attesa_psw']) // se attende la password    
            {       
            $output = gestioneLogin($db,$username,$dati_utente,$messaggio); 
            $loggato = $output['loggato'];
            $text = $output['testo'];
            }
        } 
    else
        $flag_psw = $loggato = false;                       
    	
    switch(true) {
    	case $messaggio == '/start':
	    include 'includes/istruzioni.php';
	    break;
	
	    case ($longitudine != NULL && $latitudine != NULL):
	    include 'includes/geoloc.php';
	    break;	       

        case $messaggio == '/preferiti':
        if (!$loggato && !$flag_psw) 
            {
            $output = gestioneLogin($db,$username,$dati_utente,$messaggio);
            $text = $output['testo'];
            }
        else
            include 'includes/preferiti.php';
        break;
            
        case $messaggio == '/reset':
        if (!$loggato && !$flag_psw) 
            {
            $output = gestioneLogin($db,$username,$dati_utente,$messaggio);
            $text = $output['testo'];
            }
        else
            include 'includes/resetpsw.php';
        break;

        default: // ha inserito la località  
        include 'includes/localita.php'; 
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
    $reset_psw = str_replace('/r','',$callback,$count_r); // elimina la parte extra che identifica il tipo di valore 
    $url = API_URL . 'answerCallbackQuery'; // url del bot telegram
    $data = array('callback_query_id' => $id_query,
                  'text' => '');

    $loggato = ($dati_utente = estraeUtente($db,$username) != NULL) ? $dati_utente['loggato'] : 0;
	
    switch(true) {	
	    case $count_p > 0: // inserisce lo stabilimento nella lista dell'utente     
	    $data['text'] = (inseriscePreferito($db,$username,$id_preferito)) ? 'Preferito aggiunto' : 'Errore';        
	    inviaMsg($data,$url,true); // invia il messaggio
	    break;
		
	    case $count_s > 0: // info dello stabilimento prescelto
	    include 'includes/stabilimento.php';
	    break;

        case $count_r > 0:
        include 'includes/resetpswcallback.php';
        break;
	
	    default: // ha inserito la località
	    include 'includes/localita.php';
	    }
    }

$data = creaMsg($chatID,$text,$encodedMarkup);	// compone il messaggio
inviaMsg($data,$url,true);  // invia il messaggio

$db->close();