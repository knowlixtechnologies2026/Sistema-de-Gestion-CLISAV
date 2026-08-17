<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<style>
    .expediente-main {
        max-width: 1000px;
        margin: 0 auto;
        padding: 24px 20px 60px;
        font-family: "Segoe UI", Arial, sans-serif;
        color: #222;
    }

    .expediente-main h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .expediente-main h2 {
        font-size: 19px;
        margin: 30px 0 14px;
        color: #146B9E;
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

    .datos-paciente {
        background-color: #f7f0f1;
        border: 1px solid #f1c9d2;
        border-radius: 8px;
        padding: 18px 22px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px 24px;
    }
    .datos-paciente p {
        margin: 4px 0;
        font-size: 15px;
    }
    .datos-paciente strong {
        color: #146B9E;
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .sin-historial {
        text-align: center;
        padding: 30px 0;
        color: #666;
        font-size: 15px;
        background-color: #f7f7f7;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
    }

    .tabla-historial-wrapper {
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        overflow: hidden;
        overflow-x: auto;
    }

    table.tabla-historial {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    table.tabla-historial thead th {
        background-color: #ececec;
        text-align: left;
        font-weight: 700;
        padding: 10px 14px;
        border: 1px solid #d9d9d9;
        white-space: nowrap;
    }

    table.tabla-historial tbody td {
        padding: 10px 14px;
        border: 1px solid #f1c9d2;
        vertical-align: top;
    }

    table.tabla-historial tbody tr:nth-child(even) {
        background-color: #f7f0f1;
    }

    table.tabla-historial tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
</style>

<main class="expediente-main">
    <h1>Expediente de <?= htmlspecialchars($paciente['nombres'] . ' ' . $paciente['apellidos']) ?></h1>

    <a href="<?= BASE_URL ?>/pacientes" class="btn-regresar">
        <span class="icono-flecha">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </span>
        Regresar
    </a>

    <div class="datos-paciente">
        <p><strong>DUI</strong><?= htmlspecialchars($paciente['dui']) ?></p>
        <p><strong>Fecha de nacimiento</strong><?= htmlspecialchars($paciente['fecha_nacimiento']) ?></p>
        <p><strong>Sexo</strong><?= htmlspecialchars($paciente['sexo']) ?></p>
        <p><strong>Teléfono</strong><?= htmlspecialchars($paciente['telefono']) ?></p>
        <p><strong>Correo</strong><?= htmlspecialchars($paciente['correo']) ?></p>
    </div>

    <h2>Historial de consultas</h2>

    <?php if (empty($historial)): ?>
        <p class="sin-historial">Este paciente aún no tiene consultas registradas.</p>
    <?php else: ?>
    <div class="tabla-historial-wrapper">
        <table class="tabla-historial">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Médico</th>
                    <th>Motivo</th>
                    <th>Diagnóstico</th>
                    <th>Tratamiento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $registro): ?>
                <tr>
                    <td><?= htmlspecialchars($registro['fecha_llegada']) ?></td>
                    <td><?= htmlspecialchars($registro['medico_nombres'] . ' ' . $registro['medico_apellidos']) ?></td>
                    <td><?= htmlspecialchars($registro['motivo']) ?></td>
                    <td><?= htmlspecialchars($registro['diagnostico']) ?></td>
                    <td><?= htmlspecialchars($registro['tratamiento']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</main>