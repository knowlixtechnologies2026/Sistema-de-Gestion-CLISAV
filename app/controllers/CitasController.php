<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Citas.php';
require_once __DIR__ . '/../models/Expedientes.php';

class CitasController {
    private $citas;
    private $expedientes;
    private $rol;
    private $id_usuario;
    private $id_medico_actual = null;

    const HORA_MIN = '07:00';
    const HORA_MAX = '16:00';

    public function __construct() {
        requerirRol(['user', 'admin', 'owner']);

        $this->citas = new Citas();
        $this->expedientes = new Expedientes();
        $this->rol = $_SESSION['rol'];
        $this->id_usuario = $_SESSION['id_usuario'];

        if ($this->rol === 'user') {
            $this->id_medico_actual = $this->citas->obtenerIdMedicoPorUsuario($this->id_usuario);
        }
    }

    public function index() {
        $esMedico = ($this->rol === 'user');

        if ($esMedico) {
            $listaCitas = $this->id_medico_actual !== null
                ? $this->citas->obtenerPorMedico($this->id_medico_actual)
                : [];
        } else {
            $listaCitas = $this->citas->obtenerTodas();
        }

        require_once __DIR__ . '/../views/citas/listar.php';
    }

    public function registrar() {
        requerirRol(['admin', 'owner']);

        $error = null;
        $pacientes = $this->citas->obtenerPacientes();
        $medicos = $this->citas->obtenerMedicos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_paciente = $_POST['id_paciente'] ?? '';
            $id_medico   = $_POST['id_medico'] ?? '';
            $fecha       = $_POST['fecha'] ?? '';
            $hora        = $_POST['hora'] ?? '';
            $motivo      = $_POST['motivo'] ?? '';

            $error = $this->validarCita($fecha, $hora, $id_medico);

            if ($error === null) {
                $fecha_hora = $fecha . ' ' . $hora . ':00';
                $this->citas->crear($id_paciente, $id_medico, $fecha_hora, $motivo);
                header("Location: " . BASE_URL . "/citas");
                exit();
            }
        }

        require_once __DIR__ . '/../views/citas/registrar.php';
    }

    public function editar($id_cita = null) {
        // Editar citas queda exclusivo de recepción/administración
        requerirRol(['admin', 'owner']);

        if ($id_cita === null) {
            http_response_code(404);
            echo "Cita no especificada";
            return;
        }

        $cita = $this->citas->obtenerPorId($id_cita);
        if (!$cita) {
            http_response_code(404);
            echo "Cita no encontrada";
            return;
        }

        $error = null;
        $pacientes = $this->citas->obtenerPacientes();
        $medicos = $this->citas->obtenerMedicos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_paciente = $_POST['id_paciente'] ?? '';
            $id_medico   = $_POST['id_medico'] ?? '';
            $fecha       = $_POST['fecha'] ?? '';
            $hora        = $_POST['hora'] ?? '';
            $motivo      = $_POST['motivo'] ?? '';
            $estado      = $_POST['estado'] ?? 'pendiente';
            $fecha_hora  = $fecha . ' ' . $hora . ':00';

            if ($id_paciente === '' || $id_medico === '' || $fecha === '' || $hora === '') {
                $error = "Completa todos los campos obligatorios.";
            } elseif ($fecha_hora !== $cita['fecha_hora']) {
                $error = $this->validarCita($fecha, $hora, $id_medico, $id_cita);
            }

            if ($error === null) {
                $this->citas->actualizar($id_cita, $id_paciente, $id_medico, $fecha_hora, $motivo, $estado);
                header("Location: " . BASE_URL . "/citas");
                exit();
            }

            $cita['id_paciente'] = $id_paciente;
            $cita['id_medico']   = $id_medico;
            $cita['fecha_hora']  = $fecha_hora;
            $cita['motivo']      = $motivo;
            $cita['estado']      = $estado;
        }

        require_once __DIR__ . '/../views/citas/editar.php';
    }

    public function eliminar($id_cita = null) {
        // Eliminar citas queda exclusivo de recepción/administración
        requerirRol(['admin', 'owner']);

        if ($id_cita !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->citas->eliminar($id_cita);
        }
        header("Location: " . BASE_URL . "/citas");
        exit();
    }

    public function atender($id_cita = null) {
        requerirRol(['user']);

        if ($id_cita === null) {
            http_response_code(404);
            echo "Cita no especificada";
            return;
        }

        $cita = $this->citas->obtenerDetallePorId($id_cita);
        if (!$cita) {
            http_response_code(404);
            echo "Cita no encontrada";
            return;
        }

        if ((int)$cita['id_medico'] !== (int)$this->id_medico_actual) {
            http_response_code(403);
            echo "No tienes permiso para atender esta cita.";
            return;
        }

        if ($cita['estado'] !== 'pendiente') {
            echo "Esta cita ya fue atendida o está cancelada.";
            return;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $diagnostico = trim($_POST['diagnostico'] ?? '');
            $tratamiento = trim($_POST['tratamiento'] ?? '');

            if ($diagnostico === '' || $tratamiento === '') {
                $error = "Completa el diagnóstico y el tratamiento.";
            } else {
                $this->expedientes->crear(
                    $cita['id_paciente'],
                    $cita['id_medico'],
                    $cita['motivo'],
                    $diagnostico,
                    $tratamiento
                );

                $this->citas->actualizar(
                    $cita['id_cita'],
                    $cita['id_paciente'],
                    $cita['id_medico'],
                    $cita['fecha_hora'],
                    $cita['motivo'],
                    'completado'
                );

                header("Location: " . BASE_URL . "/citas");
                exit();
            }
        }

        require_once __DIR__ . '/../views/citas/atender.php';
    }

    private function validarCita($fecha, $hora, $id_medico, $id_cita_excluir = null) {
        if ($fecha === '' || $hora === '' || $id_medico === '') {
            return "Completa todos los campos obligatorios.";
        }

        $fecha_hora = $fecha . ' ' . $hora . ':00';
        $timestamp = strtotime($fecha_hora);

        if ($timestamp === false) {
            return "Fecha u hora inválida.";
        }

        if ($timestamp < time()) {
            return "No puedes registrar una cita en una fecha u hora que ya pasó.";
        }

        if ($hora < self::HORA_MIN || $hora > self::HORA_MAX) {
            return "El horario de citas es de " . self::HORA_MIN . " a " . self::HORA_MAX . ".";
        }

        if ($this->citas->existeConflicto($id_medico, $fecha_hora, $id_cita_excluir)) {
            return "Ese médico ya tiene otra cita a menos de " . Citas::INTERVALO_MINUTOS . " minutos de esa hora.";
        }

        return null;
    }
}