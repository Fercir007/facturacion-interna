#!/bin/sh
set -e

if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

PORT="${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="$PORT"
