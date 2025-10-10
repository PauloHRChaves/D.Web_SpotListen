<?php
namespace web\Controllers;

use web\Services\SpotifyService;
use web\Exceptions\ApiException;
use Exception;

class AuthController{
    // API: SPOTIFY
    // BUSCANDO O TOKEN DO SPOTIFY
    public function getAccessToken(){
        $client_id = $_ENV['SPOTIFY_CLIENT_ID'] ?? null;
        $client_secret = $_ENV['SPOTIFY_CLIENT_SECRET'] ?? null;

        // Se já houver o access_token vai parar por aqui
        if (isset($_SESSION['spotify_token']) && time() < $_SESSION['expires_at']) {
            $data_cache = [
                'access_token' => $_SESSION['spotify_token'],
                'expires_in' => $_SESSION['spotify_expires_in'] ?? 3600,
            ];
            return $data_cache;
        }

        // Requisitando o token do Spotify
        $url = 'https://accounts.spotify.com/api/token';
        $ch = curl_init($url);

        $headers = [
            'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
            'Content-Type: application/x-www-form-urlencoded', // Exigência do OAuth2.0
        ];
        $body = 'grant_type=client_credentials';

        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body); 

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // ********** TRATAMENTO DE ERRO DE SISTEMA (CURL) **********
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            // Lança Exception padrão para cair no catch(Exception $e) do routes.php
            throw new Exception("CURL Error during token retrieval: " . $error_message);
        }
        // **********************************************************
        
        curl_close($ch);

        $data = json_decode($response, true);
        $access_token = $data['access_token'] ?? null;
        $expires_in = $data['expires_in'] ?? 0;

        if ($http_code === 200 && $access_token) {

            $_SESSION['spotify_token'] = $access_token;
            $_SESSION['expires_at'] = time() + $expires_in - 60;
            $_SESSION['spotify_expires_in'] = $expires_in; 
            return $data; 

        }else {
            $errorMessage = $data['error']['message'] ?? 'TOKEN_API_ERROR';
            $logMessage = "Spotify API Error ({$http_code}): " . $errorMessage;

            throw new ApiException($logMessage, $http_code);
        }
    }

    // BUSCAR ARTISTA NO SPOTIFY ( rota: / )
    public function searchSpotify() {
        $query = $_GET['q'] ?? 'Johnny Cash'; // NOME DO ARTISTA
        $type = $_GET['type'] ?? 'artist';
        
        // Erro por falta dos dados
        if (empty($query)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Parâmetro de busca "q" é obrigatório.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $token_info = $this->getAccessToken();
        $accessToken = $token_info['access_token'];

        $spotifyService = new SpotifyService();

        $results = $spotifyService->searchSptfy($accessToken, $query, $type); 
        
        http_response_code(200);
        echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return;
    }

    // API: Lastfm
    // Top Artistas Global
    public function getLastfm() {
        $apikey = $_ENV['LASTFM_KEY'] ?? null;

        // Top Artistas Global
        $url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettopartists&api_key={$apikey}&format=json&limit=500";
        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // ********** TRATAMENTO DE ERRO DE SISTEMA (CURL) **********
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            throw new Exception("CURL Error during Deezer API call: " . $error_message);
        }
        // **********************************************************

        curl_close($ch);
        $data = json_decode($response, true); 

        if ($http_code === 200) {
            $artists = $data['artists']['artist'] ?? [];
            
            // Filtragem e Limpeza dos Dados
            $filtered_artists = [];

            foreach ($artists as $artist) {
                $filtered_artists[] = [
                    'name' => $artist['name'],
                    'playcount' => $artist['playcount'],
                    'listeners' => $artist['listeners'],
                    'url' => $artist['url'],
                    'mbid' => $artist['mbid'] ?? null
                ];
            }
            
            // Retorna a lista de artistas limpa
            return $filtered_artists;

        }else {
            $errorMessage = $data['error']['message'] ?? 'Erro desconhecido da API.';
            $logMessage = "LastFM API Error ({$http_code}): " . $errorMessage;
            throw new ApiException($logMessage, $http_code);
        }
    }
}