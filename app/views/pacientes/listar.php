<?php
require_once __DIR__ . '/../../../includes/header.php';
$rol = $_SESSION['rol'] ?? '';
$puedeGestionar = in_array($rol, ['admin', 'owner']);
$busqueda = $_GET['buscar'] ?? '';
?>
<main>
    <h1>Pacientes</h1>

    <a href="<?= BASE_URL ?>/dashboard">
        <button type="button">Regresar</button>
    </a>

    <?php if ($puedeGestionar): ?>
    <a href="<?= BASE_URL ?>/pacientes/registrar">
        <button type="button">Agregar paciente</button>
    </a>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error_pacientes'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_SESSION['error_pacientes']) ?></p>
        <?php unset($_SESSION['error_pacientes']); ?>
    <?php endif; ?>

    <form method="GET" action="<?= BASE_URL ?>/pacientes">
        <label for="buscar">Buscar paciente</label>
        <input type="text" id="buscar" name="buscar" placeholder="Nombre, apellido o DUI"
               value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit">Buscar</button>
        <?php if ($busqueda !== ''): ?>
            <a href="<?= BASE_URL ?>/pacientes"><button type="button">Limpiar</button></a>
        <?php endif; ?>
    </form>

    <table>
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
            <tr><td colspan="8">No se encontraron pacientes.</td></tr>
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
                    <a href="<?= BASE_URL ?>/pacientes/expediente/<?= $paciente['id_paciente'] ?>">
                        <button type="button">Ver expediente</button>
                    </a>
                    <?php if ($puedeGestionar): ?>
                    <a href="<?= BASE_URL ?>/pacientes/editar/<?= $paciente['id_paciente'] ?>">
                        <button type="button">Editar</button>
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>/pacientes/eliminar/<?= $paciente['id_paciente'] ?>"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este paciente?');">
                        <button type="submit">Eliminar</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>