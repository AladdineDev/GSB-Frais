#!/bin/bash

# Démarrage du service mysql et création de l'utilisateur "developpeur"
/etc/init.d/mysql start
mariadb -e "grant all privileges on gsb_frais.* to developpeur@localhost identified by \"azerty\";"

# Installation des dépendances
composer install -n

# Création de la base de données et migration
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate -n

# Exécution du script de peuplement de la base de données
chmod +x script/database.sh
./script/database.sh