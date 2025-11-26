<?php
namespace src\Services;

use src\Infrastructure\HttpClient;

use src\Exceptions\ApiException;

class AudioDbService extends HttpClient {  

    public function getArtistInfo(string $artistname): array {
        $encodedArtistName = urlencode($artistname);
        $url = "https://www.theaudiodb.com/api/v1/json/123/search.php?s=$encodedArtistName";
        $data = $this->_executarRequest($url);

        $artists = $data['artists'];

        $firstArtist = $artists[0];

        $filteredInfo = [
            'style'         => $firstArtist['strStyle'] ?? null,
            'mood'          => $firstArtist['strMood'] ?? null,
            'country'       => $firstArtist['strCountry'] ?? null,
            'biography'     => $firstArtist['strBiographyPT'] ?? $firstArtist['strBiographyEN'] ?? null
        ];
        
        return $filteredInfo;
    }
}