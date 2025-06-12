#!/bin/bash

# Configuration
USERHOME="{{USER}}" # Automatically fetch the username of the user
DEST_DIR="/home/$USERHOME/public_html"
VERSION_FILE="/home/$USERHOME/scripts/current_version.txt"
RESET_FILE="/home/$USERHOME/scripts/reset_time.txt"
RATE_LIMIT_FILE="/home/$USERHOME/scripts/rate_limit.txt"
RELEASE_API_URL="https://api.github.com/repos/TecWEBBoyz/project-tw/releases/latest"
TEMP_DIR="$HOME/project-tw"  # Using the home directory
TEMP_TAR="$HOME/php-app.tar.gz"  # Using the home directory
VERSION_FILE_URL=""
RATE_LIMIT_API_URL="https://api.github.com/rate_limit"

# Log file
LOG_FILE="/home/$USERHOME/scripts/update_log.txt"

#Crontab log file
CRONTAB_LOG_FILE="/home/$USERHOME/scripts/crontab.log"

# Log function
log_to_file() {
    local message="$1"
    local timestamp=$(date +"%Y-%m-%d %H:%M:%S")
    echo "[$timestamp] $message" | tee -a "$LOG_FILE"
}

# Log startup
log_to_file "Starting the script."

# Files for tracking updates
LAST_CHECK_FILE="/home/$USERHOME/scripts/last_check.txt"
LAST_UPDATE_FILE="/home/$USERHOME/scripts/last_update.txt"
NEXT_CHECK_FILE="/home/$USERHOME/scripts/next_check.txt"
NEXT_CHECK_FILE="/home/$USERHOME/scripts/next_check.txt"

# Calculate the next check time
NEXT_CHECK=$(date -d "+600 seconds" +"%Y-%m-%d %H:%M:%S")
echo "$NEXT_CHECK" > "$NEXT_CHECK_FILE"
log_to_file "Next check scheduled at: $NEXT_CHECK"

# Log the last check time
LAST_CHECK=$(date +"%Y-%m-%d %H:%M:%S")
echo "$LAST_CHECK" > "$LAST_CHECK_FILE"
log_to_file "Last check logged at: $LAST_CHECK"

update_info_dir() {
   UPDATE_INFO_DIR="$DEST_DIR/update_info"

   mkdir -p "$UPDATE_INFO_DIR"

   TXT_FILES=("$NEXT_CHECK_FILE" "$RATE_LIMIT_FILE" "$LAST_CHECK_FILE" "$LAST_UPDATE_FILE" "$VERSION_FILE" "$RESET_FILE" "$CRONTAB_LOG_FILE")

   for TXT_FILE in "${TXT_FILES[@]}"; do
       if [[ ! -f "$TXT_FILE" ]]; then
           log_to_file "File $TXT_FILE not found, creating an empty one..."
           mkdir -p "$(dirname "$TXT_FILE")"
           touch "$TXT_FILE"
       fi
   done

   for TXT_FILE in "${TXT_FILES[@]}"; do
       if [[ -f "$TXT_FILE" ]]; then
           BASENAME=$(basename "$TXT_FILE")
           SYMLINK="$UPDATE_INFO_DIR/$BASENAME"
           log_to_file "Creating symlink for $TXT_FILE -> $SYMLINK"

           if [[ -L "$SYMLINK" || -e "$SYMLINK" ]]; then
               rm -f "$SYMLINK"
           fi

           ln -s "$TXT_FILE" "$SYMLINK"
           log_to_file "Symlink created for $TXT_FILE -> $SYMLINK"
       fi
   done
}

mkdir -p "$DEST_DIR"
mkdir -p "$TEMP_DIR"

check_rate_limit() {
    local response=$(curl -s "$RATE_LIMIT_API_URL")
    local remaining_requests=$(echo "$response" | grep -o '"remaining":\s*[0-9]*' | head -n 1 | sed 's/"remaining":\s*//')
    echo "$remaining_requests"
}

get_reset_time() {
    local response=$(curl -s "$RATE_LIMIT_API_URL")
    local reset_timestamp=$(echo "$response" | grep -o '"reset":\s*[0-9]*' | head -n 1 | sed 's/"reset":\s*//')

    if [[ -z "$reset_timestamp" || ! "$reset_timestamp" =~ ^[0-9]+$ ]]; then
        log_to_file "Error retrieving reset timestamp. Check your connection or URL validity."
        exit 1
    fi

    local reset_date=$(date -d @"$reset_timestamp" +"%Y-%m-%d %H:%M:%S")
    echo "$reset_date"
}

remaining_requests=$(check_rate_limit)

if [[ -z "$remaining_requests" || ! "$remaining_requests" =~ ^[0-9]+$ ]]; then
    log_to_file "Error retrieving API request limit. Check your connection or URL validity."
    exit 1
fi

log_to_file "Remaining API requests: $remaining_requests"

echo "$remaining_requests" > "$RATE_LIMIT_FILE"

if [[ "$remaining_requests" -le 0 ]]; then
    reset_date=$(get_reset_time)
    log_to_file "API limit reached. Reset scheduled at: $reset_date"
    echo "$reset_date" > "$RESET_FILE"
    exit 1
