# Accessibilità TW

## HTML

### Tag e Attributi

- **Case sensitivity**: Tutti i tag devono essere in minuscolo.
- **Chiusura dei tag**: Ogni tag deve essere chiuso correttamente (esempio: `<br />`).
- **Attributi**: Gli attributi devono sempre avere un valore.
- **Struttura HTML5**: Usare tag semantici come:
  - `<header>` / `<footer>`: Intestazione e piè di pagina di un documento. Utilizzabili più volte all'interno della stessa pagina Anche all'interno di sezioni.
  - `<main>`: Il contenuto principale della pagina.
  - `<nav>`: Contiene elementi di supporto alla navigazione.
  - `<aside>`: Sidebar, contenuto di supporto. Può essere rimosso senza diminuire il significato della pagina. Il contenuto resta legato al contenuto del tag in cui è annidato.
  - `<section>`: Raggruppa contenuti con lo stesso tema.
  - `<article>`: Porzione di testo indipendente dal resto del documento.
- Elementi Generali:
  - `<div>`: Contenitore generico. Contenitore a blocco.
  - `<span>`: Contenitore generico inline. Spesso utilizzato per aggiungere una lingua.
- Per verificare la struttura: <http://gsnedders.html5.org/outliner/>

### Testo, titoli e tipografia

- Il testo deve essere sempre racchiuso in tag `<p>`.
- Scrivere titoli efficaci:
  - Utilizzare la maiuscola per la prima lettera della frase o di ogni parola.
  - Lunghezza massima di 60 caratteri.
- Utilizzare font sans-serif per la pagina web visualizzata e font dotati di serif per la versione da stampare.
- Anche la versione stampata della pagine web deve essere ordinata, ben strutturata e deve avere tutto il contenuto del sito corrispondente (consigliato l'suo di allineamento `justify`).
- L'interlinea dovrebbe essere almeno pari a 1.5em.
- Le lettere accentate (o simili) vanno inserite come codice per velocizzare il rendering.
- Deve esserci un'alternativa testuale per ogni media che non sia testo.
- Tag Nocivi: `<b>`, `<i>`, `<big>`, `<small>`, `<marquee>`, `<blink>`, `<u>`, `<tt>`, `<sub>`, `<sup>`, `<center>`, `<hr>`
- Evitare l'uso del tag `<br>` (unica eccezione sono i form).
- Evitare font elaborati, difficili da leggere, sottolineati o barrati.
- Usare font accessibili. Puoi trovarne qui: <https://www.accessibilitychecking.com/blog/fonts-accessibility/>

### Head

- Sezione Head deve contenere:
  - **Title**
- Sezione Head può contenere:
  - `link`, `meta`, `base`, `style` e `script`
- Alcuni tag meta importanti:
  - **Description**: Descrizione dei contenuti della pagina.
  - **keywords**: Lista di parole chiave separate da una virgola.
  - **Charset**: Definire il set di caratteri con `<meta charset="UTF-8" />`.
- **Base Href**: Utilizzare `<base href="/miacartella/" />`.

### Citazioni

- **Tag per citazioni**:
  - `<blockquote>`: Per ampie citazioni a blocco.
  - `<q>`: Per citazioni brevi in linea.
  - `<cite>`: Per indicare il titolo di un lavoro (libro, film, ecc.).
- La fonte può essere indicata con gli attributi `cite` (URI) o `title`.

### Abbreviazioni, Acronomi e Indirizzi

- **Tag dedicati**:
  - `<abbr>`: Per abbreviazioni.
  - `<address>`: Per identificare un indirizzo.
  - `<acronym>`: Per acronimi (solo in XHTML).
- E' sempre necessario segnalare abbrevazioni e acronimi con i tag sopra indicati quando presenti.

### Testo Formattato

- **Tag utili**:
  - `<code>`: Per inserire codice.
  - `<var>`: Per identificare variabili.
  - `<samp>`: Per output di un programma.
  - `<pre>`: Per testo preformattato (spazi e tabulazioni conservati).
  - `<ins>`: Per identificare inserimenti redazionali (sottolineato).
  - `<del>`: Per identificare cancellazioni redazionali (barrato).

### Tag Semantici

- Tag semantici utili:
  - `<figure>`/`<picture>`: Per immagini e figure con descrizioni.
  - `<mark>`: Per evidenziare testo.
  - `<time>`: Per date e orari (con attributo `datetime`).
  - `<meter>`: Per misure con valori minimi e massimi.
  - `<progress>`: Per indicare un valore in evoluzione.
  - `<small>`: Per note a piè di pagina o termini contrattuali.
- Per le parole in lingua straniera usare `<span lang="[lingua]">` così che vengano pronunciate correttamente dagli screenreader.

### Liste Ordinate

- **Attributi**:
  - `reversed`: Inverte l'ordine.
  - `start`: Specifica il numero iniziale.
  - `type`: Specifica il tipo di marcatore.
  - `value`: Imposta un numero arbitrario per un elemento.

### Elenchi di Definizioni

- **Struttura**:
  - `<dl>`: Lista di definizioni.
  - `<dd>`: Dettaglio della definizione.

### Immagini

- **Attributi Importanti**:
  - `alt`: Testo alternativo.
  - `longdesc`: URI per una descrizione dettagliata.
  - `width` e `height`: Specificano le dimensioni dell'immagine. Vanno usati sempre quando possibile poichè velocizzano il rendering.
- **Figure e Didascalie**:
  - `<figure>`: Per raggruppare immagini.
  - `<figcaption>`: Per aggiungere una didascalia. Se presente `alt` è superfluo.
- Il colore di background deve essere simile a quello dell'immagine per ogni tag contenente un immagine di sfondo (per garantire contrasto anche quando l'immagine non è caricata correttamente).
- Usare il tag `<img>` solo per le immagini di contenuto, mentre le immagini di layout devono essere inserite come background.

