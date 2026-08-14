<?php
require_once __DIR__ . '/../../../includes/header.php';
$hoy = date('Y-m-d');
$esMedico = ($_SESSION['rol'] ?? '') === 'user';
[$fechaActual, $horaActual] = explode(' ', $cita['fecha_hora']);
$horaActual = substr($horaActual, 0, 5);
?>
<main>
    <h1>Editar cita</h1>

    <a href="<?= BASE_URL ?>/citas">
        <button type="button">Regresar</button>
    </a>

    <?php if (!empty($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/citas/editar/<?= $cita['id_cita'] ?>">
        <div>
            <label for="id_paciente">Paciente</label>
            <select id="id_paciente" name="id_paciente" required>
                <?php foreach ($pacientes as $p): ?>
                    <option value="<?= $p['id_paciente'] ?>"
                        <?= ($cita['id_paciente'] == $p['id_paciente']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="id_medico">Médico</label>
            <?php if ($esMedico): ?>
                <select id="id_medico" disabled>
                    <?php foreach ($medicos as $m): ?>
                        <?php if ($m['id_medico'] == $cita['id_medico']): ?>
                            <option selected><?= htmlspecialchars($m['nombres'] . ' ' . $m['apellidos']) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="id_medico" value="<?= $cita['id_medico'] ?>">
            <?php else: ?>
                <select id="id_medico" name="id_medico" required>
                    <?php foreach ($medicos as $m): ?>
                        <option value="<?= $m['id_medico'] ?>"
                            <?= ($cita['id_medico'] == $m['id_medico']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['nombres'] . ' ' . $m['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>

        <div>
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" min="<?= $hoy ?>"
                   value="<?= htmlspecialchars($fechaActual) ?>" required>
        </div>

        <div>
            <label for="hora">Hora</label>
            <input type="time" id="hora" name="hora" min="07:00" max="16:00"
                   value="<?= htmlspecialchars($horaActual) ?>" required>
        </div>

        <div>
            <label for="motivo">Motivo</label>
            <textarea id="motivo" name="motivo" required><?= htmlspecialchars($cita['motivo']) ?></textarea>
        </div>

        <div>
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required>
                <option value="pendiente" <?= ($cita['estado'] === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                <option value="completado" <?= ($cita['estado'] === 'completado') ? 'selected' : '' ?>>Completado</option>
                <option value="cancelada" <?= ($cita['estado'] === 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
            </select>
        </div>

        <button type="submit">Guardar cambios</button>
    </form>
</main>