#!/bin/bash

# Peuplement de la base de données (+ fixtures)
symfony console doctrine:fixtures:load -n
mariadb -e "use gsb_frais; source sql/gsb_insert_tables.sql;"
symfony console doctrine:fixtures:load --append