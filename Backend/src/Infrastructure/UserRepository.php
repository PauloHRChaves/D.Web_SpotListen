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

    public function saveSpotifyInfo(array $data): void {
        try {
            $sql = "
                INSERT INTO USER_INFO (USER_ID, SPFY_USER_ID, SPFY_USERNAME, PROFILE_IMG) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    SPFY_USER_ID = VALUES(SPFY_USER_ID), 
                    SPFY_USERNAME = VALUES(SPFY_USERNAME), 
                    PROFILE_IMG = VALUES(PROFILE_IMG)
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['user_id'], 
                $data['spotify_id'], 
                $data['spotify_username'], 
                $data['profile_img']
            ]);
        } catch (PDOException $e) {
            error_log("Database error in UserRepository::saveSpotifyInfo: " . $e->getMessage());
            throw new Exception("Falha ao salvar info do Spotify.", 500);
        }
    }
    
    public function saveSpotifyCredentials(array $data): void {
        
        $hasRefreshToken = isset($data['refresh_token']) && !empty($data['refresh_token']);
        
        $updateClauses = "
            SPFY_ACCESS_TOKEN = VALUES(SPFY_ACCESS_TOKEN), 
            TOKEN_EXPIRY = VALUES(TOKEN_EXPIRY)
        ";
        
        if ($hasRefreshToken) {
            $updateClauses = "SPFY_REFRESH_TOKEN = VALUES(SPFY_REFRESH_TOKEN),\n" . $updateClauses;
        }

        try {
            $sql = "
                INSERT INTO SPOTIFY_CREDENTIALS (USER_ID, SPFY_REFRESH_TOKEN, SPFY_ACCESS_TOKEN, TOKEN_EXPIRY) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    $updateClauses
            ";
            
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->execute([
                $data['user_id'], 
                $data['refresh_token'],
                $data['access_token'], 
                $data['expiry_datetime']
            ]);
            
        } catch (\PDOException $e) {
            error_log("Database error in UserRepository::saveSpotifyCredentials: " . $e->getMessage());
            throw new \Exception("Falha ao salvar credenciais do Spotify.", 500); 
        }
    }
    public function isSpotifyIdAlreadyLinked(string $spotifyId, int $currentUserId): bool {
        $sql = "
            SELECT COUNT(*) 
            FROM USER_INFO 
            WHERE SPFY_USER_ID = ? AND USER_ID != ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$spotifyId, $currentUserId]);
        
        return $stmt->fetchColumn() > 0;
    }

    public function getSpotifyProfileData(int $userId): ?array {
        $sql = "SELECT SPFY_USERNAME, PROFILE_IMG FROM USER_INFO WHERE USER_ID = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        $spotifyInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $spotifyInfo ?: null; 
    }

    public function getSpotifyCredentials(int $userId): ?array { 
        $sql = "SELECT SPFY_ACCESS_TOKEN, SPFY_REFRESH_TOKEN, TOKEN_EXPIRY 
                FROM SPOTIFY_CREDENTIALS 
                WHERE USER_ID = ?";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        
        $credentials = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($credentials) {
            $data = [
                'access_token'    => $credentials['SPFY_ACCESS_TOKEN'],
                'refresh_token'   => $credentials['SPFY_REFRESH_TOKEN'],
                'expiry_datetime' => $credentials['TOKEN_EXPIRY'],
            ];

            $expiryTimestamp = strtotime($data['expiry_datetime']);
            $data['expiry_timestamp'] = $expiryTimestamp;
            $data['is_valid'] = ($expiryTimestamp > time());
            
            return $data;
        }

        return null;
    }
    
}