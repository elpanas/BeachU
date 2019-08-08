<?php
function mapboxReverse($long, // input: longitudine
                       $lat){ // input: latitudine

    $url = MAPBOX_URL.$long.','.$lat.'.json?access_token='.MAPBOX_TOKEN.'&types=address';
   
    $inputhttp = inviaMsg(null,$url,false);
    
    $content = json_decode($inputhttp,true);

    $loc = $content['features'][0]['context'][1]['text'];
    
    return $loc; // loc: array con la località
}
/*
function mapboxForward($indirizzo){ // input: indirizzo

    $url = MAPBOX_URL.$indirizzo.'.json?limit=1&access_token='.MAPBOX_TOKEN;
   
    $inputhttp = inviaMsg(null,$url,false);
    
    $content = json_decode($inputhttp,true);
   
   echo '<pre>';
   print_r($content);
   echo '</pre>';

    $long = $content['features'][0]['center'][0]['text'];
    $lat = $content['features'][0]['center'][1]['text'];

    $output = array('longitudine' => $long,
                    'latitudine' => $lat);
    
    return $output; // output: array con le coordinate
}*/