### Tag `<picture>`

- Utilizzato per immagini in formati differenti (design responsive).

### Link

- **Attributi**:
  - `target`: Specifica il frame di destinazione (in HTML5).
  - `media`: Per media query.
  - `download`: Forza il download.
- **Accesso da tastiera**:
  - `accesskey`: Specifica un carattere per il focus.
  - `tabindex`: Ordina la tabulazione.
- Evitare l'uso dell'attributo `acceskey` poichè si rischiano spesso conflitti.
- Tutti i link devono essere accessibili mediante tabulazione e l'ordine di tabulazione deve corrispondere all'ordine visivo.
- Indicare la dimensione in caso di download.
- Segnalare quando un link esce dal sito corrente.
- Utilizzare dei link non visibili per aiuti alla navigazione accessibili solo mediante screenreader.
- Per i pulsanti non usare dei link cliccabili ma usare sempre il tag `<button>` poichè questo è attivabile mediante pressione del tasto spazio.
- I link visitati devono avere un colore diverso dai link non visitati. Questa differrenza deve esserci anche per immagini che sono dei link (il modo migliore di farlo e avere tre versioni del link-immagine in una sola ammigine e spostare la posizione del background a seconda che il link sia da cliccare, attivo o cliccato).
- I link dovrebbero essere indicati mediante sottolineatura (si può fare eccezzione per il link del menu di navigazione).
- Evitare i pop-up.

### Tabelle

- **Regole**:
  - Ogni riga deve contenere celle.
  - Celle definite con `<td>`.
  - Intestazioni con `<th>`.
- **Attributi utili**:
  - `colspan` e `rowspan`: Per celle che occupano più colonne o righe.
  - `<caption>`: Titolo della tabella.
  - `aria-describedby`: Breve descrizione del contenuto.
