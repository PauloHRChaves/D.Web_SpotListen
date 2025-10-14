<?php
namespace web\Services;

use web\Utils\ApiConfig;

use GuzzleHttp\Client;

class MbrainzService extends ApiConfig {
    public function extractSpotifyId(array $data): ?string {
        foreach ($data['relations'] ?? [] as $rel) {
            $url = $rel['url']['resource'] ?? '';
            if (str_contains($url, 'spotify.com/artist/')) {
                $id = basename(parse_url($url, PHP_URL_PATH));
                if ($id && $id !== 'artist') {
                    return $id;
                }
            }
        }
        return null;
    }
    public function Mbrainzinfo(Client $client, $appName, $appVersion, $appContact, $mbidtag): ?string {
        $url = "https://musicbrainz.org/ws/2/artist/$mbidtag?inc=aliases+url-rels&fmt=json";
        
        $userAgentValue = "{$appName}/{$appVersion} ({$appContact})";
        
        $headers = [
            'User-Agent' => $userAgentValue
        ];
        
        $data = $this->_executarRequest($url, $headers);

        return $this->extractSpotifyId($data);
    }

    public function MbrainzinfoAsync(Client $client, $appName, $appVersion, $appContact, $mbidtag) {
        $url = "https://musicbrainz.org/ws/2/artist/$mbidtag?inc=aliases+url-rels&fmt=json";
        return $client->getAsync($url, [
            'headers' => [
                'User-Agent' => "{$appName}/{$appVersion} ({$appContact})"
            ]
        ]);
    }
}