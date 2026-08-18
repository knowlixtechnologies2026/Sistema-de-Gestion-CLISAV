<?php
require_once __DIR__ . '/../../../includes/header.php';
?>
<style>
    .usuario-main {
        max-width: 720px;
        margin: 0 auto;
        padding: 32px 24px 64px;
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: #1f2937;
    }

    .usuario-main h1 {
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
        text-decoration: none;
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
        margin-bottom: 20px;
        background: #fdecec;
        color: #c0342c;
        border: 1px solid #f3b7b3;
    }

    .usuario-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 24px;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-field.full {
        grid-column: 1 / -1;
    }

    .form-field label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .form-field input,
    .form-field select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        color: #111827;
        outline: none;
        background: #ffffff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-field select {
        appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='6'><path d='M0 0l5 6 5-6z' fill='%236b7280'/></svg>");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
    }

    .form-field input:focus,
    .form-field select:focus {
        border-color: #2f8fdb;
        box-shadow: 0 0 0 3px rgba(47, 143, 219, 0.15);
    }

    .form-field small {
        font-size: 12px;
        color: #9ca3af;
    }

    .form-actions {
        grid-column: 1 / -1;
        margin-top: 8px;
    }

    .btn-save {
        background: #34d399;
        color: #064e3b;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #2bbd8a;
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="usuario-main">
    <h1>Registrar usuario</h1>

    <a href="<?= BASE_URL ?>/usuarios" class="btn-back">
        <button type="button" style="all: unset; cursor: pointer;">&larr; Regresar</button>
    </a>

    <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="usuario-card">
        <form method="POST" action="<?= BASE_URL ?>/usuarios/registrar">
            <div class="form-grid">
                <div class="form-field full">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario"
                           value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>

                <div class="form-field">
                    <label for="confirmar_contrasena">Confirmar contraseña</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
                </div>

                <div class="form-field">
                    <label for="nombres">Nombres</label>
                    <input type="text" id="nombres" name="nombres"
                           value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos"
                           value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccione...</option>
                        <option value="user" <?= (($_POST['rol'] ?? '') === 'user') ? 'selected' : '' ?>>Médico</option>
                        <option value="admin" <?= (($_POST['rol'] ?? '') === 'admin') ? 'selected' : '' ?>>Recepción</option>
                    </select>
                </div>

                <div class="form-field">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="0000-0000"
                           value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                           maxlength="9" inputmode="numeric">
                    <small>Solo si el rol es Médico</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    document.getElementById('telefono').addEventListener('input', function (e) {
        let digits = e.target.value.replace(/\D/g, '').slice(0, 8);
        e.target.value = digits.length > 4
            ? digits.slice(0, 4) + '-' + digits.slice(4)
            : digits;
    });
</script>