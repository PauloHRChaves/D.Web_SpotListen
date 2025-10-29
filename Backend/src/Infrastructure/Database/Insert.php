<?php
namespace src\Infrastructure\Database;

use PDO;
use PDOException;

use Exception;

require_once ROOT_PATH . 'src/Config/Database.php'; 

class Insert {
    private ?PDO $pdo = null;

    private function getConnection(): PDO {
        if ($this->pdo === null) {
            $this->pdo = connectToDatabase(); 
        }
        return $this->pdo;
    }

    // Salvar usuário na tabela USERS
    public function saveRegister(string $email, string $username, string $hashedPassword): bool {
        $pdo = $this->getConnection();
        try {
            $sql = "INSERT INTO USERS (EMAIL, USERNAME, USER_PASSWORD, IS_ACTIVE ) VALUES (?, ?, ?, true)";
            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute([$email, $username, $hashedPassword]);

        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao salvar usuário em USERS. ", 500);
        }
    }

    // Salvar informações do usuário na tabela USER_INFO
    public function saveSpotifyInfo(array $data): void {
        $pdo = $this->getConnection();
        try {
            $sql = "INSERT INTO USER_INFO (USER_ID, SPFY_USER_ID, SPFY_USERNAME, PROFILE_IMG) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    SPFY_USER_ID = VALUES(SPFY_USER_ID), 
                    SPFY_USERNAME = VALUES(SPFY_USERNAME), 
                    PROFILE_IMG = VALUES(PROFILE_IMG)
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['user_id'], 
                $data['spotify_id'], 
                $data['spotify_username'], 
                $data['profile_img']
            ]);

        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao salvar USER_INFO", 500);
        }
    }

    // Salvar informações de Tokens do usuário na tabela USER_INFO
    public function saveSpotifyCredentials(array $data): void {
        $pdo = $this->getConnection();
        try {
            $sql = "INSERT INTO SPOTIFY_CREDENTIALS (USER_ID, SPFY_REFRESH_TOKEN, SPFY_ACCESS_TOKEN, TOKEN_EXPIRY) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    SPFY_REFRESH_TOKEN = VALUES(SPFY_REFRESH_TOKEN), 
                    SPFY_ACCESS_TOKEN = VALUES(SPFY_ACCESS_TOKEN), 
                    TOKEN_EXPIRY = VALUES(TOKEN_EXPIRY)
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['user_id'], 
                $data['refresh_token'], 
                $data['access_token'], 
                $data['expiry_datetime']
            ]);

        } catch (PDOException $e) {
            throw new Exception("Erro no banco de dados ao salvar SPOTIFY_CREDENTIALS.", 500);
        }
    }
    
    //
    public function clearSpotifyCredentials(int $userId): void {
        $pdo = $this->getConnection();

        $sql = "UPDATE SPOTIFY_CREDENTIALS 
            SET 
                SPFY_ACCESS_TOKEN = NULL, 
                SPFY_REFRESH_TOKEN = NULL, 
                TOKEN_EXPIRY = NULL 
            WHERE 
                USER_ID = ?
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        
    }
}