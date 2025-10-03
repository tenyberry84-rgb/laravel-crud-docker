# Laravel CRUD with Docker

Laravel CRUD API with multi-stage Docker build, MySQL, Redis, and Docker Swarm support.

## Features
- Multi-stage Dockerfile (optimized size)
- Docker Compose for local development
- Docker Swarm ready with 3 replicas
- Published to GitHub Container Registry
- MySQL + Redis integration
- Health checks configured

## Quick Start

```bash
# Pull from GHCR
docker pull ghcr.io/tenyberry84-rgb/laravel-crud:1.0.0

# Run with Docker Compose
docker compose up -d

# Or deploy to Swarm
docker stack deploy -c docker-compose.yml laravel
```

## Endpoints
- `GET /api/tasks` - List all tasks
- `POST /api/tasks` - Create task
- `GET /api/tasks/{id}` - Show task
- `PUT /api/tasks/{id}` - Update task
- `DELETE /api/tasks/{id}` - Delete task

## Tech Stack
- PHP 8.2-FPM (Alpine Linux)
- Laravel (custom lightweight CRUD API)
- MySQL 8.0 (persistent storage with volumes)
- Redis 7 (caching layer)
- Nginx + Supervisor (process management)
- Docker multi-stage build (optimized for production)
