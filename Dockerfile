FROM alpine:3

# Install packages
RUN apk update && \
    apk upgrade && \
    apk --no-cache add \
        libzip-dev zip curl \
        php7 php7-cgi php7-curl php7-opcache php7-zip \
        php7-bcmath php7-pcntl php7-json php7-phar \
        php7-mbstring php7-openssl php7-xml php7-tokenizer \
        php7-dom php7-xmlwriter php7-posix php7-ctype php7-iconv \
        php7-pdo_sqlite php7-pdo_mysql php7-session \
        yarn

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Dependecies
ADD composer.json composer.lock package.json yarn.lock /var/www/
RUN cd /var/www/ && \
    composer install -n --prefer-dist --no-dev --no-suggest --no-scripts && \
    yarn install

# Source
ADD . /var/www/

# Build source
RUN cd /var/www/ && \
    composer dump-autoload -n --no-dev --optimize && \
    composer dump-env prod && \
    yarn build && \
    ./bin/console cache:clear --env=prod

EXPOSE 80
WORKDIR /var/www/

ADD docker/entrypoint.sh /
ENTRYPOINT ["/entrypoint.sh"]