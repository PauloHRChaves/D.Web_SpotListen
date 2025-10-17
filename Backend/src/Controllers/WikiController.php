<?php
namespace web\Controllers;

// Services
use web\Services\LastfmService;
use web\Services\SpotifyService;
use web\Services\RedisCacheService;
use web\Services\WikiService;

class WikiController {
    public function __construct() {
        $this->wikiService = new WikiService();
    }
    public function getArtistBiography(string $artistName){
        $appContact = $_ENV['EMAIL'];
        
        $pageTitle = $this->wikiService->searchWikiTitle($artistName, $appContact);

        if (!$pageTitle) {
            return ['error' => 'Artista não encontrado ou não relacionado à música.']; 
        }

        $biographyHTML = $this->wikiService->getWikiExtract($pageTitle, $appContact);

        if (!$biographyHTML) {
            return ['error' => 'Biografia não disponível.'];
        }
        
        return [
            'title' => $pageTitle,
            'biography_html' => $biographyHTML
        ];
    }
}