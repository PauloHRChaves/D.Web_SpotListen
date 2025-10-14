<?php
namespace web\Services;


use web\Utils\ApiConfig;

use GuzzleHttp\Client;

class LastfmService extends ApiConfig {
    public function getTopArtists($apikey) {
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
}