<?php
// DESLIGAR a exibição de erros no output
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Desabilita o uso do Cookie de Sessão
ini_set('session.use_cookies', 0);
// Permiti IDs de Sessão que não sejam via Cookies
ini_set('session.use_only_cookies', 0);

// Verifica se o ID de Sessão foi passado na URL e força o PHP a usar o ID encontrado na URL
if (isset($_GET['PHPSESSID'])) {
    session_id($_GET['PHPSESSID']);
}

// Verifica o estado da Sessão e inicia ou retoma a sessão (usando o ID definido acima ou um novo)
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

//-------------- AUTOLOAD MANUALMENTE CONFIGURADO --------------//
// constante global (ROOT_PATH) que aponta para a raiz do diretorio (Backend)
define('ROOT_PATH', __DIR__ . '/../');

spl_autoload_register(function ($class) { 
    $file_path = str_replace('\\', '/', $class) . '.php'; 
    $file = ROOT_PATH . $file_path;
    
    if (file_exists($file)) { 
        require $file; 
    }
});

require ROOT_PATH . 'bootstrap.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Bypass-Tunnel-Reminder");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

use src\Exceptions\ApiException;

// Filtragem da URI
$uri = $_SERVER['REQUEST_URI'];
$request_uri = parse_url($uri, PHP_URL_PATH);

$request_method = $_SERVER['REQUEST_METHOD'];

$routes = require ROOT_PATH . 'routes.php';

function requestType(string $method): array {
    if ($method === 'GET') {
        return $_GET;
    }

    $inputData = [];
    
    if (in_array($method, ['POST', 'DELETE'])) { 
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (str_contains($contentType, 'application/json')) {
            $jsonBody = file_get_contents('php://input');
            $inputData = json_decode($jsonBody, true) ?? [];
        } else if ($method === 'POST') {
            $inputData = $_POST;
        }
    }

    return array_merge($_GET, $inputData);
}

function castType($value) {
    if (is_numeric($value)) return $value + 0;
    if (in_array(strtolower($value), ['true', 'false'])) return strtolower($value) === 'true';
    return $value;
}

try {
    if (isset($routes[$request_method][$request_uri])) {
        [$controllerClass, $method] = $routes[$request_method][$request_uri];
        $controller = new $controllerClass();

        $data = array_map('castType', requestType($request_method));
        $responseData = $controller->$method(...array_values($data));
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'NOT FOUND'], JSON_UNESCAPED_UNICODE);
    }

} catch (ApiException $e){
    error_log("API Logic Error: ".$e->getMessage());

    http_response_code($e->getCode());
    $responseData = [
        'code' => $e->getCode(),
        'message' => $e->getMessage()
    ];
    header('Content-Type: application/json');
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("System Exception: " . $e->getMessage()); 
    
    http_response_code(500);
    $responseData = [
        'code' => 500,
        'message' => 'Ocorreu um erro interno. Tente novamente mais tarde.' 
    ];
    header('Content-Type: application/json');
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
}