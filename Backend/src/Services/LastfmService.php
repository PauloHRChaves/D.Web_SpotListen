<?php
namespace src\Services;

use src\Infrastructure\HttpClient;

class LastfmService extends HttpClient {
    public function getTopArtists(string $apikey): array {
        $url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettopartists&api_key={$apikey}&format=json&limit=15";
        
        $data = $this->_executarRequest($url); 
        $artists = $data['artists']['artist'];

        $artistMap = function(array $artist): array {
            return [
                'name' => $artist['name'],
                'playcount' => $artist['playcount'],
                'listeners' => $artist['listeners'],
                'mbid' => $artist['mbid'] ?? null,
                'url' => $artist['url'],
            ];
        };

        return array_map($artistMap, $artists);
    }

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

    public function getByName(string $apikey, string $artistName): array {
        $url = "http://ws.audioscrobbler.com/2.0/?method=artist.getInfo&artist={$artistName}&api_key={$apikey}&format=json&lang=pt";
        
        $data = $this->_executarRequest($url);

        $artist = $data['artist'];

        $result=[];

        $result = [
            'name' => $artist['name'] ?? null,
            
            'tags' => array_map(function($tag) {return $tag['name'];}, $artist['tags']['tag'] ?? []),
            
            'similars' => array_map(function($sim) {return $sim['name'];}, $artist['similar']['artist'] ?? []),
            
            'content' => $artist['bio']['content'] ?? null,
        ];
        return $result;


        $artistInfo = $data['artists'][0];

        $biography = $artistInfo['strBiographyPT'];

        if (empty($biography)) {
            $biography = $artistInfo['strBiographyEN'];
        }
    }
}