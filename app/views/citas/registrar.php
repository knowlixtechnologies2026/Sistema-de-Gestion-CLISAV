<?php
require_once __DIR__ . '/../../../includes/header.php';
$hoy = date('Y-m-d');
?>
<style>
    .registrar-main {
        max-width: 700px;
        margin: 0 auto;
        padding: 24px 20px 60px;
        font-family: "Segoe UI", Arial, sans-serif;
        color: #222;
    }

    .registrar-main h1 {
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

    .form-registrar {
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
    .campo select,
    .campo input[type="date"],
    .campo input[type="time"],
    .campo textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-family: inherit;
        font-size: 14px;
        box-sizing: border-box;
        background-color: #fff;
    }
    .campo textarea {
        min-height: 90px;
        resize: vertical;
    }
    .campo select:focus,
    .campo input:focus,
    .campo textarea:focus {
        outline: none;
        border-color: #1C8FCC;
        box-shadow: 0 0 0 2px rgba(28, 143, 204, 0.2);
    }

    .fila-doble {
        display: flex;
        gap: 16px;
    }
    .fila-doble .campo {
        flex: 1;
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

<main class="registrar-main">
    <h1>Registrar cita</h1>

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

    <form method="POST" action="<?= BASE_URL ?>/citas/registrar" class="form-registrar">
        <div class="campo">
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

        <div class="campo">
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

        <div class="fila-doble">
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" min="<?= $hoy ?>"
                       value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" min="07:00" max="16:00"
                       value="<?= htmlspecialchars($_POST['hora'] ?? '') ?>" required>
            </div>
        </div>

        <div class="campo">
            <label for="motivo">Motivo</label>
            <textarea id="motivo" name="motivo" required><?= htmlspecialchars($_POST['motivo'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-guardar">Guardar cita</button>
    </form>
</main>