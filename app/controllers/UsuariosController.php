<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Usuarios.php';
require_once __DIR__ . '/../models/Medicos.php';

class UsuariosController {
    private $usuarios;
    private $medicos;

    const ROLES_ASIGNABLES = ['user', 'admin'];

    public function __construct() {
        requerirRol(['owner']);
        $this->usuarios = new Usuarios();
        $this->medicos = new Medicos();
    }

    public function index() {
        $listaUsuarios = $this->usuarios->obtenerTodos();
        require_once __DIR__ . '/../views/usuarios/listar.php';
    }

    public function registrar() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario    = trim($_POST['usuario'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            $confirmar  = $_POST['confirmar_contrasena'] ?? '';
            $nombres    = trim($_POST['nombres'] ?? '');
            $apellidos  = trim($_POST['apellidos'] ?? '');
            $rol        = $_POST['rol'] ?? '';
            $telefono   = trim($_POST['telefono'] ?? '');

            $error = $this->validarUsuario($usuario, $contrasena, $confirmar, $nombres, $apellidos, $rol, $telefono, true);

            if ($error === null) {
                $id_usuario = $this->usuarios->crear($usuario, $contrasena, $nombres, $apellidos, $rol);

                if ($rol === 'user') {
                    $this->medicos->crear($id_usuario, $telefono !== '' ? $telefono : null);
                }

                header("Location: " . BASE_URL . "/usuarios");
                exit();
            }
        }

        require_once __DIR__ . '/../views/usuarios/registrar.php';
    }

    public function editar($id_usuario = null) {
        if ($id_usuario === null) {
            http_response_code(404);
            echo "Usuario no especificado";
            return;
        }

        $usuarioData = $this->usuarios->obtenerPorId($id_usuario);
        if (!$usuarioData) {
            http_response_code(404);
            echo "Usuario no encontrado";
            return;
        }

        if ($usuarioData['rol'] === 'owner') {
            http_response_code(403);
            echo "La cuenta owner no se administra desde aquí.";
            return;
        }

        $medico = $this->medicos->obtenerPorUsuario($id_usuario);
        $usuarioData['telefono'] = $medico['telefono'] ?? '';

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario    = trim($_POST['usuario'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            $confirmar  = $_POST['confirmar_contrasena'] ?? '';
            $nombres    = trim($_POST['nombres'] ?? '');
            $apellidos  = trim($_POST['apellidos'] ?? '');
            $rol        = $_POST['rol'] ?? '';
            $telefono   = trim($_POST['telefono'] ?? '');

            $error = $this->validarUsuario($usuario, $contrasena, $confirmar, $nombres, $apellidos, $rol, $telefono, false, $id_usuario);

            if ($error === null) {
                $this->usuarios->actualizar($id_usuario, $usuario, $nombres, $apellidos, $rol);

                if ($contrasena !== '') {
                    $this->usuarios->actualizarContrasena($id_usuario, $contrasena);
                }

                if ($rol === 'user') {
                    if ($medico) {
                        $this->medicos->actualizarTelefono($id_usuario, $telefono !== '' ? $telefono : null);
                    } else {
                        $this->medicos->crear($id_usuario, $telefono !== '' ? $telefono : null);
                    }
                }

                header("Location: " . BASE_URL . "/usuarios");
                exit();
            }

            $usuarioData['usuario']   = $usuario;
            $usuarioData['nombres']   = $nombres;
            $usuarioData['apellidos'] = $apellidos;
            $usuarioData['rol']       = $rol;
            $usuarioData['telefono']  = $telefono;
        }

        require_once __DIR__ . '/../views/usuarios/editar.php';
    }

    public function eliminar($id_usuario = null) {
        if ($id_usuario !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioData = $this->usuarios->obtenerPorId($id_usuario);

            if (!$usuarioData) {
                header("Location: " . BASE_URL . "/usuarios");
                exit();
            }

            if ($usuarioData['rol'] === 'owner') {
                $_SESSION['error_usuarios'] = "La cuenta owner no se puede eliminar.";
            } elseif ($this->medicos->tieneCitas($id_usuario)) {
                $_SESSION['error_usuarios'] = "No se puede eliminar: este médico tiene citas registradas.";
            } else {
                $this->medicos->eliminarPorUsuario($id_usuario);
                $this->usuarios->eliminar($id_usuario);
            }
        }
        header("Location: " . BASE_URL . "/usuarios");
        exit();
    }

    private function validarUsuario($usuario, $contrasena, $confirmar, $nombres, $apellidos, $rol, $telefono, $esNuevo, $id_usuario_excluir = null) {
        if ($usuario === '' || $nombres === '' || $apellidos === '' || $rol === '') {
            return "Completa todos los campos obligatorios.";
        }

        if ($esNuevo && $contrasena === '') {
            return "La contraseña es obligatoria.";
        }

        if ($contrasena !== '' && strlen($contrasena) < 6) {
            return "La contraseña debe tener al menos 6 caracteres.";
        }

        if ($contrasena !== $confirmar) {
            return "Las contraseñas no coinciden.";
        }

        if (!in_array($rol, self::ROLES_ASIGNABLES)) {
            return "Selecciona un rol válido.";
        }

        if ($this->usuarios->existeUsuario($usuario, $id_usuario_excluir)) {
            return "Ya existe un usuario con ese nombre de usuario.";
        }

        if ($telefono !== '' && !preg_match('/^\d{4}-?\d{4}$/', $telefono)) {
            return "El teléfono debe tener 8 dígitos (formato 0000-0000).";
        }

        return null;
    }
}