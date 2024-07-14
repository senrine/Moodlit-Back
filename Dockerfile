FROM php:8.1-apache

# Install PostgreSQL driver
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache modules
RUN a2enmod rewrite ssl

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions mbstring xml zip ctype iconv intl pdo pdo_mysql dom filter gd iconv json

# Install Composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer

# Copy application files
COPY . /var/www/

# Set working directory
WORKDIR /var/www/

# Create SSL directory
RUN mkdir -p /etc/apache2/ssl

# Copy Apache configuration files
COPY ./.docker/apache/apache2.conf /etc/apache2/apache2.conf
COPY ./.docker/apache/vhosts/000-default.conf /etc/apache2/sites-enabled/000-default.conf



# Copy SSL certificates
COPY ./.docker/apache/ssl/localhost.crt /etc/apache2/ssl/localhost.crt
COPY ./.docker/apache/ssl/localhost.key /etc/apache2/ssl/localhost.key


# Set entrypoint
ENTRYPOINT ["bash", "./.docker/docker.sh"]


# Add wait-for-it
ADD https://github.com/vishnubob/wait-for-it/raw/master/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

# Expose ports
EXPOSE 80 443
