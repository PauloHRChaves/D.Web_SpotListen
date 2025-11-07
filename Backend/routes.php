<?php
use src\Controllers\AuthController;
use src\Controllers\SpotifyController;
use src\Controllers\LastfmController;
use src\Controllers\MusicBrainzController;
use src\Controllers\SongsterrController;

return [
    'GET' => [
        '/logged-in' => [AuthController::class, 'logged'],

        '/spotify/auth' => [AuthController::class, 'spotifyLoginRedirect'],
        '/spotify/callback' => [AuthController::class, 'spotifyCallback'],

        '/spotify/my/playlists' => [SpotifyController::class, 'myPlaylists'],
        '/spotify/my/recent-tracks' => [SpotifyController::class, 'recentTracks'],
        '/spotify/my/current-track' => [SpotifyController::class, 'currentTrack'],
        '/spotify/my/top-artists' => [SpotifyController::class, 'userTopArtists'],

        '/spotify/search/genre' => [SpotifyController::class, 'searchSpotifyGenre'],
        '/spotify/search/artist' => [SpotifyController::class, 'searchArtistByName'],

        '/lasfm/top15artists' => [LastfmController::class, 'getLastfmArtists'],
        '/lasfm/top15tracks' => [LastfmController::class, 'getLastfmTracks'],

        '/learning' => [SongsterrController::class, 'getTabs'],
    ],

    'POST' => [
        // REMOVER
        // '/spotify/token' => [SpotifyController::class, 'spotifyToken'],
        '/login' => [AuthController::class, 'login'],
        '/register' => [AuthController::class, 'register'],
        '/logout' => [AuthController::class, 'logout'],
    ]
];