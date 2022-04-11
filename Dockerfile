FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER www-data

WORKDIR /app
COPY . .
RUN composer install --ignore-platform-reqs
RUN npm run production

USER root
RUN chmod -R 775 storage
RUN chmod -R 775 bootstrap

EXPOSE 80
RUN sed -i 's/\r//g' /app/run.sh
ENTRYPOINT [ "/app/run.sh" ]
