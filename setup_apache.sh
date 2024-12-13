#!/bin/bash

# Aggiornamento dei pacchetti e installazione dei necessari
apt-get update && apt-get install -y \
    apache2 \
    php8.1 \
    libapache2-mod-php8.1 \
    mariadb-server=1:10.6.7* \
    php8.1-xdebug \
    php8.1-mysql \
    unzip \
    curl \
    && apt-get clean

# Configurazione di Apache
echo 'ServerName localhost' >> /etc/apache2/apache2.conf
a2enmod rewrite

# Configurazione di MariaDB
service mariadb start

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
PHP_INI_FILE="/etc/php/8.1/apache2/php.ini"

sed -i 's/display_errors = .*/display_errors = On/' $PHP_INI_FILE
sed -i 's/log_errors = .*/log_errors = On/' $PHP_INI_FILE
sed -i 's/error_reporting = .*/error_reporting = E_ALL/' $PHP_INI_FILE

# Installazione di Composer
curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer

# Update del composer
cd /var/www/html && composer update

# Set Local Configurations
rm /var/www/html/Config/config.php
cp /var/www/html/Config/config.local.php /var/www/html/Config/config.php

# Riavvio dei servizi per applicare le configurazioni
service apache2 restart
service mariadb restart

# Manteniamo il container attivo
tail -f /dev/null
