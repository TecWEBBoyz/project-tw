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

# Configura MariaDB per UTF-8
sed -i 's/\[mysqld\]/[mysqld]\ncharacter-set-server=utf8mb4\ncollation-server=utf8mb4_unicode_ci\nskip-character-set-client-handshake/' /etc/mysql/mariadb.conf.d/50-server.cnf
sed -i 's/\[client\]/[client]\ndefault-character-set=utf8mb4/' /etc/mysql/mariadb.conf.d/50-server.cnf
sed -i 's/\[mysql\]/[mysql]\ndefault-character-set=utf8mb4/' /etc/mysql/mariadb.conf.d/50-server.cnf

# Riavvio di MariaDB per applicare le modifiche
service mariadb restart

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

# Modifica del file php.ini
sed -i 's/display_errors = .*/display_errors = On/' $PHP_INI_FILE
sed -i 's/display_errors = .*/display_errors = Off/' $PHP_INI_FILE
sed -i 's/log_errors = .*/log_errors = On/' $PHP_INI_FILE
#sed -i 's/error_reporting = .*/error_reporting = E_ALL/' $PHP_INI_FILE
sed -i 's/error_reporting = .*/error_reporting = E_ALL & ~E_WARNING/' $PHP_INI_FILE
sed -i 's/;error_log = php_errors.log/error_log = \/var\/log\/php_errors.log/' $PHP_INI_FILE

# Installazione di Composer
curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer

# Update del composer
cd /var/www/html/username && composer update

# #Copy files
# cp -r /var/www/html/username/static/uploads_temp/** /var/www/html/username/static/uploads
# chmod -R 777 /var/www/html/username/static/uploads

# # Set Local Configurations
# rm /var/www/html/username/Config/config.php
# cp /var/www/html/username/Config/config.local.php /var/www/html/username/Config/config.php

# Creazione di un index.html per il redirect
# cat <<EOL > /var/www/html/index.html
# <!DOCTYPE html>
# <html>
# <head>
#     <meta http-equiv="refresh" content="0;url=/username" />
#     <title>Redirecting...</title>
# </head>
# <body>
#     <p>Redirecting to <a href="/username">/username</a>...</p>
# </body>
# </html>
# EOL

# Riavvio dei servizi per applicare le configurazioni
service apache2 restart
service mariadb restart

# Manteniamo il container attivo
tail -f /dev/null