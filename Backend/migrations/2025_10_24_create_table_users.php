<?php
require_once ROOT_PATH . 'src/Config/DatabaseConnect.php'; 

$pdo = connectToDatabase();

try {
    // Criação da tabela USERS
    $sql = "
    CREATE TABLE IF NOT EXISTS USERS (
        ID INT(11) PRIMARY KEY AUTO_INCREMENT,
        EMAIL VARCHAR(255) NOT NULL UNIQUE,
        USERNAME VARCHAR(25) NOT NULL UNIQUE,
        USER_PASSWORD VARCHAR(255) NOT NULL,
        DATA_CREATE DATETIME DEFAULT CURRENT_TIMESTAMP
        IS_ACTIVE BOOLEAN NOT NULL
    );
    ";

    $pdo->exec($sql);

} catch (PDOException $e) {
    die("Erro ao criar a tabela: " . $e->getMessage());
}
?>