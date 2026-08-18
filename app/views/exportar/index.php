<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<style>
    .backup-main {
        max-width: 900px;
        margin: 0 auto;
        padding: 32px 24px 64px;
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: #1f2937;
    }

    .backup-main h1 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #111827;
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
        margin-bottom: 24px;
    }

    .btn-back:hover {
        background: #1a2540;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .alert-success {
        background: #e7f9f1;
        color: #147a52;
        border: 1px solid #a9e6cb;
    }

    .alert-warning {
        background: #fdf3d9;
        color: #92600a;
        border: 1px solid #f0d493;
    }

    .alert-error {
        background: #fdecec;
        color: #c0342c;
        border: 1px solid #f3b7b3;
    }

    .backup-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
    }

    .backup-card p {
        font-size: 14px;
        color: #4b5563;
        margin: 0 0 16px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-action-primary {
        background: #2f8fdb;
        color: #ffffff;
    }

    .btn-action-primary:hover {
        background: #2578b9;
    }

    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn-secondary {
        background: #eef2ff;
        color: #1f6fb0;
        border: 1px solid #dbe6f7;
    }

    .btn-secondary:hover {
        background: #e2eaf9;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }

    .file-label {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
    }

    .file-label:hover {
        background: #e9eaec;
    }

    .file-label input[type="file"] {
        display: none;
    }

    .file-name {
        font-size: 13px;
        color: #6b7280;
    }

    .btn-import {
        background: #34d399;
        color: #064e3b;
    }

    .btn-import:hover {
        background: #2bbd8a;
    }
</style>

<main class="backup-main">
    <h1>Exportar / Importar datos</h1>

    <a href="<?= BASE_URL ?>/dashboard">
        <button type="button" class="btn-back" style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; background: #10182b; color: #4ea9f5; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; margin-bottom: 24px;">&larr; Regresar</button>
    </a>

    <?php if (!empty($_GET['exito'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['exito']) ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET['advertencia'])): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($_GET['advertencia']) ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="backup-card">
        <p>Descarga un archivo <strong>.sql</strong> con los datos actuales de la tabla que elijas.</p>
        <div class="btn-group">
            <a href="<?= BASE_URL ?>/exportar/tabla/pacientes" class="btn-action btn-secondary">Exportar pacientes</a>
            <a href="<?= BASE_URL ?>/exportar/tabla/citas" class="btn-action btn-secondary">Exportar citas</a>
            <a href="<?= BASE_URL ?>/exportar/tabla/expedientes" class="btn-action btn-secondary">Exportar expedientes</a>
            <a href="<?= BASE_URL ?>/exportar/tabla/todas" class="btn-action btn-action-primary">Exportar todo (backup completo)</a>
        </div>
    </div>

    <div class="backup-card">
        <p>Sube un archivo <strong>.sql</strong> generado por este sistema para importar registros.</p>
        <form method="POST" action="<?= BASE_URL ?>/exportar/importar" enctype="multipart/form-data"
              onsubmit="return confirm('Esto reemplazará los datos existentes de las tablas incluidas en el archivo. ¿Deseas continuar?');">
            <div class="form-row">
                <label class="file-label" for="archivo">
                    Seleccionar archivo .sql
                    <input type="file" id="archivo" name="archivo" accept=".sql" required
                           onchange="document.getElementById('archivo-nombre').textContent = this.files[0]?.name || 'Ningún archivo seleccionado';">
                </label>
                <span class="file-name" id="archivo-nombre">Ningún archivo seleccionado</span>
                <button type="submit" class="btn-action btn-import">Importar</button>
            </div>
        </form>
    </div>
</main>