set dotenv-load

# run project
run:
    composer run dev

# install composer and npm deps
install:
    npm install
    composer install

# check and fix code with biome
biome:
    npx biome check --write

# Lint everything
lint:
    just biome
    just pint_fix

# run and detach project with tmux
up:
    tmux new-session -d -s "preums"
    tmux send-keys -t "preums" "just run" ENTER

# open project main page
open:
    open "http://localhost:8000/"

# test github api endpint
test_api endpoint="" output="out":
    curl -L \
        -H "Accept: application/vnd.github+json" \
        -H "Authorization: Bearer $GITHUB_TOKEN" \
        -H "X-GitHub-Api-Version: 2022-11-28" \
        https://api.github.com/repos/amiceli/vitest-cucumber{{endpoint}} > {{output}}.json

# Test file(s) with Pint
pint file="":
    ./vendor/bin/pint {{file}} --test

# Test and fix files with Pint
pint_fix file="":
    ./vendor/bin/pint {{file}}

# Run Pest tests
pest file="":
    ./vendor/bin/pest {{file}}

# Run postgres with docker
up_db:
    docker run -d \
        --name pg \
        -e POSTGRES_DB=preums \
        -e POSTGRES_USER=root \
        -e POSTGRES_PASSWORD=toor \
        -p 5432:5432 \
        -v $PWD/pgdata:/var/lib/postgresql \
        postgres

# Run adminer
up_adminer:
    docker run -d \
        --name adminer \
        --link pg:db \
        -p 8080:8080 \
        adminer

# Clean database
clean:
    php artisan migrate:fresh

# Run all commands to sync
sync:
    just clean
    php artisan app:frooze-repositories
    php artisan app:load-pro-lang
    php artisan app:pro-lang-assets

clean_smala:
    php artisan config:clear
    php artisan config:cache

start:
    just up
    just up_db
    just up_adminer
    just open
