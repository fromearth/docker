# Dockerで環境構築
> Dockerはコンテナ型のプログラム実行環境です。
>
> Dockerを使うことで必要な環境を手元に構築し、その環境でWebアプリケーションを実行することができます。
> 開発環境としても便利です。

## Docker Desktopをインストールする
まずはDockerを管理するための「Docker Desktop」というソフトウェアをインストールします。  
※個人利用は無料で使えます。

## Docker環境を構築する（面倒な方法）
1. Dockerfileを用意してカスタムイメージ作成

ネット上にあるDockerHubでは、多くのイメージが配布されています。

そこから必要なイメージを取得し、自分の手元に持ってきて必要な設定を加えた「カスタムイメージ」を作成します。  
この設定を「Dockerfile」に記述します。  
(通常は複数のイメージを使用するため、それぞれのイメージに対して「Dockerfile」を用意します。)

カスタムイメージをビルドするために以下のコマンドを実行します
```sh
docker build -t my-image-name .
```
* 「-t my-image-name」指定により、ビルドするイメージに名前を付けます。
* 「.」指定は、Dockerfileを配置しているディレクトリを指します。

2. ネットワークの作成

コンテナ同士が通信するための「ネットワーク」を作成します。
```sh
docker network create {ネットワーク名}
```

3. コンテナの作成と起動

カスタムイメージを基にコンテナを作成して起動するために、以下のコマンドを実行します
```sh
docker run -d -p 80:80 --name my-container-name --network {ネットワーク名} my-image-name
```
* 「-d」は、バックグラウンドでコンテナを実行します
* 「-p 80:80」は、ホストOSのポート80をコンテナのポート80にマッピングします
* 「--name my-container-name」は、コンテナに名前を付けます
* 「--network {ネットワーク名}」は、作成したネットワークにコンテナを接続します

カスタムイメージごとにサーバ設定（ホストOSとDocker環境間のポートマッピングなど）を加えて「コンテナ」を作成します。  
（イメージの数 ＝ コンテナの数）  
作成したコンテナにネットワークを適用して起動しています。

## Docker環境をコマンド1つで構築する
上記に記載したDocker環境の構築方法はコマンドを何度も実行しているのが面倒です。

Dockerfileを用意するところまでは同じで、その後のコマンド指定をすべて「compose.yml」に記述するように変更することでコマンド1つでDocker環境を構築（つまりコンテナの作成と起動まで）を実現できます。

### Dockerfileの記述例
```markdown
FROM php:8.2.25-apache

RUN apt-get update \
    && apt-get install -y \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql

WORKDIR /var/www/html
```
#### 捕捉
* FROMでDockerHubから取得するイメージを指定
* RUNでカスタムイメージに追加するパッケージのインストール実行

### compose.ymlの記述例
```yaml
services:
  web:
    container_name: run-web
    build: ./web
    ports:
      - "80:80"
    networks:
      - my-network

networks:
  my-network:
```
#### 捕捉
* buildはDockerfileの配置ディレクトリ指定

これにより、すべての設定が1つのファイルにまとまり以下コマンドで簡単に環境を構築できます。
```sh
docker compose up -d
```
