<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<style>
    .editar-paciente-main {
        max-width: 700px;
        margin: 0 auto;
        padding: 24px 20px 60px;
        font-family: "Segoe UI", Arial, sans-serif;
        color: #222;
    }

    .editar-paciente-main h1 {
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

    .form-paciente {
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
    .campo input[type="text"],
    .campo input[type="date"],
    .campo input[type="email"],
    .campo select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-family: inherit;
        font-size: 14px;
        box-sizing: border-box;
        background-color: #fff;
    }
    .campo input:focus,
    .campo select:focus {
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

<main class="editar-paciente-main">
    <h1>Editar paciente</h1>

    <a href="<?= BASE_URL ?>/pacientes" class="btn-regresar">
        <span class="icono-flecha">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </span>
        Regresar
    </a>

    <?php if ($error): ?>
        <div class="alerta-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/pacientes/editar/<?= $paciente['id_paciente'] ?>" class="form-paciente">
        <div class="fila-doble">
            <div class="campo">
                <label for="nombres">Nombres</label>
                <input type="text" id="nombres" name="nombres" value="<?= htmlspecialchars($paciente['nombres']) ?>" required>
            </div>

            <div class="campo">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($paciente['apellidos']) ?>" required>
            </div>
        </div>

        <div class="fila-doble">
            <div class="campo">
                <label for="dui">DUI</label>
                <input type="text" id="dui" name="dui"
                       value="<?= htmlspecialchars($paciente['dui']) ?>"
                       oninput="formatearDui(this)"
                       maxlength="10"
                       inputmode="numeric"
                       required>
            </div>

            <div class="campo">
                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($paciente['fecha_nacimiento']) ?>" required>
            </div>
        </div>

        <div class="fila-doble">
            <div class="campo">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo" required>
                    <option value="M" <?= ($paciente['sexo'] === 'M') ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= ($paciente['sexo'] === 'F') ? 'selected' : '' ?>>Femenino</option>
                </select>
            </div>

            <div class="campo">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono"
                       value="<?= htmlspecialchars($paciente['telefono']) ?>"
                       oninput="formatearTelefono(this)"
                       maxlength="9"
                       inputmode="numeric">
            </div>
        </div>

        <div class="campo">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($paciente['correo']) ?>">
        </div>

        <button type="submit" class="btn-guardar">Guardar cambios</button>
    </form>
</main>

<script>
function formatearDui(input) {
    let valor = input.value.replace(/\D/g, '');
    valor = valor.substring(0, 9);

    if (valor.length > 8) {
        valor = valor.substring(0, 8) + '-' + valor.substring(8);
    }

    input.value = valor;
}

function formatearTelefono(input) {
    let valor = input.value.replace(/\D/g, '');
    valor = valor.substring(0, 8);

    if (valor.length > 4) {
        valor = valor.substring(0, 4) + '-' + valor.substring(4);
    }

    input.value = valor;
}
</script>