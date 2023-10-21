FROM --platform=linux/amd64 php:8.1-apache

WORKDIR /var/www/html

# Install Composer from latest Docker image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Section to install PHP
RUN apt-get update -y && apt-get install -y \
    openssh-client \
    # PHP Modules
curl libicu-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libgd-dev \
    libjpeg62-turbo-dev \
    libpq-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

    # Install Postgre PDO
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN docker-php-ext-install xml
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pcntl

    # Add Xdebug
RUN pecl install xdebug-3.2.2 \
    && docker-php-ext-enable xdebug

    # Copy PHP configurations
COPY docker/php/php.ini docker/php/php-development.ini docker/php/xdebug.ini /usr/local/etc/php/conf.d/

# Section to configure Apache 2 web server

    # Ensure log directory exists
RUN mkdir /var/www/log

    # Copy and enable site config
#COPY docker/apache/100-site.conf /etc/apache2/sites-available/
#RUN echo 'Mutex posixsem' >>/etc/apache2/apache2.conf
#RUN a2ensite 100-site

RUN echo 'Mutex posixsem' >>/etc/apache2/apache2.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


    # Enable Modules
RUN a2enmod env \
    && a2enmod headers \
    && a2enmod deflate \
    && a2enmod expires \
    && a2enmod rewrite

# create local user inside docker and make apache use this user to solve permissions
ARG APACHE_USER=www-data
ARG APACHE_GROUP=www-data
ARG USER_ID=1000

# Set env vars used by apache
ENV APACHE_RUN_GROUP=${APACHE_GROUP}
ENV APACHE_RUN_USER=${APACHE_USER}

RUN id -u $APACHE_USER || useradd -rm -s /bin/sh -g ${APACHE_GROUP} -u ${USER_ID} ${APACHE_USER}
