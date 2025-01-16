#!/bin/bash
# Configurazione
REPO_DIR="/home/baraldodavide/GIT/project-tw"
DOCKER_COMPOSE_FILE="docker-compose-prod.yml"
BRANCH="develop"  # Specifica il branch da monitorare
INTERVAL=30  # Intervallo di controllo in secondi
LOG_FILE="/var/log/watcher.log"  # File di log

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
        echo "[INFO] Cambiando al branch $BRANCH..." | tee -a "$LOG_FILE"
        git checkout "$BRANCH" || { echo "[ERRORE] Cambio branch fallito!" | tee -a "$LOG_FILE"; exit 1; }
    fi
}

# Controlla aggiornamenti e ricostruisci il Docker Compose
run_watcher() {
    while true; do
        ensure_on_branch
        REMOTE_COMMIT=$(get_remote_commit)
        LOCAL_COMMIT=$(get_local_commit)

        if [ "$REMOTE_COMMIT" != "$LOCAL_COMMIT" ]; then
            echo "[INFO] Nuovo commit rilevato sul branch $BRANCH. Eseguo git pull e rebuild..." | tee -a "$LOG_FILE"
            git pull origin "$BRANCH" || { echo "[ERRORE] git pull fallito!" | tee -a "$LOG_FILE"; exit 1; }
            docker-compose -f "$DOCKER_COMPOSE_FILE" down
            docker-compose -f "$DOCKER_COMPOSE_FILE" up --build -d || { echo "[ERRORE] Docker Compose rebuild fallito!" | tee -a "$LOG_FILE"; exit 1; }
            echo "[INFO] Rebuild completato con successo." | tee -a "$LOG_FILE"
        else
            echo "[INFO] Nessun nuovo commit sul branch $BRANCH. Riprovo tra $INTERVAL secondi..." | tee -a "$LOG_FILE"
        fi

        sleep "$INTERVAL"
    done
}

# Avvia il watcher direttamente
run_watcher
