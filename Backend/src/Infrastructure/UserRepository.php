<?php
namespace src\Infrastructure;

use PDO;
use PDOException;

use Exception;

require_once ROOT_PATH . 'src/Config/Database.php'; 

class UserRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = connectToDatabase(); 
    }

    public function existsByEmailOrUsername(string $email, string $username) {
        $sql_check = "SELECT EMAIL FROM USERS WHERE EMAIL = ? OR USERNAME = ?";
        $stmt_check = $this->pdo->prepare($sql_check);
        $stmt_check->execute([$email, $username]);
        
        return (bool)$stmt_check->fetch(); 
    }

    public function save(string $email, string $username, string $hashedPassword) {
        try {
            $sql_insert = "INSERT INTO USERS (EMAIL, USERNAME, USER_PASSWORD, IS_ACTIVE ) VALUES (?, ?, ?, true)";
            $stmt_insert = $this->pdo->prepare($sql_insert);
            
            return $stmt_insert->execute([$email, $username, $hashedPassword]);

        } catch (PDOException $e) {
            error_log("Erro de banco de dados ao salvar usuário: " . $e->getMessage());
            return false;
        }

    }   

    public function findByEmail(string $email): ?array {
        try {
            $sql = "SELECT ID, USERNAME, USER_PASSWORD FROM USERS WHERE EMAIL = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null; 
            
        } catch (PDOException $e) {
            error_log("Database error in findByEmail: " . $e->getMessage());
            
            throw new Exception("Falha de infraestrutura ao buscar o usuário.", 500);
        }
    }

    public function findById(int $userId): ?array {
        try {
            $sql = "SELECT ID, USERNAME FROM USERS WHERE ID = ?"; 
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ?: null; 

        } catch (PDOException $e) {
            error_log("Database error in UserRepository::findById: " . $e->getMessage());
            throw new Exception("Falha de infraestrutura ao buscar o usuário por ID.", 500);
        }
    }
}