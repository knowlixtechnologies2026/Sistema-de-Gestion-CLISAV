<?php
require_once __DIR__ . '/../../config/conexion.php';

class Alertas {
    private $conexion;

    const MESES_PARA_VENCER = 1;

    public function __construct() {
        $this->conexion = conectar();
    }

    public function obtenerFechaUltimoBackup() {
        $sql = "SELECT fecha FROM ultimo_backup ORDER BY fecha DESC LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? $fila['fecha'] : null;
    }

    public function backupVencido() {
        $fechaBackup = $this->obtenerFechaUltimoBackup();

        if ($fechaBackup === null) {
            return true;
        }

        $fechaBackupDT = new DateTime($fechaBackup);
        $fechaActual = new DateTime();
        $diferencia = $fechaBackupDT->diff($fechaActual);

        return ($diferencia->y > 0 || $diferencia->m >= self::MESES_PARA_VENCER);
    }
}