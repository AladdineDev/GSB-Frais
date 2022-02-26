FROM mariadb:10.3

RUN echo "[mysql]" > /etc/mysql/conf.d/custom.cnf && \
    echo "default-character-set = utf8mb4" >> /etc/mysql/conf.d/custom.cnf

WORKDIR /var/www/GSB-Frais/sql