<?php
require_once __DIR__ . '/../../config/conexion.php';

class Citas {
    private $pdo;

    const INTERVALO_MINUTOS = 30;

    public function __construct() {
        $this->pdo = conectar();
    }

    public function obtenerTodas() {
        $sql = "SELECT c.id_cita, c.fecha_hora, c.estado, c.motivo,
                       p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
                       u.nombres AS medico_nombres, u.apellidos AS medico_apellidos
                FROM citas c
                INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                INNER JOIN medicos m ON c.id_medico = m.id_medico
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                ORDER BY c.fecha_hora ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorMedico($id_medico) {
        $sql = "SELECT c.id_cita, c.fecha_hora, c.estado, c.motivo,
                       p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
                       u.nombres AS medico_nombres, u.apellidos AS medico_apellidos
                FROM citas c
                INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                INNER JOIN medicos m ON c.id_medico = m.id_medico
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE c.id_medico = :id_medico
                ORDER BY c.fecha_hora ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_medico' => $id_medico]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_cita) {
        $stmt = $this->pdo->prepare("SELECT * FROM citas WHERE id_cita = :id_cita");
        $stmt->execute([':id_cita' => $id_cita]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDetallePorId($id_cita) {
        $sql = "SELECT c.id_cita, c.id_paciente, c.id_medico, c.fecha_hora, c.estado, c.motivo,
                       p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
                       u.nombres AS medico_nombres, u.apellidos AS medico_apellidos
                FROM citas c
                INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                INNER JOIN medicos m ON c.id_medico = m.id_medico
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE c.id_cita = :id_cita";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_cita' => $id_cita]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerIdMedicoPorUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT id_medico FROM medicos WHERE id_usuario = :id_usuario LIMIT 1");
        $stmt->execute([':id_usuario' => $id_usuario]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? $fila['id_medico'] : null;
    }

    public function crear($id_paciente, $id_medico, $fecha_hora, $motivo) {
        $sql = "INSERT INTO citas (id_paciente, id_medico, fecha_hora, estado, motivo, created_at)
                VALUES (:id_paciente, :id_medico, :fecha_hora, 'pendiente', :motivo, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_paciente' => $id_paciente,
            ':id_medico'   => $id_medico,
            ':fecha_hora'  => $fecha_hora,
            ':motivo'      => $motivo,
        ]);
    }

    public function actualizar($id_cita, $id_paciente, $id_medico, $fecha_hora, $motivo, $estado) {
        $sql = "UPDATE citas
                SET id_paciente = :id_paciente,
                    id_medico = :id_medico,
                    fecha_hora = :fecha_hora,
                    motivo = :motivo,
                    estado = :estado
                WHERE id_cita = :id_cita";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_paciente' => $id_paciente,
            ':id_medico'   => $id_medico,
            ':fecha_hora'  => $fecha_hora,
            ':motivo'      => $motivo,
            ':estado'      => $estado,
            ':id_cita'     => $id_cita,
        ]);
    }

    public function eliminar($id_cita) {
        $stmt = $this->pdo->prepare("DELETE FROM citas WHERE id_cita = :id_cita");
        return $stmt->execute([':id_cita' => $id_cita]);
    }

    public function existeConflicto($id_medico, $fecha_hora, $id_cita_excluir = null) {
        $ts = strtotime($fecha_hora);
        $inicio = date('Y-m-d H:i:s', $ts - self::INTERVALO_MINUTOS * 60);
        $fin    = date('Y-m-d H:i:s', $ts + self::INTERVALO_MINUTOS * 60);

        $sql = "SELECT COUNT(*) FROM citas
                WHERE id_medico = :id_medico
                  AND estado != 'cancelada'
                  AND fecha_hora > :inicio AND fecha_hora < :fin";
        $params = [':id_medico' => $id_medico, ':inicio' => $inicio, ':fin' => $fin];

        if ($id_cita_excluir !== null) {
            $sql .= " AND id_cita != :id_cita_excluir";
            $params[':id_cita_excluir'] = $id_cita_excluir;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function obtenerPacientes() {
        return $this->pdo->query("SELECT id_paciente, nombres, apellidos FROM pacientes ORDER BY nombres")
                          ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMedicos() {
        $sql = "SELECT m.id_medico, u.nombres, u.apellidos
                FROM medicos m
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                WHERE u.rol = 'user'
                ORDER BY u.nombres";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}