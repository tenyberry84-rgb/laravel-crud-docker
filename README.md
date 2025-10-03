Laravel CRUD with Docker - Phase 1
==================================

Stack
-----
- Laravel 10 (PHP 8.2)
- MySQL 8.0
- Redis 7
- Nginx + PHP-FPM

Quick Start
-----------
1. Make sure Docker Desktop is running
2. Build and start:
   ```
   docker compose up --build -d
   ```
3. Install Laravel (first time):
   ```
   docker compose exec app composer create-project laravel/laravel:^10.0 . --prefer-dist
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
   ```
4. Open: http://localhost:8080

Commands
--------
- Build: `docker compose build`
- Start: `docker compose up -d`
- Logs: `docker compose logs -f app`
- Stop: `docker compose down`
- Shell: `docker compose exec app sh`
- Artisan: `docker compose exec app php artisan <command>`

Phase 1 Learning
----------------
- Images: Laravel app image built from Dockerfile
- Containers: app, mysql, redis running together
- Volumes: mysql-data (DB persistence), storage-data (Laravel storage)
- Networks: laravel-net (bridge) connects all services
- Env vars: DB credentials, Redis host passed to Laravel

CRUD Endpoints (coming next)
-----------------------------
- GET /api/tasks - list all
- POST /api/tasks - create
- GET /api/tasks/{id} - show one
- PUT /api/tasks/{id} - update
- DELETE /api/tasks/{id} - delete
