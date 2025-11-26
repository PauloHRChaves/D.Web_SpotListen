<?php
use src\Controllers\AuthController;
use src\Controllers\SpotifyController;
use src\Controllers\LastfmController;
// use src\Controllers\MusicBrainzController;
use src\Controllers\GeneralController;
return [
    'GET' => [
        '/logged-in' => [AuthController::class, 'logged'],

        '/spotify/auth' => [AuthController::class, 'spotifyLoginRedirect'],
        '/spotify/callback' => [AuthController::class, 'spotifyCallback'],

        '/spotify/my/playlists' => [SpotifyController::class, 'myPlaylists'],
        '/spotify/my/recent-tracks' => [SpotifyController::class, 'recentTracks'],
        '/spotify/my/current-track' => [SpotifyController::class, 'currentTrack'],
        '/spotify/my/top-artists' => [SpotifyController::class, 'userTopArtists'],
        '/spotify/my/fav-artists' => [SpotifyController::class, 'myTopArtist'],

        '/spotify/search/genre' => [SpotifyController::class, 'searchSpotifyGenre'],
        '/spotify/search/artist' => [SpotifyController::class, 'searchArtistByName'],

        '/lasfm/top15artists' => [LastfmController::class, 'getLastfmArtists'],
        '/lasfm/top15tracks' => [LastfmController::class, 'getLastfmTracks'],

        '/info/artists' => [GeneralController::class, 'getInfo'],
    ],

    'POST' => [
        '/login' => [AuthController::class, 'login'],
        '/register' => [AuthController::class, 'register'],
        '/logout' => [AuthController::class, 'logout'],
    ],

    'DELETE' => [
        '/spotify/unlink' => [AuthController::class, 'unlinkspt'],
    ]
];