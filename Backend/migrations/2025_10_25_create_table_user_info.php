<?php
$pdo = connectToDatabase();

try {
    // Criação da tabela USER_INFO
    $sql = "
    CREATE TABLE IF NOT EXISTS USER_INFO (
        USER_ID INT PRIMARY KEY,
        SPFY_USER_ID VARCHAR(100) NULL,      
        SPFY_USERNAME VARCHAR(100) NULL,      
        PROFILE_IMG TEXT NULL,                
        FOREIGN KEY (USER_ID) REFERENCES USERS(ID)
    );
    ";

    $pdo->exec($sql);

} catch (PDOException $e) {
    die("Erro ao criar a tabela: " . $e->getMessage());
}