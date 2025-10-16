<?php
namespace web\Services;

use web\Utils\ApiConfig;

use GuzzleHttp\Client;

class SpotifyService extends ApiConfig {
    public function searchSptfy(string $accessToken, string $artistId) {
        $safeQuery = urlencode($artistId);
        $url = "https://api.spotify.com/v1/artists/{$safeQuery}";
        
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        return $this->_executarRequest($url, $headers);
    }
    public function searchSptfygenre(string $accessToken, string $genre) {
        $url = "https://api.spotify.com/v1/search?q=genre:{$genre}&type=artist";
        
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        return $this->_executarRequest($url, $headers);
    }

    public function searchSptfyAsync(Client $client, string $accessToken, string $query, string $type) {
        $safeQuery = urlencode($query);
        $url = "https://api.spotify.com/v1/search?q={$safeQuery}&type={$type}&limit=1";

        return $client->getAsync($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);
    }
}