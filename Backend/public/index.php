<?php
session_start();
define('ROOT_PATH', __DIR__ . '/../');

require ROOT_PATH . 'vendor/autoload.php';
require ROOT_PATH . 'bootstrap.php';

header("Access-Control-Allow-Origin: http://localhost:8231");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

use web\Exceptions\ApiException;

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

$routes = require ROOT_PATH . 'routes.php';

try {
    $allParams = $_GET;

    $route_uri = strtok($request_uri, '?');
    
    if (isset($routes[$request_method][$route_uri])){
        [$controllerClass, $method] = $routes[$request_method][$route_uri];

        $controller = new $controllerClass();
        
        // --- USA REFLECTION PARA MONTAR OS ARGUMENTOS CORRETOS ---
        $reflection = new ReflectionMethod($controllerClass, $method);
        $methodParameters = $reflection->getParameters();
        
        $args = [];
        
        // Itera sobre os parâmetros que o MÉTODO (do Controller) realmente espera
        foreach ($methodParameters as $param) {
            $paramName = $param->getName();
            
            // Verifica se o parâmetro esperado pelo método existe no $allParams (do $_GET)
            if (isset($allParams[$paramName])) {
                $value = $allParams[$paramName];
                
                if ($param->hasType()) {
                    $typeName = $param->getType()->getName();
                    if ($typeName === 'int') {
                        $value = (int)$value;
                    } elseif ($typeName === 'bool') {
                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    }
                    // caso precisar, add mais converções
                }
                $args[] = $value;
            } 
            // Se o parâmetro for obrigatório e não estiver no $_GET, a chamada vai falhar
            // Se for opcional e não estiver no $_GET, o PHP usará o valor padrão
        }
        
        // Passa os argumentos filtrados para o método
        $responseData = $controller->$method(...$args);

        header('Content-Type: application/json');

        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    } else {
        http_response_code(404);
        $responseData = ['message' => 'NOT FOUND'];
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE);    
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