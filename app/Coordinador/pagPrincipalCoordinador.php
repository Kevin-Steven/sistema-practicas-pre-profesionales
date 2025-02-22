<?php
session_start();
require '../config.php'; 
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

        <a href="#" class="logo">Coordinador</a>

        <div class="bx bx-menu" id="menu-icon"></div>

        <nav class="navbar">
            <a href="#inicio" class="active">Inicio</a>
            <a href="#asignarFechas">Asignar Fechas</a>
            <a href="#visualizar">Visualizar Fechas</a>
            <?php $usuario = $_SESSION['usuario']; ?>
            <a href="modificar_coordinador.php?correo=<?php echo $usuario["email"]; ?>">
            <span class="nombre-usuario">
                    <?php echo obtenerNombreCompleto($conn); ?>
                </span>
            </a>
            <a href="../../login.php"><i class='bx bx-exit'></i></a>
            

        </nav>

    </header>

    <section class="inicio" id="inicio">
        <div class="inicio-content">
            <h1>Coordinación de Prácticas <span>Profesionales</span></h1>
            <div class="text-secund">
                <h3>Asignación de Fechas</h3>
            </div>
            <p>Bienvenido al área de coordinación de prácticas pre-profesionales, 
            donde se lleva a cabo la gestión de fechas para las experiencias 
            profesionales de nuestros estudiantes. Aquí, la excelencia académica 
            se encuentra con oportunidades laborales significativas. Nos enorgullece 
            ser el enlace que conecta a estudiantes talentosos con empresas e 
            instituciones que buscan fomentar el crecimiento profesional, 
            asignando cuidadosamente las fechas de inicio y finalización de las 
            prácticas.
            </p>
        </div>
        <img src="/image/calendario.png" alt="Imagen de fondo" class="inicio-img">
    </section>

   
<!--SECTION INSCRIPCION-->
<section class="asignarFechas" id="asignarFechas">
        <h2 class="heading">Asignar Fechas</h2>

        <?php
            // Realizar la consulta SQL para obtener las entidades
            $sqlEntidades = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre_entidad FROM usuarios WHERE rol = 'entidad'";
            $resultEntidades = $conn->query($sqlEntidades);
        ?>

        <form action="procesar_asignar_fechas.php" method="post">
        <div class="input-box">
            <label for="fecha_inicial">Fecha de Inicialización:</label>
            <input type="date" id="fecha_inicial" name="fecha_inicial" required>
        </div>

        <div class="input-box">
            <label for="fecha_final">Fecha de Finalización:</label>
            <input type="date" id="fecha_final" name="fecha_final" required>
        </div>

        <div class="input-box">
            <label for="entidad">Seleccionar Entidad:</label>
            <select id="entidad" name="entidad" required>
            <?php
                // Mostrar opciones del select con entidades
                if ($resultEntidades->num_rows > 0) {
                    while ($rowEntidad = $resultEntidades->fetch_assoc()) {
                        echo '<option value="' . $rowEntidad['id'] . '">' . $rowEntidad['nombre_entidad'] . '</option>';
                    }
                } else {
                    echo '<option value="" disabled selected>No hay entidades disponibles</option>';
                }
                ?>
            </select>
        </div>

        <div class="btn-box">
            <button class="btn" type="submit" name="submit">Asignar Fechas</button>
        </div>
    </form>
        
</section>



<!-- SECCION PARA VISUALIZAR LAS FECHAS ESTABLECIDAS -->
<section class="visualizacion" id="visualizar">
    <h2 class="heading">Fechas Establecidas para las Entidades</h2>

    <?php
    // Realizar la consulta SQL para obtener las fechas establecidas por cada entidad
    $sqlFechasEstablecidas = "SELECT CONCAT(u.nombres, ' ', u.apellidos) AS nombre_entidad, fecha_inicial, fecha_final
                             FROM fechas_asignadas fe
                             INNER JOIN registrar_usuarios u ON fe.entidad_id = u.id";

    $resultFechasEstablecidas = $conn->query($sqlFechasEstablecidas);
    ?>

    <div class="table-container">
        <table class="table">
            <thead class="sticky-header">
                <tr>
                    <th>Entidad</th>
                    <th>Inicialización de Prácticas</th>
                    <th>Finalización de Prácticas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar filas de la tabla con las fechas establecidas por cada entidad
                if ($resultFechasEstablecidas->num_rows > 0) {
                    while ($rowFechaEstablecida = $resultFechasEstablecidas->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $rowFechaEstablecida['nombre_entidad'] . '</td>';
                        echo '<td>' . $rowFechaEstablecida['fecha_inicial'] . '</td>';
                        echo '<td>' . $rowFechaEstablecida['fecha_final'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No hay fechas establecidas para las entidades</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>



    <footer class="footer" id="foot2">
        <div class="footer-text">
            <p>Copyright &copy; 2023 - 4to A TDS | Todos los derechos Reservados.</p>
        </div>
        <div class="footer-iconoFlecha">
            <a href="#"><i class='bx bx-up-arrow-alt'></i></a>
        </div>
    </footer>

        <script src = "/js/script.js"></script>
</body>
</html>