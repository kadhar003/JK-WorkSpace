# Docker Setup Guide for JK Workspace

This guide provides instructions for running the JK Workspace Laravel application using Docker.

## Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- Minimum 4GB RAM allocated to Docker

## Quick Start

### 1. Setup Environment Variables

Copy the Docker environment template:

```bash
cp .env.docker .env
```

Generate a Laravel application key:

```bash
docker-compose run --rm app php artisan key:generate
```

### 2. Build and Run Containers

```bash
docker-compose up -d
```

This will build and start:
- **webserver**: Nginx reverse proxy and web server
- **app**: PHP-FPM application server
- **db**: MySQL 8.0 database
- **redis**: Redis cache store
- **queue**: Background job queue worker

### 3. Run Database Migrations

```bash
docker-compose exec app php artisan migrate
```

### 4. Seed Database (Optional)

```bash
docker-compose exec app php artisan db:seed
```

### 5. Build Frontend Assets (if needed)

```bash
docker-compose exec app npm run build
```

### 6. Access the Application

Open your browser and navigate to: `http://localhost`

## Docker Services

### Webserver (Nginx)
- **Port**: 80 (HTTP), 443 (HTTPS)
- **Role**: Reverse proxy and static file server
- **Config**: `docker/nginx.conf` and `docker/default.conf`

### App (PHP-FPM)
- **Port**: 9000 (internal only)
- **Role**: Application server
- **Mounts**: Entire application directory with persistent storage

### Database (MySQL)
- **Port**: 3306 (internal only)
- **Database**: `laravel` (configurable via `.env`)
- **Volume**: `db-data` for persistent storage
- **Init Script**: `database/database.sql` (auto-imported)

### Redis
- **Port**: 6379 (internal only)
- **Role**: Cache store, session storage, queue broker
- **Volume**: `redis-data` for persistent storage

### Queue Worker
- **Role**: Processes background jobs
- **Command**: `php artisan queue:work`
- **Auto-restart**: Yes, unless stopped manually

## Common Commands

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f webserver
docker-compose logs -f db
```

### Run Artisan Commands

```bash
docker-compose exec app php artisan <command>

# Examples
docker-compose exec app php artisan tinker
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
```

### Run NPM Commands

```bash
docker-compose exec app npm run dev    # Development with Vite
docker-compose exec app npm run build  # Production build
docker-compose exec app npm install    # Install dependencies
```

### Access Database

```bash
docker-compose exec db mysql -u laravel -p laravel
```

### Access Redis CLI

```bash
docker-compose exec redis redis-cli
```

### Stop All Services

```bash
docker-compose stop
```

### Remove All Containers and Volumes

```bash
docker-compose down -v
```

### Rebuild Images

```bash
docker-compose build --no-cache
docker-compose up -d
```

## Environment Configuration

The `.env` file controls application and Docker settings:

```
APP_ENV=production          # Set to 'development' for local work
APP_DEBUG=false             # Set to 'true' for debugging
DB_CONNECTION=mysql         # Database type
DB_HOST=db                  # Database hostname (service name)
REDIS_HOST=redis            # Redis hostname (service name)
DOCKER_PORT=80              # Web server port on host
```

## Production Deployment

### 1. Set Environment Variables

```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=<your-generated-key>
DB_PASSWORD=<strong-password>
REDIS_PASSWORD=<strong-password>
```

### 2. Build Production Image

```bash
docker build -t jk-workspace:latest -t jk-workspace:v1.0 .
```

### 3. Run with Production Compose File

Modify `docker-compose.yml` for production (add SSL, remove health checks if desired, etc.)

### 4. Security Considerations

- Set strong database passwords
- Use environment secrets for sensitive data
- Configure SSL/TLS certificates in Nginx
- Use separate compose file for production
- Don't mount source code in production containers
- Use read-only volumes where possible

## Troubleshooting

### Permission Denied Errors

```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database Connection Failed

```bash
# Check if MySQL is ready
docker-compose logs db

# Restart database
docker-compose restart db
```

### Cache/Config Not Updating

```bash
# Clear all caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Build Fails

```bash
# Clean build
docker-compose down -v
docker system prune
docker-compose build --no-cache
docker-compose up -d
```

## Development Workflow

For local development, you might want to:

1. Keep the app container running
2. Make code changes directly in your editor
3. Use `docker-compose exec app` to run commands
4. Use `docker-compose logs -f app` to see real-time logs

## Performance Optimization

- Use `.dockerignore` to exclude unnecessary files (already configured)
- Enable Docker BuildKit: `export DOCKER_BUILDKIT=1`
- Use Alpine-based images for smaller sizes (already configured)
- Consider using Docker Desktop resource limits based on your system

## Additional Resources

- [Laravel Docker Documentation](https://laravel.com/docs)
- [Docker Official Docs](https://docs.docker.com/)
- [Docker Compose Reference](https://docs.docker.com/compose/compose-file/)
- [Nginx Configuration](https://nginx.org/en/docs/)
