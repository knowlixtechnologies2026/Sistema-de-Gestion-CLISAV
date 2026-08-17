<?php
require_once __DIR__ . '/../../../includes/header.php';
$rol = $_SESSION['rol'] ?? '';
$puedeGestionar = in_array($rol, ['admin', 'owner']);
$busqueda = $_GET['buscar'] ?? '';
?>
<style>
    .pacientes-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 20px 60px;
        font-family: "Segoe UI", Arial, sans-serif;
        color: #222;
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

    .btn-registrar {
        display: inline-block;
        background-color: #1c7ed6;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        margin-bottom: 24px;
        margin-left: 12px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-registrar:hover {
        background-color: #1565bd;
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

    .form-buscar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        background-color: #f7f7f7;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 20px;
    }
    .form-buscar label {
        font-weight: 700;
        font-size: 14px;
    }
    .form-buscar input[type="text"] {
        flex: 1;
        min-width: 220px;
        padding: 9px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }
    .btn-buscar {
        background-color: #1c7ed6;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 9px 20px;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-buscar:hover {
        background-color: #1565bd;
    }
    .btn-limpiar {
        background-color: #e0e0e0;
        color: #333;
        border: none;
        border-radius: 6px;
        padding: 9px 20px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-limpiar:hover {
        background-color: #cfcfcf;
    }

    .tabla-citas-wrapper {
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        overflow: hidden;
        overflow-x: auto;
    }

    table.tabla-citas {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    table.tabla-citas thead th {
        background-color: #ececec;
        text-align: left;
        font-weight: 700;
        padding: 10px 14px;
        border: 1px solid #d9d9d9;
        white-space: nowrap;
    }

    table.tabla-citas tbody td {
        padding: 10px 14px;
        border: 1px solid #f1c9d2;
        vertical-align: middle;
    }

    table.tabla-citas tbody tr:nth-child(even) {
        background-color: #f7f0f1;
    }

    table.tabla-citas tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .sin-resultados {
        text-align: center;
        color: #888;
        font-style: italic;
    }

    .acciones-cell {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-ver {
        background-color: #4dd9a8;
        color: #0d1b2a;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }
    .btn-ver:hover {
        background-color: #38c090;
    }

    .btn-editar {
        background-color: #1c7ed6;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }
    .btn-editar:hover {
        background-color: #1565bd;
    }

    .btn-eliminar {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-eliminar:hover {
        background-color: #c0392b;
    }
</style>

<main class="pacientes-main">
    <a href="<?= BASE_URL ?>/dashboard" class="btn-regresar">
        <span class="icono-flecha">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </span>
        Regresar
    </a>

    <?php if ($puedeGestionar): ?>
    <a href="<?= BASE_URL ?>/pacientes/registrar" class="btn-registrar">
        Agregar paciente
    </a>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error_pacientes'])): ?>
        <div class="alerta-error"><?= htmlspecialchars($_SESSION['error_pacientes']) ?></div>
        <?php unset($_SESSION['error_pacientes']); ?>
    <?php endif; ?>

    <form method="GET" action="<?= BASE_URL ?>/pacientes" class="form-buscar">
        <label for="buscar">Buscar paciente</label>
        <input type="text" id="buscar" name="buscar" placeholder="Nombre, apellido o DUI"
               value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit" class="btn-buscar">Buscar</button>
        <?php if ($busqueda !== ''): ?>
            <a href="<?= BASE_URL ?>/pacientes" class="btn-limpiar">Limpiar</a>
        <?php endif; ?>
    </form>

    <div class="tabla-citas-wrapper">
        <table class="tabla-citas">
            <thead>
                <tr>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>DUI</th>
                    <th>Fecha nacimiento</th>
                    <th>Sexo</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($listaPacientes)): ?>
                <tr><td colspan="8" class="sin-resultados">No se encontraron pacientes.</td></tr>
                <?php endif; ?>
                <?php foreach ($listaPacientes as $paciente): ?>
                <tr>
                    <td><?= htmlspecialchars($paciente['nombres']) ?></td>
                    <td><?= htmlspecialchars($paciente['apellidos']) ?></td>
                    <td><?= htmlspecialchars($paciente['dui']) ?></td>
                    <td><?= htmlspecialchars($paciente['fecha_nacimiento']) ?></td>
                    <td><?= htmlspecialchars($paciente['sexo']) ?></td>
                    <td><?= htmlspecialchars($paciente['telefono']) ?></td>
                    <td><?= htmlspecialchars($paciente['correo']) ?></td>
                    <td>
                        <div class="acciones-cell">
                            <a href="<?= BASE_URL ?>/pacientes/expediente/<?= $paciente['id_paciente'] ?>" class="btn-ver">
                                Ver expediente
                            </a>
                            <?php if ($puedeGestionar): ?>
                            <a href="<?= BASE_URL ?>/pacientes/editar/<?= $paciente['id_paciente'] ?>" class="btn-editar">
                                Editar
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>/pacientes/eliminar/<?= $paciente['id_paciente'] ?>"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este paciente?');">
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