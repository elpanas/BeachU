<?php
$text = "Continuando resetterai la password attuale. I tuoi preferiti NON saranno cancellati";

$inline_keyboard['inline_keyboard'][0][0]['text'] = 'Premi qui per confermare';
$inline_keyboard['inline_keyboard'][0][0]['callback_data'] = '/rY';

$encodedMarkup = json_encode($inline_keyboard);
