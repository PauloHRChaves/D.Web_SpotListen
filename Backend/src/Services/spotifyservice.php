<?php
namespace web\Services;

use web\Utils\ApiConfig;

use GuzzleHttp\Client;

class SpotifyService extends ApiConfig {
    public function getAccessToken(): array{
        $client_id = $_ENV['SPOTIFY_CLIENT_ID']; // .env SPOTIFY_CLIENT_ID='xxxxxxxxxxxxxxxxxxxxxxxxxx'
        $client_secret = $_ENV['SPOTIFY_CLIENT_SECRET']; // .env SPOTIFY_CLIENT_SECRET='xxxxxxxxxxxxxxxxxxxxxxxxxx'
        
        // Se já houver o access_token na sessão vai parar por aqui
        if (isset($_SESSION['spotify_token']) && isset($_SESSION['spotify_token']) && time() < $_SESSION['expires_at']) {
            return [
                'access_token' => $_SESSION['spotify_token'],
                'expires_in' => $_SESSION['spotify_expires_in'],
            ];
        }

        // Requisitando o token do Spotify
        $url = 'https://accounts.spotify.com/api/token';
        
        $headers = [
            'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
            'Content-Type: application/x-www-form-urlencoded', // Exigência do OAuth2.0
        ];
        $body = 'grant_type=client_credentials';

        $tokenconnect = new ApiConfig();
        $result = $tokenconnect->_executarCurl($url, $headers, $body);
        
        $data = $result['data'] ?? [];
        $http_code = $result['http_code'] ?? 0;
    
        $access_token = $data['access_token'] ?? null;
        $expires_in = $data['expires_in'] ?? 0;

        if ($http_code === 200 && $access_token) {
            $_SESSION['spotify_token'] = $access_token;
            $_SESSION['expires_at'] = time() + $expires_in - 120;
            $_SESSION['spotify_expires_in'] = $expires_in; 
            http_response_code(200);
            return $data; 

        }else {
            $errorMessage = $data['error']['message'] ?? 'TOKEN_API_ERROR';
            $logMessage = "Spotify API Error ({$http_code}): " . $errorMessage;

            throw new ApiException($logMessage, $http_code);
        }
    }

    // aprimorar estrutura
    public function searchById(string $artistId) {
        $token = $this-> getAccessToken();
        $accessToken = $token['access_token'];

        $safeQuery = urlencode($artistId);
        $url = "https://api.spotify.com/v1/artists/{$safeQuery}";
        
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        return $this->_executarRequest($url, $headers);
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

    // Chamada no LastfmController
    public function searchGenre(string $genre) {
        $token = $this->getAccessToken();
        $accessToken = $token['access_token'];
        
        $url = "https://api.spotify.com/v1/search?q=genre:{$genre}&type=artist";
        
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        return $this->_executarRequest($url, $headers);
    }

    // Chamada no LastfmController
    public function searchSptfyAsync(Client $client, string $query) {
        $token = $this->getAccessToken();
        $accessToken = $token['access_token'];
        
        $safeQuery = urlencode($query);
        $url = "https://api.spotify.com/v1/search?q={$safeQuery}&type=artist&limit=1";

        return $client->getAsync($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);
    }
}