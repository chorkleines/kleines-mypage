FROM ubuntu:20.04 AS builder

RUN apt update && \
    apt install -y \
        software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt update && \
    apt install -y \
        php8.0-cli \
        curl && \
    apt clean && \
    rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


FROM ubuntu:20.04

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN apt update && \
    apt install -y \
        software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt update && \
    apt install -y \
        php8.0-cli \
        php8.0-mysql \
        php8.0-dom \
        php8.0-curl \
        php8.0-zip\
        php8.0-mbstring\
        curl && \
    apt clean && \
    rm -rf /var/lib/apt/lists/*

COPY --from=builder /usr/local/bin/composer /usr/local/bin/
