<?php
require_once __DIR__ . '/../../../includes/header.php';
$esMedico = ($_SESSION['rol'] ?? '') === 'user';

$citasPorFecha = [];
foreach ($listaCitas as $cita) {
    $fecha = substr($cita['fecha_hora'], 0, 10); 
    $citasPorFecha[$fecha][] = $cita;
}
?>
<main>
    <h1>Citas</h1>

    <a href="<?= BASE_URL ?>/dashboard">
        <button type="button">Regresar</button>
    </a>

    <?php if (!$esMedico): ?>
    <a href="<?= BASE_URL ?>/citas/registrar">
        <button type="button">Registrar nueva cita</button>
    </a>
    <?php endif; ?>

    <?php if (empty($listaCitas)): ?>
        <p><?= $esMedico ? "No tienes citas asignadas." : "No hay citas registradas." ?></p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Estado</th>
                <th>Motivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($citasPorFecha as $fecha => $citasDelDia): ?>
                <?php $totalDelDia = count($citasDelDia); ?>
                <?php foreach ($citasDelDia as $i => $cita): ?>
                <tr>
                    <?php if ($i === 0): ?>
                    <td rowspan="<?= $totalDelDia ?>">
                        <?= htmlspecialchars(date('d/m/Y', strtotime($fecha))) ?>
                    </td>
                    <?php endif; ?>

                    <td><?= htmlspecialchars(date('H:i', strtotime($cita['fecha_hora']))) ?></td>
                    <td><?= htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']) ?></td>
                    <td><?= htmlspecialchars($cita['medico_nombres'] . ' ' . $cita['medico_apellidos']) ?></td>
                    <td><?= htmlspecialchars($cita['estado']) ?></td>
                    <td><?= htmlspecialchars($cita['motivo']) ?></td>
                    <td>
                        <?php if ($esMedico): ?>
                            <?php if ($cita['estado'] === 'pendiente'): ?>
                            <a href="<?= BASE_URL ?>/citas/atender/<?= $cita['id_cita'] ?>">
                                <button type="button">Atender</button>
                            </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/citas/editar/<?= $cita['id_cita'] ?>">
                                <button type="button">Editar</button>
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>/citas/eliminar/<?= $cita['id_cita'] ?>"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta cita?');">
                                <button type="submit">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</main>