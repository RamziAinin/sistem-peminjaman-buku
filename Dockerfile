FROM php:8.2-apache

# Install kebutuhan sistem untuk Laravel
RUN apt-get update && apt-get install -y zip unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Aktifkan fitur mod_rewrite supaya routing (URL) Laravel jalan
RUN a2enmod rewrite

# Ubah settingan root server langsung ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy semua file dari komputermu ke dalam server
COPY . /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Beri izin server untuk mengedit folder penyimpanan
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache