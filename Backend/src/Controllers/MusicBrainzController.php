<?php
namespace src\Controllers;

// Services
use src\Services\MbrainzService;


class MusicBrainzController{
    public function __construct() {
        $this->mBrainzService= new MbrainzService();
    }

    
}
?>