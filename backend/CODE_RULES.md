# Backend Code Rules

## Services
- When implementing the service layer, use Dependency Injection in service constructors.
- Do not duplicate code in services; extract shared logic into common services and reuse it.
- When appropriate, create abstractions and extend class logic rather than copy-pasting behavior.

## Eloquent
- When working with Eloquent models and collections, avoid N+1 queries by eager-loading required relations in advance.

## Query Builder
- For list endpoints with filters, use Spatie Query Builder and its filter system.
