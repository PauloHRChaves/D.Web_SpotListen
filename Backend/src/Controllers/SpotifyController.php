<?php
namespace src\Controllers;

// Services
use src\Services\SpotifyService;

class SpotifyController {
    public function __construct() {
        $this->sptService= new SpotifyService();
    }

    //
    public function searchArtistByName(string $artistName): array {
        $results = $this->sptService->searchArtistByNameSingle($artistName);
        
        return $results;
    }

    //
    public function searchSpotifyGenre(string $genre, int $limit, int $offset): array { 
        $genre = trim($genre);
        $genre = urlencode($genre);

        $result = $this->sptService->searchTopGenres($genre, $limit, $offset);
        
        return $result; 
    }

    //
    public function recentTracks(): array {
        $userId = $this->sptService->getAuthenticatedUserId();
        $results = $this->sptService->getRecentlyPlayed($userId);
        
        return $results;
    }

    //
    public function myPlaylists(): array {
        $userId = $this->sptService->getAuthenticatedUserId();
        $results = $this->sptService->getMyPlaylists($userId);

        return $results;
    }

    //
    public function currentTrack(): array {
        $userId = $this->sptService->getAuthenticatedUserId();
        $result = $this->sptService->getCurrentTrack($userId);

        return $result;
    }
}