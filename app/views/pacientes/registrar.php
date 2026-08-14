<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Registrar paciente</h1>

    <a href="<?= BASE_URL ?>/pacientes">
        <button type="button">Regresar</button>
    </a>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/pacientes/registrar">
        <label>Nombres:
            <input type="text" name="nombres" value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>" required>
        </label><br>

        <label>Apellidos:
            <input type="text" name="apellidos" value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>" required>
        </label><br>

        <label>DUI:
            <input type="text" name="dui" placeholder="00000000-0"
                   value="<?= htmlspecialchars($_POST['dui'] ?? '') ?>"
                   oninput="formatearDui(this)"
                   maxlength="10"
                   inputmode="numeric"
                   required>
        </label><br>

        <label>Fecha de nacimiento:
            <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>" required>
        </label><br>

        <label>Sexo:
            <select name="sexo" required>
                <option value="">Seleccione...</option>
                <option value="M" <?= (($_POST['sexo'] ?? '') === 'M') ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= (($_POST['sexo'] ?? '') === 'F') ? 'selected' : '' ?>>Femenino</option>
            </select>
        </label><br>

        <label>Teléfono:
            <input type="text" name="telefono" placeholder="0000-0000"
                   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                   oninput="formatearTelefono(this)"
                   maxlength="9"
                   inputmode="numeric">
        </label><br>

        <label>Correo:
            <input type="email" name="correo" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
        </label><br>

        <button type="submit">Guardar</button>
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