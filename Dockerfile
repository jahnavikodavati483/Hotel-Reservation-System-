FROM php:8.2-apache

# Install PHP extensions for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files to Apache directory
COPY . /var/www/html/

# Render provides PORT as an environment variable
ARG PORT
ENV PORT ${PORT}

# Update Apache to listen on Render PORT
RUN sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Start Apache server
CMD ["apache2-foreground"]
