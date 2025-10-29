<?php
namespace src\Controllers;

// Controllers
use src\Controllers\SpotifyController;

// Services
use src\Services\LastfmService;
use src\Services\SpotifyService;

class LastfmController {
    public function __construct() {
        $this->lfmService= new LastfmService();
    }

    // Usado no Carousel
    public function getLastfm(): array {
        $apikey = $_ENV['LASTFM_KEY'];

        $artists = $this->lfmService->getTopArtists($apikey);
        
        if (!is_array($artists) || empty($artists)) {
            error_log("Last.fm getTopArtists returned non-array data or empty array.");
            return [];
        }

        $spotifyService = new SpotifyService;
        
        $artistNames = array_column($artists, 'name');

        $spotifyResponses = $spotifyService->searchArtist($artistNames);
        
        $finalData = [];

        foreach ($artists as $index => $artist) {
            $spotifyData = $spotifyResponses[$index] ?? []; 
            
            $artistItem = $spotifyData['artists']['items'][0] ?? [];

            $finalData[] = [
                'name'      => $artist['name'] ?? null,
                'playcount' => $artist['playcount'] ?? null,
                'listeners' => $artist['listeners'] ?? null,
                'url'       => $artistItem['external_urls']['spotify'] ?? null,
                'spotify_id'=> $artistItem['id'] ?? null,
                'images'    => $artistItem['images'][0]['url'] ?? null,
            ];
        }

        return $finalData;
    }
}