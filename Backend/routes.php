<?php
use src\Controllers\AuthController;
use src\Controllers\SpotifyController;
use src\Controllers\LastfmController;
use src\Controllers\MusicBrainzController;

return [
    'GET' => [
        '/logged-in' => [AuthController::class, 'logged'],
        
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
        '/login' => [AuthController::class, 'login'],
        '/register' => [AuthController::class, 'register'],
        '/logout' => [AuthController::class, 'logout'],
    ]
];