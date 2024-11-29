# DockerにLaravel開発環境を構築
> 人気のPHPフレームワーク「Laravel」の開発環境を構築します。
## ディレクトリ構造
```markdown
myproject/
      +- mysql/         {MySQLサーバ関連}
      |     +- Dockerfile
      |
      +- phpapache/     {Apache Webサーバ関連}
      |     +- Dockerfile
      |     +- my-httpd.conf
      |     +- php.ini
      |
      +- compose.yml
```
Laravelインストールにより「myproject」直下に「laravel」ディレクトリが作られ、その中の「public」ディレクトリをドキュメントルート設定にしています。

## Laravel環境の構築手順
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

4. Laravelをインストールする（ソースファイルを取得）
Apache+PHPコンテナにターミナル接続します
```sh
docker compose exec phpapache bash
```
接続したらその場所にLaravelをインストールします
```sh
composer create-project "laravel/laravel=9.*" .
```
すこし時間がかかります

インストールが完了したらパーミッション設定を行います
```sh
chmod -R 777 storage bootstrap/cache
```

5. Laravelトップ画面表示を確認する  
ここでLaravelトップ画面が表示できることを確認します

Docker Desktop画面「コンテナ」の中から「run-phpapache」の「8080:80」を押下します。（http://localhost:8080/）  
ブラウザ画面に以下が表示できていれば正常に動作しています。

6. Laravelの初期設定をする  
最低限の設定をしておきます  

file: config/app.php
```php
    'timezone' => 'Asia/Tokyo',
    'locale' => 'ja',
```
データベース接続設定周りはcompose.ymlの設定に合わせます  
file: .env
```ini
DB_CONNECTION=mysql
DB_HOST=run-mysql
DB_PORT=3306
DB_DATABASE=test_db
DB_USERNAME=test
DB_PASSWORD=test
```
* ホスト名「`DB_HOST`」はcompose.ymlで設定したコンテナ名を指定します。

7. DBを構築する  
DBが使えることを確認するためにマイグレーションを実行します（確認後にロールバックして元の状態に戻すことができます）
```sh
php artisan migrate
```

8. DBデータを画面表示する  
DBにレコード挿入してそのレコードを取得して画面に表示できることを確認します

初めから記述されているルート定義を以下のようにします（2行目と3行目を追加）  
file: routes/web.php
```markdown
Route::get('/', function () {
    App\Models\User::updateOrCreate(['id'=>1],['name'=>'日本語テスト表示','email'=>'hoge@example.com','password'=>'hoge']);
    var_dump(App\Models\User::find(1)->name);
    return view('welcome');
});
```
手順「5」の方法でLaravelトップ画面を表示します  
トップ画面のいちばん上に「string(24) "日本語テスト表示"」が表示できていればDBとの連携ができているのでOKです

先ほどルート定義に追加した2行は削除しておきましょう  
マイグレーションを元に戻す場合は以下を実行します
```sh
php artisan migrate:rollback
```

9. コンテナを停止する  
Dockerの使い方としてコンテナを起動して開発を行い、  
「今日は開発終わり」となったらコンテナを停止して削除します。（イメージとボリュームは残した状態）

以下のコマンドを実行してコンテナの停止と削除を行うことができます。
```sh
docker compose down
```

## DockerのLaravel環境で開発
上記までで既にDockerのLaravel環境を使った開発ができる状態になっています。

開発（ファイルの作成・編集・削除）はターミナルを使ってDocker環境でもできるし、ホストOS側で通常のファイル操作でもできます。

Apache+PHPコンテナにターミナル接続するには
```sh
docker compose exec phpapache bash
```
ターミナル接続した地点（/var/www/html）がLaravelルートで、その中の「public」ディレクトリをドキュメントルート設定にしています。ここではLinux（debian）の操作が自由にできます。  
viエディタを使って開発することができます。

MySQLコンテナにターミナル接続するには（ターミナルのタブを右クリックして「タブを複製する」から増やせます）
```sh
docker compose exec mysql bash
```
ターミナル接続したらLinux（debian）の操作が自由にできます。  
mysqlクライアントを使ってサーバに接続することができます。
```sql
# mysql -u test -ptest test_db
mysql> select now();
mysql> show variables like 'char%';
mysql> \q
```
