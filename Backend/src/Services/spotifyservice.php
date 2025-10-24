<?php
namespace src\Services;

use src\Utils\ApiConfig;
use src\Exceptions\ApiException;

class SpotifyService extends ApiConfig {
    public function getAccessToken(): array {
        $client_id = $_ENV['SPOTIFY_CLIENT_ID'];
        $client_secret = $_ENV['SPOTIFY_CLIENT_SECRET'];

        if (isset($_SESSION['spotify_token']) && time() < ($_SESSION['expires_at'] ?? 0)) {
            return [
                'access_token' => $_SESSION['spotify_token'],
                'expires_in'   => $_SESSION['spotify_expires_in'],
            ];
        }

        $url = 'https://accounts.spotify.com/api/token';
        $headers = [
            'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
            'Content-Type: application/x-www-form-urlencoded',
        ];
        $body = 'grant_type=client_credentials';

        $result = $this->_executarRequest($url, $headers, $body, 'POST');

        $access_token = $result['access_token'] ?? null;
        $expires_in   = $result['expires_in'] ?? 0;

        if (!$access_token) {
            $errorMessage = $result['error']['message'] ?? 'TOKEN_API_ERROR';
            throw new ApiException("Spotify API Error: $errorMessage", 500);
        }

        $_SESSION['spotify_token']      = $access_token;
        $_SESSION['expires_at']         = time() + $expires_in - 120;
        $_SESSION['spotify_expires_in'] = $expires_in;

        return [
            'access_token' => $access_token,
            'expires_in'   => $expires_in,
        ];
    }

