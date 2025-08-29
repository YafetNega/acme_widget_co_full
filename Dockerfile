# Simple PHP + Composer + PHPUnit environment
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y git unzip zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP extensions (if needed)
RUN docker-php-ext-install pcntl

CMD ["php", "-a"]
