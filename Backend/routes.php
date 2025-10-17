<?php
use web\Controllers\SpotifyController;
use web\Controllers\LastfmController;
use web\Controllers\MusicBrainzController;
use web\Controllers\WikiController;

return [
    'GET' => [
        '/spotify/search/track' => [SpotifyController::class, 'searchArtistById'],
        '/spotify/search/genre' => [SpotifyController::class, 'searchSpotifyGenre'],

        '/lasfm/top15artists' => [LastfmController::class, 'getLastfm'],
        '/lasfm/genres' => [LastfmController::class, 'searchLastfmGenre'],
        '/lasfm/searchartist' => [LastfmController::class, 'searchLastfm'],

        '/mbrainz/artist-info' => [MusicBrainzController::class, 'getMbrainz'],

        '/wiki/artist-info' => [WikiController::class, 'getArtistBiography'],
    ],

    'POST' => [ ]
];