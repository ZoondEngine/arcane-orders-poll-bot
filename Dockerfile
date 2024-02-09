FROM php:8.1-fpm
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    supervisor \
    && docker-php-ext-install zip
COPY . ./
#RUN apt-get update && \
#    apt-get install -y libpq-dev && \
#    docker-php-ext-install pdo pdo_pgsql pgsql


RUN apt-get update && \
    apt-get install -y libpq-dev

RUN docker-php-ext-install pdo_pgsql

RUN chmod o+w storage/ -R && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chmod -R 755 bootstrap/cache
RUN composer update
RUN chmod -R 777 storage/
ADD ./supervisor.conf /etc/supervisor/conf.d/worker.conf
RUN php artisan key:generate
#RUN php artisan test --testsuite=Feature; exit 0
#RUN php artisan migrate:fresh --seed
#RUN php artisan make:monesy-user
#RUN php artisan storage:link
#RUN php artisan scribe:generate
#RUN composer dumpautoload -o
ADD ./supervisor.conf /etc/supervisor/conf.d/worker.conf
CMD php -S 0.0.0.0:8000 -t public