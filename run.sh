#!/bin/sh
cd /app
php artisan key:generate
php artisan config:cache
php artisan serve --host=0.0.0.0 --port=80
