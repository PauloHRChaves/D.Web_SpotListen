<?php
namespace src\Services;

use src\Services\SpotifyService;

use src\Infrastructure\HttpClient;

use src\Infrastructure\Database\Search;
use src\Infrastructure\Database\Insert;

use src\Exceptions\ApiException;

use DateTime;

class AuthService {
    public function __construct() {
        $this->search = new Search();
        $this->insert = new Insert();
    }

    // Cadastro
    public function registerUser(string $registerEmail, string $username, string $registerPassword): bool {
        if ($this->search->searchEmailOrUsername($registerEmail, $username)) {
            throw new ApiException("Email ou username já em uso.", 409); 
        }
        
        $hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);
        
        http_response_code(201);
        return $this->insert->saveRegister($registerEmail, $username, $hashedPassword);
    }

    // Login
    public function loginUser(string $email, string $password): array {
        $user = $this->search->findByEmail($email);

        if (!$user) {
            throw new ApiException("E-mail ou senha incorretos.", 401); 
        }
        
        if (!password_verify($password, $user['USER_PASSWORD'])) {
            throw new ApiException("E-mail ou senha incorretos.", 401);
        }
        
        http_response_code(200);
        return [
            'id' => $user['ID'], 
            'username' => $user['USERNAME'],
        ];
    }

    // Logged
    public function logged(){
        if (isset($_SESSION['user_id'])) {
            try {
                $userId = $_SESSION['user_id'];
                
                $user = $this->search->findById($userId);

                if (!$user) {
                    throw new ApiException("Usuário não encontrado.", 404);
                }

                $spotifyInfo = $this->search->getSpotifyProfileData($userId); 
                
                http_response_code(200);

                return [
                    'isLoggedIn' => true,
                    'user' => [
                        'id' => $userId,
                        'username' => $user['USERNAME'],
                        'spotify_info' => $spotifyInfo, 
                    ]
                ];

            } catch (ApiException $e) {
                unset($_SESSION['user_id']);
                throw $e; 
            }

        } else {
            throw new ApiException("Não autorizado.", 401); 
        }
    }

    // Redirect para o Login no Spotify
    public function spotifyLogin() {
        $client_id = $_ENV['SPOTIFY_CLIENT_ID'];
        $redirect_uri = $_ENV['SPOTIFY_REDIRECT_URI'];
        
        if (!isset($_SESSION['user_id'])) {
            throw new ApiException("Usuário não logado no site.", 401);
        }
        
        $nonce = bin2hex(random_bytes(16));
        $state_data = [
            'user_id' => $_SESSION['user_id'], 
            'nonce' => $nonce, 
            'session_id' => session_id()
        ];

        $state = base64_encode(json_encode($state_data));

        $_SESSION['spotify_auth_nonce'] = $nonce;

        $scopes = 'user-read-private playlist-read-private user-read-recently-played user-top-read ' .
              'playlist-read-collaborative playlist-modify-public playlist-modify-private user-library-modify user-library-read ' .
              'user-read-playback-state user-modify-playback-state user-read-currently-playing';
        
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $_ENV['SPOTIFY_CLIENT_ID'],
            'scope' => $scopes,
            'redirect_uri' => $_ENV['SPOTIFY_REDIRECT_URI'],
            'state' => $state,
        ]);

        $auth_url = 'https://accounts.spotify.com/authorize?' . $query; 
        header("Location: $auth_url");
        exit;
    }

    // Callback do Spotify
    public function processSpotifyCallback($code, $state) {
        $decodedState = json_decode(base64_decode($state), true);
        $receivedNonce = $decodedState['nonce'] ?? null;
        $authUserId = $decodedState['user_id'] ?? null; 
        $receivedSessionId = $decodedState['session_id'] ?? null;

        if ($receivedSessionId && session_id() !== $receivedSessionId) {
            session_abort(); 
            session_id($receivedSessionId);
            session_start();
        }

        $stateFromSession = $_SESSION['spotify_auth_nonce'] ?? null;
        if (!$state || $receivedNonce !== $stateFromSession || !$authUserId) {
            unset($_SESSION['spotify_auth_nonce']); 
            throw new ApiException("Sessão inválida, CSRF.", 403);
        }
        unset($_SESSION['spotify_auth_nonce']); 
        
        $spotifyService = new SpotifyService();
        $tokenResult = $spotifyService->_requestSpotifyToken(
        'authorization_code', 
        [
            'code' => $code,
            'redirect_uri' => $_ENV['SPOTIFY_REDIRECT_URI'],
        ]);
        
        $accessToken = $tokenResult['access_token'];
        $refreshToken = $tokenResult['refresh_token'] ?? null;
        $expiresIn = $tokenResult['expires_in'] ?? 3600;
        
        $spotifyUser = $spotifyService->getSpotifyUserProfile($accessToken); 
        $spotifyId = $spotifyUser['id'];
        
        if ($this->search->isSpotifyIdAlreadyLinked($spotifyId, $authUserId)) {
            throw new ApiException("Esta conta do Spotify já está vinculada a outro usuário no sistema.", 409);
        }
        
        $profileImg = empty($spotifyUser['images']) ? '' : $spotifyUser['images'][0]['url'];

        $this->insert->saveSpotifyInfo([
            'user_id' => $authUserId,
            'spotify_id' => $spotifyId,
            'spotify_username' => $spotifyUser['display_name'],
            'profile_img' => $profileImg,
        ]);
        
        $expiryDatetime = (new DateTime())->modify("+ $expiresIn seconds")->format('Y-m-d H:i:s');

        $this->insert->saveSpotifyCredentials([
            'user_id' => $authUserId,
            'refresh_token' => $refreshToken,
            'access_token' => $accessToken,
            'expiry_datetime' => $expiryDatetime,
        ]);
        
        return true; 
    }
}