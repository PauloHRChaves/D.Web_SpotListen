<?php
namespace web\Services;

use web\Utils\ApiConfig;

class WikiService extends ApiConfig {
    // aprimorar estrutura
    public function searchWikiTitle(string $artistName, string $appContact): ?string{
        $urlBase = "https://pt.wikipedia.org/w/api.php";
        
        $params = [
            'action' => 'query',
            'list' => 'search',
            'srsearch' => $artistName,
            'format' => 'json',
            'srlimit' => 1
        ];
        
        $query = http_build_query($params);
        $fullUrl = $urlBase . '?' . $query;

        $headers = [
            'User-Agent' => "HearBySong/1.0 ($appContact) - Usado para biografia de artistas",
        ];

        $response = $this->_executarRequest($fullUrl, $headers);

        if (isset($response['query']['search'][0])) {
            $firstResult = $response['query']['search'][0];
            $pageTitle = $firstResult['title'];
            $snippet = $firstResult['snippet'];
            
            if ($this->validateMusicContextFromSnippet($snippet)) {
                return $pageTitle;
            }
        }

        return null;
    }

    private function validateMusicContextFromSnippet(string $snippet): bool{
        $lowerSnippet = strtolower($snippet);

        $musicRequiredTerms = [
            'banda', 
            'cantor', 
            'músico',
            'cantora',
            'rapper',
            'álbum',
            'compositor',
        ];
        foreach ($musicRequiredTerms as $term) {
            if (strpos($lowerSnippet, $term) !== false) {
                return true;
            }
        }
        return false;
    }

    public function getWikiExtract(string $pageTitle, string $appContact): ?string{
        $urlBase = "https://pt.wikipedia.org/w/api.php";
        
        $params = [
            'action' => 'query',
            'prop' => 'extracts',
            'titles' => $pageTitle,
            // 'exintro' => 0,
            'exhtml' => 1,
            'exlimit' => 1,
            'format' => 'json'
        ];
                
        $query = http_build_query($params);
        $fullUrl = $urlBase . '?' . $query;

        $headers = [
            'User-Agent' => "HearBySong/1.0 ($appContact) - Usado para biografia de artistas",
        ];

        $response = $this->_executarRequest($fullUrl, $headers);

        if (isset($response['query']['pages'])) {
            $pageId = array_key_first($response['query']['pages']);
            
            if (isset($response['query']['pages'][$pageId]['extract'])) {
                return $response['query']['pages'][$pageId]['extract'];
            }
        }

        return null;
    }
}