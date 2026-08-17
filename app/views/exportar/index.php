<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<main>
    <h1>Exportar / Importar datos</h1>

    <a href="<?= BASE_URL ?>/dashboard">
        <button type="button">Regresar</button>
    </a>

    <?php if (!empty($_GET['exito'])): ?>
        <p style="color:green;"><?= htmlspecialchars($_GET['exito']) ?></p>
    <?php endif; ?>
    <?php if (!empty($_GET['advertencia'])): ?>
        <p style="color:orange;"><?= htmlspecialchars($_GET['advertencia']) ?></p>
    <?php endif; ?>
    <?php if (!empty($_GET['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <h2>Exportar</h2>
    <ul>
        <li>
            <a href="<?= BASE_URL ?>/exportar/tabla/pacientes">
                <button type="button">Exportar pacientes</button>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/exportar/tabla/citas">
                <button type="button">Exportar citas</button>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/exportar/tabla/expedientes">
                <button type="button">Exportar expedientes</button>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/exportar/tabla/todas">
                <button type="button">Exportar todo (backup completo)</button>
            </a>
        </li>
    </ul>

    <h2>Importar</h2>
    <form method="POST" action="<?= BASE_URL ?>/exportar/importar" enctype="multipart/form-data"
          onsubmit="return confirm('Esto reemplazará los datos existentes de las tablas incluidas en el archivo. ¿Deseas continuar?');">
        <label for="archivo">Archivo .sql</label>
        <input type="file" id="archivo" name="archivo" accept=".sql" required>
        <button type="submit">Importar</button>
    </form>
</main>