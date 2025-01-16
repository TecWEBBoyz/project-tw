#!/bin/bash

# Configurazione
REPO_DIR="/home/baraldodavide/project-tw"
DOCKER_COMPOSE_FILE="docker-compose-prod.yml"
BRANCH="main"  # Specifica il branch da monitorare
INTERVAL=30  # Intervallo di controllo in secondi

cd "$REPO_DIR" || { echo "La directory della repo non esiste!"; exit 1; }

# Ottieni l'ultimo hash di commit remoto
get_remote_commit() {
    git fetch origin "$BRANCH" > /dev/null 2>&1
    git rev-parse origin/$BRANCH
}

# Ottieni l'ultimo hash di commit locale
get_local_commit() {
    git rev-parse HEAD
}

# Cambia branch se necessario
ensure_on_branch() {
    CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
    if [ "$CURRENT_BRANCH" != "$BRANCH" ]; then
        echo "[INFO] Cambiando al branch $BRANCH..."
        git checkout "$BRANCH" || { echo "[ERRORE] Cambio branch fallito!"; exit 1; }
    fi
}

# Controlla aggiornamenti e ricostruisci il Docker Compose
run_watcher() {
    while true; do
        ensure_on_branch
        REMOTE_COMMIT=$(get_remote_commit)
        LOCAL_COMMIT=$(get_local_commit)

        if [ "$REMOTE_COMMIT" != "$LOCAL_COMMIT" ]; then
            echo "[INFO] Nuovo commit rilevato sul branch $BRANCH. Eseguo git pull e rebuild..."
            git pull origin "$BRANCH" || { echo "[ERRORE] git pull fallito!"; exit 1; }
            docker-compose -f "$DOCKER_COMPOSE_FILE" down
            docker-compose -f "$DOCKER_COMPOSE_FILE" up --build -d || { echo "[ERRORE] Docker Compose rebuild fallito!"; exit 1; }
            echo "[INFO] Rebuild completato con successo."
        else
            echo "[INFO] Nessun nuovo commit sul branch $BRANCH. Riprovo tra $INTERVAL secondi..."
        fi

        sleep "$INTERVAL"
    done
}

# Esegui in background
run_watcher &
echo "[INFO] Watcher avviato in background sul branch $BRANCH con PID $!"

# Script per terminare il watcher
kill_watcher() {
    PID=$(pgrep -f "run_watcher")
    if [ -z "$PID" ]; then
        echo "[INFO] Nessun watcher in esecuzione."
    else
        kill "$PID" && echo "[INFO] Watcher con PID $PID terminato." || echo "[ERRORE] Impossibile terminare il watcher."
    fi
}

# Verifica se lo script viene eseguito per terminare
if [ "$1" == "stop" ]; then
    kill_watcher
    exit 0
fi
