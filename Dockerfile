# 1. Usamos una imagen base oficial de PHP 8.3 con Composer y Node
FROM composer/composer:2-bin AS composer_bin
FROM node:22 AS node_bin
FROM php:8.3-fpm-alpine

# 2. Instalamos las dependencias de PHP que Laravel necesita
RUN apk add --no-cache \
    libpng-dev libzip-dev \
    postgresql-dev \
    && docker-php-ext-install \
    pdo pdo_pgsql pgsql zip pcntl \
    && docker-php-ext-enable pdo_pgsql

# 3. Copiamos los binarios de Composer y Node
COPY --from=composer_bin /composer /usr/bin/composer
COPY --from=node_bin /usr/local/bin/ /usr/local/bin/
COPY --from=node_bin /usr/local/lib/ /usr/local/lib/

# 4. Configuramos el directorio de trabajo
WORKDIR /var/www

# 5. Copiamos el código de la app
COPY . .

# 6. Instalamos dependencias de Composer (para producción)
RUN composer install --no-dev --optimize-autoloader

# 7. Limpiamos la caché de configuración
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

# 8. Establecemos permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 9. Exponemos el puerto (Render lo necesita)
EXPOSE 8000

# 10. El comando para iniciar (¡OJO! Render usará el "Start Command" de la UI)
#CMD php artisan serve --host 0.0.0.0 --port 8000