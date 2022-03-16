FROM debian:buster

RUN apt-get update && apt-get -y install cron mariadb-client nano

COPY cloturer-fichefrais.sh /etc/cron.d/cloturer-fichefrais.sh

RUN chmod 0644 /etc/cron.d/cloturer-fichefrais.sh

RUN crontab /etc/cron.d/cloturer-fichefrais.sh

WORKDIR /var/www/GSB-Frais

CMD cron -f