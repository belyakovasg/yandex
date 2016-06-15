<?php
$logFile = 'not_in_db';
$host = 'localhost';
$db = 'yandex';
$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, 'root', '');
$items = $pdo->query('SELECT distinct id FROM merchandise')->fetchAll(PDO::FETCH_COLUMN);
$clients = $pdo->query('SELECT distinct id FROM clients')->fetchAll(PDO::FETCH_COLUMN);
$file = fopen('orders.csv', 'r');
if (is_file($logFile)) exec('rm '.$logFile);
while($str = fgetcsv($file, 0, ';')) {
    if (in_array($str[0], $clients) === true && in_array($str[1], $items) === true) {
        $stmt = $pdo->prepare("INSERT INTO orders (client_id, item_id, comment, order_date, status)  VALUES  (:client_id, :item_id, :comment, :order_date, :status)");
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':order_date', $time);
        $stmt->bindParam(':status', $status);

        $client_id = htmlentities($str[0]);
        $item_id = htmlentities($str[1]);
        $time = date('Y-m-d h:m:s');
        $comment = 'from csv';
        $status = htmlentities($str[2]);
        $stmt->execute();
    } else {
        $str[] = PHP_EOL;
        file_put_contents($logFile, implode(';',$str), FILE_APPEND);
    }
}