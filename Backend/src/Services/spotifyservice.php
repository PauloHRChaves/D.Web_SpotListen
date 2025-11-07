<?php
namespace src\Services;

use src\Infrastructure\HttpClient;
use src\Exceptions\ApiException;

use src\Infrastructure\Database\Search;
use src\Infrastructure\Database\Insert;

use Datetime;

class SpotifyService extends HttpClient {
    public function __construct() {
        $this->search = new Search();
        $this->insert = new Insert();
    }
    public function _requestSpotifyToken(string $grantType, array $params = []): array {
        $client_id = $_ENV['SPOTIFY_CLIENT_ID'];
        $client_secret = $_ENV['SPOTIFY_CLIENT_SECRET'];
        $url = 'https://accounts.spotify.com/api/token';
        $auth = base64_encode("$client_id:$client_secret");
        
        $headers = [
            "Authorization: Basic $auth",
            "Content-Type: application/x-www-form-urlencoded",
        ];
        
        $body = http_build_query(array_merge(['grant_type' => $grantType], $params));

        $result = $this->_executarRequest($url, $headers, $body, 'POST');

        if (!isset($result['access_token'])) {
             $errorMessage = $result['error_description'] ?? 'Erro desconhecido na API de Token.';
             throw new ApiException("Falha ao obter token do Spotify: " . $errorMessage, 500);
        }
        return $result;
    }

    public function getAccessToken(): array {
        if (isset($_SESSION['spotify_token']) && time() < ($_SESSION['expires_at'] ?? 0)) {
            return [
                'access_token' => $_SESSION['spotify_token'],
                'expires_in'   => $_SESSION['spotify_expires_in'],
            ];
        }
        try {
            $result = $this->_requestSpotifyToken('client_credentials');
        } catch (ApiException $e) {
            throw $e;
        }
        $access_token = $result['access_token'] ?? null;
        $expires_in = $result['expires_in'] ?? 0;

        $_SESSION['spotify_token'] = $access_token;
        $_SESSION['expires_at'] = time() + $expires_in - 120; 
        $_SESSION['spotify_expires_in'] = $expires_in;

        return [
            'access_token' => $access_token,
            'expires_in' => $expires_in,
        ];
    }

