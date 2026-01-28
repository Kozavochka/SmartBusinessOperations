# Backend AGENTS

## Containers
- `smartops-backend-app` - Laravel app (HTTP on port 8000).
- `smartops-backend-worker` - Queue worker.
- `smartops-backend-scheduler` - Laravel scheduler.

Infrastructure (from `infra/docker-compose.yml`):
- `smartops-postgres` - Postgres.
- `smartops-redis` - Redis.
- `smartops-rabbitmq` - RabbitMQ (Management UI on 15672).

## MCP
Use the MCP server `mcp_servers.smartops-boost` when implementing backend functionality.
It is available via `php artisan boost:mcp` inside the `smartops-backend-app` container.
