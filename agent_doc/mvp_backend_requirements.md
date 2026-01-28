# Smart Ops Dashboard - MVP Backend Requirements (v1)

## Scope
This document captures the requirements for the first backend version built on Laravel.
Out of scope: Billing, dashboards, ML, realtime services.

## Core MVP Features

### 1) Admin Panel
- Admin panel is a separate frontend application; backend provides admin APIs.
- Admin area runs in the `public` schema (no tenant context).
- Functions:
  - create, view, and disable tenants;
  - manage tenant base parameters (name, status, optional event limits);
  - view tenant users list (read-only).

### 2) Tenant Login / Access
- User authentication via JWT.
- User is linked to a specific tenant.
- Tenant context:
  - allow tenant selection if a user has multiple tenants;
  - resolve tenant by domain/subdomain.
- All tenant context requests must be isolated to the selected tenant.

### 3) Event Ingestion (Leads)
- API endpoint to accept events (leads).
- Validate incoming payload (minimum fields: source, payload/body, external_id or timestamp).
- Normalize and store the event in the tenant schema.
- Basic deduplication by external_id + source.

### 4) Statistics API
- Event statistics endpoints:
  - total events for a period;
  - events by source;
  - simple daily time series.
- Filters: time period, source, event type (if applicable).

## Data & Multi-Tenancy
- Postgres with `public` schema for global entities (tenants, users).
- Separate `tenant_{id}` schema for tenant data (events, etc.).
- Middleware selects schema per tenant request.
- Cross-tenant access protection is mandatory.

## Initial Domain Entities (Minimal)
- Tenant (id, name, status, created_at).
- User (id, email, password, status, tenant_id or many-to-many relation).
- Event (id, source, external_id, payload, created_at, metadata).

## Non-Goals (Explicitly Out of Scope)
- Billing, plans, quotas.
- Dashboards and widget builder.
- Realtime (WebSocket/SSE).
- ML scoring.
- Deep external integrations.

## Notes / Decisions
- Tenancy model: per-tenant schemas in Postgres.
- Tenancy package: tenancyforlaravel.com (`stancl/tenancy`), tenant resolution by domain/subdomain.
- Admin runs in `public` schema; tenant endpoints require tenant context.
- Queues are used: RabbitMQ is the main broker for async tasks (ingestion/aggregations/notifications as needed).
- Outbox is not required yet, but DB structure should allow future expansion.
- API-first; OpenAPI spec is an optional artifact.

## Dependencies (Proposed)
Composer packages on top of standard Laravel 12.

### Runtime
- `stancl/tenancy` - multi-tenancy (per-tenant schemas).
- `tymon/jwt-auth` - JWT auth.
- `spatie/laravel-permission` - RBAC (admin vs tenant roles/permissions).
- `vladimir-yuldashev/laravel-queue-rabbitmq` - RabbitMQ queue driver.
- `spatie/laravel-query-builder` - filtering/sorting for API (events/statistics).
- `spatie/laravel-activitylog` - audit of significant actions (e.g., tenant create/disable, critical admin ops).
- `opcodesio/log-viewer` - technical logs UI (Laravel logs), super-admin only in `public` area.

### Dev-only
- `laravel/boost` - local dev tools.
- `laravel/telescope` - debug requests/queues/exceptions in dev.
