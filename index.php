<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$url = $_GET['url'] ?? '';
$url = trim($url, '/');

if ($url === '') {
    $url = 'login';
}

$partes = explode('/', $url);

$controlador = $partes[0] !== '' ? $partes[0] : 'login';
$accion = $partes[1] ?? 'index';
$params = array_slice($partes, 2);

$controladorClass = ucfirst($controlador) . 'Controller';
$archivo = __DIR__ . '/app/controllers/' . $controladorClass . '.php';

if (file_exists($archivo)) {
    require_once $archivo;

    $obj = new $controladorClass();

    if (method_exists($obj, $accion)) {
        call_user_func_array([$obj, $accion], $params);
    } else {
        http_response_code(404);
        echo "Acción no encontrada";
    }
} else {
    http_response_code(404);
    echo "Página no encontrada";
}

