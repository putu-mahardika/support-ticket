#!/bin/sh
cd /app
php artisan config:cache
php artisan cache:clear
php artisan key:generate
php artisan serve --host=0.0.0.0 --port=80
