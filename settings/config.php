<?php
define('API_URL', 'https://api.telegram.org/bot'.getenv("BOT_TOKEN").'/');
define('MAPBOX_URL', 'https://api.mapbox.com/geocoding/v5/mapbox.places/');
define('RICERCA_URL', 'https://www.google.com/search?q=');
define('MAIL_URL', 'https://api.sendgrid.com/v3/mail/send');
define('PATH_TO_SSL_CLIENT_KEY_FILE', getenv("CERT_PATH").'key.pem');
define('PATH_TO_SSL_CLIENT_CERT_FILE', getenv("CERT_PATH").'cert.pem');
define('PATH_TO_CA_CERT_FILE', getenv("CERT_PATH").'ca.pem');
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
define('HOSTNAME', $url['host']);
define('USERNAME', $url['user']);
define('PASSWORD', $url['pass']);
define('DATABASE_NAME', substr($url["path"], 1));
