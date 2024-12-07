# Project TW

Questa repository contiene il progetto di Tecnologie Web sviluppato da:

| Nome             | Matricola |
| ---------------- | --------- |
| Baraldo Davide   |           |
| Monetti Luca     | 2069429   |
| Trolese Leonardo |           |
| Zocche Tommaso   |           |

## Struttura

- www : Contiene tutti i file relativi al sito web. Tutti i file che verranno caricati nel server.
- / : Contiene tutti i file di configurazione necessari allo sviluppo locale del progetto e alla sua gestione.

## Installazione Server

Per configurare il progetto su server:

1. Accedere al server tramite ssh.
2. Copiare lo script [scripts/install.sh](https://github.com/TecWEBBoyz/project-tw/blob/main/scripts/install.sh) all'interno della cartella `public.html`
3. Controllare i permessi di esecuzione
```bash
chmod +x install.sh
```
4. Eseguire lo script
```bash
./install.sh
```