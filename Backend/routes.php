<?php
use web\Controllers\AuthController;
use web\Exceptions\ApiException;

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();
try{
    if ($request_uri === '/' && $request_method === 'GET') {
        $responseData = $authController->getLastfm();
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;

    }if ($request_uri === '/2' && $request_method === 'GET') {
        $responseData = $authController->searchSpotify();
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;

    }else {
        // 404 NOT FOUND
        http_response_code(404);
        echo json_encode([
            'message' => 'NOT FOUND.'
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    
} catch (ApiException $e) {
    // Erros da Lógica de Negócio
    error_log("Spotify API Logic Error: " . $e->getMessage()); 
    
    $responseCode = $e->getCode();

    header('Content-Type: application/json');
    http_response_code($responseCode); 
    echo json_encode([
        'code' => $responseCode,
        'message' => 'Erro de acesso à API.'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $e) {
    // Erros de Sistema/Gerais
    error_log("SystemException: " . $e->getMessage()); 

    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'code' => 500,
        'message' => 'Ocorreu um error interno. Tente novamente mais tarde.' 
    ], JSON_UNESCAPED_UNICODE);
    exit;
}