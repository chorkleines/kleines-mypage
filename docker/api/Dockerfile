FROM php:8.0.30-fpm-alpine

COPY --from=composer:2.5.5 /usr/bin/composer /usr/bin/composer
COPY docker/api/php.ini /usr/local/etc/php/

WORKDIR /var/www/html

RUN apk --no-cache update && \
    apk --no-cache upgrade && \
    apk --no-cache add \
    freetype-dev libjpeg-turbo-dev libpng-dev libxml2-dev \
    git vim unzip tzdata \
    zlib-dev pcre-dev curl-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql mysqli mbstring gd \
    && docker-php-ext-enable mysqli \
    && cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    && apk del tzdata \
    && rm -rf /var/cache/apk/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
 
ENV TZ=Asia/Tokyo
 
EXPOSE 8000
 
COPY docker/api/start.sh /var/www/
RUN chmod +x /var/www/start.sh
CMD ["../start.sh"]
