<?php
namespace src\Infrastructure\Database;

use PDO;
use PDOException;

use Exception;

require_once ROOT_PATH . 'src/Config/Database.php'; 

class Delete {
    private ?PDO $pdo = null;

    private function getConnection(): PDO {
        if ($this->pdo === null) {
            $this->pdo = connectToDatabase(); 
        }
        return $this->pdo;
    }

    public function unlinkSpotifyConnection(int $userId): void {
        $pdo = $this->getConnection();
        $pdo->beginTransaction();

        try {
            // DELETAR CREDENCIAIS (Tokens)
            $sqlCredentials = "DELETE FROM SPOTIFY_CREDENTIALS WHERE USER_ID = ?";
            $stmtCredentials = $pdo->prepare($sqlCredentials);
            $stmtCredentials->execute([$userId]);

            // ATUALIZAR INFORMAÇÕES (Limpar campos de perfil)
            $sqlInfo = "UPDATE USER_INFO SET SPFY_USER_ID = NULL, SPFY_USERNAME = NULL, PROFILE_IMG = NULL WHERE USER_ID = ?";
            $stmtInfo = $pdo->prepare($sqlInfo);
            $stmtInfo->execute([$userId]);
            
            $pdo->commit();
            
        } catch (PDOException $e) {
                $pdo->rollBack();
                throw new ApiException("Erro no banco de dados ao desvincular Spotify: ", 500); 
            }
        }
}