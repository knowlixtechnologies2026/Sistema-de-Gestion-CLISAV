<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Usuarios.php';
require_once __DIR__ . '/../models/Medicos.php';

class PerfilController {
    private $usuarios;
    private $medicos;

    public function __construct() {
        requerirLogin();
        $this->usuarios = new Usuarios();
        $this->medicos = new Medicos();
    }

    public function index() {
        $id_usuario = $_SESSION['id_usuario'];
        $usuarioData = $this->usuarios->obtenerPorId($id_usuario);

        if (!$usuarioData) {
            http_response_code(404);
            echo "Usuario no encontrado";
            return;
        }

        $esMedico = ($usuarioData['rol'] === 'user');
        $medico = $esMedico ? $this->medicos->obtenerPorUsuario($id_usuario) : null;
        $usuarioData['telefono'] = $medico['telefono'] ?? '';

        $error = null;
        $exito = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario    = trim($_POST['usuario'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            $confirmar  = $_POST['confirmar_contrasena'] ?? '';
            $nombres    = trim($_POST['nombres'] ?? '');
            $apellidos  = trim($_POST['apellidos'] ?? '');
            $telefono   = trim($_POST['telefono'] ?? '');

            $error = $this->validarPerfil($usuario, $contrasena, $confirmar, $nombres, $apellidos, $telefono, $id_usuario);

            if ($error === null) {
                $this->usuarios->actualizarPerfil($id_usuario, $usuario, $nombres, $apellidos);

                if ($contrasena !== '') {
                    $this->usuarios->actualizarContrasena($id_usuario, $contrasena);
                }

                if ($esMedico) {
                    if ($medico) {
                        $this->medicos->actualizarTelefono($id_usuario, $telefono !== '' ? $telefono : null);
                    } else {
                        $this->medicos->crear($id_usuario, $telefono !== '' ? $telefono : null);
                    }
                }

                $_SESSION['usuario']   = $usuario;
                $_SESSION['nombres']   = $nombres;
                $_SESSION['apellidos'] = $apellidos;

                $exito = "Tus datos se actualizaron correctamente.";
            }

            $usuarioData['usuario']   = $usuario;
            $usuarioData['nombres']   = $nombres;
            $usuarioData['apellidos'] = $apellidos;
            $usuarioData['telefono']  = $telefono;
        }

        require_once __DIR__ . '/../views/perfil/index.php';
    }

    private function validarPerfil($usuario, $contrasena, $confirmar, $nombres, $apellidos, $telefono, $id_usuario) {
        if ($usuario === '' || $nombres === '' || $apellidos === '') {
            return "Completa todos los campos obligatorios.";
        }

        if ($contrasena !== '' && strlen($contrasena) < 6) {
            return "La contraseña debe tener al menos 6 caracteres.";
        }

        if ($contrasena !== $confirmar) {
            return "Las contraseñas no coinciden.";
        }

        if ($this->usuarios->existeUsuario($usuario, $id_usuario)) {
            return "Ya existe un usuario con ese nombre de usuario.";
        }

        if ($telefono !== '' && !preg_match('/^\d{4}-?\d{4}$/', $telefono)) {
            return "El teléfono debe tener 8 dígitos (formato 0000-0000).";
        }

        return null;
    }
}