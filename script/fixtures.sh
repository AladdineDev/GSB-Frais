#!/bin/bash

# Peuplement de la base de données (+ fixtures)
docker exec gsb_frais_www sh -c "php bin/console doctrine:fixtures:load -n"
docker exec gsb_frais_db sh -c "mysql -e \"use gsb_frais; source gsb_insert_tables.sql;\""
docker exec gsb_frais_www sh -c "php bin/console doctrine:fixtures:load --append"