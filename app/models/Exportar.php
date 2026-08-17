<?php
require_once __DIR__ . '/../../config/conexion.php';

class Exportar {
    private $bd;

    public function __construct() {
        $this->bd = conectar();
    }

    public function obtenerPacientes() {
        $stmt = $this->bd->prepare(
            "SELECT id_paciente, nombres, apellidos, dui, fecha_nacimiento, sexo, telefono, correo FROM pacientes"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCitas() {
        $stmt = $this->bd->prepare(
            "SELECT id_cita, id_paciente, id_medico, fecha_hora, estado, motivo, created_at FROM citas"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerExpedientes() {
        $stmt = $this->bd->prepare(
            "SELECT id_detalle, id_paciente, id_medico, fecha_llegada, motivo, diagnostico, tratamiento FROM expedientes"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ejecutarSQL($sql) {
        $stmt = $this->bd->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function guardarFechaBackup() {
        $this->bd->prepare("DELETE FROM ultimo_backup")->execute();
        $stmt = $this->bd->prepare("INSERT INTO ultimo_backup (fecha) VALUES (CURDATE())");
        $stmt->execute();
    }

    public function obtenerFechaBackup() {
        $stmt = $this->bd->prepare("SELECT fecha FROM ultimo_backup ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}