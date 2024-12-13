# Connessione SSH e Accesso al Sito Web

## Prerequisiti

1. **Docker**
   Assicurati che Docker sia installato e configurato sul tuo sistema.

2. **File di configurazione**
   Il file `docker-compose-tecwebssh.yml` e il file delle variabili `.env` devono essere configurati correttamente.

3. **File `.env`**
   Il file `.env` deve contenere le seguenti variabili:
   ```env
   SSH_USERNAME=tuo_username
   SSH_PASSWORD=tuo_password
   ```

   Assicurati che il file `.env` sia salvato nella stessa directory del file `docker-compose-tecwebssh.yml`.

---

## Avvio dei Servizi

1. **Esegui Docker Compose**

   Per avviare i container, utilizza il comando:
   ```bash
   docker-compose -f docker-compose-tecwebssh.yml up -d
   ```

   Questo comando avvierà i servizi definiti nel file `docker-compose-tecwebssh.yml`.

2. **Verifica lo stato dei container**

   Usa il seguente comando per verificare che i container siano in esecuzione:
   ```bash
   docker ps
   ```
   Dovresti vedere due container:
    - `ssh_tunnel_1` per il sito web
    - `ssh_tunnel_2` per l'accesso SSH

---

## Connessione SSH

1. **Host e Porta**

   Puoi connetterti al server SSH tramite:
    - **Host**: `localhost`
    - **Porta**: `22`

2. **Comando SSH**

   Usa il seguente comando per connetterti:
   ```bash
   ssh ${SSH_USERNAME}@localhost -p 22
   ```
   Sostituisci `${SSH_USERNAME}` con il tuo nome utente specificato nel file `.env`.

3. **Password**

   Quando richiesto, inserisci la password specificata nel file `.env` (`SSH_PASSWORD`).

---

## Accesso al Sito Web

1. **URL del Sito**

   Dopo aver avviato i container, il sito web è disponibile all'indirizzo:
   ```
   http://localhost:3000/iltuousername
   ```

2. **Verifica l'accesso**

   Apri un browser web e naviga all'indirizzo sopra. Dovresti vedere il contenuto del sito web ospitato su `tecweb.studenti.math.unipd.it`.

---

## Arresto dei Servizi

1. **Spegni i container**

   Per arrestare i container, usa il comando:
   ```bash
   docker-compose -f docker-compose-tecwebssh.yml down
   ```

2. **Rimuovi i container**

   Questo comando fermerà e rimuoverà tutti i container definiti nel file `docker-compose-tecwebssh.yml`.

---

## Note di Sicurezza

1. **Protezione delle credenziali**
    - Non condividere il file `.env`.
    - Aggiungi il file `.env` a `.gitignore` per evitare di includerlo nei repository Git.

2. **Restrizioni di rete**
    - Assicurati di utilizzare firewall o altre configurazioni di sicurezza per proteggere l'accesso alle porte esposte.


