<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Editar paciente</h1>

    <a href="<?= BASE_URL ?>/pacientes">
        <button type="button">Regresar</button>
    </a>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/pacientes/editar/<?= $paciente['id_paciente'] ?>">
        <label>Nombres:
            <input type="text" name="nombres" value="<?= htmlspecialchars($paciente['nombres']) ?>" required>
        </label><br>

        <label>Apellidos:
            <input type="text" name="apellidos" value="<?= htmlspecialchars($paciente['apellidos']) ?>" required>
        </label><br>

        <label>DUI:
            <input type="text" name="dui"
                   value="<?= htmlspecialchars($paciente['dui']) ?>"
                   oninput="formatearDui(this)"
                   maxlength="10"
                   inputmode="numeric"
                   required>
        </label><br>

        <label>Fecha de nacimiento:
            <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($paciente['fecha_nacimiento']) ?>" required>
        </label><br>

        <label>Sexo:
            <select name="sexo" required>
                <option value="M" <?= ($paciente['sexo'] === 'M') ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= ($paciente['sexo'] === 'F') ? 'selected' : '' ?>>Femenino</option>
            </select>
        </label><br>

        <label>Teléfono:
            <input type="text" name="telefono"
                   value="<?= htmlspecialchars($paciente['telefono']) ?>"
                   oninput="formatearTelefono(this)"
                   maxlength="9"
                   inputmode="numeric">
        </label><br>

        <label>Correo:
            <input type="email" name="correo" value="<?= htmlspecialchars($paciente['correo']) ?>">
        </label><br>

        <button type="submit">Guardar cambios</button>
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