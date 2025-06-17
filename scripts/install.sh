#!/bin/bash

# Configurazioni
USERHOME="{{USER}}" # Automatically fetch the username of the user
SCRIPT_DIR="/home/$USERHOME/scripts"
SCRIPT_URL="https://raw.githubusercontent.com/TecWEBBoyz/project-tw/main/scripts/update_release.sh"
SCRIPT_PATH="$SCRIPT_DIR/update_release.sh"
LOGS_PATH="$SCRIPT_DIR/crontab.log"
UPDATE_LOGS_PATH="$SCRIPT_DIR/update_log.txt"
MAX_LOG_SIZE=2097152 # 2 MB in byte

# Crea la directory se non esiste
if [[ ! -d "$SCRIPT_DIR" ]]; then
    echo "Creazione della directory $SCRIPT_DIR..."
    mkdir -p "$SCRIPT_DIR"
fi

# Rimuovi lo script esistente se c'è
if [[ -f "$SCRIPT_PATH" ]]; then
    echo "Rimozione dello script esistente $SCRIPT_PATH..."
    rm -f "$SCRIPT_PATH"
fi

# Scarica il nuovo script
echo "Scaricamento dello script da $SCRIPT_URL..."
wget -O "$SCRIPT_PATH" "$SCRIPT_URL"

# Sostituisci {{USER}} con USERHOME nello script scaricato
if [[ -f "$SCRIPT_PATH" ]]; then
    echo "Sostituzione di {{USER}} con $USERHOME nello script..."
    sed -i "s/{{USER}}/$USERHOME/g" "$SCRIPT_PATH"
fi

# Rendi eseguibile lo script
echo "Impostazione dello script come eseguibile..."
chmod +x "$SCRIPT_PATH"

echo "Lo script è stato scaricato e configurato in $SCRIPT_PATH"

# Aggiungi la crontab per eseguire ogni minuto
CRON_JOB="* * * * * rm -rf $SCRIPT_PATH; wget -O \"$SCRIPT_PATH\" \"$SCRIPT_URL\"; sed -i \"s/{{USER}}/$USERHOME/g\" \"$SCRIPT_PATH\"; chmod +x \"$SCRIPT_PATH\"; $SCRIPT_PATH >> $LOGS_PATH 2>&1"
LOG_CRON_JOB="* * * * * /bin/bash -c 'if [[ -f \"$LOGS_PATH\" ]]; then tail -n 100 \"$LOGS_PATH\" > \"$LOGS_PATH.tmp\" && cat \"$LOGS_PATH.tmp\" > \"$LOGS_PATH\" && rm \"$LOGS_PATH.tmp\"; fi'"
UPDATE_LOG_CRON_JOB="* * * * * /bin/bash -c 'if [[ -f \"$UPDATE_LOGS_PATH\" ]]; then tail -n 100 \"$UPDATE_LOGS_PATH\" > \"$UPDATE_LOGS_PATH.tmp\" && cat \"$UPDATE_LOGS_PATH.tmp\" > \"$UPDATE_LOGS_PATH\" && rm \"$UPDATE_LOGS_PATH.tmp\"; fi'"
( crontab -l 2>/dev/null | grep -v "$SCRIPT_PATH"; echo "$CRON_JOB"; echo "$LOG_CRON_JOB"; echo "$UPDATE_LOG_CRON_JOB" ) | crontab -

echo "Crontab configurata per eseguire lo script ogni minuto e controllare i log."

# Esegui immediatamente lo script
echo "Esecuzione immediata dello script..."
"$SCRIPT_PATH"