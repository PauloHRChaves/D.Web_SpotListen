<?php
use web\Controllers\AuthController;
use web\Exceptions\ApiException;

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();
try{
    if ($request_uri === '/' && $request_method === 'GET') {
        $authController->searchSpotify();
        exit;
    }
    else if ($request_uri === '/getlastfm' && $request_method === 'GET') {
        $responseData = $authController->getLastfm();
        exit;
    }else{
        // NOT FOUND
        header('Content-Type: text/html');
        include 'src/Utils/error.html';
        http_response_code(404);
        return;
    }
    
} catch (ApiException $e) {
    // Erros da Lógica de Negócio (4xx, 5xx ...)

    error_log("Spotify API Logic Error: " . $e->getMessage()); 
    $responseCode = $e->getHttpCode();

    header('Content-Type: application/json');
    http_response_code($responseCode); 
    echo json_encode([
        'code' => $responseCode,
        'message' => 'Erro de acesso à API.'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $e) {
    // Erros de Sistema/Gerais (CURL, PHP, Banco de Dados ...)

    error_log("SystemException: " . $e->getMessage()); 
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'code' => 500,
        'message' => 'Ocorreu um error interno. Tente novamente mais tarde.' 
    ], JSON_UNESCAPED_UNICODE);
    exit;
}