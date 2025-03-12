# Use official PHP image
FROM php:8.2

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Set correct permissions
RUN chown -R www-data:www-data /var/www

# Expose Laravel's default development server port
EXPOSE 8000

# Start Laravel's built-in web server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
