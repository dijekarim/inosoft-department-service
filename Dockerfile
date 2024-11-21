# Use the official PHP 8.3 image
FROM php:8.3.12-fpm

# Install required PHP extensions and RabbitMQ client
RUN apt-get update && apt-get -y install gcc make autoconf libc-dev pkg-config unzip git
RUN docker-php-ext-install bcmath pdo pdo_mysql sockets
RUN apt-get install -y libssl-dev librabbitmq-dev && pecl install amqp
RUN docker-php-ext-enable amqp

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

# Set the working directory
WORKDIR /app

# Copy the Laravel application files
COPY . .

# Install Composer dependencies
COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer
RUN composer install --ignore-platform-req=ext-sockets

# Expose the port on which the application will run (adjust this port if needed)
EXPOSE 80

# Start the Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=80