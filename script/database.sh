#!/bin/bash

# Peuplement de la base de données (+ fixtures)
symfony console d:f:l -n
mariadb -e "use gsbFrais; source sql/gsb_insert_tables.sql;"
symfony console d:f:l --append