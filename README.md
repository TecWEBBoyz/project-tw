# Project TW

Questa repository contiene il progetto di Tecnologie Web sviluppato da:

| Nome             | Matricola |
| ---------------- |-----------|
| Baraldo Davide   | 2082847   |
| Monetti Luca     | 2069429   |
| Trolese Leonardo | 2068238   |
| Zocche Tommaso   | 2075547   |

## Struttura

- www : Contiene tutti i file relativi al sito web. Tutti i file che verranno caricati nel server.
- root : Contiene tutti i file di configurazione necessari allo sviluppo locale del progetto e alla sua gestione.

## Installazione Server

Per configurare il progetto su server:

1. Accedere al server tramite ssh.
2. Copiare lo script [scripts/install.sh](https://github.com/TecWEBBoyz/project-tw/blob/main/scripts/install.sh) all'interno della cartella `public_html`
3. Controllare i permessi di esecuzione

```bash
chmod +x install.sh
```

4. Eseguire lo script

```bash
./install.sh
```

## Avvio del Compose

Per avviare l'ambiente tramite Docker Compose, eseguire i seguenti passaggi:

1. Posizionarsi nella directory root del progetto.
2. Controllare che il file `docker-compose.yml` sia configurato correttamente.
3. Avviare Docker Compose con il comando:

```bash
docker-compose up -d
```

4. Verificare che i container siano in esecuzione:

```bash
docker-compose ps
```

## Accesso al Sito Locale

Il sito sarà disponibile localmente sulla porta **8080**.

Aprire il browser e accedere a:

```
http://localhost:3000
```

## Debugging

TDB