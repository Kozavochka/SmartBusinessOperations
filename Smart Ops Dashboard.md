# Smart Ops Dashboard

## Product Overview

Smart Ops Dashboard is an event‑driven operations platform designed for
small teams and businesses to centralize, analyze, and automate incoming
operational signals such as form submissions, tickets, logs, messages,
and external integrations.

The system unifies all incoming events into a single stream, enables
rule‑based automation, provides real‑time dashboards, and applies simple
machine learning to prioritize what matters most.

This project demonstrates a modern architecture using:

-   **Laravel** as the primary API and business backend
-   **Vue** for interactive dashboards and flow builders
-   **Go** microservices for real‑time communication and event streaming
-   **Python** microservices for ML scoring and background analytics

------------------------------------------------------------------------

## Core Idea

Everything in the system is treated as an **Event**.

Events come from different **Sources**, pass through **Rules**, are
enriched with **Smart Scoring**, and become visible in **Dashboards**
and **Notifications**.

This creates a unified operational control center for teams.

------------------------------------------------------------------------

## Target Users

-   Small businesses handling website leads and requests
-   Support teams processing tickets and messages
-   Operations teams monitoring system logs and alerts
-   Product teams needing visibility into user activity streams

------------------------------------------------------------------------

## Key Features

### Event Ingestion

-   Webhook endpoints for external systems
-   Integrations with forms, email parsers, messengers, task trackers
-   Unified storage of raw events

### Real‑Time Event Feed

-   Live updating event stream
-   Filtering, searching, and grouping
-   WebSocket powered updates via Go service

### Rule Engine (Automation Builder)

Users can create rules like:

> If `source = WebsiteForm` AND message contains "price" → set priority
> HIGH and send notification to Telegram.

### Smart Prioritization (Basic ML)

A simple perceptron/logistic regression model scores incoming events: -
Importance - Likely conversion - Possible spam - Potential issue
severity

The score is shown in the UI and used for sorting.

### Dashboards

-   Operational metrics
-   Event analytics
-   Processing speed and load
-   Custom dashboard builder

### Notifications

-   Telegram / Email / Webhooks
-   Triggered by rules or ML score

------------------------------------------------------------------------

## Architecture Overview

### Laravel (Core API)

-   Authentication and organizations
-   Event storage
-   Rules and actions
-   Dashboard data API
-   Multi‑tenant ready structure

### Go Realtime Service

-   WebSocket gateway
-   Subscription channels
-   High‑performance fan‑out of updates
-   Rate limiting and deduplication

### Python ML Service

-   `/score` endpoint for event scoring
-   `/train` scheduled model retraining
-   Simple stored model (pickle)
-   Feature extraction from events

### Vue Frontend

-   Event feed UI
-   Rule builder interface
-   Dashboard constructor
-   Real‑time updates

------------------------------------------------------------------------

## Modern Engineering Practices Demonstrated

-   Event‑driven architecture
-   CQRS‑style separation of writes and reads
-   Outbox pattern for reliable message delivery
-   Microservices communication via message broker (Redis Streams /
    RabbitMQ / NATS)
-   Observability (OpenTelemetry ready)
-   API‑first design with OpenAPI contracts
-   Multi‑tenant SaaS‑style structure

------------------------------------------------------------------------

## MVP Scope

1.  Authentication and organizations
2.  Webhook event ingestion
3.  Event feed UI
4.  Rule engine (condition → action)
5.  Real‑time updates via Go
6.  Basic ML scoring via Python
7.  Simple operational dashboard

------------------------------------------------------------------------

## Future Extensions

-   Feature flags
-   Advanced integrations (Jira, Telegram, Email, CRM)
-   Extended analytics storage (ClickHouse / Elasticsearch)
-   Integration builder (Zapier‑like)
-   Docker Compose / Kubernetes deployment

------------------------------------------------------------------------

## Value Proposition

It represents how modern backend, frontend, microservices, and machine
learning can be combined into a cohesive, production‑style system that
solves real operational problems while showcasing up‑to‑date engineering
practices.
