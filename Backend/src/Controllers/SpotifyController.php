<?php
namespace src\Controllers;

// Services
use src\Services\SpotifyService;

class SpotifyController {
    public function __construct() {
        $this->sptService= new SpotifyService();
    }

    public function searchArtistById(): array {
        $artistId = '2UMj7NCbuqy1yUZmiSYGjJ';

        $results = $this->sptService->searchById($artistId);
        
        return $results;
    }

    public function spotifyLoginRedirect() { 
        $this->sptService->spotifyLogin(); 
    }

    public function spotifyCallback() {
        $result = $this->sptService->spotifyCallback($_GET['code']);
    }

    public function searchSpotifyGenre(string $genre, int $limit, int $offset): array { 
        $genre = trim($genre);
        $genre = urlencode($genre);

        $result = $this->sptService->searchTopGenres($genre, $limit, $offset);
        
        return $result; 
    }
}