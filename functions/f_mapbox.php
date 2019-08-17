<?php
function mapboxReverse($long, // input: longitudine
                       $lat){ // input: latitudine

    $url = MAPBOX_URL.$long.','.$lat.'.json?access_token='.getenv("MAPBOX_TOKEN").'&types=address'; // indirizzo per le richieste all'API   
    $inputhttp = inviaMsg(null,$url,false); // invia il messaggio GET    
    $content = json_decode($inputhttp,true); // converte il contenuto json in array 
    $loc = $content['features'][0]['context'][1]['text']; // output: località/paese

    return $loc; // loc: array con la località
}

function mapboxForward($indirizzo) {
    
    $url = MAPBOX_URL.urlencode($indirizzo).'.json?limit=1&access_token='.getenv("MAPBOX_TOKEN"); // indirizzo per le richieste all'API   
    $inputhttp = inviaMsg(null,$url,false); // invia il messaggio GET    
    $content = json_decode($inputhttp,true); // converte il contenuto json in array 
    $coordinate['latitudine'] = $content['features'][0]['center'][0]; // output: coordinate
    $coordinate['longitudine'] = $content['features'][0]['center'][1];

    return $coordinate; // loc: array con la località
}