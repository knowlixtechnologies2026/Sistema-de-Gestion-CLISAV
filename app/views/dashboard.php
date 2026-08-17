<?php
require_once __DIR__ . '/../../includes/header.php';
$rol = $_SESSION['rol'] ?? '';
$nombres = $_SESSION['nombres'] ?? '';
?>


<!-- FRONTEND DEL DASHBOARD -->
<style>
    .clisav-bienvenida {
        max-width: 900px;
        margin: 48px auto 0;
        padding: 0 24px;
        text-align: center;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .clisav-bienvenida__saludo {
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 2.1rem;
        font-weight: 700;
        color: #1A1A1A;
        margin: 0;
        line-height: 1.3;
    }

    .clisav-bienvenida__nombre {
        color: #1C8FCC;
    }

    .clisav-bienvenida__subtitulo {
        color: #6B7280;
        font-size: 0.95rem;
        margin: 12px 0 40px;
    }

    .clisav-bienvenida__divisor {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        color: #9CA3AF;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 2px;
        margin-bottom: 28px;
    }

    .clisav-bienvenida__divisor::before,
    .clisav-bienvenida__divisor::after {
        content: "";
        flex: 1;
        max-width: 90px;
        height: 1px;
        background-color: #E5E7EB;
    }

    .clisav-secciones {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 24px;
        padding-bottom: 48px;
    }

    .clisav-seccion-card {
        display: block;
        background-color: #FFFFFF;
        border: 1px solid #DCEEFA;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(28, 143, 204, 0.08);
        padding: 28px 32px;
        min-width: 220px;
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .clisav-seccion-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(28, 143, 204, 0.16);
        border-color: #1C8FCC;
    }

    .clisav-seccion-card__etiqueta {
        display: inline-block;
        background-color: #E4F2FB;
        color: #146B9E;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 999px;
        margin-bottom: 16px;
    }

    .clisav-seccion-card__titulo {
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1A1A1A;
        margin: 0 0 14px;
    }

    .clisav-seccion-card__linea {
        display: block;
        width: 32px;
        height: 2px;
        background-color: #E5E7EB;
        margin: 0 auto;
    }

    @media (max-width: 560px) {
        .clisav-bienvenida__saludo {
            font-size: 1.6rem;
        }

        .clisav-seccion-card {
            min-width: 0;
            width: 100%;
        }
    }
</style>

<main>
    <div class="clisav-bienvenida">
        <h1 class="clisav-bienvenida__saludo">
            <span id="saludoTexto">Bienvenido</span>,
            <span class="clisav-bienvenida__nombre"><?= htmlspecialchars($nombres) ?></span>.
        </h1>
        <p class="clisav-bienvenida__subtitulo">Clínica Salud Vital</p>

        <?php if (in_array($rol, ['user', 'admin', 'owner'])): ?>
            <div class="clisav-bienvenida__divisor">¿Qué deseas hacer hoy?</div>

            <div class="clisav-secciones">
                <a href="<?= BASE_URL ?>/citas" class="clisav-seccion-card">
                    <span class="clisav-seccion-card__etiqueta">Gestionar</span>
                    <h2 class="clisav-seccion-card__titulo">Citas</h2>
                    <span class="clisav-seccion-card__linea"></span>
                </a>

                <a href="<?= BASE_URL ?>/pacientes" class="clisav-seccion-card">
                    <span class="clisav-seccion-card__etiqueta">Gestionar</span>
                    <h2 class="clisav-seccion-card__titulo">Pacientes</h2>
                    <span class="clisav-seccion-card__linea"></span>
                </a>

                <?php if ($rol === 'owner'): ?>
                <a href="<?= BASE_URL ?>/usuarios" class="clisav-seccion-card">
                    <span class="clisav-seccion-card__etiqueta">Gestionar</span>
                    <h2 class="clisav-seccion-card__titulo">Usuarios</h2>
                    <span class="clisav-seccion-card__linea"></span>
                </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        (function () {
            var saludos = {
                manana: [
                    "Buenos días", "Que tengas un excelente día", "Arriba ese ánimo esta mañana",
                    "Buen inicio de día", "Que la mañana te trate bien", "Empezamos el día"
                ],
                mediodia: [
                    "Buen mediodía", "Que tengas un buen provecho", "Ya casi es hora de almorzar",
                    "¿Ya va tocando ir a comer no?", "A mitad del día", "Sigamos con buen ánimo"
                ],
                tarde: [
                    "Buenas tardes", "Que sigas teniendo una buena tarde", "Espero que tu tarde vaya bien",
                    "¿Que tal?", "Vamos a mitad de la tarde", "¿Trabajando duro o durando en el trabajo?"
                ],
                noche: [
                    "Buenas noches", "Que tengas una buena noche", "Trabajando hasta tarde",
                    "¿Un café?", "Muy buenas noches", "Ya casi termina el día"
                ]
            };

            var hora = new Date().getHours();
            var grupo;

            if (hora >= 5 && hora < 12) grupo = saludos.manana;
            else if (hora >= 12 && hora < 14) grupo = saludos.mediodia;
            else if (hora >= 14 && hora < 19) grupo = saludos.tarde;
            else grupo = saludos.noche;

            var elegido = grupo[Math.floor(Math.random() * grupo.length)];
            document.getElementById('saludoTexto').textContent = elegido;
        })();
    </script>
</main>
