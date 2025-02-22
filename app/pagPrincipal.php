<?php
session_start();
require 'config.php'; 
// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prácticas Preprofesionales</title>
    <link rel="stylesheet" href="/css/pagPrincipal.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="icon" href="/image/a.png" type="image/png">
</head>

<body class="pag-principal">

    <!-- Encabezado -->
    <header class="header">
    <?php
                // Función para obtener el nombre completo del usuario
                function obtenerNombreCompleto($conexion) {
                    if (!isset($_SESSION['usuario']['id'])) {
                        return 'ID de usuario no disponible en la sesión';
                    }

                    $usuario_id = $_SESSION['usuario']['id'];

                    $query = "SELECT nombres, apellidos FROM usuarios WHERE id = $usuario_id";
                    $resultado = $conexion->query($query);

                    if (!$resultado) {
                        return 'Error en la consulta SQL: ' . $conexion->error;
                    }

                    if ($row = $resultado->fetch_assoc()) {
                        return $row['nombres'] . ' ' . $row['apellidos'];
                    } else {
                        return 'Usuario no encontrado en la base de datos';
                    }
                }
            ?>

        <a href="#" class="logo">Practicas</a>

        <div class="bx bx-menu" id="menu-icon"></div>

        <nav class="navbar">
            <a href="#inicio" class="active">Inicio</a>
            <a href="#sobre">Sobre</a>
            <a href="#convenios">Convenios</a>
            <a href="#inscripcion">Inscríbete</a>
            <?php $usuario = $_SESSION['usuario']; ?>
            <a href="modificar.php?correo=<?php echo $usuario["email"]; ?>">
            <span class="nombre-usuario">
                    <?php echo obtenerNombreCompleto($conn); ?>
                </span>
            </a>
            <a href="../../login.php"><i class='bx bx-exit'></i></a>

        </nav>

    </header>

    <section class="inicio" id="inicio">
        <div class="inicio-content">
            <h1>Prácticas <br><span>Pre-Profesionales</span></h1>
            <div class="text-secund">
                <h3>Explora Oportunidades</h3>
            </div>
            <p>Bienvenido a nuestra plataforma dedicada a la gestión de 
                prácticas pre-profesionales, donde la excelencia académica 
                se encuentra con oportunidades laborales significativas. 
                Nos enorgullece ser el puente que conecta a estudiantes 
                talentosos con empresas e instituciones que buscan fomentar 
                el crecimiento profesional.
            </p>
        </div>
        <img src="/image/pagPrincipal.png" alt="Imagen de fondo" class="inicio-img">
    </section>
    
    <!--SECTION SOBRE-->
    <section class="sobre" id="sobre">
        <h2 class="heading">¿Quiénes <span>Somos?</span></h2>

        <div class="sobre-img">
            <img src="/image/sobre.jpg" alt="">
        </div>

        <div class="sobre-content">
            <h3>Conectamos Talento con Oportunidades</h3>

            <p>Somos una plataforma comprometida en simplificar el encuentro entre 
                estudiantes talentosos y oportunidades laborales significativas. 
                Nuestra misión consiste en erigir conexiones sólidas que unan el 
                potencial de los futuros profesionales con empresas e instituciones 
                comprometidas con su desarrollo y crecimiento.
            </p>
        </div>
    </section>

    <!--SECTION CONVENIOS-->
    <section class="convenio" id="convenios">
        <h2 class="heading">Convenios</h2> 

        <div class="convenio-container">
            <div class="convenio-caja">
                <i class='bx bxs-business'></i>
                <h3>GAD DAULE</h3>
                <p>Únete al dinámico programa de prácticas en GAD Daule. 
                    Contribuirás a proyectos, aprenderás de profesionales 
                    experimentados y enfrentarás desafíos diarios. Explora 
                    diversas facetas del entorno laboral y haz crecer tu carrera 
                    con nosotros. </p>
            </div>

            <div class="convenio-caja">
                <i class='bx bxs-business'></i>
                <h3>GAD LAUREL</h3>
                <p>Intégrate al innovador programa de prácticas en GAD Laurel. 
                    Aporta a proyectos, adquiere conocimientos de profesionales 
                    experimentados y afronta desafíos cotidianos. Descubre múltiples 
                    aspectos del entorno laboral y avanza en tu carrera con nosotros.</p>
            </div>

            <div class="convenio-caja">
                <i class='bx bxs-business'></i>
                <h3>Fundacion Nikols</h3>
                <p>La Fundación Nikols, comprometida con el cambio positivo, impulsa 
                    iniciativas innovadoras para mejorar comunidades. Nuestro enfoque 
                    se centra en la educación, el bienestar y el desarrollo sostenible. 
                    Únete a nosotros en la construcción de un futuro mejor para todos.</p>
            </div>
        </div>
    </section>

    <!--SECTION INSCRIPCION-->
    <section class="inscripcion" id="inscripcion">


        <h2 class="heading">Inscríbete</h2>
        <?php if(isset($_SESSION["mensaje_confirmacion"])): ?>
            <div class="mensaje-confirmacion">
            <?php echo $_SESSION["mensaje_confirmacion"]; ?>
            </div>

            <?php unset($_SESSION["mensaje_confirmacion"]); ?>

        <?php endif; ?>

        <?php if(isset($_SESSION["solicitud_denegada"])): ?>
            <div class="mensaje-denegado">
            <?php echo $_SESSION["solicitud_denegada"]; ?>

            <?php unset($_SESSION["solicitud_denegada"]); ?> 
            
            </div>
        <?php endif; ?>
        <form action="inscripcion.php" method="post">
            <!-- Campos del formulario existentes -->
            <div class="form-group">
                <label for="carrera">Carrera</label>
                <select name="carrera" id="carrera" required>
                    <option value="TDS">TDS</option>
                    <option value="TEMEC">TEMEC</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nivel">Nivel</label>
                <select name="nivel" id="nivel">
                    <option value="4to nivel">4to nivel</option>
                    <option value="5to nivel">5to nivel</option>
                    <option value="Egresado">Egresado</option>
                </select>
            </div>

            <div class="input-box">
                <input type="submit" value="Inscribirse" class="btn btn-primary" name="submit_inscripcion" id="submitButton">
                <input type="hidden" name="estado" value="pendiente">
            </div>
        </form>

        <div id="chatContainer">
            <img src="../image/chat box.gif" alt="Icono del Chatbot" id="chatIcon" onclick="toggleChat()" wordwrap = break-word>
            <div id="chatContentContainer">
                <?php include "chat-bot.php"; ?>
            </div>
        </div>

        <script>
            var chatContentContainer = document.getElementById('chatContentContainer');
            var chatIcon = document.getElementById('chatIcon');

            function showChatContent() {
                chatContentContainer.style.display = 'block';
            }

            var chatExpanded = false;

            function toggleChat() {
                var chatContentContainer = document.getElementById('chatContentContainer');
                if (chatContentContainer.style.display === 'none') {
                    chatContentContainer.style.display = 'block';
                    chatExpanded = true;
                } else {
                    chatContentContainer.style.display = 'none';
                    chatExpanded = false;
                }
            }

            document.getElementById('chatIcon').addEventListener('mouseover', function() {
                if (chatContentContainer.style.display === 'none') {
                    chatContentContainer.style.display = 'block';
                    chatExpanded = true;
                }
            });

            document.getElementById('chatContainer').addEventListener('mouseout', function() {
                if (!chatExpanded) {
                    chatContentContainer.style.display = 'none';
                }
            });

        </script>
        
    </section>

    <footer class="footer">
        <div class="footer-text">
            <p>Copyright &copy; 2023 - 4to A TDS | Todos los derechos Reservados.</p>
        </div>
        <div class="footer-iconoFlecha">
            <a href="#"><i class='bx bx-up-arrow-alt'></i></a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.convenio-container').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 4000, 
                arrows: false, 
                dots: true 
            });
        });
    </script>

        <script src = "/js/script.js"></script>
</body>
</html>