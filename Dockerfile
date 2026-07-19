# Gunakan image resmi PHP dengan Apache
FROM php:8.2-apache

# Install sistem dependensi dan ekstensi PHP yang diperlukan CI4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl pdo pdo_mysql gd

# Aktifkan mod_rewrite Apache (wajib untuk routing CI4)
RUN a2enmod rewrite

# Ubah konfigurasi Apache agar mengarah ke folder public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Salin semua file project ke dalam container
COPY . /var/www/html/
# Pastikan folder writable bisa diakses penuh
RUN chown -R www-data:www-data /var/www/html/writable
RUN chmod -R 777 /var/www/html/writable

# Atur kepemilikan folder writable agar bisa ditulis oleh server
RUN chown -R www-data:www-data /var/www/html/writable

# Install Composer untuk memuat dependensi project
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Tambahkan mysqli ke dalam daftar ekstensi yang di-install
RUN docker-php-ext-install intl pdo pdo_mysql mysqli