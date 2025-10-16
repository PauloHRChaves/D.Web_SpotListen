<?php
namespace web\Utils;

use web\Exceptions\ApiException;
use Exception;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiConfig {
    protected Client $client;

    public function __construct() {
        $this->client = new Client([
            'timeout'  => 10.0,
            'http_errors' => false
        ]);
    }

    public function _executarCurl(string $url, array $headers, string $body): array {
        $ch = curl_init($url);

        $defaultOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST=> true,
            CURLOPT_TIMEOUT=> 30,
            CURLOPT_HTTPHEADER=> $headers,
            CURLOPT_POSTFIELDS=> $body,
        ];

        curl_setopt_array($ch, $defaultOptions);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // ********** TRATAMENTO DE ERRO DE SISTEMA (CURL) **********
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            // Lança Exception padrão para cair no catch(Exception $e) do routes.php
            throw new Exception("CURL Error during search on API: " . $error_message);
        }
        // **********************************************************
        
        curl_close($ch);
        $data = json_decode($response, true);
        
        return [
            'data' => $data,
            'http_code' => $http_code 
        ];
    }
    
    protected function _executarRequest(string $url, array $headers = []): array {
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => $headers
            ]);

            $httpCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($httpCode !== 200) {
                $message = $body['error']['message'] ?? 'Erro desconhecido';
                throw new ApiException("API Error: $message", $httpCode);
            }

            return $body;
        } catch (RequestException $e) {
            throw new Exception('Guzzle Error: ' . $e->getMessage());
        }
    }
}