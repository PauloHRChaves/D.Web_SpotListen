<?php
namespace web\Controllers;

// Services
use web\Services\SpotifyService;
use web\Services\LastfmService;
use web\Services\MbrainzService;
use web\Services\RedisCacheService;

use web\Utils\ApiConfig;

// Tratamento de erros
use web\Exceptions\ApiException;
use Exception;

// Guzzle metodos async
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class AuthController{
    public function __construct() {
        header('Content-Type: application/json');
    }

    // API: SPOTIFY
    // BUSCANDO O ACCESS_TOKEN DO SPOTIFY
    public function getAccessToken(): array{
        $client_id = $_ENV['SPOTIFY_CLIENT_ID']; // .env SPOTIFY_CLIENT_ID='xxxxxxxxxxxxxxxxxxxxxxxxxx'
        $client_secret = $_ENV['SPOTIFY_CLIENT_SECRET']; // .env SPOTIFY_CLIENT_SECRET='xxxxxxxxxxxxxxxxxxxxxxxxxx'
        
        // Se já houver o access_token vai parar por aqui
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
    
    // BUSCAR ARTISTA NO SPOTIFY
    public function searchSpotify(): array {
        $artistId = '4V8LLVI7PbaPR0K2TGSxFF';

        $token_info = $this->getAccessToken();
        $accessToken = $token_info['access_token'];

        $spotifyService = new SpotifyService();
        $results = $spotifyService->searchSptfy($accessToken, $artistId);
        
        return $results;
    }

    public function searchSpotifyGenre(): array {
        $genre = 'pop';

        $token_info = $this->getAccessToken();
        $accessToken = $token_info['access_token'];

        $spotifyService = new SpotifyService();
        $results = $spotifyService->searchSptfygenre($accessToken, $genre);
        
        return $results;
    }

/**************************************************************** */
    // API: MusicBrainz
    public function getMbrainz(string $mbidtag, string $artistName): array {
        $appName = 'SpotListen';
        $appVersion = '1.0';
        $appContact = $_ENV['EMAIL']; // .env EMAIL='example@gmail.com'

        $token_info = $this->getAccessToken();
        $accessToken = $token_info['access_token'];

        $spotifyId = null;

        // BUSCA MBID NO MUSICBRAINZ
        // Só chama o Service se o MBID existir
        if (!empty($mbidtag)) {
            $mbrainzService = new MbrainzService();
            $spotifyId = $mbrainzService->Mbrainzinfo($appName, $appVersion, $appContact, $mbidtag);
        }

        // FALLBACK: TENTA BUSCAR DIRETAMENTE NO SPOTIFY PELO NOME
        if (empty($spotifyId) && !empty($artistName)) {
            $spotifyService = new SpotifyService();
            $result = $spotifyService->searchSptfy($accessToken, $artistName, 'artist');
            $spotifyId = $result['artists']['items'][0]['id'] ?? null;
        }

        return  $spotifyId;
    }
    
/**************************************************************** */
    // API: Lastfm
    // Top Artistas Global
    // Dados no Carousel : Lastfm -> Spotify
    public function getLastfm(): array {
        $cacheService = new RedisCacheService();
        $cacheKey = 'carrossel:artists_data'; 
        $cacheTTL = 86400; // 24 horas
        
        // Tenta buscar no Redis. Se a chave existir e for válida
        if ($cachedData = $cacheService->get($cacheKey)) {
            return $cachedData;
        }

        $apikey = $_ENV['LASTFM_KEY'];
        $fmService = new LastfmService();
        $spotifyService = new SpotifyService();
        $client = new Client();

        $artists = $fmService->getTopArtists($apikey);
        $token_info = $this->getAccessToken();
        $accessToken = $token_info['access_token'];

        $promises = [];

        foreach ($artists as $artist) {
            $promises[$artist['name']] = $spotifyService->searchSptfyAsync(
                $client, $accessToken, $artist['name'], 'artist'
            );
        }

        $responses = \GuzzleHttp\Promise\Utils::settle($promises)->wait();
        $finalData = [];

        foreach ($artists as $artist) {
            $name = $artist['name'];
            $spotifyId = null;
            $images = null;
            $spotifyUrl = null;

            $resp = $responses[$name];

            if ($resp['state'] === 'fulfilled') {
                $data = json_decode($resp['value']->getBody(), true);
                $artistItem = $data['artists']['items'][0] ?? null;

                if ($artistItem) {
                    $spotifyId = $artistItem['id'] ?? null;
                    $spotifyUrl = $artistItem['external_urls']['spotify'] ?? null; 
                    $images = $artistItem['images'][0]['url'] ?? null;
                }
            }

            $finalData[] = [
                'name' => $name,
                'playcount' => $artist['playcount'],
                'listeners' => $artist['listeners'],
                'url' => $spotifyUrl,
                'spotify_id' => $spotifyId,
                'images' => $images
            ];
        }

        $cacheService->set($cacheKey, $finalData, $cacheTTL);

        return $finalData;
    }

    public function searchLastfmGenre(): array {
        $lastfm_apikey = $_ENV['LASTFM_KEY']; 
        $fmService = new LastfmService(); 
        
        $genres = $fmService->getTopGenres($lastfm_apikey);
        
        return array_slice($genres, 0, 30);
    }
    public function searchLastfmTrack(): array {
        $apikey = $_ENV['LASTFM_KEY'];
        $fmService = new LastfmService();
        
        $results = $fmService->getTopTracks($apikey, 5); 
        
        return $results;
    }
}
?>