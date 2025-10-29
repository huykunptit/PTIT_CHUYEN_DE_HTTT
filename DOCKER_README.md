# Cinemat - Laravel 11 Docker Setup

## Prerequisites

- Docker
- Docker Compose
- Make (optional, for using Makefile commands)

## Quick Start

1. **Clone and setup environment:**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Edit .env file with your database credentials
   # Make sure to set APP_KEY
   ```

2. **Start the application:**
   ```bash
   # Using Makefile (recommended)
   make dev
   
   # Or using Docker Compose directly
   docker-compose up -d
   ```

3. **Generate application key:**
   ```bash
   make key-generate
   # or
   docker-compose exec app php artisan key:generate
   ```

4. **Run migrations:**
   ```bash
   make migrate-fresh
   # or
   docker-compose exec app php artisan migrate:fresh --seed
   ```

## Services

- **App**: Laravel application (PHP 8.2-FPM)
- **Nginx**: Web server (Port 8080)
- **MySQL**: Database (Port 3307)
- **Redis**: Cache and sessions (Port 6379)
- **Queue**: Background job processing
- **Scheduler**: Laravel task scheduler
- **phpMyAdmin**: Database management (Port 8081)

## Access Points

- **Application**: http://localhost:8089
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3307
- **Redis**: localhost:6379

## Available Commands

### Using Makefile (Recommended)

```bash
make help              # Show all available commands
make dev               # Build, start services and run fresh migrations
make up                # Start all services
make down              # Stop all services
make restart           # Restart all services
make logs              # Show logs for all services
make shell             # Open shell in app container
make migrate           # Run database migrations
make migrate-fresh     # Fresh migration with seeding
make test              # Run tests
make cache-clear       # Clear all caches
make clean             # Clean up Docker resources
```

### Using Docker Compose

```bash
# Basic commands
docker-compose up -d                    # Start services
docker-compose down                     # Stop services
docker-compose logs -f                  # View logs
docker-compose exec app sh              # Access container shell

# Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan key:generate
docker-compose exec app composer install
docker-compose exec app npm install
```

## Environment Variables

Key environment variables for Docker setup:

```env
APP_NAME=Cinemat
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8089

DB_HOST=mysql
DB_DATABASE=cinemat
DB_USERNAME=cinemat
DB_PASSWORD=secret

REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## Troubleshooting

### Common Issues

1. **Permission issues:**
   ```bash
   make shell-root
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

2. **Database connection issues:**
   - Check if MySQL container is running: `docker-compose ps`
   - Wait for MySQL to be ready: `docker-compose logs mysql`

3. **Cache issues:**
   ```bash
   make cache-clear
   ```

4. **Rebuild containers:**
   ```bash
   make clean
   make dev
   ```

### Logs

- View all logs: `make logs`
- View specific service logs: `make logs-app`, `make logs-mysql`, etc.

## Development Workflow

1. **Start development:**
   ```bash
   make dev
   ```

2. **Make changes to your code** (files are mounted as volumes)

3. **Run tests:**
   ```bash
   make test
   ```

4. **Clear caches when needed:**
   ```bash
   make cache-clear
   ```

5. **Stop when done:**
   ```bash
   make down
   ```

## Production Considerations

For production deployment:

1. Update `APP_ENV=production` and `APP_DEBUG=false`
2. Use proper database credentials
3. Set up SSL certificates
4. Configure proper logging
5. Use production-optimized Docker images
6. Set up proper backup strategies
