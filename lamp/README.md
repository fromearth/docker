# DockerにLAMP環境を構築
> 多くのWebサービスでLAMP環境が使われています。
>
> 開発環境を用意する場合にDockerを使うと便利です。
## ディレクトリ構造
```markdown
myproject/
      +- mysql/         {MySQLサーバ関連}
      |     +- initdb.d/
      |     |      +- init.sql
      |     +- Dockerfile
      |
      +- phpapache/     {Apache Webサーバ関連}
      |     +- Dockerfile
      |     +- php.ini
      |
      +- src/           {Webアプリケーション(ドキュメントルート)}
      |     +- index.php
      |
      +- compose.yml
```

## Docker環境の構築手順
1. Docker Desktopを起動します。  
(要らない「イメージ」「ボリューム」「コンテナ」は削除しておく）

2. ターミナルを開く  
「myproject」ディレクトリを右クリックして「ターミナルで開く」を選択する。（PowerShellが開きます）

3. コンテナを起動する  
PowerShellコマンドラインから以下コマンドを打つことで（カスタムイメージ作成 ⇒コンテナ作成 ⇒コンテナ起動）一連の流れを実行する。
```sh
docker compose up -d
```
結果、Docker Desktop画面で「イメージ」「ボリューム」「コンテナ」が作成されていることが確認でき、コンテナが起動した状態になっています。

4. デモ画面表示を確認  
Docker Desktop画面「コンテナ」の中から「run-phpapache」の「8080:80」を押下します。（http://localhost:8080/）  
ブラウザ画面に以下が表示できていれば正常に動作しています。
```markdown
array(2) { ["id"]=> int(1) ["name"]=> string(6) "富士" }
```
表示データはMySQL（`test_db`データベースのitemテーブル）に保存したデータを取得したものです。

5. コンテナを停止する  
Dockerの使い方としてコンテナを起動して開発を行い、  
「今日は開発終わり」となったらコンテナを停止して削除します。（イメージとボリュームは残した状態）

以下のコマンドを実行してコンテナの停止と削除を行うことができます。
```sh
docker compose down
```

## DockerのLAMP環境で開発
上記までで既にDockerのLAMP環境を使った開発ができる状態になっています。

開発（ファイルの作成・編集・削除）はターミナルを使ってDocker環境でもできるし、ホストOS側で通常のファイル操作でもできます。

Apache+PHPコンテナにターミナル接続するには
```sh
docker compose exec phpapache bash
```
ターミナル接続した地点（/var/www/html）をドキュメントルート設定にしています。ここではLinux（debian）の操作が自由にできます。  
viエディタを使って開発することができます。

MySQLコンテナにターミナル接続するには（ターミナルのタブを右クリックして「タブを複製する」から増やせます）
```sh
docker compose exec mysql bash
```
ターミナル接続したらLinux（debian）の操作が自由にできます。  
mysqlクライアントを使ってサーバに接続することができます。
```sql
# mysql -u test -ptest test_db
mysql> select * from item;
+----+-----------+
| id | name      |
+----+-----------+
|  1 | 富士      |
|  2 | 鷹        |
|  3 | なすび    |
+----+-----------+
3 rows in set (0.00 sec)

mysql>
```
