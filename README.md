# Sportradar – Live Football World Cup Score Board

This repository contains the implementation of a **Live Football World Cup Score Board** with unit tests.

## Requirements

- **Docker** installed
- **Docker Compose** available (`docker compose`)

---

## Run tests
> Run all commands **from the project root** (the folder that contains `composer.json`).

One-off test run (no containers need to stay running):
```bash
docker compose run --rm php bin/phpunit --testdox
```

Keep a PHP container running and execute tests inside it:
```bash
docker compose up -d
docker exec -it php-container bash
php bin/phpunit --testdox
```
