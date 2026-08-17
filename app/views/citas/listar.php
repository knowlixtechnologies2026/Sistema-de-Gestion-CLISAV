<?php
require_once __DIR__ . '/../../../includes/header.php';
$esMedico = ($_SESSION['rol'] ?? '') === 'user';

$citasPorFecha = [];
foreach ($listaCitas as $cita) {
    $fecha = substr($cita['fecha_hora'], 0, 10);
    $citasPorFecha[$fecha][] = $cita;
}

// Mapeo de estado -> etiqueta y clase css
$estadoInfo = [
    'pendiente'  => ['label' => 'Pendiente',  'class' => 'estado-pendiente'],
    'confirmada' => ['label' => 'Confirmada', 'class' => 'estado-confirmada'],
    'cancelada'  => ['label' => 'Cancelada',  'class' => 'estado-cancelada'],
];
?>
<style>
    .citas-main {
        max-width: 1100px;
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

    .tabla-citas-wrapper {
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        overflow: hidden;
    }

    .tabla-citas-titulo {
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        padding: 14px 0;
        background-color: #ffffff;
        border-bottom: 1px solid #d9d9d9;
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

    .estado-badge {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        width: 118px;
        font-weight: 600;
    }
    .estado-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }
    .estado-pendiente .estado-dot  { background-color: #f4d03f; }
    .estado-confirmada .estado-dot { background-color: #2ecc71; }
    .estado-cancelada .estado-dot  { background-color: #e74c3c; }

    .btn-atender {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #f2c14e;
        border: 1px solid #d4a92a;
        border-radius: 4px;
        padding: 6px 14px;
        font-weight: 700;
        color: #33270a;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }
    .btn-atender:hover {
        background-color: #e0b03c;
    }
    .btn-atender svg {
        flex-shrink: 0;
    }

    .sin-accion {
        color: #888;
        font-weight: 700;
        display: inline-block;
        text-align: center;
        width: 100%;
    }

    .acciones-cell {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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
    .btn-eliminar:hover { background-color: #c0392b; }
    .btn-editar:hover   { background-color: #1565bd; }

    .sin-citas {
        text-align: center;
        padding: 40px 0;
        color: #666;
        font-size: 16px;
    }
</style>

<main class="citas-main">
    <div>
        <a href="<?= BASE_URL ?>/dashboard" class="btn-regresar">
            <span class="icono-flecha">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </span>
            Regresar
        </a>

        <?php if (!$esMedico): ?>
        <a href="<?= BASE_URL ?>/citas/registrar" class="btn-registrar">
            Registrar nueva cita
        </a>
        <?php endif; ?>
    </div>

    <?php if (empty($listaCitas)): ?>
        <p class="sin-citas"><?= $esMedico ? "No tienes citas asignadas." : "No hay citas registradas." ?></p>
    <?php else: ?>
    <div class="tabla-citas-wrapper">
        <div class="tabla-citas-titulo">CITAS</div>
        <table class="tabla-citas">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Motivo</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citasPorFecha as $fecha => $citasDelDia): ?>
                    <?php $totalDelDia = count($citasDelDia); ?>
                    <?php foreach ($citasDelDia as $i => $cita): ?>
                        <?php
                            $estadoKey = $cita['estado'];
                            $info = $estadoInfo[$estadoKey] ?? ['label' => ucfirst($estadoKey), 'class' => ''];
                        ?>
                        <tr>
                            <?php if ($i === 0): ?>
                            <td rowspan="<?= $totalDelDia ?>">
                                <?= htmlspecialchars(date('d/m/Y', strtotime($fecha))) ?>
                            </td>
                            <?php endif; ?>

                            <td><?= htmlspecialchars(date('H:i', strtotime($cita['fecha_hora']))) ?></td>
                            <td><?= htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']) ?></td>
                            <td><?= htmlspecialchars($cita['medico_nombres'] . ' ' . $cita['medico_apellidos']) ?></td>
                            <td><?= htmlspecialchars($cita['motivo']) ?></td>
                            <td>
                                <span class="estado-badge <?= $info['class'] ?>">
                                    <?= htmlspecialchars($info['label']) ?>
                                    <span class="estado-dot"></span>
                                </span>
                            </td>
                            <td>
                                <?php if ($esMedico): ?>
                                    <?php if ($estadoKey === 'pendiente'): ?>
                                    <a href="<?= BASE_URL ?>/citas/atender/<?= $cita['id_cita'] ?>" class="btn-atender">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                        </svg>
                                        Atender
                                    </a>
                                    <?php else: ?>
                                    <span class="sin-accion">&ndash;</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="acciones-cell">
                                        <a href="<?= BASE_URL ?>/citas/editar/<?= $cita['id_cita'] ?>" class="btn-editar">
                                            Editar
                                        </a>
                                        <form method="POST" action="<?= BASE_URL ?>/citas/eliminar/<?= $cita['id_cita'] ?>"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar esta cita?');">
                                            <button type="submit" class="btn-eliminar">Eliminar</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</main>