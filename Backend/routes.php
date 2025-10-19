<?php
use src\Controllers\SpotifyController;
use src\Controllers\LastfmController;
use src\Controllers\MusicBrainzController;
use src\Controllers\WikiController;

return [
    'GET' => [
        '/spotify/search/artistId' => [SpotifyController::class, 'searchArtistById'],
        '/spotify/search/genre' => [SpotifyController::class, 'searchSpotifyGenre'],

        '/lasfm/top15artists' => [LastfmController::class, 'getLastfm'],
        '/lasfm/genres' => [LastfmController::class, 'searchLastfmGenre'],
    ],

    'POST' => [

        // REMOVER
        // '/spotify/token' => [SpotifyController::class, 'spotifyToken'],
    ]
];