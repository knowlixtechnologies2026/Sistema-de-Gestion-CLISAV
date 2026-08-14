<?php
define('BASE_URL', (function() {
    $protocolo = (!empty($_SERVER['HTTPS'])) ? 'https' : 'http';
    $host      = $_SERVER['HTTP_HOST'];
    $ruta      = dirname($_SERVER['SCRIPT_NAME']);
    $ruta      = rtrim($ruta, '/');
    return "$protocolo://$host$ruta";
})());

require_once __DIR__ . '/auth.php';
?>