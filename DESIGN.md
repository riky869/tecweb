## Struttura

### Header

-   logo in alto

### Breadcrumb

### Menu

-   login e registrazione oppure icona utente se loggato
-   lista orizzontale del menù
-   pagine
    -   home
    -   categorie
    -   about
    -   lista utenti se utente loggato amministratore

### Content

-   pulsante per tornare su

### Footer

## Pagine

### Home

-   descrizione del sito, cosa fa, chi è, etc..
-   carosello dei top 3 film con link al loro dettaglio

### Categorie

-   lista di tutte le categorie di film con link per andare alla lista dei film per quella categoria

### Films

-   lista dei film filtrati per parametro query (ricerca di un determinato film) e/o per categoria dei film

### Login

-   pagina di login per amministratore e utenti normali
    che in caso di successo ti manda sulla home e in alto a destra mostra il tuo profilo

### Registrazione

-   solo per utenti normali
-   utente admin creato a db

### Profilo utente

-   mostrare info dell'utente
-   numero film recensiti
-   lista recensioni dell'utente

### Lista utenti

-   visibile solo all'amministratore

### Dettaglio Film con recensioni

-   recensione dell'utente in alto, le altre sotto
-   ordinate per data
-   utenti normali possono aggiungere recensioni
-   amministratore può cancellare recensioni

-   se non loggato può vedere lista recensioni e nascondere possibilità di recensire, aggiungere "se vuoi recensire fai il login"

### Pagina errore 404

-   con stesso layout delle altre ma contenuto
-   "hai sbagliato sala, qui non c'è nessuna proiezione"

### Pagine errore 5xx

## Altro

### Note

-   una recensione per film per persona
-   controllo sql injection
-   caricare file .env in php

### In più

-   redirect a login se non si è loggati quando si scrive una recensione
-   lista cinema vicini a Padova
-   rendere possibile cancellazione delle proprie recensioni
