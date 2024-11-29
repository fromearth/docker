FROM mysql:8.0.40-debian

# 日本語環境を追加
RUN apt-get update \
    && apt-get install -y locales \
    && sed -i 's/# \(ja_JP.UTF-8\)/\1/' /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=ja_JP.UTF-8

ENV LC_ALL=ja_JP.UTF-8
