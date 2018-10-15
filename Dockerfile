FROM php:fpm

RUN apt-get update && apt-get install -my wget gnupg openssl zip unzip git
RUN curl -sL https://deb.nodesource.com/setup_9.x | bash -
RUN apt-get install -y nodejs libpng-dev

RUN apt remove cmdtest
RUN apt remove yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install yarn

RUN apt-get update -y && apt-get install -y npm

COPY . /var/www

WORKDIR /var/www

RUN chmod +x composerinstall.sh

RUN docker-php-ext-install pdo pdo_mysql mbstring gd curl

RUN ./composerinstall.sh
RUN php composer.phar install --no-scripts \
    && rm composer.phar

RUN yarn install

RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache

RUN rm composerinstall.sh

RUN php artisan key:generate

RUN php artisan migrate

RUN php artisan passport:install

EXPOSE 9000
