FROM debian:buster

RUN apt-get update && apt-get -y install cron mariadb-client nano

COPY cloturer-fichefrais.sh /etc/cron.d/cloturer-fichefrais.sh

RUN chmod 0644 /etc/cron.d/cloturer-fichefrais.sh

RUN crontab /etc/cron.d/cloturer-fichefrais.sh

RUN touch /var/log/cron.log

CMD cron -f && tail -f /var/log/cron.log