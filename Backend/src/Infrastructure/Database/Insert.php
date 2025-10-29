<?php
namespace src\Infrastructure\Database;

use PDO;
use PDOException;

use Exception;

require_once ROOT_PATH . 'src/Config/Database.php'; 

class Insert {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = connectToDatabase(); 
    }

    // Salvar usuário na tabela USERS
    public function saveRegister(string $email, string $username, string $hashedPassword) {
        try {
            $sql_insert = "INSERT INTO USERS (EMAIL, USERNAME, USER_PASSWORD, IS_ACTIVE ) VALUES (?, ?, ?, true)";
            $stmt_insert = $this->pdo->prepare($sql_insert);
            
            return $stmt_insert->execute([$email, $username, $hashedPassword]);

        } catch (PDOException $e) {
            error_log("Erro de banco de dados ao salvar usuário: " . $e->getMessage());
            return false;
        }
    }

    // Salvar informações do usuário na tabela USER_INFO
    public function saveSpotifyInfo(array $data): void {
        try {
            $sql_insert = "INSERT INTO USER_INFO (USER_ID, SPFY_USER_ID, SPFY_USERNAME, PROFILE_IMG) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    SPFY_USER_ID = VALUES(SPFY_USER_ID), 
                    SPFY_USERNAME = VALUES(SPFY_USERNAME), 
                    PROFILE_IMG = VALUES(PROFILE_IMG)
            ";
            $stmt = $this->pdo->prepare($sql_insert);
            $stmt->execute([
                $data['user_id'], 
                $data['spotify_id'], 
                $data['spotify_username'], 
                $data['profile_img']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Falha ao salvar user_info do Spotify.", 500);
        }
    }

    // Salvar informações de Tokens do usuário na tabela USER_INFO
    public function saveSpotifyCredentials(array $data): void {
        try {
            $sql = "
                INSERT INTO SPOTIFY_CREDENTIALS (USER_ID, SPFY_REFRESH_TOKEN, SPFY_ACCESS_TOKEN, TOKEN_EXPIRY) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    SPFY_REFRESH_TOKEN = VALUES(SPFY_REFRESH_TOKEN), 
                    SPFY_ACCESS_TOKEN = VALUES(SPFY_ACCESS_TOKEN), 
                    TOKEN_EXPIRY = VALUES(TOKEN_EXPIRY)
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['user_id'], 
                $data['refresh_token'], 
                $data['access_token'], 
                $data['expiry_datetime']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Falha ao salvar credenciais do Spotify.", 500);
        }
    }
    public function clearSpotifyCredentials(int $userId): void {
        $sql = "
            UPDATE SPOTIFY_CREDENTIALS 
            SET 
                SPFY_ACCESS_TOKEN = NULL, 
                SPFY_REFRESH_TOKEN = NULL, 
                TOKEN_EXPIRY = NULL 
            WHERE 
                USER_ID = ?
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        
    }
}