<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>MySQL連携テスト</title>
</head>
<body>
<?php
try {
    $dbh = new PDO('mysql:dbname=test_db;host=run-mysql', 'test', 'test');
    $sth = $dbh->query("select * from item where id = 1");
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    var_dump($row);
} catch (\Throwable $e) {
    die($e->getMessage());
}
?>
</body>
</html>
