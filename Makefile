# Cinemat Docker Management

.PHONY: help build up down restart logs shell migrate seed test clean

help: ## Show this help message
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker images
	docker-compose build

up: ## Start all services
	docker-compose up -d

down: ## Stop all services
	docker-compose down

restart: ## Restart all services
	docker-compose restart

logs: ## Show logs for all services
	docker-compose logs -f

logs-app: ## Show logs for app service
	docker-compose logs -f app

logs-nginx: ## Show logs for nginx service
	docker-compose logs -f nginx

logs-mysql: ## Show logs for mysql service
	docker-compose logs -f mysql

logs-redis: ## Show logs for redis service
	docker-compose logs -f redis

shell: ## Open shell in app container
	docker-compose exec app sh

shell-root: ## Open shell as root in app container
	docker-compose exec --user root app sh

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

migrate-fresh: ## Fresh migration with seeding
	docker-compose exec app php artisan migrate:fresh --seed

seed: ## Run database seeders
	docker-compose exec app php artisan db:seed

test: ## Run tests
	docker-compose exec app php artisan test

key-generate: ## Generate application key
	docker-compose exec app php artisan key:generate

cache-clear: ## Clear application cache
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

storage-link: ## Create storage link
	docker-compose exec app php artisan storage:link

composer-install: ## Install composer dependencies
	docker-compose exec app composer install

composer-update: ## Update composer dependencies
	docker-compose exec app composer update

npm-install: ## Install npm dependencies
	docker-compose exec app npm install

npm-build: ## Build assets
	docker-compose exec app npm run build

clean: ## Clean up Docker resources
	docker-compose down -v
	docker system prune -f

status: ## Show status of all services
	docker-compose ps

# Development shortcuts
dev: build up migrate-fresh ## Build, start services and run fresh migrations
dev-restart: down up migrate ## Restart services and run migrations
