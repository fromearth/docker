FROM node:22.11.0-slim AS node
FROM php:8.2.25-apache

# 必要なPHP拡張をインストール
RUN apt-get update \
    && apt-get install -y \
    zip \
    unzip \
    vim \
    git \
    curl \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring

# apacheモジュール追加
RUN a2enmod rewrite

# 日本語環境を追加
RUN apt-get update \
    && apt-get install -y locales \
    && sed -i 's/# \(ja_JP.UTF-8\)/\1/' /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=ja_JP.UTF-8

ENV LC_ALL=ja_JP.UTF-8

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# node.jsのインストール
COPY --from=node /usr/local/include/ /usr/local/include/
COPY --from=node /usr/local/lib/ /usr/local/lib/
COPY --from=node /usr/local/bin/ /usr/local/bin/
RUN npm install npm@latest -g

# 作業ディレクトリの設定
WORKDIR /var/www/html

# Apacheの起動
CMD ["apache2-foreground"]
