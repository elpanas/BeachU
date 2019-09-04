# BeachU
![Logo](https://github.com/elpanas/BeachU/blob/master/images/logo.png)
## Sviluppatore

**Nome:** Luca 

**Cognome:** Panariello 

**Matricola:** 289182

## Descrizione
Una generica Domenica di Agosto. 38 °C
Decidete di andare al mare. Avete organizzato tutto. Arrivate sul lungomare e... lo stabilimento non ha più posto, quindi chiedete al successivo e così ancora per 15 bagni!. D'ora in poi tutto ciò sarà un ricordo.
BeachU è un'applicazione sviluppata per piattaforma Telegram che permette di verificare in tempo reale la disponibilità di ombrelloni presso un qualunque stabilimento in base alle informazioni fornite dall'utente: semplicemente la posizione o il nome della località.

## Struttura e scelte implementative
L'applicazione è attualmente costituita da 2 componenti:
- **Client:**  bot Telegram
- **Server:** un web service remoto implementato in PHP

#### Il Client
E' stato creato utilizzando oggetti e metodi forniti dall'API di Telegram. Si è scelto di utilizzare Telegram per la sua semplicità di utilizzo e la sua base installata piuttosto ampia.

#### Il Web Service
Il fornitore dello spazio web è Heroku.
Il WS è stato implementato in linguaggio PHP v7, utilizzando un approccio prevalentemente procedurale, a parte rare eccezioni.

#### Il Database
E' stato scelto un database MySQL, in particolare ClearDB sempre fornito da Heroku, come add-on. Nel database vengono memorizzate le informazioni sugli stabilimenti e sugli utenti.

Il database è organizzato in 3 tabelle:

**Preferiti**

| id | idstab | idutente |
| -- | ------ | -------- |
| integer | integer | integer |

**Stabilimenti**

| id | nome | località | posti | indirizzo | civico | cap | provincia |
| -- | ---- | -------- | ----- | --------- | ------ | --- | --------- |
| integer | varchar(45) | varchar(45) | tinyint | varchar(45) | smallint | mediumint | varchar(45) |

**Utenti**

| id | username | password | attesa_psw | sessione |
| -- | -------- | -------- | ---------- | -------- |
| integer | varchar(45) | varchar(45) | tinyint | datetime |

## Funzionalità
- #### Ricerca per località
Inserendo il nome della località, appare una lista degli stabilimenti disponibili e il numero di posti/ombrelloni ancora liberi, a patto che lo stabilimento sia presente nell'archivio.
- #### Ricerca per posizione
Per mezzo della funzione di telegram o del pulsante nel menu che appare in basso */posizione*, è possibile inviare la propria posizione, attraverso la quale il web service restituisce una lista degli stabilimenti disponibili nelle vicinanze.
- #### Lista Preferiti
Sempre dal menu in basso, cliccando sul bottone <code>/preferiti</code>, è possibile visualizzare una lista degli stabilimenti preferiti, previa registrazione.

## Dettagli tecnici
L'API basa il suo funzionamento sull'interscambio di dati tra client e server per mezzo di richieste HTTP così formate:

#### HTTP Requests client/server
Le richieste HTTP tra il client (bot telegram) e il web service sono di tipo POST.

Sia quelle in input che output trasportano dati in formato JSON, preventivamente (de)codificati per mezzo dell'apposita funzione <code>json_encode/decode()</code> fornita dal framework PHP.

I dati all'interno dei file json, sono costituiti da voci di menu, informazioni sulla posizione e password.

Per l'invio delle richieste HTTP è stata utilizzata la classe _cURL_.

Le richieste tra il web service e l'API Mapbox vengono effettuate con metodo GET, poichè è l'unico supportato dalla suddeta API.

#### Query al DB
Vengono effettuate in linguaggio SQL e inviate per mezzo della classe mysqli, messa a disposizione dal framework PHP.
La creazione delle tabelle è stata invece effettuata per mezzo del software MySQL Workbench.

#### Sicurezza
Le richieste HTTP avvengono tutte con protocollo https, sia quelle POST che GET.

La comunicazione con il DB avviene con autenticazione SSL.

I parametri esterni delle query sql vengono preventivamente "trattati" con la funzione <code>mysqli_real_escape_string()</code> di PHP per evitare SQL Injiections.

Le liste preferiti sono accessibili solo agli utenti registrati, con una semplice password. In caso di smarrimento può essere resettata direttamente dal client Telegram.

Le password degli utenti e il codice identificativo della chat memorizzati nel db, vengono convertiti con codifica hash di tipo sha1

I dati relativi alla configurazione, quali API Keys e dati di accesso al DB, sono memorizzati in variabili d'ambiente, impostate tramite l'interfaccia Heroku. Il file in cui sono memorizzati questi dati sensibili si trova al di fuori dello spazio pubblico, proprio per evitare accessi indesiderati.

## Codifiche

### Database

**Colonne**
* testo: UTF-8
* date: ISO 8601 (YYYY-MM-DD HH:MM)

### Messaggi

**URL**
* RFC 3986

**Corpo dei messaggi:**
* text/plain
* application/json
* application/vnd.geo+json

**Codifica caratteri**
* UTF-8

## Approfondimento
Per ulteriori dettagli implementativi e analisi del codice, si rimanda al wiki: [BeachU Wiki](https://github.com/elpanas/BeachU/wiki)

## Specifica OpenAPI
[Clicca qui](https://github.com/elpanas/BeachU/blob/master/openapi-beachu.yaml)

## API esterne
#### Mapbox
Mapbox è un servizio simile a google maps, che fornisce informazioni sulla base della posizione. In questo caso è stata sfruttata la sua API gratuita per effettuare un Reverse Geocoding, cioè la conversione delle coordinate geografiche (latitudine  e longitudine) in semplici indirizzi "umani".
Tale conversione viene effettuata nel caso l'utente fornisca la sua posizione. Il web service prende le coordinate dal messaggio telegram, le invia all'api di Mapbox e riceve come risposta l'indirizzo, da cui poi estrapola la località e, sulla base di questa, effettua la ricerca degli stabilimenti.

Anche se alcuni servizi avanzati legati all'API sono a pagamento, Mapbox è fornito in licenza open-source, come si può leggere qui: https://www.mapbox.com/about/open/

## Messa online del servizio
Avviene automaticamente, in quanto il fornitore dello spazio web, Heroku, è collegato alla repository GitHub dell'app in questione. Ad ogni modifica, i file presenti sul branch indicato vengono caricati sui server Heroku.

## Esempio di utilizzo
Per la natura del servizio non è stato possibile usare dei dataset Opendata. Quindi il database è stato popolato con dati fittizi, al fine di effettuare alcune prove. In futuro è prevista l'implementazione di un secondo client, che permetterà ai gestori degli stabilimenti di fornire le informazioni sulla posizione (l'indirizzo) e soprattutto il numero di ombrelloni disponibili man mano che vengono occupati.

### Info sul test

**Client**
* Modello: HTC One M9
* Sistema Operativo: Android 7.0 Nougat
* Piattaforma: Telegram X (Versione 0.21.9.1172)

**Server**
* Server Web: Apache 2

Per l'esposizione delle funzionalità e per alcuni esempi si rimanda al wiki dell'app:
[BeachU-Wiki](https://github.com/elpanas/BeachU/wiki)

## Conclusione
Ovviamente l'utente (bagnante) non può fare tutto da solo. C'è bisogno della collaborazione dei gestori degli stabilimenti. Se ci pensate bene è un vantaggio anche per loro. In primis per...: 

- i lidi meno conosciuti o difficili da raggiungere (quindi con più posti liberi ;) )
- i lidi più grandi. Molti bagnanti rinunciano o optano per la spiaggia libera perchè non sanno che c'è l'ultimo ombrellone vacante a pochi metri da loro.

Camminare sotto il sole, cercando un posto libero, costa fatica e sudore. Il nostro sudore è fatto d'acqua... e l'acqua non va sprecata.