    public function getUserToken(int $userId): string {
        $credentials = $this->search->getSpotifyCredentials($userId);

        if (!$credentials || empty($credentials['refresh_token'])) {
            throw new ApiException("Usuário não tem credenciais válidas do Spotify.", 401);
        }
        
        if ($credentials['is_valid']) {
            return $credentials['access_token'];
        }

        try {
            $tokenResult = $this->_requestSpotifyToken('refresh_token', [
                'refresh_token' => $credentials['refresh_token'],
            ]);
            
        } catch (ApiException $e) {
            $this->insert->clearSpotifyCredentials($userId);
            throw new ApiException("Sessão Spotify expirada ou inválida. Por favor, vincule novamente.", 401);
        }
        
        $newAccessToken = $tokenResult['access_token'];
        $expiresIn = $tokenResult['expires_in'] ?? 3600;
        $newRefreshToken = $tokenResult['refresh_token'] ?? $credentials['refresh_token'];

        $expiryDatetime = (new DateTime())->modify("+ $expiresIn seconds")->format('Y-m-d H:i:s');
        
        $this->insert->saveSpotifyCredentials([
            'user_id' => $userId,
            'refresh_token' => $newRefreshToken,
            'access_token' => $newAccessToken,
            'expiry_datetime' => $expiryDatetime,
        ]);
        
        return $newAccessToken;
    }

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
            'Pop'           => ['rock', 'hip hop', 'eletronic', 'reggae', 'brazil mpb', 'classic', 'indie',],
            'Rock'          => ['pop', 'hip hop', 'eletronic', 'reggae', 'brazil mpb', 'classic', 'indie',],
            'Hip Hop'       => ['pop', 'rock', 'electronic', 'reggae', 'brazil mpb', 'classic', 'indie'],
            'Electronic'    => ['pop', 'rock', 'hip hop', 'reggae', 'brazil mpb', 'classic', 'indie'],
            'Reggae'        => ['pop', 'rock', 'hip hop', 'electronic', 'brazil mpb', 'classic', 'indie'],
            'Classic'       => ['pop', 'rock', 'hip hop', 'eletronic', 'brazil mpb', 'mpb', 'indie'],
            'Indie'         => ['pop', 'rock', 'hip hop', 'eletronic', 'brazil mpb', 'mpb'],
        ];

        $max_fetch = 1000; // Limite de quantos artistas buscar no total
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

    // Chamado no LastfmController - noAuth
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

    // Chamado no LastfmController - noAuth
    public function searchTrack(array $trackNames, array $artistNames): array {
        $token = $this->getAccessToken()['access_token'];
        $headers = ["Authorization: Bearer $token"];
        $requests = [];

        $result[]=[];

        foreach ($trackNames as $index => $name) {
            $artist = $artistNames[$index];

            $url = "https://api.spotify.com/v1/search?q=track:" .urlencode($name). "%20artist:" .urlencode($artist). "&type=track&limit=1";
            $requests[] = [
                'url'     => $url,
                'headers' => $headers,
                'body'    => '',
                'method'  => 'GET'
            ];
        }
        $responses = $this->_executarMultiRequest($requests);

        $results = [];
        foreach ($responses as $response) {
            if (!empty($response['tracks']['items'][0])) {
                $track = $response['tracks']['items'][0];

                $results[] = [
                    'url' => $track['external_urls']['spotify'] ?? null,
                    'popularity' => $track['popularity'] ?? null,
                    'image' => $track['album']['images'][0]['url'] ?? null,
                ];
            }
        }

        return $results;
    }


    public function searchArtistByNameSingle(string $artistName): array {
        $token = $this->getAccessToken()['access_token'];
        $headers = ["Authorization: Bearer $token"];
        
        $encodedArtistName = urlencode($artistName);
        
        $url = "https://api.spotify.com/v1/search?q={$encodedArtistName}&type=artist&limit=10";

        $request = [
            'url'     => $url,
            'headers' => $headers,
            'body'    => '',
            'method'  => 'GET'
        ];

        $response = $this->_executarMultiRequest([$request]); 
        
        $rawArtistsList = $response[0]['artists']['items'] ?? [];
        
        $filteredList = [];
        foreach ($rawArtistsList as $artist) {
            
            $filteredList[] = [
                'id'              => $artist['id'],
                'name'            => $artist['name'],
                'popularity'      => $artist['popularity'] ?? 0,
                'genres'          => $artist['genres'],
                'href'            => $artist['href'],
                'followers_total' => $artist['followers']['total'] ?? 0,
                'profile_url'     => $artist['external_urls']['spotify'] ?? '#',
                'image_url'       => isset($artist['images'][0]['url']) ? $artist['images'][0]['url'] : null,
            ];
        }

        return ['items' => $filteredList];
    }

    // Chamada no CallBack do AuthService
    public function getSpotifyUserProfile(string $accessToken): array {
        $url = 'https://api.spotify.com/v1/me'; 
        $headers = ["Authorization: Bearer $accessToken"];
        $result = $this->_executarRequest($url, $headers,"", 'GET'); 

        if (!isset($result['id'])) {
            throw new ApiException("Falha ao obter perfil do Spotify.", 500);
        }
        return [
            'id' => $result['id'],
            'display_name' => $result['display_name'] ?? $result['id'],
            'images' => $result['images'] ?? []
        ];
    }

    public function getAuthenticatedUserId(): int {
        if (!isset($_SESSION['user_id'])) {
            return 0;
        }
        return (int) $_SESSION['user_id'];
    }

    public function getRecentlyPlayed(int $userId): array {
        $accessToken = $this->getUserToken($userId); 

        $url = "https://api.spotify.com/v1/me/player/recently-played?limit=20";
        
        $headers = [
            "Authorization: Bearer $accessToken",
        ];
        
        $response = $this->_executarRequest($url, $headers, '', 'GET');

        return $response; 
    }

    public function getMyPlaylists(int $userId): array {
        $accessToken = $this->getUserToken($userId); 

        $url = "https://api.spotify.com/v1/me/playlists?limit=10";

        $headers = [
            "Authorization: Bearer $accessToken",
        ];

        $response = $this->_executarRequest($url, $headers, '', 'GET');

        return $response;
    }

    public function getCurrentTrack(int $userId): array {
        $accessToken = $this->getUserToken($userId);
        $url = "https://api.spotify.com/v1/me/player/currently-playing";

        $headers = [
            "Authorization: Bearer $accessToken",
        ];

        $response = $this->_executarRequest($url, $headers, '', 'GET');

        if (empty($response)) {
            throw new ApiException("Nenhuma faixa sendo reproduzida.", 404);
        }

        return $response;
    }

    public function getUserTopArtists(int $userId): array {
        $accessToken = $this->getUserToken($userId); 
        
        $max_fetch = 500;
        
        $periods = ['long_term', 'medium_term', 'short_term']; 

        $allGenres = [];
        
        foreach ($periods as $term) {
            $currentUrl = "https://api.spotify.com/v1/me/top/artists?limit=50&time_range={$term}";
            $totalFetched = 0;
            
            while ($currentUrl && $totalFetched < $max_fetch) {
                $headers = ["Authorization: Bearer $accessToken"];
                $requestData = $this->_executarRequest($currentUrl, $headers); 
                
                if (!isset($requestData['items'])) { break; }
                
                $artistsFromPage = $requestData['items'];
                
                foreach ($artistsFromPage as $artist) {
                    $genres = $artist['genres'] ?? [];
                    $allGenres = array_merge($allGenres, $genres);
                }

                $totalFetched += count($artistsFromPage);
                $currentUrl = $requestData['next'] ?? null;
            }
        }
        
        // Chama o método para Contar e Ordenar (dashboardGenres)
        return $this->dashboardGenres($allGenres);
        // return $allGenres;
    }

    public function dashboardGenres(array $genres): array {
        $genreCounts = array_count_values($genres);

        $genreCounts = array_filter($genreCounts, function ($count) {
            return $count > 2;
        });

        arsort($genreCounts);
        return $genreCounts;
    }

}