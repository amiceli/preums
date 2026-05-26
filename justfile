set dotenv-load := true

# Build dev env
dev_build:
    docker compose up --build

# Run dev env with docker
run_dev:
    docker compose up --build -d

# Stop project
stop:
    docker compose down

# Connect to postgres container
psql:
    docker compose exec postgres psql -U ${DB_USERNAME} -d ${DB_DATABASE}

# Run artisan command
artisan *args:
    docker compose exec app php artisan {{args}}

# Run composer command
composer *args:
    docker compose exec app composer {{args}}

# Run npm command
npm *args:
    docker compose exec app npm {{args}}

# Run laravel db migrations
migrate:
    docker compose exec app php artisan migrate

# Show docker logs
logs:
    docker compose logs -f

# Run all commands to sync
sync:
    just artisan migrate:fresh
    just artisan app:frooze-repositories
    just artisan app:load-pro-lang
    just artisan app:pro-lang-assets

# Clear and cache config
clean_smala:
    just artisan config:clear
    just artisan config:cache

# check and fix code with biome
front_lint:
    docker compose exec app npx biome check --write

# Yamllint
yamllint:
    docker run --rm -v "$(pwd):/data" cytopia/yamllint ./*.yml

# Test and fix files with Pint
pint_fix file="":
    docker compose exec app ./vendor/bin/pint {{file}}

# Lint everything
lint:
    just front_lint
    just pint_fix

# open project main page
open:
    open "http://localhost:8000/"

# Open adminer page
go_adminer:
    open "http://localhost:8081/?pgsql=postgres&username=app&db=app&ns=public"

# # test github api endpint
test_api endpoint="" output="out":
    curl -L \
        -H "Accept: application/vnd.github+json" \
        -H "Authorization: Bearer $GITHUB_TOKEN" \
        -H "X-GitHub-Api-Version: 2022-11-28" \
        https://api.github.com/repos/amiceli/vitest-cucumber{{endpoint}} > {{output}}.json

# Test file(s) with Pint
pint file="":
    docker compose exec app ./vendor/bin/pint {{file}} --test

# # Run Pest tests
pest file="":
    docker compose exec app ./vendor/bin/pest {{file}}
