<?php
require_once __DIR__ . '/../../../includes/header.php';

$etiquetasRol = ['user' => 'Médico', 'admin' => 'Recepción', 'owner' => 'Owner'];
?>
<main>
    <h1>Usuarios</h1>

    <a href="<?= BASE_URL ?>/dashboard">
        <button type="button">Regresar</button>
    </a>

    <a href="<?= BASE_URL ?>/usuarios/registrar">
        <button type="button">Agregar usuario</button>
    </a>

    <?php if (!empty($_SESSION['error_usuarios'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_SESSION['error_usuarios']) ?></p>
        <?php unset($_SESSION['error_usuarios']); ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaUsuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['usuario']) ?></td>
                <td><?= htmlspecialchars($u['nombres']) ?></td>
                <td><?= htmlspecialchars($u['apellidos']) ?></td>
                <td><?= htmlspecialchars($etiquetasRol[$u['rol']] ?? $u['rol']) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/usuarios/editar/<?= $u['id_usuario'] ?>">
                        <button type="button">Editar</button>
                    </a>
                    <?php if ((int)$u['id_usuario'] !== (int)$_SESSION['id_usuario']): ?>
                    <form method="POST" action="<?= BASE_URL ?>/usuarios/eliminar/<?= $u['id_usuario'] ?>"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        <button type="submit">Eliminar</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>