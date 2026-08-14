<?php
require_once __DIR__ . '/../../includes/header.php';
$rol = $_SESSION['rol'] ?? '';
$nombres = $_SESSION['nombres'] ?? '';
?>
<main>
    <h1>Bienvenido, <?= htmlspecialchars($nombres) ?></h1>

    <?php if (in_array($rol, ['user', 'admin', 'owner'])): ?>
        <nav>
            <ul>
                <li>
                    <a href="<?= BASE_URL ?>/citas">
                        <button type="button">Citas</button>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/pacientes">
                        <button type="button">Pacientes</button>
                    </a>
                </li>

                <?php if ($rol === 'owner'): ?>
                <li>
                    <a href="<?= BASE_URL ?>/usuarios">
                        <button type="button">Usuarios</button>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</main>