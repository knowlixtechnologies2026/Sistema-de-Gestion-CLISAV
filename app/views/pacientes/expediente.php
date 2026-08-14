<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Expediente de <?= htmlspecialchars($paciente['nombres'] . ' ' . $paciente['apellidos']) ?></h1>

    <a href="<?= BASE_URL ?>/pacientes">
        <button type="button">Regresar</button>
    </a>

    <div>
        <p><strong>DUI:</strong> <?= htmlspecialchars($paciente['dui']) ?></p>
        <p><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars($paciente['fecha_nacimiento']) ?></p>
        <p><strong>Sexo:</strong> <?= htmlspecialchars($paciente['sexo']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($paciente['telefono']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($paciente['correo']) ?></p>
    </div>

    <h2>Historial de consultas</h2>

    <?php if (empty($historial)): ?>
        <p>Este paciente aún no tiene consultas registradas.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Médico</th>
                <th>Motivo</th>
                <th>Diagnóstico</th>
                <th>Tratamiento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historial as $registro): ?>
            <tr>
                <td><?= htmlspecialchars($registro['fecha_llegada']) ?></td>
                <td><?= htmlspecialchars($registro['medico_nombres'] . ' ' . $registro['medico_apellidos']) ?></td>
                <td><?= htmlspecialchars($registro['motivo']) ?></td>
                <td><?= htmlspecialchars($registro['diagnostico']) ?></td>
                <td><?= htmlspecialchars($registro['tratamiento']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</main>