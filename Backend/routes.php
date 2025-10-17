<?php
use web\Controllers\SpotifyController;
use web\Controllers\LastfmController;

return [
    'GET' => [
        '/spotify/search/track' => [SpotifyController::class, 'searchArtistById'],
        '/spotify/search/genre' => [SpotifyController::class, 'searchSpotifyGenre'],
        '/lasfm/top15artists' => [LastfmController::class, 'getLastfm'],
        '/lasfm/genres' => [LastfmController::class, 'searchLastfmGenre'],
    ],

    'POST' => [ ]
];