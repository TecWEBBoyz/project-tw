#!/bin/bash

# Aggiornamento dei pacchetti e installazione dei necessari
apt-get update && apt-get install -y \
    apache2 \
    php8.1 \
    libapache2-mod-php8.1 \
    mariadb-server=1:10.6.7* \
    php8.1-xdebug \
    php8.1-mysql \
    php8.1-gd \
    unzip \
    curl \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    certbot \
    python3-certbot-apache \
    && apt-get clean

# Verifica che GD sia attivo
PHP_INI_FILE="/etc/php/8.1/apache2/php.ini"
php -m | grep -q gd && echo "GD è correttamente abilitato" || echo "Errore: GD non è abilitato"

# Configurazione di Apache
echo 'ServerName localhost' >> /etc/apache2/apache2.conf
a2enmod rewrite
a2enmod deflate  # Abilita il modulo di compressione

# Configurazione della compressione in Apache
cat <<EOL >> /etc/apache2/conf-available/deflate.conf
<IfModule mod_deflate.c>
    # Attiva la compressione per i tipi di file comuni
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json application/xml
    # Esclude alcuni tipi di file dalla compressione
    SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png|zip|gz|bz2|pdf|exe|mp4|avi|mov|rar)$ no-gzip
    # Imposta le intestazioni per indicare la compressione
    Header append Vary Accept-Encoding
</IfModule>
EOL

# Abilita il file di configurazione della compressione
a2enconf deflate

# Abilitare Headers
a2enmod headers

# Abilitazione del caricamento file nel php.ini
sed -i 's/file_uploads = .*/file_uploads = On/' $PHP_INI_FILE
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 5000M/' $PHP_INI_FILE
sed -i 's/post_max_size = .*/post_max_size = 5000M/' $PHP_INI_FILE
sed -i 's/max_execution_time = .*/max_execution_time = 600/' $PHP_INI_FILE
sed -i 's/max_input_time = .*/max_input_time = 600/' $PHP_INI_FILE
sed -i 's/memory_limit = .*/memory_limit = 512M/' $PHP_INI_FILE
sed -i 's/;date.timezone.*/date.timezone = Europe\/Rome/' $PHP_INI_FILE
sed -i 's/max_file_uploads = .*/max_file_uploads = 200/' $PHP_INI_FILE

# Aggiornamento del file di configurazione di Apache per consentire l'uso di .htaccess
cp /000-default.conf /etc/apache2/sites-available/000-default.conf

# Configurazione di MariaDB
service mariadb start

# Configura MariaDB per l'accesso remoto
sed -i 's/^bind-address.*/bind-address = 0.0.0.0/' /etc/mysql/mariadb.conf.d/50-server.cnf

# Configura il firewall per MariaDB
ufw allow 3306

# Configurazione dell'utente di default per MariaDB
MYSQL_ROOT_PASSWORD="root_password"
MYSQL_USER="default_user"
MYSQL_USER_PASSWORD="default_password"
MYSQL_DATABASE="default_database"

# Impostazione della password per l'utente root
mysql -u root <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';
FLUSH PRIVILEGES;

-- Creazione di un database di default
CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE};

-- Creazione di un utente di default
CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'%' IDENTIFIED BY '${MYSQL_USER_PASSWORD}';

-- Concessione dei privilegi all'utente di default
GRANT ALL PRIVILEGES ON ${MYSQL_DATABASE}.* TO '${MYSQL_USER}'@'%';

-- Applica le modifiche
FLUSH PRIVILEGES;
EOF

# Esegui il file init.sql per configurare il database (assicurati che il file sia nella root)
mysql -u root -p${MYSQL_ROOT_PASSWORD} ${MYSQL_DATABASE} < /init.sql

# Configurazione di Xdebug
XDEBUG_CONFIG_FILE="/etc/php/8.1/mods-available/xdebug.ini"

cat <<EOL > $XDEBUG_CONFIG_FILE
zend_extension=xdebug.so
xdebug.mode=develop,coverage,debug,profile
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.log=/var/log/xdebug.log
xdebug.remote_autostart=1
xdebug.idekey=PHPSTORM
EOL

# Abilita Xdebug per PHP
phpenmod xdebug

# Modifica del file php.ini
sed -i 's/display_errors = .*/display_errors = On/' $PHP_INI_FILE
sed -i 's/log_errors = .*/log_errors = On/' $PHP_INI_FILE
sed -i 's/error_reporting = .*/error_reporting = E_ALL/' $PHP_INI_FILE

# Installazione di Composer
curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer

# Update del composer
cd /var/www/html/username && composer update

# Set Local Configurations
rm /var/www/html/username/Config/config.php
cp /var/www/html/username/Config/config.prod.php /var/www/html/username/Config/config.php

# Configurazione Certbot per SSL
certbot --apache --non-interactive --agree-tos --email baraldodavide@gmail.com -d filipporizzato.ddns.net

# Configurazione del rinnovo automatico del certificato
cat <<EOL > /etc/cron.d/certbot-renew
0 3 * * * root certbot renew --quiet && systemctl reload apache2
EOL

chmod 644 /etc/cron.d/certbot-renew

# Riavvio dei servizi per applicare le configurazioni
service apache2 restart
service mariadb restart

# Manteniamo il container attivo
tail -f /dev/null
