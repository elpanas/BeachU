<?php
function mapboxReverse($long, // input: longitudine
                       $lat){ // input: latitudine

    $url = MAPBOX_URL.$long.','.$lat.'.json?access_token='.MAPBOX_TOKEN.'&types=address'; // indirizzo per le richieste all'API
   
    $inputhttp = inviaMsg(null,$url,false); // invia il messaggio GET
    
    $content = json_decode($inputhttp,true); // converte il contenuto json in array 

    $loc = $content['features'][0]['context'][1]['text'];
    
    return $loc; // loc: array con la località
}
