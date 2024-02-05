<img src="public/images/logo.png" align="right" width="200px"/>

GSB Frais [![Licence](https://img.shields.io/badge/licence-MIT-2fba00.svg?style=flat-square)](https://github.com/AladdineDev/GSB-Frais/blob/master/LICENSE.md)
========================

GSB Frais – par [@AladdineDev](https://github.com/AladdineDev)

[![Symfony 5](https://img.shields.io/badge/Symfony-5.4-e5e8e4.svg?style=flat-square&logo=symfony)](https://symfony.com/5)
[![Composer 2](https://img.shields.io/badge/Composer-2.2-89552c.svg?style=flat-square&logo=composer)](https://getcomposer.org/)
[![PHP 7](https://img.shields.io/badge/PHP-7.3-8892bf.svg?style=flat-square&logo=php&logoColor=ffffff)](https://www.php.net/)
[![MariaDB 10](https://img.shields.io/badge/MariaDB-10.3-c0765a.svg?style=flat-square&logo=mariadb)](https://mariadb.org/)
[![Apache 2](https://img.shields.io/badge/Apache-2.4-a2205c.svg?style=flat-square&logo=apache)](https://httpd.apache.org/)
[![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5.1-7952b3.svg?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

Application web d'enregistrement des frais engagés et de suivi des remboursements.

<img src="screenshots/visiteur/0-Second Screenshot README.png" width="76%"></img> <img src="screenshots/visiteur/0-Premier Screenshot README.jpg" width="22%"></img>
<img src="screenshots/comptable/0-Second Screenshot README.png" width="76%"></img> <img src="screenshots/comptable/0-Premier Screenshot README.jpg" width="22%"></img>

### Documentation

  * [Documentation utilisateur](docs/Documentation-Utilisateur.pdf)
  * [Documentation technique](docs/Documentation-Technique.pdf)

### Contexte

1. [GSB - Contexte](docs/contexte/01-GSB-Contexte.pdf)
2. [GSB - Organisation](docs/contexte/02-GSB-Organisation.pdf)
3. [GSB - Cahier des charges](docs/contexte/03-GSB-AppliFrais-Description.pdf)
4. [GSB - Exemples](docs/contexte/04-GSB-AppliFrais-Commentaires.pdf)
5. [GSB - Architecture de l'existant](docs/contexte/05-GSB-Architecture-Application-Existante.pdf)

### Prérequis


  * [Docker](https://docs.docker.com/get-docker)
  * [Docker compose](https://docs.docker.com/compose/install)

> En cas de difficulté, reportez-vous à la documentation officielle de [Docker](https://docs.docker.com/).

## Installation

Tout d'abord, clonez ce dépôt puis placez-vous au sein du projet :

```bash
$ git clone https://github.com/AladdineDev/GSB-Frais
$ cd GSB-Frais
```

Ensuite, construisez et lancez les conteneurs Docker :

```bash
$ docker-compose up -d --build
```

Enfin, exécutez le script d'initialisation de l'application :

```bash
$ chmod +x script/start.sh && ./script/start.sh
```

Vous pouvez maintenant accéder à l'application depuis votre navigateur à l'URL suivante : [`http://localhost:9973`](http://localhost:9973)

> Vous avez la possibilité de repeupler la base de données avec un nouveau jeu de données grâce aux fixtures :
>```bash
> $ ./script/fixtures.sh
>```

## Fonctionnement

Voici les services déclarés dans le fichier `docker-compose.yml` :

* `cron` : Le conteneur du planificateur de tâches,
* `db` : Le conteneur de la base de données MariaDB,
* `www` : Le conteneur PHP incluant le serveur apache.

Les conteneurs en cours d'exécution sont donc les suivants :

```bash
$ docker-compose ps
NAME               COMMAND                    SERVICE     STATUS      PORTS
gsb_frais_cron     "/bin/sh -c 'cron -f…"     cron        running     
gsb_frais_db       "docker-entrypoint.s…"     db          running     3306/tcp
gsb_frais_www      "docker-php-entrypoi…"     www         running     0.0.0.0:9973->80/tcp, :::9973->80/tcp
```

La commande suivante vous permet d'entrer dans le shell d'un des conteneurs : 

```bash
$ docker exec -it <nom_du_conteneur> bash
```

> Vous pouvez personnaliser votre environnement (ports, volumes...) en modifiant le fichier `docker-compose.yml`.

## Licence

Voir le fichier [LICENSE.md](https://github.com/AladdineDev/GSB-Frais/blob/master/LICENSE.md) fourni.