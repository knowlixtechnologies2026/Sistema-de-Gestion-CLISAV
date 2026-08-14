<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Usuarios.php';
class LoginController {
    private $usuario;
    public function __construct() {
        $this->usuario = new Usuarios();
    }

    public function index() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $contrasena = $_POST['contrasena'];

            $usuarioR = $this->usuario->obtenerUsuarioPorNombre($nombre);

            if ($usuarioR && password_verify($contrasena, $usuarioR['contrasena'])) {
            session_start();
            $_SESSION['id_usuario'] = $usuarioR['id_usuario'];
            $_SESSION['usuario'] = $usuarioR['usuario'];
            $_SESSION['nombres'] = $usuarioR['nombres'];
            $_SESSION['apellidos'] = $usuarioR['apellidos'];
            $_SESSION['rol'] = $usuarioR['rol'];

            header("Location: " . BASE_URL . "/dashboard");
            } /*else {
            $error = "Usuario o contraseña incorrectos";
            require __DIR__ . '/../views/login.php';
        }*/
        }
        require_once __DIR__ . '/../views/login.php';
    }
}
?>