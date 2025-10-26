<?php
namespace src\Models;

require_once ROOT_PATH . 'src/config/database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = connectToDatabase();
    }

    public function createuser(string $email, string $username, string $password) {
        try {
            $sql_check = "SELECT EMAIL, USERNAME FROM USERS WHERE EMAIL = ? OR USERNAME = ?";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->execute([$email, $username]);
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return "email_or_username_exists";
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO USERS (EMAIL, USERNAME, USER_PASSWORD) VALUES (?, ?, ?)";
            $stmt_insert = $this->pdo->prepare($sql_insert);
            
            return $stmt_insert->execute([$email, $username, $hashed_password]);

        } catch (PDOException $e) {
            error_log("Erro de banco de dados: " . $e->getMessage());
            return false;
        }
    }
}