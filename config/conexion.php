<?php
function conectar() {
    $host = 'sql311.infinityfree.com'; 
    $port = '3306';
    $dbname = 'if0_42642657_clisav';
    $user = 'if0_42642657';
    $password = 'EK19xPhAVVgU9nK';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8mb4";

        $conexion = new PDO($dsn, $user, $password);

        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
    return $conexion;
}