<?php
namespace web\Controllers;

// Services
use web\Services\SpotifyService;
use web\Services\LastfmService;
use web\Services\MbrainzService;


class MusicBrainzController{
    public function __construct() {
        $this->mBrainzService= new MbrainzService();
    }

    public function getMbrainz(): array {
        $appName = 'SpotListen';
        $appVersion = '1.0';
        $appContact = $_ENV['EMAIL']; // .env EMAIL='example@gmail.com'

        $artistName = 'Nirvana';

        $result = $this->mBrainzService->Mbrainzinfo($appName, $appVersion, $appContact, $artistName);
    
        return  $result;
    }
}
?>