<?php
require_once __DIR__ . '/../../../includes/header.php';
$hoy = date('Y-m-d');
$esMedico = ($_SESSION['rol'] ?? '') === 'user';
[$fechaActual, $horaActual] = explode(' ', $cita['fecha_hora']);
$horaActual = substr($horaActual, 0, 5);

$pacienteSeleccionado = null;
foreach ($pacientes as $p) {
    if ((string)$p['id_paciente'] === (string)$cita['id_paciente']) {
        $pacienteSeleccionado = $p;
        break;
    }
}

$pacientesJs = array_map(function ($p) {
    return ['id' => $p['id_paciente'], 'texto' => $p['nombres'] . ' ' . $p['apellidos']];
}, $pacientes);
?>
<style>
    .editar-main {
        max-width: 700px;
        margin: 0 auto;
        padding: 24px 20px 60px;
        font-family: "Segoe UI", Arial, sans-serif;
        color: #222;
    }

    .editar-main h1 {
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

    .form-editar {
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
    .campo input[type="text"],
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
    .campo select:disabled {
        background-color: #f0f0f0;
        color: #777;
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

    /* Buscador de paciente */
    .campo-buscador {
        position: relative;
    }
    .lista-sugerencias {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #ccc;
        border-top: none;
        border-radius: 0 0 6px 6px;
        max-height: 220px;
        overflow-y: auto;
        z-index: 20;
        list-style: none;
        margin: 0;
        padding: 0;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }
    .lista-sugerencias li {
        padding: 10px 12px;
        cursor: pointer;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
    }
    .lista-sugerencias li:last-child {
        border-bottom: none;
    }
    .lista-sugerencias li:hover {
        background-color: #eaf6fd;
    }
    .lista-sugerencias li.sin-resultados {
        cursor: default;
        color: #999;
    }
    .lista-sugerencias li.sin-resultados:hover {
        background-color: #ffffff;
    }
    .error-buscador {
        color: #c0392b;
        font-size: 13px;
        font-weight: 600;
        margin-top: 6px;
    }
</style>

<main class="editar-main">
    <h1>Editar cita</h1>

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

    <form method="POST" action="<?= BASE_URL ?>/citas/editar/<?= $cita['id_cita'] ?>" class="form-editar"
          onsubmit="return validarBusquedaPaciente('id_paciente', 'error_paciente');">
        <div class="campo campo-buscador">
            <label for="buscar_paciente">Paciente</label>
            <input type="text" id="buscar_paciente" autocomplete="off" required
                   placeholder="Escribe el nombre del paciente"
                   value="<?= htmlspecialchars($pacienteSeleccionado ? $pacienteSeleccionado['nombres'] . ' ' . $pacienteSeleccionado['apellidos'] : '') ?>">
            <input type="hidden" id="id_paciente" name="id_paciente"
                   value="<?= htmlspecialchars($pacienteSeleccionado['id_paciente'] ?? '') ?>">
            <ul id="lista_paciente" class="lista-sugerencias" style="display:none;"></ul>
            <p id="error_paciente" class="error-buscador" style="display:none;">Selecciona un paciente de la lista.</p>
        </div>

        <div class="campo">
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

        <div class="fila-doble">
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" min="<?= $hoy ?>"
                       value="<?= htmlspecialchars($fechaActual) ?>" required>
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" min="07:00" max="16:00"
                       value="<?= htmlspecialchars($horaActual) ?>" required>
            </div>
        </div>

        <div class="campo">
            <label for="motivo">Motivo</label>
            <textarea id="motivo" name="motivo" required><?= htmlspecialchars($cita['motivo']) ?></textarea>
        </div>

        <div class="campo">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required>
                <option value="pendiente" <?= ($cita['estado'] === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                <option value="completado" <?= ($cita['estado'] === 'completado') ? 'selected' : '' ?>>Completado</option>
                <option value="cancelada" <?= ($cita['estado'] === 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
            </select>
        </div>

        <button type="submit" class="btn-guardar">Guardar cambios</button>
    </form>
</main>

<script src="<?= BASE_URL ?>/includes/buscadorPaciente.js"></script>
<script>
    initBuscadorPaciente({
        pacientes: <?= json_encode($pacientesJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
        inputTextoId: 'buscar_paciente',
        inputOcultoId: 'id_paciente',
        listaId: 'lista_paciente'
    });
</script>