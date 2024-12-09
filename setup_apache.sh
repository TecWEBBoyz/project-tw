#!/bin/bash

# Aggiornamento dei pacchetti e installazione dei necessari
apt-get update && apt-get install -y apache2 php8.1 libapache2-mod-php8.1 mariadb-server=1:10.6.7* php8.1-xdebug && apt-get clean

# Configurazione di Apache
echo 'ServerName localhost' >> /etc/apache2/apache2.conf
a2enmod rewrite

# Aggiornamento del file di configurazione di Apache per consentire l'uso di .htaccess
cp /000-default.conf /etc/apache2/sites-available/000-default.conf

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
xdebug.idekey=docker
EOL

# Abilita Xdebug per PHP
phpenmod xdebug

# Riavvio dei servizi per applicare le configurazioni
service apache2 restart
service mariadb restart

# Manteniamo il container attivo
tail -f /dev/null