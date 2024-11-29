FROM php:8.2.25-apache

# 必要なPHP拡張ほかをインストール
RUN apt-get update \
    && apt-get install -y \
    zip \
    unzip \
    vim \
    git \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring

RUN apt-get update \
    && apt-get install -y locales \
    && sed -i 's/# \(ja_JP.UTF-8\)/\1/' /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=ja_JP.UTF-8

ENV LC_ALL=ja_JP.UTF-8

# 作業ディレクトリの設定
WORKDIR /var/www/html

# Apacheの起動
CMD ["apache2-foreground"]
