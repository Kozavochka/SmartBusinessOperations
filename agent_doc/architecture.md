# Smart Ops Dashboard - Architecture v1

## Goals
- Event-driven operations dashboard for small teams
- Monolith-first with clear module boundaries
- Multi-tenant Postgres using per-tenant schemas
- Ready to add Go (realtime) and Python (ML/analytics) as services

## High-Level Components

### Backend (Laravel Monolith)
- API (REST/GraphQL) for UI and integrations
- Auth, RBAC, billing, tenant management
- Core domains: Sources, Events, Automations, Dashboards, Notifications
- In-process domain events for module communication
- Outbox for reliable external delivery

### Database (Postgres)
- `public` schema: global tables (tenants, users, plans, billing, audit)
- `tenant_{id}` schemas: events, rules, dashboards, notifications, etc.
- Migration flow: global migrations + per-tenant schema migrations

### Queue (RabbitMQ) / Cache (Redis)
- RabbitMQ for async jobs (aggregation, notifications, ML scoring requests)
- Redis for cache and rate limits

### Worker (Laravel Queue)
- Processes background jobs
- Rebuilds read models / dashboard views
- Executes automations and notifications

### Frontend (Vue)
- Dashboard builder and analytics views
- Rule builder (conditions -> actions)
- Live updates (SSE/WS later)

### Optional Services (Later / v1.5)
- Centrifugo Realtime Gateway: WebSocket/SSE, fan-out, throttling
- Python ML/Analytics: scoring, simple models, scheduled training

## Interaction Overview

### Core Flow (Event Ingestion)
1) UI or external source sends event to Laravel API
2) Laravel validates, normalizes, and stores raw + enriched event
3) Domain event emitted in-process to trigger automations
4) Jobs pushed to Redis for async work
5) Worker updates dashboard read models
6) UI fetches updated dashboards (polling or realtime later)

### Realtime (Centrifugo)
- Preferred: async via broker
  - Laravel writes to Outbox
  - Worker publishes to Redis Streams/NATS
  - Centrifugo consumes and pushes to connected clients
- Alternative: HTTP RPC from Laravel for broadcasts

### Python Service (ML/Analytics)
- Preferred: async scoring via broker
  - Laravel publishes scoring job
  - Python consumes, computes score, publishes result
  - Laravel updates event/insight fields
- Alternative: HTTP `/score` for synchronous scoring (MVP only)

## Module Boundaries (Monolith)
- `Tenants`: schema selection, lifecycle, limits
- `Auth`: users, roles, permissions
- `Sources`: connectors, webhooks, imports
- `Events`: storage, enrichment, tags, outbox
- `Automations`: rules, triggers, actions
- `Dashboards`: views, widgets, aggregates
- `Notifications`: channels, templates, deliveries
- `Billing`: plans, usage, quotas
- `Observability`: audit logs, metrics

## Multi-Tenancy Strategy
- Tenant resolution by subdomain or header
- Middleware selects `tenant_{id}` schema per request
- Public schema remains isolated for global data
- Strict tests to prevent cross-tenant leakage

## Deployment (Docker)
- `app`: Laravel API
- `worker`: Laravel queue worker
- `db`: Postgres
- `rabbitmq`: queue
- `redis`: cache
- Optional: `realtime` (Go), `ml` (Python)

## MVP Notes
- Start without Go/Python services
- Keep Outbox table and async jobs to simplify future split
- Use polling for live updates, add realtime gateway later
