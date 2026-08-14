<?php
class Medicos {
    private $conexion;

    public function __construct() {
        require_once __DIR__ . '/../../config/conexion.php';
        $this->conexion = conectar();
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM medicos WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($id_usuario, $telefono) {
        $sql = "INSERT INTO medicos (id_usuario, telefono) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id_usuario, $telefono]);
    }

    public function actualizarTelefono($id_usuario, $telefono) {
        $sql = "UPDATE medicos SET telefono = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$telefono, $id_usuario]);
    }

    public function eliminarPorUsuario($id_usuario) {
        $sql = "DELETE FROM medicos WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }

    public function tieneCitas($id_usuario) {
        $medico = $this->obtenerPorUsuario($id_usuario);
        if (!$medico) return false;

        $sql = "SELECT COUNT(*) FROM citas WHERE id_medico = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$medico['id_medico']]);
        return $stmt->fetchColumn() > 0;
    }
}
?>