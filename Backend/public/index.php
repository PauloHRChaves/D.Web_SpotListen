<?php
session_start();
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
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

use web\Exceptions\ApiException;

$uri = $_SERVER['REQUEST_URI'];
$request_uri = parse_url($uri, PHP_URL_PATH);

$request_method = $_SERVER['REQUEST_METHOD'];

$routes = require ROOT_PATH . 'routes.php';


function getRequestData(string $method): array {
    switch ($method) {
        case 'GET':
            return $_GET;

        case 'POST':
            return $_POST ?: json_decode(file_get_contents('php://input'), true) ?? [];

        default:
            return [];
    }
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

        $data = array_map('castType', getRequestData($request_method));
        $responseData = $controller->$method(...array_values($data));

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
        'message' => 'Erro de acesso à API.'
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