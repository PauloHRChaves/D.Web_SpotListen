<?php
namespace src\Services;

use src\Infrastructure\HttpClient;

class LastfmService extends HttpClient {
    // Usado no Carousel
    public function getTopArtists(string $apikey): array {
        $url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettopartists&api_key={$apikey}&format=json&limit=15";
        
        $data = $this->_executarRequest($url); 
        $artists = $data['artists']['artist'];

        $artistMap = function(array $artist): array {
            return [
                'name' => $artist['name'],
                'playcount' => $artist['playcount'],
                'listeners' => $artist['listeners'],
            ];
        };

        return array_map($artistMap, $artists);
    }
    
    //
    public function getTopGenres(string $apikey): array {
        $url = "http://ws.audioscrobbler.com/2.0/?method=tag.getTopTags&api_key={$apikey}&format=json";
        
        $data = $this->_executarRequest($url);

        if (!isset($data['toptags']['tag'])) {
            return [];
        }

        $top_tags_raw = $data['toptags']['tag'];
        $valid_genres = [];

        foreach ($top_tags_raw as $tag) {
            $tagName = strtolower($tag['name']);
            
            $valid_genres[] = [
                'name' => $tag['name']
            ];
        }
        
        return array_slice($valid_genres, 0, 30);
    }
}