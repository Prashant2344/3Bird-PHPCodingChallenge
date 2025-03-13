FROM php:8.3.11-fpm
 
LABEL author="Prashant Silpakar"
 
# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential libpng-dev libjpeg-dev libwebp-dev libxpm-dev libfreetype6-dev \
    libzip-dev zip unzip git bash fcgiwrap libmcrypt-dev libonig-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*
 
# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_pgsql mbstring zip exif pcntl bcmath opcache
 
# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
 
# Copy application code
COPY . /var/www/html/
 
# Configure permissions and timezone
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www \
    && ln -snf /usr/share/zoneinfo/Asia/Kathmandu /etc/localtime \
    && echo "Asia/Kathmandu" > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "Asia/Kathmandu"\n' > /usr/local/etc/php/conf.d/tzone.ini
 
USER www
 
EXPOSE 9000
 
CMD ["php-fpm"]