<?php

$headers = array();

$headers['personalizations'][0]['to'][0]['email'] = 'test@example.com';
$headers['from']['email'] = 'test@example';
$headers['subject'] = 'Sending with SendGrid is Fun';
$headers['content'][0]['type'] = 'text/plain';
$headers['content'][0]['value'] = 'and easy to do anywhere, even with cURL';

echo '<pre>';
print_r($headers);
echo '</pre>';

echo '<pre>';
print_r(json_encode($headers));
echo '</pre>';


//inviaMsg($url,);