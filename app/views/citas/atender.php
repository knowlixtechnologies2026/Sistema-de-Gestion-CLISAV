<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<style>
    .atender-main {
        max-width: 700px;
        margin: 0 auto;
        padding: 24px 20px 60px;
        font-family: "Segoe UI", Arial, sans-serif;
        color: #222;
    }

    .atender-main h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .btn-regresar {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: #0d1b2a;
        color: #4fa9e8;
        font-weight: 700;
        font-size: 15px;
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        cursor: pointer;
        text-decoration: none;
        margin-bottom: 24px;
    }
    .btn-regresar:hover {
        background-color: #14283a;
    }
    .btn-regresar .icono-flecha {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #16324f;
        color: #ffffff;
        flex-shrink: 0;
    }

    .alerta-error {
        background-color: #fdecea;
        border: 1px solid #e74c3c;
        color: #c0392b;
        font-weight: 600;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .info-cita {
        background-color: #f7f0f1;
        border: 1px solid #f1c9d2;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    .info-cita p {
        margin: 6px 0;
        font-size: 15px;
    }
    .info-cita strong {
        color: #146B9E;
    }

    .form-atender {
        background-color: #ffffff;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        padding: 20px;
    }

    .campo {
        margin-bottom: 18px;
    }
    .campo label {
        display: block;
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 14px;
    }
    .campo textarea {
        width: 100%;
        min-height: 110px;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-family: inherit;
        font-size: 14px;
        resize: vertical;
        box-sizing: border-box;
    }
    .campo textarea:focus {
        outline: none;
        border-color: #1C8FCC;
        box-shadow: 0 0 0 2px rgba(28, 143, 204, 0.2);
    }

    .btn-guardar {
        background-color: #4DD9A8;
        color: #0d1b2a;
        border: none;
        border-radius: 6px;
        padding: 11px 26px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
    }
    .btn-guardar:hover {
        background-color: #38c090;
    }
</style>

<main class="atender-main">
    <h1>Atender cita</h1>

    <a href="<?= BASE_URL ?>/citas" class="btn-regresar">
        <span class="icono-flecha">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </span>
        Regresar
    </a>

    <?php if (!empty($error)): ?>
        <div class="alerta-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="info-cita">
        <p><strong>Paciente:</strong> <?= htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']) ?></p>
        <p><strong>Fecha y hora:</strong> <?= htmlspecialchars($cita['fecha_hora']) ?></p>
        <p><strong>Motivo de la cita:</strong> <?= htmlspecialchars($cita['motivo']) ?></p>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/citas/atender/<?= $cita['id_cita'] ?>" class="form-atender">
        <div class="campo">
            <label for="diagnostico">Diagnóstico</label>
            <textarea id="diagnostico" name="diagnostico" required><?= htmlspecialchars($_POST['diagnostico'] ?? '') ?></textarea>
        </div>

        <div class="campo">
            <label for="tratamiento">Tratamiento</label>
            <textarea id="tratamiento" name="tratamiento" required><?= htmlspecialchars($_POST['tratamiento'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-guardar">Guardar en expediente</button>
    </form>
</main>