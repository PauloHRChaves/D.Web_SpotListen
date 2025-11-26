<?php
namespace src\Services;

use src\Infrastructure\HttpClient;

class LastfmService extends HttpClient {
    // Usado no Carousel
    public function getTopArtists(): array {
        $apikey = $_ENV['LASTFM_KEY'];
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
    public function getTopTracks(): array {
        $apikey = $_ENV['LASTFM_KEY'];
        $url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettoptracks&api_key={$apikey}&format=json&limit=15";
        
        $data = $this->_executarRequest($url); 
        $tracks = $data['tracks']['track'];

        $trackMap = function(array $track): array {
            return [
                'name' => $track['name'],
                'artist' => $track['artist']['name'],
                'duration' => $track['duration'],
                'playcount' => $track['playcount'],
                'listeners' => $track['listeners']
            ];
        };
        
        return array_map($trackMap, $tracks);
    }

    //
    public function getTopGenres(): array {
        $apikey = $_ENV['LASTFM_KEY'];
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

    //
    public function getArtistTags(string $artistmbid): array {
        $apikey = $_ENV['LASTFM_KEY'];
        $url = "http://ws.audioscrobbler.com/2.0/?method=artist.getInfo&api_key=$apikey&mbid={$artistmbid}&format=json";
        
        $data = $this->_executarRequest($url);
        
        $artist = $data['artist'];

        $rawTags = $artist['tags']['tag'] ?? [];
        
        $filteredInfo = [
            'tags' => array_column($rawTags, 'name')
        ];
        
        return $filteredInfo;
    }

    public function getArtistSimiliars(string $artistmbid): array {
        $apikey = $_ENV['LASTFM_KEY'];
        $url = "http://ws.audioscrobbler.com/2.0/?method=artist.getSimilar&api_key=$apikey&mbid={$artistmbid}&format=json";
        
        $data = $this->_executarRequest($url);

        $similarArtistsContainer = $data['similarartists'] ?? [];
        $rawArtists = $similarArtistsContainer['artist'] ?? [];

        $limitedArtists = array_slice($rawArtists, 0, 10);
        
        $filteredInfo = [
            'name' => array_column($limitedArtists, 'name')
        ];
        
        return $filteredInfo;
    }
}