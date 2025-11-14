<?php
namespace src\Controllers;

// Services
use src\Services\MbrainzService;


class MusicBrainzController{
    public function __construct() {
        $this->mBrainzService= new MbrainzService();
    }

    
    // public function getMbrainz(): array {
    //     $appName = 'SpotListen';
    //     $appVersion = '1.0';
    //     $appContact = $_ENV['EMAIL'];

    //     $artistName = 'Nirvana';

    //     $result = $this->mBrainzService->Mbrainzinfo($appName, $appVersion, $appContact, $artistName);
    
    //     return  $result;
    // }
}
?>