FROM php:8.4-fpm

# Устанавливаем системные зависимости
RUN apt update && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev librdkafka-dev libmemcached-dev

# Устанавливаем PHP-расширения через универсальный установщик
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions decimal opcache pdo_pgsql pdo_mysql pcntl mysqli bcmath intl zip redis
RUN pecl install memcached && docker-php-ext-enable memcached

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Пользовательские аргументы
ARG UID=1000
ARG GID=1000
ARG USER=appuser

# Создаём пользователя
RUN groupadd -g ${GID} ${USER} \
    && useradd -m -u ${UID} -g ${GID} -s /bin/bash ${USER}

WORKDIR /app
RUN chown -R ${USER}:${USER} /app
RUN git config --global --add safe.directory /app

USER ${USER}

CMD ["php-fpm"]
