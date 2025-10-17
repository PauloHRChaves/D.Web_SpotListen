<?php
namespace web\Controllers;

// Services
use web\Services\SpotifyService;
use web\Services\LastfmService;
use web\Services\MbrainzService;


class AuthController{
    public function __construct() {
        header('Content-Type: application/json');
    }

/**************************************************************** */
    // API: MusicBrainz
    public function getMbrainz(string $mbidtag, string $artistName): array {
        $appName = 'SpotListen';
        $appVersion = '1.0';
        $appContact = $_ENV['EMAIL']; // .env EMAIL='example@gmail.com'

        $token_info = $this->getAccessToken();
        $accessToken = $token_info['access_token'];

        $spotifyId = null;

        // BUSCA MBID NO MUSICBRAINZ
        // Só chama o Service se o MBID existir
        if (!empty($mbidtag)) {
            $mbrainzService = new MbrainzService();
            $spotifyId = $mbrainzService->Mbrainzinfo($appName, $appVersion, $appContact, $mbidtag);
        }

        // FALLBACK: TENTA BUSCAR DIRETAMENTE NO SPOTIFY PELO NOME
        if (empty($spotifyId) && !empty($artistName)) {
            $spotifyService = new SpotifyService();
            $result = $spotifyService->searchSptfy($accessToken, $artistName, 'artist');
            $spotifyId = $result['artists']['items'][0]['id'] ?? null;
        }

        return  $spotifyId;
    }
}
?>