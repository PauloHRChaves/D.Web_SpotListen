<?php
namespace web\Controllers;

// Services
use web\Services\SpotifyService;

class SpotifyController {
    public function __construct() {
        $this->sptService= new SpotifyService();
    }

    // BUSCAR ARTISTA NO SPOTIFY PELO ID
    public function searchArtistById(): array {
        $artistId = '2UMj7NCbuqy1yUZmiSYGjJ';

        $results = $this->sptService->searchById($artistId);
        
        return $results;
    }

    // BUSCAR GENERO NO SPOTIFY - POST
    public function searchSpotifyGenre(string $genre, int $limit, int $offset): array { 
        $result = $this->sptService->searchTopGenres($genre, $limit, $offset);
        
        return $result; 
    }
}