FROM php:7-fpm
#instalar algunas dependencias para compilar las librerias necesarias
RUN apt update && apt install -y --no-install-recommends libpng-dev libjpeg-dev dcmtk && apt-get clean
#configura gd para imágenes
RUN docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr
#intalar los modulos necesarios de php
RUN docker-php-ext-install json pdo pdo_mysql gd
COPY . /code
RUN chown www-data:www-data /code/public/avatars
RUN chown www-data:www-data /code/public/data
USER www-data