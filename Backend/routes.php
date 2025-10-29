<?php
use src\Controllers\AuthController;
use src\Controllers\SpotifyController;
use src\Controllers\LastfmController;
use src\Controllers\MusicBrainzController;

return [
    'GET' => [
        '/logged-in' => [AuthController::class, 'logged'],

        '/spotify/auth' => [AuthController::class, 'spotifyLoginRedirect'],
        '/spotify/callback' => [AuthController::class, 'spotifyCallback'],

        '/spotify/my/playlists' => [SpotifyController::class, 'myPlaylists'],
        '/spotify/my/recent-tracks' => [SpotifyController::class, 'recentTracks'],
        
        '/spotify/search/artistId' => [SpotifyController::class, 'searchArtistById'],
        '/spotify/search/genre' => [SpotifyController::class, 'searchSpotifyGenre'],
        '/spotify/search/artist' => [SpotifyController::class, 'searchArtistByName'],

        '/lasfm/top15artists' => [LastfmController::class, 'getLastfm'],
        '/lasfm/genres' => [LastfmController::class, 'searchLastfmGenre'],
    ],

    'POST' => [
        // REMOVER
        // '/spotify/token' => [SpotifyController::class, 'spotifyToken'],
        '/login' => [AuthController::class, 'login'],
        '/register' => [AuthController::class, 'register'],
        '/logout' => [AuthController::class, 'logout'],
    ]
];