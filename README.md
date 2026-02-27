# Sportradar – Live Football World Cup Score Board

This repository contains the implementation of a **Live Football World Cup Score Board** with unit tests.

## Requirements

- **Docker** installed

---

## Project Setup (Install dependencies)

From the project root:

```bash
docker compose up -d
docker compose run --rm composer install

## Run tests

Access the root project in docker container:
```bash
docker compose up -d
docker exec -it php-container bash
php bin/phpunit
```
