<?php
namespace web\Services;

use web\Exceptions\ApiException;

class SpotifyService {
    public function searchSptfy($accessToken, $query, $type = 'track') {
        $safeQuery = urlencode($query);
        
        $url = "https://api.spotify.com/v1/search?q={$safeQuery}&type={$type}&limit=10";
        $ch = curl_init($url);

        $headers = [
            'Authorization: Bearer ' . $accessToken,
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // ********** TRATAMENTO DE ERRO DE SISTEMA (CURL) **********
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);

            throw new Exception("CURL Error during search: " . $error_message);
        }
        // **********************************************************

        curl_close($ch);
        $data = json_decode($response, true);

        if ($http_code === 200) {
            return $data;
        }else {
            $errorMessage = $data['error']['message'] ?? 'Erro desconhecido da API Spotify.';
            $logMessage = "Spotify API Error ({$http_code}): " . $errorMessage; 
        
            throw new ApiException($logMessage, $http_code);
        }
    }
}