#!/bin/bash

# Peuplement de la base de données (+ fixtures)
docker exec gsb_frais_www sh -c "symfony console doctrine:fixtures:load -n"
docker exec gsb_frais_db sh -c "mariadb -e \"use gsb_frais; source sql/gsb_insert_tables.sql;\""
docker exec gsb_frais_www sh -c "symfony console doctrine:fixtures:load --append"