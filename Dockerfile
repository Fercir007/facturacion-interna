# ============================================================
# Stage 1 — Dependencias JS y compilación de assets
# ============================================================
FROM node:20-alpine AS assets

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# ============================================================
# Stage 2 — Dependencias PHP
# ============================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

ARG APP_ENV=production

RUN if [ "$APP_ENV" = "production" ]; then \
        composer install \
            --no-dev \
            --no-interaction \
            --no-plugins \
            --no-scripts \
            --prefer-dist \
            --optimize-autoloader \
            --ignore-platform-reqs; \
    else \
        composer install \
            --no-interaction \
            --prefer-dist \
            --ignore-platform-reqs; \
    fi
#RUN composer install \
#    --no-dev \
#    --no-interaction \
#    --no-plugins \
#    --no-scripts \
#    --prefer-dist \
#    --optimize-autoloader

# ============================================================
# Stage 3 — Imagen final (Nginx + PHP-FPM)
# ============================================================
FROM php:8.2-fpm-alpine

# Instalar Nginx y dependencias del sistema
RUN apk add --no-cache \
    nginx \
    postgresql-dev \
    libpq \
    oniguruma-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pdo_mysql \
        mbstring \
        xml \
        opcache

# Configuración de OPcache para producción
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

# Copiar vendor y assets compilados de los stages anteriores
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Copiar el código fuente
COPY . .

# Permisos correctos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Configuración de Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]