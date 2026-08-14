<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Mi perfil</h1>

    <a href="<?= BASE_URL ?>/dashboard">
        <button type="button">Regresar</button>
    </a>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($exito)): ?>
        <p style="color:green;"><?= htmlspecialchars($exito) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/perfil">
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

        <?php if ($esMedico): ?>
        <label>Teléfono:
            <input type="text" name="telefono" placeholder="0000-0000"
                   value="<?= htmlspecialchars($usuarioData['telefono']) ?>"
                   maxlength="9" inputmode="numeric">
        </label><br>
        <?php endif; ?>

        <button type="submit">Guardar cambios</button>
    </form>
</main>