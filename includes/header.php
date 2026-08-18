<?php
require_once __DIR__ . '/../app/models/Alertas.php';

$backupVencido = false;
$backupFecha   = null;

$alertas = new Alertas();
$backupVencido = $alertas->backupVencido();
$backupFecha   = $alertas->obtenerFechaUltimoBackup();

$esAdmin = ($_SESSION['rol'] ?? '') === 'owner';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLISAV</title>
    <link rel="icon" type="image/png" href="/images/logo2.png">
    <link rel="stylesheet" href="/includes/header.css?v=5">
</head>
<body>

    <header class="clisav-header">

        <div class="clisav-header__marca">
             <img class="clisav-header__logo" src="/images/logo.png" alt="Logo">
            <span class="clisav-header__titulo">CLISAV</span>

            <!-- Interruptor oculto (checkbox): controla todo por CSS, sin JS -->
            <input type="checkbox" id="menuToggle" class="clisav-header__toggle-input">

            <label for="menuToggle" class="clisav-header__hamburguesa" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <nav class="clisav-header__menu">
                <ul>
                    <li><a href="/dashboard">Inicio</a></li>
                    <li><a href="/perfil">Perfil</a></li>
                    <li><a href="/citas">Citas</a></li>
                    <li><a href="/pacientes">Pacientes</a></li>
                    <li><a href="/logout" class="clisav-header__cerrar-sesion">Cerrar sesión</a></li>
                </ul>
            </nav>

            <label for="menuToggle" class="clisav-header__overlay"></label>
        </div>

    </header>

    <?php if ($esAdmin && $backupVencido): ?>
    <div class="clisav-notificacion clisav-notificacion--alerta">
        <input type="checkbox" id="cerrarNotifBackup" class="clisav-notificacion__cerrar-input">
        <div class="clisav-notificacion__contenido">
            <svg class="clisav-notificacion__icono" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 3 1.6 21h20.8L12 3z"/>
                <rect x="11.1" y="9" width="1.8" height="6" rx="0.9" fill="#FFFFFF"/>
                <circle cx="12" cy="17.3" r="1.1" fill="#FFFFFF"/>
            </svg>
            <p class="clisav-notificacion__texto">
                <strong>Respaldo pendiente.</strong>
                El último backup completo fue el <?php echo date("d/m/Y", strtotime($backupFecha)); ?> — se recomienda realizar uno nuevo.
            </p>
            <button type="button" class="clisav-notificacion__accion" onclick="window.location.href='<?php echo BASE_URL; ?>/exportar'">
                Respaldar ahora
            </button>
            <label for="cerrarNotifBackup" class="clisav-notificacion__cerrar" aria-label="Cerrar aviso">&times;</label>
        </div>
    </div>
    <?php endif; ?>