# Laravel Dockerized - Developer Setup Guide

## Tech Stack

- **Backend:** Laravel 13, PHP 8.4, Inertia.js v2
- **Frontend:** Vue 3, Tailwind CSS v4, Vite 8
- **Database:** MySQL 8.0
- **Server:** Nginx + PHP-FPM (via Docker)

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
- Git

> **Note:** You do NOT need PHP, Composer, Node.js, or MySQL installed locally. Everything runs inside Docker.

## Quick Start (First Time)

```bash
# 1. Clone the repository
git clone <repository-url>
cd laravel-dockerized

# 2. Build and start all containers
docker-compose up --build
```

That's it. The entrypoint script automatically handles:
- Installing Composer dependencies
- Installing NPM dependencies and building frontend assets
- Waiting for MySQL to be ready
- Generating the application key
- Running database migrations and seeders
- Caching config, routes, and views

Once you see the containers are healthy, open your browser.

## Services & URLs

| Service | URL | Description |
|---------|-----|-------------|
| **App** | [http://localhost:8000](http://localhost:8000) | Laravel application |
| **phpMyAdmin** | [http://localhost:8080](http://localhost:8080) | Database management UI |
| **MySQL** | `localhost:3306` | Database (connect via any MySQL client) |

## Daily Development

### Start the app (containers already built)

```bash
# Foreground (see logs in terminal)
docker-compose up

# Background (detached)
docker-compose up -d
```

### Stop the app

```bash
docker-compose down
```

### Check container status

```bash
docker-compose ps
```

### View logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f mysql
```

## Using the `dev.sh` Helper Script

A convenience script is included for common tasks:

```bash
# Make it executable (first time only)
chmod +x dev.sh

# Start / stop
./dev.sh start         # Start in foreground
./dev.sh start-bg      # Start in background
./dev.sh stop          # Stop all services

# Build
./dev.sh build         # Build and start
./dev.sh rebuild       # Full rebuild (down + build)

# Database
./dev.sh migrate       # Run migrations
./dev.sh seed          # Run seeders
./dev.sh fresh         # Fresh migration + seed

# Utilities
./dev.sh shell         # Open bash inside app container
./dev.sh artisan <cmd> # Run any artisan command
./dev.sh npm-build     # Rebuild frontend assets
./dev.sh status        # Show container status
./dev.sh logs [service]# View logs
./dev.sh clean         # Remove volumes and prune images
```

## Running Commands Inside the Container

```bash
# Open a shell
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan <command>

# Run Composer
docker-compose exec app composer <command>

# Run NPM
docker-compose exec app npm <command>
```

## Environment Configuration

| File | Purpose |
|------|---------|
| `.env.example` | Template for local environment variables |
| `.env.docker` | Docker-specific env (copied to `.env` during build) |
| `.env` | Active environment file (auto-generated in Docker) |

### Database Credentials

| Variable | Value |
|----------|-------|
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `mysql` (Docker service name) |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `laravel_vue_dockerized` |
| `DB_USERNAME` | `root` |
| `DB_PASSWORD` | `root` |

### phpMyAdmin Login

- **Server:** `mysql`
- **Username:** `root`
- **Password:** `root`

## Database Management

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Rollback last migration
docker-compose exec app php artisan migrate:rollback

# Fresh migration with seeders
docker-compose exec app php artisan migrate:fresh --seed
```

### Default Seeded User

| Field | Value |
|-------|-------|
| Name | Test User |
| Email | `test@example.com` |

## Important: Run Artisan Commands Inside Docker

All `php artisan` commands must be run inside the Docker container, **not** on your local machine:

```bash
docker-compose exec app php artisan <command>
```

### Route & Cache Management

Routes are **cached on container startup**. After making changes to routes, controllers, or config, you must clear the cache — **no need to rebuild the container**:

```bash
# After adding/modifying routes or controllers
docker-compose exec app php artisan route:clear

# After deleting a controller and its route
docker-compose exec app php artisan optimize:clear

# View all registered routes
docker-compose exec app php artisan route:list
```

> **Tip:** If you skip cache clearing after deleting a controller/route, the app will error because the cached routes still reference the deleted class.

## Frontend Development

Frontend assets are built during Docker startup. To rebuild manually:

```bash
docker-compose exec app npm run build
```

> **Note:** Hot Module Replacement (HMR) via `npm run dev` is not configured for Docker. Frontend changes require running `npm run build` inside the container, or you can run Vite locally if you have Node.js installed.

## Running Tests

```bash
# Run all tests
docker-compose exec app php artisan test --compact

# Run specific test file
docker-compose exec app php artisan test --compact --filter=ExampleTest

# Run with coverage
docker-compose exec app php artisan test --coverage
```

## Code Formatting

```bash
# Fix PHP code style
docker-compose exec app vendor/bin/pint

# Check frontend linting
docker-compose exec app npm run lint

# Format frontend code
docker-compose exec app npm run format
```

## Troubleshooting

### Containers won't start
```bash
# Check what's running
docker-compose ps

# Check logs for errors
docker-compose logs -f app
```

### "MySQL is unavailable" loop on startup
This usually means the app container cannot authenticate with MySQL. Common causes:

1. **Changed database credentials in `docker-compose.yml`** — MySQL only sets the root password on first initialization. If you changed `MYSQL_ROOT_PASSWORD` after the volume was already created, the old password is still stored in the volume. Fix:
   ```bash
   # Remove containers AND volumes, then rebuild
   docker-compose down -v
   docker-compose up --build
   ```
   > **Warning:** `-v` deletes the `mysql_data` volume. All database data will be lost (migrations and seeders will re-run automatically).

2. **MySQL hasn't finished initializing** — On first run, MySQL can take 30-60 seconds to initialize. The app will retry automatically. Check MySQL logs:
   ```bash
   docker-compose logs -f mysql
   ```

### Database connection refused
The app waits for MySQL on startup, but if issues persist:
```bash
# Check MySQL is running
docker-compose logs mysql

# Restart just MySQL
docker-compose restart mysql
```

### Port already in use
If port 8000 or 3306 is occupied:
```bash
# Find what's using the port
lsof -i :8000

# Or change the port mapping in docker-compose.yml
# e.g., '9000:80' instead of '8000:80'
```

### Need a full reset
```bash
# Remove everything (containers, volumes, data)
docker-compose down -v
docker-compose up --build
```

> **Warning:** `-v` removes the MySQL data volume. All database data will be lost.

## Project Structure

```
├── app/                    # Laravel application code
├── bootstrap/              # App bootstrap files
├── config/                 # Laravel configuration
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── docker/
│   ├── mysql/my.cnf        # MySQL configuration
│   ├── nginx/default.conf  # Nginx vhost configuration
│   └── php/
│       ├── local.ini       # PHP settings overrides
│       └── www.conf        # PHP-FPM configuration
├── public/                 # Web root (compiled assets)
├── resources/
│   ├── css/                # Tailwind styles
│   ├── js/                 # Vue components & pages
│   └── views/              # Blade templates
├── routes/                 # Route definitions
├── storage/                # Logs, cache, uploads
├── tests/                  # Pest test files
├── .env.docker             # Docker environment template
├── .env.example            # Local environment template
├── dev.sh                  # Docker helper script
├── docker-compose.yml      # Docker services definition
├── docker-entrypoint.sh    # Container startup script
├── Dockerfile              # App container build
├── vite.config.ts          # Vite bundler configuration
├── composer.json           # PHP dependencies
└── package.json            # Node.js dependencies
```
