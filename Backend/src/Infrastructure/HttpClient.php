<?php
namespace src\Infrastructure;

use src\Exceptions\ApiException;
use Exception;

class HttpClient {
    protected int $timeout;

    public function __construct(int $timeout = 10) {
        $this->timeout = $timeout;
    }

    protected function _executarRequest(string $url, array $headers = [], string $body = '', string $method = 'GET'): array {
        $ch = curl_init();
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = is_int($key) ? $value : "$key: $value";
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $formattedHeaders,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FAILONERROR => false,
        ]);

        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        $body = json_decode($response, true);
        if ($httpCode !== 200) {
            $message = $body['error']['message'] ?? 'Erro desconhecido';
            throw new ApiException("API Error: $message", $httpCode);
        }

        return $body;
    }
    
    protected function _executarMultiRequest(array $requests): array {
        $multiHandle = curl_multi_init();
        $handles = [];
        $results = [];

        foreach ($requests as $index => $request) {
            $ch = curl_init();
            $formattedHeaders = [];
            foreach ($request['headers'] as $key => $value) {
                $formattedHeaders[] = is_int($key) ? $value : "$key: $value";
            }

            curl_setopt_array($ch, [
                CURLOPT_URL            => $request['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => $formattedHeaders,
                CURLOPT_TIMEOUT        => $this->timeout,
                CURLOPT_FAILONERROR    => false,
            ]);

            if (strtoupper($request['method']) === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request['body'] ?? '');
            }
            
            curl_multi_add_handle($multiHandle, $ch);
            $handles[$index] = $ch;
        }

        $running = null;
        do {
            $status = curl_multi_exec($multiHandle, $running);
            if ($running) {
                curl_multi_select($multiHandle, 1.0);
            }
        } while ($running > 0 && $status === CURLM_OK);

        foreach ($handles as $index => $ch) {
            $response = curl_multi_getcontent($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                throw new Exception("cURL Error for request $index: " . curl_error($ch));
            }

            curl_multi_remove_handle($multiHandle, $ch);
            curl_close($ch);
            $body = json_decode($response, true);
            if ($httpCode !== 200) {
                $message = $body['error']['message'] ?? 'Erro desconhecido';
                throw new ApiException("API Error for request $index: $message", $httpCode);
            }

            $results[$index] = $body;
        }

        curl_multi_close($multiHandle);

        return $results;
    }
}