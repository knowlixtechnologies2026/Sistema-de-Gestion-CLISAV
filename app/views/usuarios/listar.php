<?php
require_once __DIR__ . '/../../../includes/header.php';

$etiquetasRol = ['user' => 'Médico', 'admin' => 'Recepción', 'owner' => 'Owner'];
$claseRol = ['user' => 'badge-medico', 'admin' => 'badge-recepcion', 'owner' => 'badge-owner'];
?>
<style>
    .usuarios-main {
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 24px 64px;
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: #1f2937;
    }

    .usuarios-main h1 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #111827;
    }

    .toolbar {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #10182b;
        color: #4ea9f5;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-back:hover {
        background: #1a2540;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #2f8fdb;
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #2578b9;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 20px;
        background: #fdecec;
        color: #c0342c;
        border: 1px solid #f3b7b3;
    }

    .table-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead {
        background: #f3f4f6;
    }

    th {
        text-align: left;
        padding: 14px 16px;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f2f4;
        vertical-align: middle;
        color: #1f2937;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #fafbfc;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-medico {
        background: #e6f2fd;
        color: #1f6fb0;
    }

    .badge-recepcion {
        background: #f2e9fb;
        color: #7c3aed;
    }

    .badge-owner {
        background: #fdf3d9;
        color: #a16207;
    }

    .acciones {
        display: flex;
        gap: 8px;
    }

    .acciones form {
        display: inline;
    }

    .btn-editar {
        background: #2f8fdb;
        color: #ffffff;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-editar:hover {
        background: #2578b9;
    }

    .btn-eliminar {
        background: #e5484d;
        color: #ffffff;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-eliminar:hover {
        background: #c93b40;
    }

    @media (max-width: 700px) {
        .table-card {
            overflow-x: auto;
        }
        table {
            min-width: 640px;
        }
    }
</style>

<main class="usuarios-main">
    <h1>Usuarios</h1>

    <div class="toolbar">
        <a href="<?= BASE_URL ?>/dashboard">
            <button type="button" class="btn-back" style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; background: #10182b; color: #4ea9f5; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px;">&larr; Regresar</button>
        </a>

        <a href="<?= BASE_URL ?>/usuarios/registrar">
            <button type="button" class="btn-primary" style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; background: #2f8fdb; color: #fff; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px;">Agregar usuario</button>
        </a>
    </div>

    <?php if (!empty($_SESSION['error_usuarios'])): ?>
        <div class="alert"><?= htmlspecialchars($_SESSION['error_usuarios']) ?></div>
        <?php unset($_SESSION['error_usuarios']); ?>
    <?php endif; ?>

    <div class="table-card">
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
                    <td>
                        <span class="badge <?= $claseRol[$u['rol']] ?? '' ?>">
                            <?= htmlspecialchars($etiquetasRol[$u['rol']] ?? $u['rol']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="acciones">
                            <a href="<?= BASE_URL ?>/usuarios/editar/<?= $u['id_usuario'] ?>">
                                <button type="button" class="btn-editar">Editar</button>
                            </a>
                            <?php if ((int)$u['id_usuario'] !== (int)$_SESSION['id_usuario']): ?>
                            <form method="POST" action="<?= BASE_URL ?>/usuarios/eliminar/<?= $u['id_usuario'] ?>"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                <button type="submit" class="btn-eliminar">Eliminar</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>