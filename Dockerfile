FROM php:8.4-apache

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel y SQLite
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    zip \
    unzip \
    nodejs \
    npm

# Instalar extensiones PHP para SQLite (no PostgreSQL)
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar Apache para apuntar a /var/www/public
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurar AllowOverride All para que .htaccess funcione
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www

# Copiar los archivos del proyecto
COPY . .

# Instalar dependencias de PHP
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Instalar dependencias de Node y compilar assets de Vite
RUN npm install && npm run build

# Crear archivo SQLite vacio
RUN mkdir -p database && touch database/database.sqlite

# Configurar permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

# Crear enlace simbolico para storage
RUN php artisan storage:link

# Exponer el puerto 80
EXPOSE 80

# Comando para correr migraciones y arrancar Apache restableciendo permisos
CMD php artisan migrate --force && chown -R www-data:www-data /var/www/database /var/www/storage /var/www/bootstrap/cache && apache2-foreground