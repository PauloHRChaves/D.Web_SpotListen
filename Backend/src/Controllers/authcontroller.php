<?php
namespace src\Controllers;

use src\Services\SpotifyService;
use src\Services\AuthService;

use src\Exceptions\ApiException;

class AuthController {

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function register(string $registerEmail, string $username, string $registerPassword): array {
        $this->authService->registerUser($registerEmail, $username, $registerPassword);
        
        http_response_code(201);
        return ['status' => 'success', 'message' => 'Usuário registrado com sucesso.'];
    }

    public function login(string $email, string $password): array {
        $result = $this->authService->loginUser($email, $password); 

        $_SESSION['user_id'] = $result['id'];
        $sessionId = session_id();

        http_response_code(200);
        return [
            "status" => "success",
            "message" => "Login realizado com sucesso!",
            "user" => ["username" => $result['username']],
            "sessionId" => $sessionId
        ];
    }
    
    public function logged(): array {
        if (isset($_SESSION['user_id'])) {
            try {
                $userData = $this->authService->getUserDataById($_SESSION['user_id']);
                
                http_response_code(200);
                return [
                    'isLoggedIn' => true,
                    'user' => [
                        'id' => $_SESSION['user_id'],
                        'username' => $userData['username'],
                    ]
                ];

            } catch (ApiException $e) {
                unset($_SESSION['user_id']);
                throw new ApiException("Sessão inválida, por favor, faça login novamente.", 401);
            }

        } else {
            throw new ApiException("Não autorizado.", 401); 
        }
    }

    public function logout(): array {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        http_response_code(200);
        return ["message" => "Logout realizado com sucesso."];
    }

    public function spotifyLoginRedirect() {
        $sptService= new SpotifyService();
        $this->sptService->spotifyLogin(); 
    }
}