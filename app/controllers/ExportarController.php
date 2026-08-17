<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Exportar.php';

class ExportarController {
    private $exportar;

    const TABLAS_EXPORTABLES = ['pacientes', 'citas', 'expedientes'];

    public function __construct() {
        requerirRol(['owner']);
        $this->exportar = new Exportar();
    }

    public function index() {
        require_once __DIR__ . '/../views/exportar/index.php';
    }

    public function tabla($tabla = null) {
        if ($tabla !== 'todas' && !in_array($tabla, self::TABLAS_EXPORTABLES)) {
            header("Location: " . BASE_URL . "/exportar?error=" . urlencode("Tabla no válida"));
            exit();
        }

        if ($tabla === 'pacientes') {
            $archivo = 'pacientes_export_' . date('Ymd_His') . '.sql';
            $contenido = "-- Exportacion de tabla: pacientes\n-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
            $contenido .= $this->generarInsertsPacientes($this->exportar->obtenerPacientes());

        } elseif ($tabla === 'citas') {
            $archivo = 'citas_export_' . date('Ymd_His') . '.sql';
            $contenido = "-- Exportacion de tabla: citas\n-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
            $contenido .= $this->generarInsertsCitas($this->exportar->obtenerCitas());

        } elseif ($tabla === 'expedientes') {
            $archivo = 'expedientes_export_' . date('Ymd_His') . '.sql';
            $contenido = "-- Exportacion de tabla: expedientes\n-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
            $contenido .= $this->generarInsertsExpedientes($this->exportar->obtenerExpedientes());

        } else { // 'todas'
            $archivo = 'backup_completo_' . date('Ymd_His') . '.sql';

            $contenido  = "-- Backup completo: pacientes + citas + expedientes\n";
            $contenido .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";

            $contenido .= "-- Tabla: pacientes\n";
            $contenido .= $this->generarInsertsPacientes($this->exportar->obtenerPacientes());

            $contenido .= "\n-- Tabla: citas\n";
            $contenido .= $this->generarInsertsCitas($this->exportar->obtenerCitas());

            $contenido .= "\n-- Tabla: expedientes\n";
            $contenido .= $this->generarInsertsExpedientes($this->exportar->obtenerExpedientes());

            $this->exportar->guardarFechaBackup();
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $archivo . '"');
        header('Content-Length: ' . strlen($contenido));
        echo $contenido;
        exit();
    }

    public function importar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['archivo'])) {
            header("Location: " . BASE_URL . "/exportar");
            exit();
        }

        $archivo = $_FILES['archivo'];

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            header("Location: " . BASE_URL . "/exportar?error=" . urlencode("Error al subir el archivo"));
            exit();
        }

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if ($extension !== 'sql') {
            header("Location: " . BASE_URL . "/exportar?error=" . urlencode("Solo se permiten archivos .sql"));
            exit();
        }

        $contenido = file_get_contents($archivo['tmp_name']);
        if ($contenido === false || trim($contenido) === '') {
            header("Location: " . BASE_URL . "/exportar?error=" . urlencode("El archivo está vacío"));
            exit();
        }

        $tienePacientes   = stripos($contenido, 'INSERT INTO `pacientes`') !== false;
        $tieneCitas       = stripos($contenido, 'INSERT INTO `citas`') !== false;
        $tieneExpedientes = stripos($contenido, 'INSERT INTO `expedientes`') !== false;

        try {
            if ($tieneExpedientes) {
                $this->exportar->ejecutarSQL("DELETE FROM `expedientes`");
            }
            if ($tieneCitas) {
                $this->exportar->ejecutarSQL("DELETE FROM `citas`");
            }
            if ($tienePacientes) {
                $this->exportar->ejecutarSQL("DELETE FROM `pacientes`");
            }
        } catch (PDOException $e) {
            header("Location: " . BASE_URL . "/exportar?error=" . urlencode("Error al limpiar tablas: " . $e->getMessage()));
            exit();
        }

        $lineas     = explode(';', $contenido);
        $insertados = 0;
        $errores    = 0;

        foreach ($lineas as $sql) {
            $sql = trim($sql);
            if ($sql === '' || stripos($sql, 'INSERT INTO') === false) {
                continue;
            }

            try {
                $this->exportar->ejecutarSQL($sql);
                $insertados++;
            } catch (PDOException $e) {
                $errores++;
            }
        }

        if ($errores > 0) {
            header("Location: " . BASE_URL . "/exportar?exito=" . urlencode("Importación completada: $insertados registros insertados") . "&advertencia=" . urlencode("$errores filas con errores"));
        } else {
            header("Location: " . BASE_URL . "/exportar?exito=" . urlencode("Importación exitosa: $insertados registros insertados"));
        }
        exit();
    }

    private function generarInsertsPacientes($datos) {
        $sql = '';
        foreach ($datos as $fila) {
            $id        = (int)$fila['id_paciente'];
            $nombres   = addslashes($fila['nombres']);
            $apellidos = addslashes($fila['apellidos']);
            $dui       = addslashes($fila['dui']);
            $fechaNac  = addslashes($fila['fecha_nacimiento']);
            $sexo      = addslashes($fila['sexo']);
            $telefono  = $fila['telefono'] !== null ? "'" . addslashes($fila['telefono']) . "'" : "NULL";
            $correo    = $fila['correo'] !== null ? "'" . addslashes($fila['correo']) . "'" : "NULL";

            $sql .= "INSERT INTO `pacientes` (`id_paciente`, `nombres`, `apellidos`, `dui`, `fecha_nacimiento`, `sexo`, `telefono`, `correo`) " .
                    "VALUES ($id, '$nombres', '$apellidos', '$dui', '$fechaNac', '$sexo', $telefono, $correo);\n";
        }
        return $sql;
    }

    private function generarInsertsCitas($datos) {
        $sql = '';
        foreach ($datos as $fila) {
            $id         = (int)$fila['id_cita'];
            $idPaciente = (int)$fila['id_paciente'];
            $idMedico   = (int)$fila['id_medico'];
            $fechaHora  = addslashes($fila['fecha_hora']);
            $estado     = addslashes($fila['estado']);
            $motivo     = addslashes($fila['motivo']);
            $createdAt  = addslashes($fila['created_at']);

            $sql .= "INSERT INTO `citas` (`id_cita`, `id_paciente`, `id_medico`, `fecha_hora`, `estado`, `motivo`, `created_at`) " .
                    "VALUES ($id, $idPaciente, $idMedico, '$fechaHora', '$estado', '$motivo', '$createdAt');\n";
        }
        return $sql;
    }

    private function generarInsertsExpedientes($datos) {
        $sql = '';
        foreach ($datos as $fila) {
            $id          = (int)$fila['id_detalle'];
            $idPaciente  = (int)$fila['id_paciente'];
            $idMedico    = (int)$fila['id_medico'];
            $fechaLleg   = addslashes($fila['fecha_llegada']);
            $motivo      = addslashes($fila['motivo']);
            $diagnostico = addslashes($fila['diagnostico']);
            $tratamiento = addslashes($fila['tratamiento']);

            $sql .= "INSERT INTO `expedientes` (`id_detalle`, `id_paciente`, `id_medico`, `fecha_llegada`, `motivo`, `diagnostico`, `tratamiento`) " .
                    "VALUES ($id, $idPaciente, $idMedico, '$fechaLleg', '$motivo', '$diagnostico', '$tratamiento');\n";
        }
        return $sql;
    }
}