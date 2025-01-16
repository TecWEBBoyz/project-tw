#!/bin/bash

# Configurazione
REPO_DIR="/home/baraldodavide/GIT/project-tw"
DOCKER_COMPOSE_FILE="docker-compose-prod.yml"
INTERVAL=5  # Intervallo di controllo in secondi
LOG_FILE="/var/log/watcher.log"  # File di log
PID_FILE="/var/run/watcher.pid"  # File per il PID del processo
MAX_LOG_LINES=1000  # Numero massimo di righe del file di log

cd "$REPO_DIR" || { echo "La directory della repo non esiste!"; exit 1; }

# Mantieni il file di log entro un limite di righe
trim_log_file() {
    if [ -f "$LOG_FILE" ] && [ $(wc -l < "$LOG_FILE") -gt $MAX_LOG_LINES ]; then
        echo "[INFO] Ridimensionamento del file di log..." | tee -a "$LOG_FILE"
        tail -n $MAX_LOG_LINES "$LOG_FILE" > "$LOG_FILE.tmp" && mv "$LOG_FILE.tmp" "$LOG_FILE"
    fi
}

# Ottieni l'ultimo hash di commit remoto
get_remote_commit() {
    git fetch origin > /dev/null 2>&1
    git rev-parse origin/$(git rev-parse --abbrev-ref HEAD)
}

# Ottieni l'ultimo hash di commit locale
get_local_commit() {
    git rev-parse HEAD
}

# Gestisci modifiche locali prima del pull
handle_local_changes() {
    if ! git diff-index --quiet HEAD --; then
        echo "[INFO] Modifiche locali rilevate. Eseguo stash..." | tee -a "$LOG_FILE"
        git stash push -m "Watcher auto-stash" || { echo "[ERRORE] Stash fallito!" | tee -a "$LOG_FILE"; exit 1; }
    fi
}

# Controlla aggiornamenti e ricostruisci il Docker Compose
run_watcher() {
    echo $$ > "$PID_FILE"  # Salva il PID del processo
    trap "rm -f $PID_FILE; exit" SIGINT SIGTERM  # Rimuovi il PID file quando termina

    echo "[INFO] Avvio del Docker Compose..." | tee -a "$LOG_FILE"
    sudo docker compose -f "$DOCKER_COMPOSE_FILE" up --build -d || { echo "[ERRORE] Avvio iniziale di Docker Compose fallito!" | tee -a "$LOG_FILE"; exit 1; }

    while true; do
        trim_log_file
        handle_local_changes
        REMOTE_COMMIT=$(get_remote_commit)
        LOCAL_COMMIT=$(get_local_commit)

        if [ "$REMOTE_COMMIT" != "$LOCAL_COMMIT" ]; then
            echo "[INFO] Nuovo commit rilevato. Eseguo git pull e rebuild..." | tee -a "$LOG_FILE"
            git pull origin $(git rev-parse --abbrev-ref HEAD) || { echo "[ERRORE] git pull fallito!" | tee -a "$LOG_FILE"; exit 1; }
            sudo docker compose -f "$DOCKER_COMPOSE_FILE" down
            sudo docker compose -f "$DOCKER_COMPOSE_FILE" up --build -d || { echo "[ERRORE] Docker Compose rebuild fallito!" | tee -a "$LOG_FILE"; exit 1; }
            echo "[INFO] Rebuild completato con successo." | tee -a "$LOG_FILE"
        else
            echo "[INFO] Nessun nuovo commit. Riprovo tra $INTERVAL secondi..." | tee -a "$LOG_FILE"
        fi

        sleep "$INTERVAL"
    done
}

start() {
    if [ -f "$PID_FILE" ] && kill -0 $(cat "$PID_FILE") 2>/dev/null; then
        echo "[INFO] Il watcher è già in esecuzione (PID $(cat "$PID_FILE"))." | tee -a "$LOG_FILE"
        exit 1
    fi

    echo "[INFO] Avvio watcher in background..." | tee -a "$LOG_FILE"
    nohup "$0" run > /dev/null 2>&1 &
    echo "[INFO] Watcher avviato con successo." | tee -a "$LOG_FILE"
}

stop() {
    if [ -f "$PID_FILE" ] && kill -0 $(cat "$PID_FILE") 2>/dev/null; then
        echo "[INFO] Arresto del watcher (PID $(cat "$PID_FILE"))..." | tee -a "$LOG_FILE"
        kill $(cat "$PID_FILE") && rm -f "$PID_FILE"
        echo "[INFO] Watcher arrestato." | tee -a "$LOG_FILE"
    else
        echo "[INFO] Nessun watcher in esecuzione." | tee -a "$LOG_FILE"
    fi
}

status() {
    if [ -f "$PID_FILE" ] && kill -0 $(cat "$PID_FILE") 2>/dev/null; then
        echo "[INFO] Il watcher è in esecuzione (PID $(cat "$PID_FILE"))." | tee -a "$LOG_FILE"
    else
        echo "[INFO] Il watcher non è in esecuzione." | tee -a "$LOG_FILE"
    fi
}

case "$1" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    status)
        status
        ;;
    run)
        run_watcher
        ;;
    *)
        echo "Uso: $0 {start|stop|status|run}" >&2
        exit 1
        ;;
esac
