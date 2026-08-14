<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLISAV</title>
    <link rel="stylesheet" href="/includes/header.css?v=3">
</head>
<body>

    <header class="clisav-header">

        <div class="clisav-header__marca">
            <svg class="clisav-header__logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Logo CLISAV">
                <path d="M49,25 C40,10 20,3 9,9 C21,15 33,21 41,31 Z"/>
                <path d="M15,58 C18,38 35,22 58,20 C68,19 76,23 80,30 C72,28 62,29 55,34 C64,37 70,44 68,53 C64,60 54,61 46,57 C49,63 48,69 41,73 C42,69 43,65 40,62 C36,67 32,72 26,77 C28,69 27,62 21,58 C17,59 15,59 15,58 Z"/>
                <circle class="clisav-header__logo-ojo" cx="63" cy="27" r="2.2"/>
            </svg>
            <span class="clisav-header__titulo">CLISAV</span>

            <!-- Interruptor oculto: controla todo por CSS, sin JS -->
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

            <!-- Overlay invisible: al hacer clic fuera del menú, también es "label"
                 del mismo checkbox, así que lo desmarca y cierra el menú -->
            <label for="menuToggle" class="clisav-header__overlay"></label>
        </div>

    </header>

    <!-- Aquí abajo va el contenido de cada página.
         El </body> y </html> se cierran en footer.php -->