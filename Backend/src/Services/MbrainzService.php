<?php
namespace src\Services;

use src\Infrastructure\HttpClient;

class MbrainzService extends HttpClient {  
    public function getArtistInfo(string $artistName): array {
        $key = $_ENV['EMAIL'];

        $encodedArtistName = urlencode($artistName);
        $url = "https://musicbrainz.org/ws/2/artist/?query=artist:{$encodedArtistName}&fmt=json&limit=1";
        
        $headers = ["User-Agent: SpotList/1.0.0 ($key)"];

        $data = $this->_executarRequest($url, $headers);
        
        $artists = $data['artists'];

        $firstArtist = $artists[0];

        $filteredInfo = [
            'mbid'      => $firstArtist['id'] ?? null,
            'begin'      => $firstArtist['life-span']['begin'] ?? null,
            'end'      => $firstArtist['life-span']['end'] ?? null,
        ];
        
        return $filteredInfo;
    }
}