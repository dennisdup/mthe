# Munthe

## Requirements

|  Docker  | Docker Compose |  PHP   |  NGINX  | NODE  | MYSQL |
| :------: | :------------: | :----: | :-----: | :---: | :---: |
| `17.05+` |    `1.22+`     | `7.2+` | `1.10+` | `9.1` | `5.7` |

## Deployment with Docker

### Manually

-   Uncomment environment files for Laravel:

        cp .env.example .env

-   Build docker:

        docker-compose build


-   Run docker:

        docker-compose up -d


-   Access to **php** container:

        docker exec -u docker -it mes_php_1 bash
        docker exec -it mes_mysql_1 bash

        docker exec -u docker -it munthe_php_1 bash
        docker exec -u docker -it munthe_mysql_1 bash


-   Execute the following commands:

        composer install
        php artisan key:generate

### Check of running networks:

        $ docker network ls
        $ docker-compose ps

### Stop all services:

        $ docker-compose stop

### starting a docker image that is inactive

docker start db15ca504d7c