    public function spotifyLogin() {
        $client_id = $_ENV['SPOTIFY_CLIENT_ID'];
        $redirect_uri = $_ENV['SPOTIFY_REDIRECT_URI'];
        
        $scopes = 'user-read-private playlist-read-private user-read-recently-played user-top-read 
            playlist-read-private playlist-read-collaborative playlist-modify-public playlist-modify-private user-library-modify user-library-read 
            user-read-playback-state user-modify-playback-state user-read-currently-playing';
        
        $state = bin2hex(random_bytes(16));

        $_SESSION['spotify_auth_state'] = $state;

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $client_id,
            'scope' => $scopes,
            'redirect_uri' => $redirect_uri,
            'state' => $state,
        ]);

        $url = 'https://accounts.spotify.com/authorize?' . $query;

        header("Location: $auth_url");
        exit;
    }

    public function spotifyCallback() {
        $code = $_GET['code'] ?? null;
        $state = $_GET['state'] ?? null;

        if ($state === null || $state !== ($_SESSION['spotify_auth_state'] ?? null)) {
            throw new ApiException("State mismatch: Possível ataque CSRF.", 403);
        }
        unset($_SESSION['spotify_auth_state']);

        if (!$code) {
            throw new ApiException("Autorização negada pelo usuário.", 401);
        }

        $client_id = $_ENV['SPOTIFY_CLIENT_ID'];
        $client_secret = $_ENV['SPOTIFY_CLIENT_SECRET'];
        $redirect_uri = $_ENV['SPOTIFY_REDIRECT_URI'];

        $url = 'https://accounts.spotify.com/api/token';
        $auth = base64_encode("$client_id:$client_secret");

        $headers = [
            "Authorization: Basic $auth",
            "Content-Type: application/x-www-form-urlencoded",
        ];
        $body = http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
        ]);

        $result = $this->_executarRequest($url, $headers, $body, 'POST');

        if (isset($result['access_token'])) {
            $_SESSION['user_spotify_token'] = $result['access_token'];
            $_SESSION['user_refresh_token'] = $result['refresh_token'] ?? null;

            header('Location: /');
            exit;
        }

        // Lidar com erro de token
        $errorMessage = $result['error_description'] ?? 'TOKEN_USER_ERROR';
        throw new ApiException("Erro ao obter token do usuário: $errorMessage", 500);
    }

    // public function searchById(string $artistId) {
    //     $token = $this-> getAccessToken();
    //     $accessToken = $token['access_token'];

    //     $safeQuery = urlencode($artistId);
    //     $url = "https://api.spotify.com/v1/artists/{$safeQuery}";
        
    //     $headers = [
    //         'Authorization' => 'Bearer ' . $accessToken,
    //     ];

    //     return $this->_executarRequest($url, $headers);
    // }

    private function buildPaginationResponse(array $filteredItems, array $rawPaginationData, int $limit, int $offset): array {
        return [
            'items'    => $filteredItems,
            'limit'    => $rawPaginationData['limit'] ?? $limit,
            'offset'   => $rawPaginationData['offset'] ?? $offset,
            'total'    => $rawPaginationData['total'] ?? 0,

            'has_next' => $rawPaginationData['next'] !== null, 
            'has_previous' => $rawPaginationData['previous'] !== null,
        ];
    }
    public function searchTopGenres(string $genre, int $limit, int $offset): array {
        $token = $this->getAccessToken();
        $accessToken = $token['access_token'];

        // Limpa gêneros indesejados
        $exclusion_rules = [
            'Pop'           => ['rock', 'hip hop', 'eletronic', 'reggae', 'mpb', 'classic', 'indie'],
            'Rock'          => ['pop', 'hip hop', 'eletronic', 'reggae', 'mpb', 'classic', 'indie'],
            'Hip Hop'       => ['pop', 'rock', 'electronic', 'reggae', 'mpb', 'classic', 'indie'],
            'Electronic'    => ['pop', 'rock', 'hip hop', 'reggae', 'mpb', 'classic', 'indie'],
            'Reggae'        => ['pop', 'rock', 'hip hop', 'electronic', 'mpb', 'classic', 'indie'],
            'Classic'       => ['pop', 'rock', 'hip hop', 'eletronic', 'reggae', 'mpb', 'indie'],
            'Indie'         => ['pop', 'rock', 'hip hop', 'eletronic', 'reggae', 'mpb'],
        ];

        $max_fetch = 300; // Limite de quantos artistas buscar no total
        $spotify_limit = 50; // O máximo que o Spotify retorna por requisição
        $url = "https://api.spotify.com/v1/search?q=genre:{$genre}&type=artist&limit={$spotify_limit}&offset=0";

        $allArtists = [];
        $totalFetched = 0;

        while ($url && $totalFetched < $max_fetch) {
            $headers = ['Authorization' => 'Bearer ' . $accessToken];
            $data = $this->_executarRequest($url, $headers);
            
            if (!isset($data['artists']['items'])) break;
            
            $allArtists = array_merge($allArtists, $data['artists']['items']);
            $totalFetched += count($data['artists']['items']);
            $url = $data['artists']['next'] ?? null;
        }

        $filteredList = [];
        $genreLower = strtolower($genre);

        foreach ($allArtists as $artist) {
            $artistName = trim($artist['name'] ?? ''); 
            $artistNameLower = strtolower($artistName);

            // Filtragem Básica
            if (empty($artistName) || $artistNameLower === 'genre:none') {
                continue;
            }

            $popularity = (int)($artist['popularity'] ?? 0);
            
            if ($popularity < 15) { 
                continue;
            }

            if (isset($exclusion_rules[$genre])) {
                foreach ($exclusion_rules[$genre] as $term) {
                    // Se o nome do artista contiver o termo de exclusão, pula.
                    if (str_contains($artistNameLower, $term)) {
                        continue 2; // Pula para o próximo artista no loop $allArtists
                    }
                }
            }
            
            $filteredList[] = [
                'id'              => $artist['id'],
                'name'            => $artistName,
                'popularity'      => $popularity,
                'followers_total' => $artist['followers']['total'] ?? 0,
                'profile_url'     => $artist['external_urls']['spotify'] ?? '#',
                'image_url'       => isset($artist['images'][0]['url']) ? $artist['images'][0]['url'] : null,
            ];
        }

        // Paginação
        usort($filteredList, function($a, $b) {
            return $b['popularity'] <=> $a['popularity'];
        });
        $totalItems = count($filteredList);
        $paginatedItems = array_slice($filteredList, $offset, $limit);
        
        // Recria os metadados de paginação
        $finalPaginationData = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $totalItems,
            'next' => ($offset + $limit < $totalItems),
            'previous' => ($offset > 0),
        ];

        return $this->buildPaginationResponse($paginatedItems, $finalPaginationData, $limit, $offset);
    }

    // Chamado no LastfmController
    public function searchArtist(array $artistNames): array {
        $token = $this->getAccessToken()['access_token'];
        $headers = ["Authorization: Bearer $token"];
        $requests = [];

        foreach ($artistNames as $name) {
            $url = "https://api.spotify.com/v1/search?q=" . urlencode($name) . "&type=artist&limit=1";
            $requests[] = [
                'url'     => $url,
                'headers' => $headers,
                'body'    => '',
                'method'  => 'GET'
            ];
        }
        return $this->_executarMultiRequest($requests);
    }
}