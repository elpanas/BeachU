<?php
// define('BOT_TOKEN', '936947513:AAEZUdAcEhYL8tSqF_TdEmZGH97WvwSszf8');
$bot_token = getenv("BOT_TOKEN");
define('API_URL', 'https://api.telegram.org/bot'.$bot_token.'/');
define('MAPBOX_TOKEN', 'pk.eyJ1IjoibHVrZTE5ODMiLCJhIjoiY2p5eTd3eGZ5MWV5YTNkcnI2amJqbWFrbyJ9.jtWFPk-5ju4CcAjZI7nDUg');
define('MAPBOX_URL', 'https://api.mapbox.com/geocoding/v5/mapbox.places/');
define('RICERCA_URL', 'https://www.google.com/search?q=');
define('PATH_TO_SSL_CLIENT_KEY_FILE', 'cert/key.pem');
define('PATH_TO_SSL_CLIENT_CERT_FILE', 'cert/cert.pem');
define('PATH_TO_CA_CERT_FILE', 'cert/ca.pem');
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
define('HOSTNAME', $url['host']);
define('USERNAME', $url['user']);
define('PASSWORD', $url['pass']);
define('DATABASE_NAME', substr($url["path"], 1));
