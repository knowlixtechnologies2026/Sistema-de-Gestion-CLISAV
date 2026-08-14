<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Atender cita</h1>

    <a href="<?= BASE_URL ?>/citas">
        <button type="button">Regresar</button>
    </a>

    <?php if (!empty($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div>
        <p><strong>Paciente:</strong> <?= htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']) ?></p>
        <p><strong>Fecha y hora:</strong> <?= htmlspecialchars($cita['fecha_hora']) ?></p>
        <p><strong>Motivo de la cita:</strong> <?= htmlspecialchars($cita['motivo']) ?></p>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/citas/atender/<?= $cita['id_cita'] ?>">
        <div>
            <label for="diagnostico">Diagnóstico</label>
            <textarea id="diagnostico" name="diagnostico" required><?= htmlspecialchars($_POST['diagnostico'] ?? '') ?></textarea>
        </div>

        <div>
            <label for="tratamiento">Tratamiento</label>
            <textarea id="tratamiento" name="tratamiento" required><?= htmlspecialchars($_POST['tratamiento'] ?? '') ?></textarea>
        </div>

        <button type="submit">Guardar en expediente</button>
    </form>
</main>