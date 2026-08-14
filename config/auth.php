<?php
function requerirLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: " . BASE_URL . "/login");
        exit();
    }
}

function requerirRol(array $rolesPermitidos) {
    requerirLogin();
    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        http_response_code(403);
        echo "No tienes permiso para acceder a esta sección.";
        exit();
    }
}