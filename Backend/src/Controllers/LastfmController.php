<?php
namespace web\Controllers;

// Controllers
use web\Controllers\SpotifyController;

// Services
use web\Services\LastfmService;
use web\Services\SpotifyService;
use web\Services\RedisCacheService;

// Guzzle metodos async
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class LastfmController {
    public function __construct() {
        $this->lfmService= new LastfmService();
    }

    public function searchLastfmGenre(): array {
        $lastfm_apikey = $_ENV['LASTFM_KEY']; 
        
        $genres = $this->lfmService->getTopGenres($lastfm_apikey);

        $spotifyService = new SpotifyService();

        $list = [];

        foreach ($genres as $genre) {
            $list[$genre['name']] = $spotifyService->searchGenre($genre['name']);
        }

        return $list;
    }

    public function searchLastfmTrack(): array {
        $apikey = $_ENV['LASTFM_KEY'];
        
        $results = $this->lfmService->getTopTracks($apikey, 5); 
        
        return $results;
    }

    public function getLastfm(): array {
        $cacheService = new RedisCacheService();
        $cacheKey = 'carrossel:artists_data'; 
        $cacheTTL = 86400; // 24 horas
        
        // Tenta buscar no Redis. Se a chave existir e for válida
        if ($cachedData = $cacheService->get($cacheKey)) {
            return $cachedData;
        }

        $apikey = $_ENV['LASTFM_KEY'];

        $client = new Client();
        $spotifyService = new SpotifyService();

        $artists = $this->lfmService->getTopArtists($apikey);

        $promises = [];

        foreach ($artists as $artist) {
            $promises[$artist['name']] = $spotifyService->searchSptfyAsync(
                $client, $artist['name']
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

    public function searchLastfm(string $artistName): array {
        $apikey = $_ENV['LASTFM_KEY'];

        $results = $this->lfmService->getByName($apikey, $artistName); 
        
        return $results;
    }
}