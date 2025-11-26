<?php
namespace src\Controllers;

use src\Services\AudioDbService;
use src\Services\SpotifyService;
use src\Services\LastfmService;
use src\Services\MbrainzService;

class GeneralController {
    public function __construct() {
        $this->sptService= new SpotifyService();
        $this->audioDbService= new AudioDbService();
        $this->lfmService= new LastfmService();
        $this->mBrainzService= new MbrainzService();
    }

    //string $searchName
    public function getInfo(string $searchName): array {
        // $searchName='Nirvana';
        $spotifyData = $this->sptService->searchArtist2($searchName);
        
        $artistName = $spotifyData['name'];
        $sptId = $spotifyData['id'];

        $results = [];
        
        $results['sptfy_base'] = $spotifyData;

        $results['sptfy_albuns'] = $this->sptService->searchArtistAlbum($sptId);
        $results['sptfy_tracks'] = $this->sptService->searchArtistTopTracks($sptId);

        $results['audiodb'] = $this->audioDbService->getArtistInfo($artistName);

        $results['mbrainz'] = $this->mBrainzService->getArtistInfo($artistName);

        $results['lastFm'] = $this->lfmService->getArtistTags($results['mbrainz']['mbid']);
        $results['lastFms_similars'] = $this->lfmService->getArtistSimiliars($results['mbrainz']['mbid']);

        return $results;
    }
}