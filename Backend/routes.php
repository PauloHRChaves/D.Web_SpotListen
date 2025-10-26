<?php
use src\Config\AuthController;
use src\Controllers\SpotifyController;
use src\Controllers\LastfmController;
use src\Controllers\MusicBrainzController;

return [
    'GET' => [
        '/spotify/search/artistId' => [SpotifyController::class, 'searchArtistById'],
        '/spotify/search/genre' => [SpotifyController::class, 'searchSpotifyGenre'],
        '/spotify/search/artist' => [SpotifyController::class, 'searchArtistByName'],

        '/lasfm/top15artists' => [LastfmController::class, 'getLastfm'],
        '/lasfm/genres' => [LastfmController::class, 'searchLastfmGenre'],
        
        '/spotify/auth' => [SpotifyController::class, 'spotifyLoginRedirect'],
    ],

    'POST' => [
        // REMOVER
        // '/spotify/token' => [SpotifyController::class, 'spotifyToken'],
        // '/register' => [AuthController::class, 'register'],
    ]
];