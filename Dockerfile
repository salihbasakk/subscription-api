FROM php:8.1-fpm
WORKDIR /var/www/symfony

RUN apt-get update && apt-get install -y \
    g++ \
    git \
    nano \
    sudo \
    supervisor \
    cron \
    librabbitmq-dev \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libmemcached-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libsocket6-perl \
    libsocket6-perl \
    libio-socket-inet6-perl \
    libio-socket-ssl-perl

RUN pecl install xdebug amqp
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql intl exif mbstring sockets -j$(nproc) gd zip
RUN docker-php-ext-enable xdebug amqp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ln -snf /usr/share/zoneinfo/Europe/Istanbul /etc/localtime && echo Europe/Istanbul > /etc/timezone \
    && printf '[Date]\ndate.timezone = "%s"\n', Europe/Istanbul > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

COPY . .

RUN composer install

#CRON
COPY docker/crontab /etc/cron.d/pending-purchases-cron

RUN chmod 0644 /etc/cron.d/pending-purchases-cron
RUN crontab /etc/cron.d/pending-purchases-cron

COPY docker/start-cron.sh /start-cron.sh

RUN chmod +x /start-cron.sh

#CMD ["/start-cron.sh"]

#SUPERVISOR
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/conf.d /etc/supervisor/conf.d

RUN mkdir -p /var/run/supervisor && touch /var/run/supervisor.sock

#CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf", "-n"]
