FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
       freetype \
       libpng \
       libjpeg-turbo \
       freetype-dev \
       libpng-dev \
       jpeg-dev \
       libjpeg \
       libjpeg-turbo-dev \
       libwebp \
       libwebp-dev
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install gd

RUN apk add --update --no-cache nodejs npm
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-install exif

RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install --ignore-platform-reqs
RUN npm install
RUN npm run production

RUN chmod -R 775 storage
RUN chmod -R 775 bootstrap

EXPOSE 80
RUN sed -i 's/\r//g' /app/run.sh
ENTRYPOINT [ "/app/run.sh" ]
