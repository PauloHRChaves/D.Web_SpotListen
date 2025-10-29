<?php
namespace src\Infrastructure\Database;

use PDO;
use PDOException;

use src\Exceptions\ApiException;

require_once ROOT_PATH . 'src/Config/Database.php'; 

class Search {
    private ?PDO $pdo = null;

    private function getConnection(): PDO {
        if ($this->pdo === null) {
            $this->pdo = connectToDatabase(); 
        }
        return $this->pdo;
    }

    // REGISTER
    // Busca se o Email ou Username ja existe
    public function searchEmailOrUsername(string $email, string $username) {
        $pdo = $this->getConnection();

        $sql = "SELECT EMAIL FROM USERS WHERE EMAIL = ? OR USERNAME = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $username]);
        
        return (bool)$stmt->fetch(); 
    }

    // LOGIN
    // Procura o ID, USERNAME, USER_PASSWORD de acordo com o EMAIL
    public function findByEmail(string $email): ?array {
        $pdo = $this->getConnection();
        try {
            $sql = "SELECT ID, USERNAME, USER_PASSWORD FROM USERS WHERE EMAIL = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ?: null; 
            
        } catch (PDOException $e) {
            throw new Exception("Falha de infraestrutura ao buscar o usuário.", 500);
        }
    }

    // LOGGED-IN
    // Verifica se o usuário está logado
    public function findById(int $userId): ?array {
        $pdo = $this->getConnection();
        try {
            $sql = "SELECT ID, USERNAME FROM USERS WHERE ID = ?"; 
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ?: null; 

        } catch (PDOException $e) {
            throw new ApiException("Falha de infraestrutura ao buscar o usuário por ID.", 500);
        }
    }

    // Chamada no CallBack -> conta do Spotify só pode ser vinculada a uma única conta do banco de dados
    public function isSpotifyIdAlreadyLinked(string $spotifyId, int $currentUserId): bool {
        $pdo = $this->getConnection();

        $sql = "SELECT COUNT(*) FROM USER_INFO WHERE SPFY_USER_ID = ? AND USER_ID != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$spotifyId, $currentUserId]);
        
        $accountLinked = $stmt->fetchColumn() > 0;

        return $accountLinked ?: null;
    }
    
    //
    public function getSpotifyProfileData(int $userId): ?array {
        $pdo = $this->getConnection();

        $sql = "SELECT SPFY_USERNAME, PROFILE_IMG FROM USER_INFO WHERE USER_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);

        $spotifyInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $spotifyInfo ?: null; 
    }

    // 
    public function getSpotifyCredentials(int $userId): ?array { 
        $pdo = $this->getConnection();

        $sql = "SELECT SPFY_ACCESS_TOKEN, SPFY_REFRESH_TOKEN, TOKEN_EXPIRY 
                FROM SPOTIFY_CREDENTIALS WHERE USER_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]); 
        
        $credentials = $stmt->fetch(PDO::FETCH_ASSOC);

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