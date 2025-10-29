<?php
namespace src\Controllers;

use src\Services\SpotifyService;
use src\Services\AuthService;

use src\Infrastructure\Database\Insert;
use src\Infrastructure\Database\Search;

use src\Exceptions\ApiException;

class AuthController {
    public function __construct() {
        $this->search = new Search();
        $this->insert = new Insert();
        $this->authService = new AuthService();
    }

    public function register(string $registerEmail, string $username, string $registerPassword): array {
        $this->authService->registerUser($registerEmail, $username, $registerPassword);
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
                $userId = $_SESSION['user_id'];
                
                $userData = $this->authService->loggedUser($userId);
                $spotifyInfo = $this->search->getSpotifyProfileData($userId); 
                
                http_response_code(200);
                return [
                    'isLoggedIn' => true,
                    'user' => [
                        'id' => $userId,
                        'username' => $userData['username'],
                        'spotify_info' => $spotifyInfo, 
                    ]
                ];

            } catch (ApiException $e) {
                unset($_SESSION['user_id']);
                throw new ApiException("Sessão inválida.", 401);
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
        $this->authService->spotifyLogin(); 
    }

    public function spotifyCallback() {
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