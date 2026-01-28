# Infrastructure (local)

This folder contains the shared infrastructure services used by the backend.

## Services
- Postgres (port 5432)
- Redis (port 6379)
- RabbitMQ + Management UI (ports 5672, 15672)

## Start
```bash
docker compose -f infra/docker-compose.yml up -d
```

## Stop
```bash
docker compose -f infra/docker-compose.yml down
```

## RabbitMQ UI
- URL: http://localhost:15672
- Login: `smartops`
- Password: `smartops`
