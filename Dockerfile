FROM php:fpm-alpine

#################################################
#               Install depedencies             #
#################################################
RUN apk add libpng-dev
RUN docker-php-ext-install pdo pdo_mysql mbstring gd bcmath

#################################################
#                   Workdir                     #
#################################################

COPY . /var/www
WORKDIR /var/www
RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache

#################################################
#                   Composer                    #
#################################################

RUN chmod +x composerinstall.sh
RUN ./composerinstall.sh
RUN php composer.phar install --no-scripts \
    && rm composer.phar
RUN rm composerinstall.sh

#################################################
#                   Artisan                     #
#################################################

RUN php artisan key:generate
RUN php artisan migrate
RUN php artisan passport:install

#################################################
#                     CMD                       #
#################################################

#Queue Worker
CMD nohup php artisan queue:work --tries=3 &
#WebSocket
CMD nohup php artisan websockets:serve &

#################################################
#                    Expose                     #
#################################################

#FPM
EXPOSE 9000
#WebSocket
EXPOSE 6001