fi

LATEST_VERSION=$(curl -s "$RELEASE_API_URL" | grep -oP '"tag_name":\s*"\Kv[0-9.]+' | head -1)

# Controlla se la versione contiene "-dev"
if [[ "$LATEST_VERSION" == *"-dev"* ]]; then
    log_to_file "Skipping deployment for development version: $LATEST_VERSION"
    exit 0
fi

if [[ -f "$VERSION_FILE" ]]; then
    CURRENT_VERSION=$(cat "$VERSION_FILE")
else
    CURRENT_VERSION=""
fi

if [[ "$LATEST_VERSION" != "$CURRENT_VERSION" ]]; then
    log_to_file "New version detected: $LATEST_VERSION (Current: $CURRENT_VERSION)"

    ASSET_URL=$(curl -s "$RELEASE_API_URL" | grep -oP '"browser_download_url":\s*"\K[^"]+php-app.tar.gz')
    VERSION_FILE_URL=$(curl -s "$RELEASE_API_URL" | grep -oP '"browser_download_url":\s*"\K[^"]+version.txt')
    INITDB_FILE_URL=$(curl -s "$RELEASE_API_URL" | grep -oP '"browser_download_url":\s*"\K[^"]+db-init-app.tar.gz')

    if [[ -z "$ASSET_URL" || -z "$VERSION_FILE_URL" || -z "$INITDB_FILE_URL" ]]; then
        log_to_file "Error: No assets found for release $LATEST_VERSION"
        exit 1
    fi

    log_to_file "Downloading release files..."
    wget -O "$TEMP_TAR" "$ASSET_URL"

    # Verifica se il file è stato scaricato correttamente
    if [[ ! -f "$TEMP_TAR" || ! -s "$TEMP_TAR" ]]; then
        log_to_file "Error: Failed to download the compressed file or file is empty. Aborting process."
        exit 1
    fi

    wget -O "$TEMP_DIR/version.txt" "$VERSION_FILE_URL"

    # Verifica se il file version.txt è stato scaricato correttamente
    if [[ ! -f "$TEMP_DIR/version.txt" || ! -s "$TEMP_DIR/version.txt" ]]; then
        log_to_file "Error: Failed to download version.txt or file is empty. Aborting process."
        exit 1
    fi

    wget -O "$TEMP_DIR/db-init-app.tar.gz" "$INITDB_FILE_URL"

    # Verifica se il file db-init-app.tar.gz è stato scaricato correttamente
    if [[ ! -f "$TEMP_DIR/db-init-app.tar.gz" || ! -s "$TEMP_DIR/db-init-app.tar.gz" ]]; then
        log_to_file "Error: Failed to download db-init-app.tar.gz or file is empty. Aborting process."
        exit 1
    fi

    log_to_file "Deleting all files and directories in $DEST_DIR..."
    rm -rf "$DEST_DIR"/*

    log_to_file "Extracting new release..."
    tar -xzf "$TEMP_TAR" -C "$DEST_DIR" --overwrite

    log_to_file "Extracting db-init-app.tar.gz..."
    tar -xzf "$TEMP_DIR/db-init-app.tar.gz" -C "$DEST_DIR" --overwrite

    # Esegui il comando per inizializzare il database con il file init.sql
    log_to_file "Initializing the database..."
    mysql -u $USERHOME -p$(cat /home/$USERHOME/pwd_db_2024-25.txt) $USERHOME < /home/$USERHOME/public_html/init.sql
    log_to_file "Database initialized."

    # Rimuovi il file init.sql
    log_to_file "Removing init.sql..."
    rm -rf /home/$USERHOME/public_html/init.sql
    log_to_file "init.sql removed."

    log_to_file "Copying version.txt to destination directory..."
    cp "$TEMP_DIR/version.txt" "$DEST_DIR/version.txt"

    CONFIG_FILE="$DEST_DIR/Config/Config.php"
    if [[ -f "$CONFIG_FILE" ]]; then
        log_to_file "Modifying Config.php..."
        sed -i "s/{DB_PASSWORD}/$(cat /home/$USERHOME/pwd_db_2024-25.txt)/g" "$CONFIG_FILE"
        sed -i "s/{USERNAME}/$USERHOME/g" "$CONFIG_FILE"
        // replace {JWT_SECRET_KEY} with a random string
        sed -i "s/{JWT_SECRET_KEY}/$(openssl rand -base64 32)/g" "$CONFIG_FILE"
    else
        log_to_file "Warning: Config.php not found at $CONFIG_FILE"
    fi

    echo "$LATEST_VERSION" > "$VERSION_FILE"

    LAST_UPDATE=$(date +"%Y-%m-%d %H:%M:%S")
    echo "$LAST_UPDATE" > "$LAST_UPDATE_FILE"
    update_info_dir
    log_to_file "Update completed to version $LATEST_VERSION"
    sleep 600
else
    log_to_file "No update needed. Current version: $CURRENT_VERSION"
    sleep 600
fi

rm -rf "$TEMP_TAR" "$TEMP_DIR"
log_to_file "Script execution completed."
