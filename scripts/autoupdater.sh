#!/bin/bash

# Configurazione
REPO_DIR="/home/baraldodavide/GIT/project-tw"
DOCKER_COMPOSE_FILE="docker-compose-prod.yml"
INTERVAL=5  # Intervallo di controllo in secondi
LOG_FILE="/var/log/watcher.log"  # File di log

cd "$REPO_DIR" || { echo "La directory della repo non esiste!"; exit 1; }

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
    while true; do
        handle_local_changes
        REMOTE_COMMIT=$(get_remote_commit)
        LOCAL_COMMIT=$(get_local_commit)

        if [ "$REMOTE_COMMIT" != "$LOCAL_COMMIT" ]; then
            echo "[INFO] Nuovo commit rilevato. Eseguo git pull e rebuild..." | tee -a "$LOG_FILE"
            git pull origin $(git rev-parse --abbrev-ref HEAD) || { echo "[ERRORE] git pull fallito!" | tee -a "$LOG_FILE"; exit 1; }
            sudo docker-compose -f "$DOCKER_COMPOSE_FILE" down
            sudo docker-compose -f "$DOCKER_COMPOSE_FILE" up --build -d || { echo "[ERRORE] Docker Compose rebuild fallito!" | tee -a "$LOG_FILE"; exit 1; }
            echo "[INFO] Rebuild completato con successo." | tee -a "$LOG_FILE"
        else
            echo "[INFO] Nessun nuovo commit. Riprovo tra $INTERVAL secondi..." | tee -a "$LOG_FILE"
        fi

        sleep "$INTERVAL"
    done
}

# Avvia il watcher direttamente
run_watcher