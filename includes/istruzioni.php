<?php
$text = '<b>***Istruzioni***</b>
Questo bot controlla la disponibilità di ombrelloni degli stabilimenti balneari, solo usando:
<b>Località:</b>
invia un messaggio con il nome della località per ricevere una lista degli stabilimenti disponibili.
Esempio: "riccione" oppure "torre pedrera"
<b>Posizione:</b>
premi sul bottone in basso per visualizzare gli stabilimenti disponibili vicini alla tua posizione attuale
<b>/preferiti:</b>
visualizza la tua lista degli stabilimenti preferiti
<b>/reset:</b>
resetta la password per eccedere ai preferiti';

$encodedMarkup = creaMenuKeyboard();    // crea il menu a tastiera
