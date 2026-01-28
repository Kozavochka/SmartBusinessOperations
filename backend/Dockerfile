FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

# System deps for common Laravel needs + pgsql support.
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
  && rm -rf /var/lib/apt/lists/*

# PHP extensions:
# - pdo_pgsql: Postgres
# - intl: i18n support (Carbon/locales/etc.)
# - zip: some packages/artisan tasks
# - pcntl: queue workers/schedule:work
# - sockets: required by php-amqplib (RabbitMQ)
RUN docker-php-ext-install -j"$(nproc)" pdo_pgsql intl zip pcntl sockets

# Redis extension (cache/locks/rate limits).
RUN pecl install redis \
  && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app source (Laravel lives under backend/app in the repo).
COPY app/ /var/www/html/

# Install PHP deps inside the image (so the container can run without bind-mounts).
# Note: keep --no-scripts to avoid running artisan during image build.
RUN composer install --no-interaction --no-progress --prefer-dist --no-scripts

# Ensure runtime-writable dirs are writable in container.
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
  && chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

# Simple dev-friendly HTTP server. For production we'll swap to nginx+php-fpm.
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
