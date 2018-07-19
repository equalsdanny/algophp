FROM php:7.2.6

RUN pecl install xdebug-2.6.0 && docker-php-ext-enable xdebug
RUN apt-get update -y && apt-get install -y procps nano less gnupg

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs

RUN mkdir /opt/project
WORKDIR /opt/project
EXPOSE 8000

ENTRYPOINT php artisan serve --host 0.0.0.0 --port 8000