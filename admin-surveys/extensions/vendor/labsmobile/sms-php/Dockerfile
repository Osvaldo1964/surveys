# Use an official PHP image as a starting point
FROM php:8.3-cli

# Download and install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Install the zip extension
RUN apt-get update \
    && apt-get install -y libzip-dev zip \
    && docker-php-ext-install zip

# Install the zip extension for alpine linux distributions
#RUN apk update && \
#    apk add --no-cache libzip-dev zip && \
#    docker-php-ext-install zip

# Set the working directory to the root of your project
WORKDIR /app

# Copy the composer.json and composer.lock file to the container
COPY composer.json ./

# Install the dependencies
RUN composer install

# Copy the rest of your application to the container
COPY . .

# Default command to run when container starts
CMD ["php", "index.php"]
