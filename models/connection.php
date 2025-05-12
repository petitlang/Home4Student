<?php

$host = 'localhost:3306';
$username = 'root';
$password = 'root'; 
$dbname = 'Home4Student';
$sqlFilePath = __DIR__ . '/createDatabase.sql';

// 新增：创建 PDO 连接
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

?>