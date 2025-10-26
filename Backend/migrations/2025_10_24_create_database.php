<?php
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    $dsnServer = "mysql:host={$host};charset=utf8mb4";
    $pdoServer = new PDO($dsnServer, $user, $password);
    $pdoServer->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdoServer->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}`;");
    
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro ao executar a migração: " . $e->getMessage() . "\n");
}