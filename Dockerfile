# Step 1: Use a base image with PHP and necessary extensions
FROM php:8.3-fpm

# Step 2: Install system dependencies
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql opcache

# Step 3: Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Step 4: Set the working directory inside the container
WORKDIR /var/www

# Step 5: Copy the Laravel app into the container
COPY . .

# Step 6: Install Composer dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Step 7: Set appropriate permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Step 8: Expose the port the app will run on
EXPOSE 9000

# Step 9: Start PHP-FPM
CMD ["php-fpm"]
