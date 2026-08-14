<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Registrar usuario</h1>

    <a href="<?= BASE_URL ?>/usuarios">
        <button type="button">Regresar</button>
    </a>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/usuarios/registrar">
        <label>Usuario:
            <input type="text" name="usuario" value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>" required>
        </label><br>

        <label>Contraseña:
            <input type="password" name="contrasena" required>
        </label><br>

        <label>Confirmar contraseña:
            <input type="password" name="confirmar_contrasena" required>
        </label><br>

        <label>Nombres:
            <input type="text" name="nombres" value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>" required>
        </label><br>

        <label>Apellidos:
            <input type="text" name="apellidos" value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>" required>
        </label><br>

        <label>Rol:
            <select name="rol" required>
                <option value="">Seleccione...</option>
                <option value="user" <?= (($_POST['rol'] ?? '') === 'user') ? 'selected' : '' ?>>Médico</option>
                <option value="admin" <?= (($_POST['rol'] ?? '') === 'admin') ? 'selected' : '' ?>>Recepción</option>
            </select>
        </label><br>

        <label>Teléfono (solo si el rol es Médico):
            <input type="text" name="telefono" placeholder="0000-0000"
                   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                   maxlength="9" inputmode="numeric">
        </label><br>

        <button type="submit">Guardar</button>
    </form>
</main>