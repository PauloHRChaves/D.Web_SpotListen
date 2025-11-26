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
    public function getLastfmArtists(): array {
        $artists = $this->lfmService->getTopArtists();
        
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

    //
    public function getLastfmTracks(): array {
        $result = $this->lfmService->getTopTracks();

        if (!is_array($result) || empty($result)) {
            error_log("Last.fm getTopTracks returned non-array data or empty array.");
            return [];
        }

        $spotifyService = new SpotifyService;
        
        $trackNames = array_column($result, 'name');
        $artistNames = array_column($result, 'artist');

        $spotifyResponses = $spotifyService->searchTrack($trackNames,$artistNames);
        
        $finalData = [];

        foreach ($result as $index => $track) {
            $spotifyData = $spotifyResponses[$index] ?? [];
            $finalData[] = [
                'name' => $track['name'],
                'artist' => $track['artist'],
                'duration' => $track['duration'],
                'playcount' => $track['playcount'],
                'listeners' => $track['listeners'],
                'url' => $spotifyData['url'],
                'image' => $spotifyData['image'],
                'popularity' => $spotifyData['popularity']
            ];
        }

        return $finalData;
    }
}