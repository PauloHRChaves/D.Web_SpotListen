<?php
namespace src\Controllers;

// Services
use src\Services\SpotifyService;

class SpotifyController {
    public function __construct() {
        $this->sptService= new SpotifyService();
    }

    // public function searchArtistById(): array {
    //     $artistId = '2UMj7NCbuqy1yUZmiSYGjJ';

    //     $results = $this->sptService->searchById($artistId);
        
    //     return $results;
    // }

    // public function spotifyToken(): array { 
    //     $result = $this->sptService->getAccessToken();
    //     return $result; 
    // }

    public function searchSpotifyGenre(string $genre, int $limit, int $offset): array { 
        $genre = $_GET['genre'] ?? '';
        $genre = trim($genre);
        $genre = urlencode($genre);

        $result = $this->sptService->searchTopGenres($genre, $limit, $offset);
        
        return $result; 
    }
}