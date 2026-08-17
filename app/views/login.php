<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLISAV - Iniciar sesión</title>
    <link rel="stylesheet" href="/../../includes/loginStyles.css">
</head>
<body>

    <div class="login-wrapper">

        <!-- Panel izquierdo: marca -->
        <div class="login-marca">
            <span class="login-marca__circulo login-marca__circulo--anillo"></span>
            <span class="login-marca__circulo login-marca__circulo--2"></span>
            <span class="login-marca__circulo login-marca__circulo--3"></span>
            <span class="login-marca__circulo login-marca__circulo--4"></span>

            <div class="login-marca__contenido">
               <img class="login-marca__logo" src="/images/logo.png" alt="Logo">
                <div class="login-marca__divisor"></div>
                <div class="login-marca__texto">
                    <h1>CLISAV</h1>
                    <p>Sistema de Gestión de la Clínica Salud Vital</p>
                </div>
            </div>
        </div>

        <!-- Panel derecho: formulario -->
        <div class="login-formulario">
            <span class="login-formulario__circulo"></span>

            <div class="login-formulario__card">
                <h2>Bienvenido de nuevo</h2>
                <p>Ingresa tus credenciales para acceder al sistema</p>

                <?php if (!empty($error)): ?>
                    <div class="login-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="login-campo">
                        <label for="nombre">Usuario</label>
                        <input type="text" id="nombre" name="nombre" required autofocus>
                    </div>

                    <div class="login-campo login-campo--password">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" required>
                        <button type="button" class="login-toggle-clave" id="toggleClave" aria-label="Mostrar contraseña">
                            <svg class="icono-ojo-abierto" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="icono-ojo-cerrado" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a19.9 19.9 0 0 1 5.06-5.94M9.9 4.24A10.6 10.6 0 0 1 12 4c7 0 11 8 11 8a19.9 19.9 0 0 1-2.16 3.19M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>

                    <button type="submit" class="btn-ingresar">Ingresar</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        const botonToggle = document.getElementById('toggleClave');
        const campoClave = document.getElementById('contrasena');

        botonToggle.addEventListener('click', function () {
            const esTexto = campoClave.type === 'text';
            campoClave.type = esTexto ? 'password' : 'text';
            botonToggle.classList.toggle('activo', !esTexto);
            botonToggle.setAttribute('aria-label', esTexto ? 'Mostrar contraseña' : 'Ocultar contraseña');
        });
    </script>

</body>
</html>