- **Raggruppamento righe**:
  - `<thead>`, `<tbody>`, `<tfoot>`: Intestazione, corpo e piè di pagina ripetibili.
  - `<colgroup>` e `<col>`: Applicano attributi a colonne.
- Mai usare le tabelle come contenitori per stabilire il layout.
- Le keywords nella caption di una tabella hanno peso superiore a del normale testo.
- Non ci possono essere righe senza celle

#### Accessibilità delle tabelle:

- Le tabelle devono trasformarsi in maniera elegante.
- Intestazioni associate alle celle con:
  - `scope`
  - `headers`
  - `abbr`
- Breve descrizione con `aria-describedby` (HTML5).
- **Per dispositivi piccoli**:
  - Usare CSS per:
    - Rendere `<tr>` e `<td>` elementi di blocco (`display:block;`).
    - Nascondere `<thead>`.
    - Aggiungere intestazioni alle celle con `data-title` e `content`.

### Form

- **Metodo**:
  - `method="get"` o `method="post"`.
- **Elementi**:
  - `<input>`: Campi di input vari.
        - Tipi: `text`, `password`, `checkbox`, `radio`, `submit`, `reset`, `hidden`, `file`, `button`, `image`.
  - `<textarea>`: Testo multilinea.
  - `<select>`: Menu a tendina.
- **Attributi utili**:
  - `name`: Identifica l'input inviato.
  - `readonly`: Campo non editabile.
  - `disabled`: Campo disabilitato (non inviato al server).
- **Raggruppamenti**:
  - `<fieldset>`: Raggruppa campi correlati.
  - `<legend>`: Titolo del gruppo.
  - `<label>`: Associa un'etichetta a un campo (`for`).
- Usare suggerimenti ove possibile.
- Le dimensioni dei campi danno un'indicazione all'utente sul quantitativo di testo da inserire.

#### Accessibilità dei form

- **Best Practices**:
  - Raggruppare con `<optgroup>` o `<fieldset>`.
  - Usare `tabindex` e `accesskey` in modo appropriato.
  - Fornire aiuti contestuali con `title`.
  - Corredare ogni campo con `<label>`.
- Per l'invio del form usare il tag `<input>` poichè questo è accessibile mediante pressione del tasto spazio.

### Ruoli e ARIA

- **Ruoli ARIA**:
  - `role="navigation"`, `role="main"`, `role="complementary"`, ecc.
  - Preferire i tag HTML5 quando possibile.
  - `alert`: Per notifiche importanti.
  - `presentation`: Elimina significato semantico (non usabile su `<button>` o `<a>`).
- **Stati ARIA**:
  - `aria-disabled="true"`, `aria-relevant="all"`.
- **Proprietà ARIA**:
  - `aria-labelledby`: Assegna un ID come etichetta.
  - `aria-required`: Indica un campo obbligatorio.
  - `aria-selected`: Indica un elemento selezionato.
  - `aria-describedby`: Descrive relazioni tra elementi.

### Regole ARIA

1. **Preferire HTML specifico**: Usare elementi nativi prima di aggiungere ruoli ARIA.
2. **Non cambiare semantica**: Evitare modifiche se non strettamente necessario.
3. **Accessibilità da tastiera**: Ogni controllo deve essere usabile da tastiera.
4. **Focus e ruoli**: Evitare `role="presentation"` o `aria-hidden="true"` su elementi focalizzabili.
5. **Etichette accessibili**: Tutti gli elementi interattivi devono avere etichette (es. `aria-label`).

- Validate: http://validator.w3.org/nu/

## CSS

### Colore

