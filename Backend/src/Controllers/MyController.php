<?php
namespace src\Controllers;

use src\Infrastructure\Database\Search;
use src\Services\SpotifyService;

use src\Exceptions\ApiException;

class MyController {
    private Search $search;

    public function __construct() {
        $this->search = new Search();
    }

    // teste
    // public function getSpotifyInfo() {
    
    //     try {
    //         $appUserId = $_GET['app_user_id'] ?? null; 
    //         if (empty($appUserId) || !is_numeric($appUserId)) {
    //             http_response_code(400);
    //             echo json_encode(['error' => 'ID de usuário (app_user_id) inválido.']); 
    //             die();
    //         }

    //         $userData = $this->search->getPublicUserData($appUserId); 
            
    //         if (!$userData) {
    //             http_response_code(404);
    //             echo json_encode(['error' => "Usuário com ID {$appUserId} não encontrado ou não vinculado."]);
    //             die();
    //         }

    //         http_response_code(200);
    //         echo json_encode($userData);
            
    //     } catch (ApiException $e) { 
    //         http_response_code(500);
    //         error_log("ERRO FATAL EM getSpotifyInfo: ID {$appUserId} | Mensagem: " . $e->getMessage() . " | Linha: " . $e->getLine()); 
    //         echo json_encode(['error' => 'Erro interno do servidor ao carregar dados. (500)']);
    //     }
    // }
}