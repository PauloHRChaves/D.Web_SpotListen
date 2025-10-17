<?php
namespace web\Services;

use web\Utils\ApiConfig;

use GuzzleHttp\Client;

class LastfmService extends ApiConfig {
    public function getTopArtists($apikey): array {
        $url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettopartists&api_key={$apikey}&format=json&limit=15";
        
        $data = $this->_executarRequest($url); 
        $artists = $data['artists']['artist'];

        return array_map(function($artist) {
            return [
                'name' => $artist['name'],
                'playcount' => $artist['playcount'],
                'listeners' => $artist['listeners'],
                'mbid' => $artist['mbid'] ?? null,
                'url' => $artist['url'],
            ];
        }, $artists);
    }

    public function getTopGenres(string $apikey): array {
        $url = "http://ws.audioscrobbler.com/2.0/?method=tag.getTopTags&api_key={$apikey}&format=json";
        
        $data = $this->_executarRequest($url);

        if (!isset($data['toptags']['tag'])) {
            return [];
        }

        $top_tags_raw = $data['toptags']['tag'];
        $valid_genres = [];
        
        $blacklist = ['seen live', 'male vocalists', 'female vocalists', 'favourite', 'best of', 'for fun', 'stylish'];

        foreach ($top_tags_raw as $tag) {
            $tagName = strtolower($tag['name']);
            
            if (in_array($tagName, $blacklist)) {
                continue;
            }
            $valid_genres[] = [
                'name' => $tag['name']
            ];
        }
        
        return array_slice($valid_genres, 0, 30);
    }
    
    public function getTopTracks(string $apikey): array {
        $url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettoptracks&api_key={$apikey}&format=json&limit=50";
        
        $data = $this->_executarRequest($url);

        if (!isset($data['tracks']['track'])) {
            return [];
        }

        $top_tracks_raw = $data['tracks']['track'];
        $top_tracks_clean = [];

        // Retornar apenas o que é relevante
        foreach ($top_tracks_raw as $track) {
            $top_tracks_clean[] = [
                'name' => $track['name'],
                'artist' => $track['artist']['name'],
                'listeners' => (int)$track['listeners'], // N° de ouvintes
                'playcount' => (int)$track['playcount'], // N° de reproduções
                'url' => $track['url']
            ];
        }

        return $top_tracks_clean;
    }
    
}