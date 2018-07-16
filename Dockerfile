FROM php:7.2.6

RUN pecl install xdebug-2.6.0 && docker-php-ext-enable xdebug
RUN echo "xdebug.max_nesting_level = 1000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ENTRYPOINT bash