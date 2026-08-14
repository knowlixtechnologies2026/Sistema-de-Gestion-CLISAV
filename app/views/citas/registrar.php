<?php
require_once __DIR__ . '/../../../includes/header.php';
$hoy = date('Y-m-d');
?>
<main>
    <h1>Registrar cita</h1>

    <a href="<?= BASE_URL ?>/citas">
        <button type="button">Regresar</button>
    </a>

    <?php if (!empty($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/citas/registrar">
        <div>
            <label for="id_paciente">Paciente</label>
            <select id="id_paciente" name="id_paciente" required>
                <option value="">--Seleccione una opcion--</option>
                <?php foreach ($pacientes as $p): ?>
                    <option value="<?= $p['id_paciente'] ?>"
                        <?= (($_POST['id_paciente'] ?? '') == $p['id_paciente']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="id_medico">Médico</label>
            <select id="id_medico" name="id_medico" required>
                <option value="">--Seleccione una opcion--</option>
                <?php foreach ($medicos as $m): ?>
                    <option value="<?= $m['id_medico'] ?>"
                        <?= (($_POST['id_medico'] ?? '') == $m['id_medico']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nombres'] . ' ' . $m['apellidos']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" min="<?= $hoy ?>"
                   value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>" required>
        </div>

        <div>
            <label for="hora">Hora</label>
            <input type="time" id="hora" name="hora" min="07:00" max="16:00"
                   value="<?= htmlspecialchars($_POST['hora'] ?? '') ?>" required>
        </div>

        <div>
            <label for="motivo">Motivo</label>
            <textarea id="motivo" name="motivo" required><?= htmlspecialchars($_POST['motivo'] ?? '') ?></textarea>
        </div>

        <button type="submit">Guardar cita</button>
    </form>
</main>