<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Pacientes.php';
require_once __DIR__ . '/../models/Expedientes.php';

class PacientesController {
    private $pacientes;
    private $expedientes;

    public function __construct() {
        // Ver pacientes y expediente: cualquier rol logueado, incluye médicos
        requerirRol(['user', 'admin', 'owner']);
        $this->pacientes = new Pacientes();
        $this->expedientes = new Expedientes();
    }

    public function index() {
        $busqueda = trim($_GET['buscar'] ?? '');
        $listaPacientes = $this->pacientes->obtenerTodos($busqueda);
        require_once __DIR__ . '/../views/pacientes/listar.php';
    }

    public function expediente($id_paciente = null) {
        if ($id_paciente === null) {
            http_response_code(404);
            echo "Paciente no especificado";
            return;
        }

        $paciente = $this->pacientes->obtenerPorId($id_paciente);
        if (!$paciente) {
            http_response_code(404);
            echo "Paciente no encontrado";
            return;
        }

        $historial = $this->expedientes->obtenerPorPaciente($id_paciente);
        require_once __DIR__ . '/../views/pacientes/expediente.php';
    }

    public function registrar() {
        requerirRol(['admin', 'owner']);

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombres          = trim($_POST['nombres'] ?? '');
            $apellidos        = trim($_POST['apellidos'] ?? '');
            $dui              = trim($_POST['dui'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
            $sexo             = $_POST['sexo'] ?? '';
            $telefono         = trim($_POST['telefono'] ?? '');
            $correo           = trim($_POST['correo'] ?? '');

            $error = $this->validarPaciente($nombres, $apellidos, $dui, $fecha_nacimiento, $sexo, $telefono, $correo);

            if ($error === null) {
                $this->pacientes->crear($nombres, $apellidos, $dui, $fecha_nacimiento, $sexo, $telefono, $correo);
                header("Location: " . BASE_URL . "/pacientes");
                exit();
            }
        }

        require_once __DIR__ . '/../views/pacientes/registrar.php';
    }

    public function editar($id_paciente = null) {
        requerirRol(['admin', 'owner']);

        if ($id_paciente === null) {
            http_response_code(404);
            echo "Paciente no especificado";
            return;
        }

        $paciente = $this->pacientes->obtenerPorId($id_paciente);
        if (!$paciente) {
            http_response_code(404);
            echo "Paciente no encontrado";
            return;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombres          = trim($_POST['nombres'] ?? '');
            $apellidos        = trim($_POST['apellidos'] ?? '');
            $dui              = trim($_POST['dui'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
            $sexo             = $_POST['sexo'] ?? '';
            $telefono         = trim($_POST['telefono'] ?? '');
            $correo           = trim($_POST['correo'] ?? '');

            $error = $this->validarPaciente($nombres, $apellidos, $dui, $fecha_nacimiento, $sexo, $telefono, $correo, $id_paciente);

            if ($error === null) {
                $this->pacientes->actualizar($id_paciente, $nombres, $apellidos, $dui, $fecha_nacimiento, $sexo, $telefono, $correo);
                header("Location: " . BASE_URL . "/pacientes");
                exit();
            }

            $paciente['nombres']          = $nombres;
            $paciente['apellidos']        = $apellidos;
            $paciente['dui']              = $dui;
            $paciente['fecha_nacimiento'] = $fecha_nacimiento;
            $paciente['sexo']             = $sexo;
            $paciente['telefono']         = $telefono;
            $paciente['correo']           = $correo;
        }

        require_once __DIR__ . '/../views/pacientes/editar.php';
    }

    public function eliminar($id_paciente = null) {
        requerirRol(['admin', 'owner']);

        if ($id_paciente !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->pacientes->tieneCitasOExpedientes($id_paciente)) {
                $_SESSION['error_pacientes'] = "No se puede eliminar: el paciente tiene citas o expedientes asociados.";
            } else {
                $this->pacientes->eliminar($id_paciente);
            }
        }
        header("Location: " . BASE_URL . "/pacientes");
        exit();
    }

    private function validarPaciente($nombres, $apellidos, $dui, $fecha_nacimiento, $sexo, $telefono, $correo, $id_paciente_excluir = null) {
        if ($nombres === '' || $apellidos === '' || $dui === '' || $fecha_nacimiento === '' || $sexo === '') {
            return "Completa todos los campos obligatorios.";
        }

        if (!preg_match('/^\d{8}-\d$/', $dui)) {
            return "El DUI debe tener el formato 00000000-0.";
        }

        if ($this->pacientes->existeDui($dui, $id_paciente_excluir)) {
            return "Ya existe un paciente registrado con ese DUI.";
        }

        $ts_nacimiento = strtotime($fecha_nacimiento);
        if ($ts_nacimiento === false) {
            return "Fecha de nacimiento inválida.";
        }
        if ($ts_nacimiento > time()) {
            return "La fecha de nacimiento no puede ser futura.";
        }

        if (!in_array($sexo, ['M', 'F'])) {
            return "Selecciona un sexo válido.";
        }

        if ($telefono !== '' && !preg_match('/^\d{4}-?\d{4}$/', $telefono)) {
            return "El teléfono debe tener 8 dígitos (formato 0000-0000).";
        }

        if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return "El correo electrónico no es válido.";
        }

        return null;
    }
}