<?php
$pdo = connectToDatabase();

try {
    // Criação da tabela SPOTIFY_CREDENTIALS
    $sql = "
    CREATE TABLE IF NOT EXISTS SPOTIFY_CREDENTIALS (
        USER_ID INT PRIMARY KEY,
        SPFY_REFRESH_TOKEN TEXT NOT NULL,
        SPFY_ACCESS_TOKEN TEXT,
        TOKEN_EXPIRY DATETIME,
        FOREIGN KEY (USER_ID) REFERENCES USERS(ID)
    );
    ";

    $pdo->exec($sql);

} catch (PDOException $e) {
    die("Erro ao criar a tabela: " . $e->getMessage());
}