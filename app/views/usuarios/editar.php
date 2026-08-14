<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Editar usuario</h1>

    <a href="<?= BASE_URL ?>/usuarios">
        <button type="button">Regresar</button>
    </a>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/usuarios/editar/<?= $usuarioData['id_usuario'] ?>">
        <label>Usuario:
            <input type="text" name="usuario" value="<?= htmlspecialchars($usuarioData['usuario']) ?>" required>
        </label><br>

        <label>Nueva contraseña (dejar en blanco para no cambiarla):
            <input type="password" name="contrasena">
        </label><br>

        <label>Confirmar nueva contraseña:
            <input type="password" name="confirmar_contrasena">
        </label><br>

        <label>Nombres:
            <input type="text" name="nombres" value="<?= htmlspecialchars($usuarioData['nombres']) ?>" required>
        </label><br>

        <label>Apellidos:
            <input type="text" name="apellidos" value="<?= htmlspecialchars($usuarioData['apellidos']) ?>" required>
        </label><br>

        <label>Rol:
            <select name="rol" required>
                <option value="user" <?= ($usuarioData['rol'] === 'user') ? 'selected' : '' ?>>Médico</option>
                <option value="admin" <?= ($usuarioData['rol'] === 'admin') ? 'selected' : '' ?>>Recepción</option>
            </select>
        </label><br>

        <label>Teléfono (solo si el rol es Médico):
            <input type="text" name="telefono" placeholder="0000-0000"
                   value="<?= htmlspecialchars($usuarioData['telefono']) ?>"
                   maxlength="9" inputmode="numeric">
        </label><br>

        <button type="submit">Guardar cambios</button>
    </form>
</main>