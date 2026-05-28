FROM php:8.3

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install NodeJS & NPM
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Get modern Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Run application builds
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build
RUN php artisan storage:link --force

# FIX PERMISSIONS: Crucial so Laravel can write logs/sessions on Render
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

# OPTIMIZED RUNTIME: Clears cached variables right before serving to read fresh Render Env settings
CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force --seed && php artisan serve --host=0.0.0.0 --port=8000"]