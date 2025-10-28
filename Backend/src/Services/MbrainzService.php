<?php
namespace src\Services;

use src\Infrastructure\HttpClient;

class MbrainzService extends HttpClient {  
    // public function Mbrainzinfo(string $appName, string $appVersion, string $appContact, string $artistName): array {
    //     $url = "https://musicbrainz.org/ws/2/artist/?query=artist:{$artistName}&fmt=json";
        
    //     $userAgentValue = "{$appName}/{$appVersion} ({$appContact})";
        
    //     $headers = [
    //         'User-Agent' => $userAgentValue
    //     ];
        
    //     $data = $this->_executarRequest($url, $headers);

    //     return $data;
    // }
}