- utilizzare un foglio di stile collegato (link) per gli stili di base, che possono essere compresi da tutti.
- utilizzare il metodo @import per gli stili sofisticati in modo da nasconderli ai browser più vecchi che farebbero solo confusione.
- In linea di principio la dimensione del testo dovrebbe essere specificata in em e non in pixel per questioni di accessibilità. Questa affermazione però non è sempre vera.
- Evitare di passare informazioni solo tramite il colore.
- Evitare di fare riferimenti al colore nelle istruzioni.
- Contrasto con lo sfondo: 4.5:1 (testo di normali dimensioni) o 3:1 (testo grande).
- Anche i link visitati e non devono avere colori in contrasto fra loro.
- Risorse utili:
  - Palette accessibili in Figma: <https://www.figma.com/community/file/909176640411029401>
  - Tanaguru Contrast Finder: <https://contrast-finder.tanaguru.com/>
  - Color safe picker: <http://colorsafe.co/>
  - Per creare palette: <https://coolors.co/>

## Altri accorgimenti ai fini dell' accessibilità

- Non usare nomi legati all'aspetto per le classi, è meglio scegliere nomi legati alla semantica.
- Usare `tabindex` per definire l'ordine di tabulazione (superfluo se si crea una buona struttura).
- Tutte le azioni che possono essere esguite da utenti vedenti devono poter essere eseguibili anche da utenti non vedenti.
- Per qualunque elemento in movimento o che cambia stato autonomamente, tale cambiamento va segnalato anche all'utente non vedente.
- Separare sempre struttura da presentazione.
- Ogni pagina deve essere dotata di sistemi di navigazione e orientamento (ex. breadcrumb).
- Le pagine devono funzionare anche se le tecnologie più recenti sono disabilitate.
- Gli aggiornamenti automatici non possono essere più di 3 al secondo.
- Il linguaggio deve essere chiaro e semplice. Adatto al pubblico per il quale è pensato. Link utili:
  - testo italiano: <https://farfalla-project.org/readability_static/>
  - testo inglese: <https://www.perrymarshall.com/grade/>
- Mostrare sempre la posizione del focus.
- Il focus deve essere percepibile da tutti, e tutti devono poter capire quando questo viene mosso.

### Validazione

- Usare uno strumento di accessibilità automatico e uno di validazione browser.
- Validare la sintassi (HTML, CSS, etc.).
- Validare i fogli di stile.
- Usare differenti browser grafici (con suoni e immagini caricati/ non caricati, senza mouse, con script, fogli di stile e script non caricati), browser vecchi e nuovi.
- Usare browser o emulatori solo testuali.
- Usare uno screen reader, un software per ipovedenti (magnifier), un display piccolo, etc..
- Usare controlli automatici di spelling e grammatica.
- Rivedere la chiarezza e la semplicità del documento.
- Strumenti di validazione utili:
  - <http://www.totalvalidator.com/validator/Validator>
  - <https://wave.webaim.org/>
  - <https://www.tpgi.com/arc-platform/arc-toolkit/>
  - <https://web.math.unipd.it/accessibility/> -> Non funziona

## SEO

### Rendering veloce

- `flex` e `grid` vanno usati il meno possibile poichè rallentano il rendering. Evitare di 
- Cache utile a velocizzare il rendering, approfondire guardando lezione HTML pagina 112.

### Parole chiave

- Usare `<em>` e  `<strong>` per le parole significative.
- Usare i tag semantici sempre ove possibile.
- Usare `<h1>`, `<h2>`, ... sempre in ordine e cambiare stile con css.
- Può apprarire al massimo un `<h1>` in ogni pagina.
- Ordine di importanza attribuita dal browser al testo a seconda del tag che lo contiene: `<p>` < `<li>` < `<mark>` < `<em>` < `<strong>` < `<h6>` < ... < `<h1>`.

### Ottimizzazione del Metatag Title

- Massimo 55 caratteri, spazi inclusi.
- Inserire una parola chiave all’inizio.
- Scrivere in modo accattivante.
- Utilizzo keyword nel title.

### Ottimizzazione del Metatag Description

- Massimo 145 caratteri, spazi inclusi.
- Deve essere breve, meglio se include una call to action.
- Deve contenere delle parole chiave.

### Alberatura

- Struttura del sito piatta, pagine raggiungibili velocemente
