#!/bin/bash

# Installation des dépendances
docker exec gsb_frais_www sh -c "composer install -n"

# Création de la base de données et migration
docker exec gsb_frais_www sh -c "symfony console doctrine:database:create"
docker exec gsb_frais_www sh -c "symfony console doctrine:migrations:migrate -n"

# Exécution du script de peuplement de la base de données
chmod +x script/fixtures.sh
./script/fixtures.sh