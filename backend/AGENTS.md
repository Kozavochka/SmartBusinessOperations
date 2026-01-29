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
Use the MCP server `mcp_servers.laravel-boost` for backend work.
It is available via `php artisan boost:mcp` inside the `smartops-backend-app` container.

## API Guidelines
### General
- API responses must use the standard wrapper (`App\Support\ApiResponse`).
- All errors must be returned in the unified error format (global exception handler in `bootstrap/app.php`).
- Prefer JSON-only responses for API routes (`routes/api.php`).

### Requests (Form Requests)
- Always use Form Request classes for validation; no inline validation in controllers.
- Location: `app/Http/Requests/...`.
- Rules live in `rules()`; authorization in `authorize()`.
- Use descriptive request names (e.g., `LoginRequest`, `StoreEventRequest`).

### Resources
- Use API Resources for response shaping when returning models/collections.
- Location: `app/Http/Resources/...`.
- Keep resources focused: only expose fields needed by the API consumer.

### Controllers
- Controllers should be thin: call Services for business logic.
- Location: `app/Http/Controllers/...`.
- Use `respondSuccess()` / `respondError()` from base Controller.
- Avoid direct `DB::` calls; use Eloquent or Services.

### Services
- Put domain logic in `app/Services/...`.
- Services should be stateless and unit-testable.

### Filters
- For list endpoints, use filters in `app/Filters/...` (e.g. for Query Builder usage).

## Project Versions (current)
- PHP 8.4.x
- Laravel 12.x
