FROM php:8.2-fpm
WORKDIR /var/www/html
RUN docker-php-ext-install pdo pdo_mysql
COPY auth.php .
COPY db.env .
