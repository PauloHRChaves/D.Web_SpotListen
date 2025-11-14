<?php
namespace src\Controllers;

use src\Services\SpotifyService;
use src\Services\AuthService;

use src\Exceptions\ApiException;

class AuthController {
    public function __construct() {
        $this->authService = new AuthService();
    }

    //
    public function register(string $registerEmail, string $username, string $registerPassword): void {
        $this->authService->registerUser($registerEmail, $username, $registerPassword);
    }

    //
    public function login(string $email, string $password): array {
        $result = $this->authService->loginUser($email, $password);
        
        $_SESSION['user_id'] = $result['id'];
        $sessionId = session_id();

        return [
            "user" => ["username" => $result['username']],
            "sessionId" => $sessionId
        ];
    }
    
    //
    public function logged(): array {
        return $this->authService->logged();
    }

    //
    public function logout(): array {
        $_SESSION = array();
        session_destroy();

        http_response_code(200);
        return ["message" => "Logout realizado com sucesso."];
    }

    //
    public function spotifyLoginRedirect(): void {
        $this->authService->spotifyLogin(); 
    }

    //
    public function spotifyCallback(): void {
        $code = $_GET['code'] ?? null;
        $state = $_GET['state'] ?? null;
        $error = $_GET['error'] ?? null;

        if ($error) {
            header('Location: http://127.0.0.1:8132/templates/profile.php?error=denied');
            exit;
        }

        try {
            $this->authService->processSpotifyCallback($code, $state);

            header('Location: http://127.0.0.1:8132/templates/profile.php');
            exit;
        } catch (ApiException $e) {
            header('Location: http://127.0.0.1:8132/templates/profile.php?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}