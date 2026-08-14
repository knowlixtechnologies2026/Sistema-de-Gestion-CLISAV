<?php
class Usuarios {
    private $conexion;

    public function __construct() {
        require_once __DIR__ . '/../../config/conexion.php';
        $this->conexion = conectar();
    }

    public function obtenerUsuarioPorNombre($username) {
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTodos() {
        $sql = "SELECT id_usuario, usuario, nombres, apellidos, rol, created_at
                FROM usuarios
                WHERE rol != 'owner'
                ORDER BY nombres, apellidos";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_usuario) {
        $sql = "SELECT id_usuario, usuario, nombres, apellidos, rol, created_at
                FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeUsuario($usuario, $id_usuario_excluir = null) {
        if ($id_usuario_excluir !== null) {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ? AND id_usuario != ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$usuario, $id_usuario_excluir]);
        } else {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$usuario]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function crear($usuario, $contrasena, $nombres, $apellidos, $rol) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (usuario, contrasena, nombres, apellidos, rol, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$usuario, $hash, $nombres, $apellidos, $rol]);
        return $this->conexion->lastInsertId();
    }

    public function actualizar($id_usuario, $usuario, $nombres, $apellidos, $rol) {
        $sql = "UPDATE usuarios
                SET usuario = ?, nombres = ?, apellidos = ?, rol = ?
                WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$usuario, $nombres, $apellidos, $rol, $id_usuario]);
    }

    public function actualizarContrasena($id_usuario, $contrasena) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$hash, $id_usuario]);
    }

    public function eliminar($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }

    public function actualizarPerfil($id_usuario, $usuario, $nombres, $apellidos) {
    $sql = "UPDATE usuarios
            SET usuario = ?, nombres = ?, apellidos = ?
            WHERE id_usuario = ?";
    $stmt = $this->conexion->prepare($sql);
    return $stmt->execute([$usuario, $nombres, $apellidos, $id_usuario]);
}
}
?>