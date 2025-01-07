# Punti di Accessibilità e Ottimizzazione

## Immagini
- Utilizzare i tag `<img>` per immagini di contenuto e impostare l'immagine come sfondo solo per elementi decorativi. [ACCESSIBILITÀ - 56]
- Specificare gli attributi `width` e `height` nelle immagini per evitare ricalcoli di layout durante il rendering. [HTML - 65]
- Aggiungere un attributo `alt` descrittivo per tutte le immagini, tranne nei casi in cui siano accompagnate da una didascalia tramite `<figcaption>`. [ACCESSIBILITÀ - 56]
- Sostituire i titoli immagine con tecniche di image replacement, mantenendo comunque un'alternativa testuale. [ACCESSIBILITÀ - 58]
- Usare un colore di sfondo simile a quello dell'immagine per garantire la leggibilità del testo in caso di mancato caricamento dell'immagine. [HTML - 42]
- Evitare immagini in movimento o che cambiano stato autonomamente, salvo includere segnali accessibili agli utenti con disabilità visive. [ACCESSIBILITÀ - 33]

## Tabelle
- Non utilizzare le tabelle per scopi di impaginazione. [HTML - 72]
- Includere una didascalia visibile tramite il tag `<caption>` per ogni tabella. [HTML - 65]
- Evitare l'uso di `<table>` per layout o contenitori. [HTML - 72]
- Le parole chiave nella didascalia di una tabella hanno maggiore peso per l'indicizzazione. [ACCESSIBILITÀ - 65]

## Testo e Tipografia
- Preferire font sans-serif per il web e serif per la stampa. [HTML - 49]
- Allineare il testo a bandiera per il web e giustificato per la stampa. [HTML - 49]
- Utilizzare un'interlinea di almeno 1.5 per migliorare la leggibilità. [HTML - 48]
- Evitare misure assolute nei fogli di stile, salvo per la stampa. [HTML - 48]
- Inserire lettere accentate come codici HTML per migliorare il rendering. [HTML - 49]

## Link
- Differenziare i link visitati dai non visitati con colori diversi. [ACCESSIBILITÀ - 45]
- Garantire che tutti i link siano accessibili tramite tabulazione e in un ordine logico. [ACCESSIBILITÀ - 54]
- Fornire un'indicazione della dimensione del file per i link di download. [ACCESSIBILITÀ - 47]
- Usare link non visibili per facilitare la navigazione agli screen reader (es. salti al menu principale). [ACCESSIBILITÀ - 51]

## Accessibilità Generale
- Assicurarsi che tutti i contenuti multimediali abbiano un'alternativa testuale. [ACCESSIBILITÀ - 26]
- Evitare azioni eseguibili solo da utenti vedenti. [ACCESSIBILITÀ - 29]
- Segnalare abbreviazioni, acronimi e espressioni in lingue straniere. [ACCESSIBILITÀ - 31]
- Utilizzare il tag `<abbr>` per abbreviazioni e il tag `<time>` per date e orari, rendendo il contenuto comprensibile indipendentemente dalla lingua. [HTML - 42]
- Evitare l'attributo `accesskey` per i link a causa del rischio di conflitti. [HTML - 69]
- Usare il tag `<button>` per i pulsanti nei form poiché è attivabile tramite il tasto spazio. [ACCESSIBILITÀ - 54]

## Performance
- Ridurre al minimo il numero di file collegati per evitare handshake multipli che allungano i tempi di caricamento. [HTML - 42]
- Evitare di abusare di layout basati su Flex e Grid per ridurre il tempo di rendering. [HTML - 48]
- Garantire tempi di caricamento rapidi per migliorare l'indicizzazione. [HTML - 42]

## SEO e Semantica
- Usare tag semantici come `<header>`, `<footer>`, `<section>` e `<article>` anziché `<div>` generici. [HTML - 42]
- I titoli (`<h1>`, `<h2>`, ecc.) devono essere diversi per evitare penalizzazioni dai motori di ricerca. [HTML - 48]
- Evitare nomi di classi legati all'aspetto; preferire nomi descrittivi e semantici. [HTML - 42]
- Usare tecniche di image replacement per creare testi accattivanti senza compromettere la leggibilità. [ACCESSIBILITÀ - 58]

## Media
- Evitare elementi audio e video senza un'alternativa testuale equivalente. [ACCESSIBILITÀ - 26]
- Segnalare il cambio di stato per elementi animati o dinamici, specialmente per utenti non vedenti o con disabilità cognitive. [ACCESSIBILITÀ - 33]

## Form e Input
- Fornire suggerimenti e dimensioni adeguate per i campi dei form per indicare implicitamente la lunghezza prevista dell'input. [HTML - 69]
- Evitare l'uso dell'attributo `tabindex` nei link, salvo eccezioni giustificate. [HTML - 69]

## Generale
- Mantenere un design accessibile e compatibile con screen reader e dispositivi diversi. [ACCESSIBILITÀ - 51]
- Assicurarsi che tutti gli utenti possano interagire con i contenuti, indipendentemente dalle loro abilità fisiche o cognitive. [ACCESSIBILITÀ - 29]

