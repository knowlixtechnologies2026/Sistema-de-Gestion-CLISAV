<?php
require_once __DIR__ . '/../../config/conexion.php';

class Pacientes {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar();
    }

    public function obtenerTodos($busqueda = '') {
        if ($busqueda !== '') {
            $termino = '%' . $busqueda . '%';
            $sql = "SELECT * FROM pacientes
                    WHERE nombres LIKE :b1 OR apellidos LIKE :b2 OR dui LIKE :b3
                    ORDER BY nombres, apellidos";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':b1' => $termino, ':b2' => $termino, ':b3' => $termino]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql = "SELECT * FROM pacientes ORDER BY nombres, apellidos";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_paciente) {
        $stmt = $this->pdo->prepare("SELECT * FROM pacientes WHERE id_paciente = :id_paciente");
        $stmt->execute([':id_paciente' => $id_paciente]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombres, $apellidos, $dui, $fecha_nacimiento, $sexo, $telefono, $correo) {
        $sql = "INSERT INTO pacientes (nombres, apellidos, dui, fecha_nacimiento, sexo, telefono, correo)
                VALUES (:nombres, :apellidos, :dui, :fecha_nacimiento, :sexo, :telefono, :correo)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombres'          => $nombres,
            ':apellidos'        => $apellidos,
            ':dui'              => $dui,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':sexo'             => $sexo,
            ':telefono'         => $telefono,
            ':correo'           => $correo,
        ]);
    }

    public function actualizar($id_paciente, $nombres, $apellidos, $dui, $fecha_nacimiento, $sexo, $telefono, $correo) {
        $sql = "UPDATE pacientes
                SET nombres = :nombres,
                    apellidos = :apellidos,
                    dui = :dui,
                    fecha_nacimiento = :fecha_nacimiento,
                    sexo = :sexo,
                    telefono = :telefono,
                    correo = :correo
                WHERE id_paciente = :id_paciente";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombres'          => $nombres,
            ':apellidos'        => $apellidos,
            ':dui'              => $dui,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':sexo'             => $sexo,
            ':telefono'         => $telefono,
            ':correo'           => $correo,
            ':id_paciente'      => $id_paciente,
        ]);
    }

    public function eliminar($id_paciente) {
        $stmt = $this->pdo->prepare("DELETE FROM pacientes WHERE id_paciente = :id_paciente");
        return $stmt->execute([':id_paciente' => $id_paciente]);
    }

    public function existeDui($dui, $id_paciente_excluir = null) {
        $sql = "SELECT COUNT(*) FROM pacientes WHERE dui = :dui";
        $params = [':dui' => $dui];

        if ($id_paciente_excluir !== null) {
            $sql .= " AND id_paciente != :id_paciente_excluir";
            $params[':id_paciente_excluir'] = $id_paciente_excluir;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function tieneCitasOExpedientes($id_paciente) {
        $stmt = $this->pdo->prepare(
            "SELECT
                (SELECT COUNT(*) FROM citas WHERE id_paciente = :id1) +
                (SELECT COUNT(*) FROM expedientes WHERE id_paciente = :id2) AS total"
        );
        $stmt->execute([':id1' => $id_paciente, ':id2' => $id_paciente]);
        return $stmt->fetchColumn() > 0;
    }
}