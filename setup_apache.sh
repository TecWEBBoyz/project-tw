#!/bin/bash

# Aggiornamento dei pacchetti e installazione dei necessari
apt-get update && apt-get install -y apache2 php8.1 libapache2-mod-php8.1 mariadb-server=1:10.6.7* && apt-get clean

# Configurazione di Apache
echo 'ServerName localhost' >> /etc/apache2/apache2.conf
a2enmod rewrite

# Aggiornamento del file di configurazione di Apache per consentire l'uso di .htaccess
cp /000-default.conf /etc/apache2/sites-available/000-default.conf

# Avvio dei servizi
service apache2 start
service mariadb start

# Manteniamo il container attivo
tail -f /dev/null