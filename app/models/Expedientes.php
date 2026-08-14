<?php
require_once __DIR__ . '/../../config/conexion.php';

class Expedientes {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar();
    }

    public function crear($id_paciente, $id_medico, $motivo, $diagnostico, $tratamiento) {
        $sql = "INSERT INTO expedientes (id_paciente, id_medico, fecha_llegada, motivo, diagnostico, tratamiento)
                VALUES (:id_paciente, :id_medico, NOW(), :motivo, :diagnostico, :tratamiento)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_paciente' => $id_paciente,
            ':id_medico'   => $id_medico,
            ':motivo'      => $motivo,
            ':diagnostico' => $diagnostico,
            ':tratamiento' => $tratamiento,
        ]);
    }

    public function obtenerPorPaciente($id_paciente) {
        $sql = "SELECT e.id_detalle, e.fecha_llegada, e.motivo, e.diagnostico, e.tratamiento,
                       u.nombres AS medico_nombres, u.apellidos AS medico_apellidos
                FROM expedientes e
                INNER JOIN medicos m ON e.id_medico = m.id_medico
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE e.id_paciente = :id_paciente
                ORDER BY e.fecha_llegada DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_paciente' => $id_paciente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}