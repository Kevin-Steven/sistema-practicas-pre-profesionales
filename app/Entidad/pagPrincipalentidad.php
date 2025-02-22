<?php
session_start();
require_once "../config.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header("Location: login.php");
    exit;
}

// Obtén el usuario de la sesión
$usuario = $_SESSION['usuario'];
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prácticas Preprofesionales</title>
    <link rel="stylesheet" href="/css/pagPrincipalentidad.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="/image/a.png" type="image/png">
</head>

<body class="pag-principal">

    <!-- Encabezado -->
    <header class="header">
        <a href="#" class="logo">Entidad</a>

        <div class="bx bx-menu" id="menu-icon"></div>

        <nav class="navbar">
            <a href="#inicio" class="active">Inicio</a>
            <a href="#cupos">Cupos</a>
            <a href="#convenio">Convenio</a>
            <a href="#informes">Subir informes</a>
            <a href="#monitorear">Monitorear</a>
            <a href="#estudiantes">Estudiantes</a>
            <a href="#gestor">Gestor</a>
            <?php $usuario = $_SESSION['usuario']; ?>
            <a href="modificar_entidad.php?correo=<?php echo $usuario["email"]; ?>">
                <span class="nombre-usuario">
                    <?php echo obtenerNombreCompleto($conn); ?>
                </span>
            </a>
            <a href="../../login.php"><i class='bx bx-exit'></i></a>
        </nav>
    </header>

    <section class="inicio" id="inicio">
        <div class="inicio-content">
            <h1>¡Explora tu Panel de Empresa <span>ahora!</span></h1>
            <div class="text-secund">
                <h3>Administra tu espacio</h3>
            </div>
            <p>Le damos la bienvenida a nuestra plataforma dedicada a la 
                gestión de prácticas pre-profesionales. Aquí podrás realizar 
                diversas acciones para potenciar la experiencia de los estudiantes. 
                Explora oportunidades laborales, revisa informes y registra 
                actividades de manera sencilla.
            </p>
        </div>

        <img src="/image/entidad.png" alt="Imagen de fondo" class="inicio-img">
    </section>

    <!--SECCION CUPOS-->
    <section class="cupos" id="cupos">
    <div class="formulario-asignacion">
        <h2 class="heading">Asignar <span class="maincolor">Cupos</span></h2>

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
        <form action="proceso_asignar_cupos.php" method="post">
            <div class="input-box">
                <label for="nombre_empresa">Nombre de la Empresa (Entidad):</label>
                <!-- Mostrar el nombre del usuario usando la función obtenerNombreCompleto -->
                <input type="text" id="nombre_empresa" name="nombre_empresa" value="<?php echo obtenerNombreCompleto($conn); ?>" readonly>
            </div>

            <div class="input-box">
                <!-- Agregar un campo oculto para almacenar el ID del usuario -->
                <input type="hidden" id="usuario_id" name="usuario_id" value="<?php echo $_SESSION['usuario']['id']; ?>">
            </div>

            <div class="input-box">
                <label for="cupo_disponible">Cupos Disponibles:</label>
                <input type="number" id="cupo_disponible" name="cupo_disponible" required>
            </div>

            <div class="btn-box">
                <button class="btn boton-visto" type="submit" name="submit">Asignar</button>
            </div>
        </form>
    </div>
    </section>


    <!--SECCION CONVENIOS-->
    <section class="convenio" id="convenio">
        <h2 class="heading">Renovación de <span class="maincolor">convenio</span></h2>
        <form action="/app/Entidad/renovacion.php" method="post">
            <div class="input-box">
                <label for="anos_renovacion">Número de años de renovación:</label>
                <input type="number" id="anos_renovacion" name="anos_renovacion" required>
            </div>

            <div class="btn-box">
                <button class="btn boton-renovar" type="submit" name="submit">Renovar Convenio</button>
            </div>
        </form>
    </section>



<!--SECCION SUBIR INFORMES ENTIDAD-->
<section class="informes" id="informes">
    <h2 class="heading">Subir <span>Informe</span></h2>
        <form action="/app/Entidad/proceso_subir_informe.php" method="post" enctype="multipart/form-data">
        <div class="input-box">
            <label for="titulo_informe">Título del Informe:</label>
            <input type="text" id="titulo_informe" name="titulo_informe" required>
        </div>

        <div class="input-box">
            <label for="descripcion_informe">Descripción:</label>
            <textarea id="descripcion_informe" name="descripcion_informe" rows="2" required></textarea>
        </div>

        <div class="input-box">
            <label for="fecha_informe">Fecha del Informe:</label>
            <input class="fecha" type="date" id="fecha_informe" name="fecha_informe" required>
        </div>

        <div class="input-box">
            <label for="archivo_informe">Seleccionar Archivo:</label>
            <input type="file" id="archivo_informe" name="archivo_informe" accept=".pdf, .doc, .docx" required>
        </div>

        <div class="btn-box">
            <button class="btn boton-informe" type="submit" name="submit">Subir Informe</button>
        </div>
    </form>
</section>


<section class="Monitorear actividades" id="monitorear">
    <h2 class="heading">Monitorear <span>Estudiantes</span></h2>

    <?php
    // Obtén el ID de la entidad actual desde la sesión
    $entidad_id = $_SESSION['usuario']['id'];

    // Consulta para obtener nombres de estudiantes, informes y actividades de la entidad actual con horas validadas
    $sql_monitorear = "SELECT 
                        usuarios.nombres, 
                        usuarios.apellidos, 
                        informe_estudiante.id as informe_id, 
                        informe_estudiante.titulo, 
                        informe_estudiante.fecha as informe_fecha, 
                        informe_estudiante.hora as informe_hora,
                        informe_estudiante.estadoHoras as estado,
                        informe_estudiante.archivo as informe_archivo
                    FROM usuarios
                    LEFT JOIN informe_estudiante ON usuarios.id = informe_estudiante.estudiante_id
                    WHERE usuarios.rol = 'estudiante' 
                        AND usuarios.entidad = ? 
                        AND informe_estudiante.estadoHoras = 'Horas Validadas' and estadoVistoEntidad = 'pendiente'";

    // Utiliza una consulta preparada para evitar la inyección SQL
    $stmt = $conn->prepare($sql_monitorear);
    $stmt->bind_param("i", $entidad_id);
    $stmt->execute();
    $resultado_monitorear = $stmt->get_result();

    // Mostrar la lista de estudiantes, informes y actividades
    if ($resultado_monitorear->num_rows > 0) {
        while ($row = $resultado_monitorear->fetch_assoc()) {
            echo '<div class="actividad-estudiante">';
            
            // Modificación en la línea siguiente
            echo '<h3>Estudiante: <span class="nombreApellido">' . $row['nombres'] . ' ' . $row['apellidos'] .  '</span></h3>';
            
            if (isset($row['informe_fecha'])) {
                echo '<p><span>Título informe: </span>' . $row['titulo'] . ' - ';
                echo '<span>Fecha: </span>' . $row['informe_fecha'] . ' <br> ';
                echo '<span>Nombre del archivo: </span>' ;
                
                echo '<a href="/archivos/' . $row['informe_archivo'] . '" download>' . $row['informe_archivo'] . '<br>' .'</a>';
                

                echo '<span>Horas trabajadas: </span>' . substr($row['informe_hora'], 0, 5) . ' - ';

                echo '<span>Estado: </span>' . $row['estado'] ;   
                

                 // Añadir formulario y botón "Visto"
                echo '<form method="post" action="procesar_visto.php">';
                echo '<input type="hidden" name="informe_id" value="' . $row['informe_id'] . '">';
                echo ' <div class="btn-box">';
                echo '<button type="submit" name="visto" class="btn boton-visto">Visto</button>';
                echo '</div>';
                echo '</form>';

                echo '</p>';
            }
    
            echo '</div>';
        }
    } else {
        echo '<div class="parrafo">';
        echo '<p>No hay informes o estudiantes registrados con horas validadas.</p>';
        echo '</div>';
    }
    
    // Cierra la consulta preparada
    $stmt->close();
    ?>
</section>


<!--SECTION ESTUDIANTES ENTIDAD-->
<section class="listado_estudiantes" id="estudiantes">
    <h2 class="heading">Listado de <span class="color_m">Estudiantes</span></h2>

        <?php
            // Obtén el ID de la entidad actual desde la sesión
            $entidad_id = $_SESSION['usuario']['id'];

            // Realizar la consulta SQL para obtener información de estudiantes y sus informes
            $sql = "SELECT
                        CONCAT(ru.nombres, ' ', ru.apellidos) AS nombre_estudiante,
                        ru.cedula AS cedula_estudiante,
                        CONCAT(u.nombres, ' ', u.apellidos) AS nombre_entidad,
                        SUM(TIME_TO_SEC(ie.hora)) AS segundos_totales
                    FROM
                        registrar_usuarios ru
                    LEFT JOIN usuarios u ON ru.entidad = u.id
                    LEFT JOIN informe_estudiante ie ON ru.id = ie.estudiante_id AND ie.estadoHoras = 'Horas Validadas'
                    WHERE
                        ru.rol = 'estudiante'
                        AND ru.entidad = ?
                    GROUP BY
                        ru.id, u.id
                    ORDER BY
                        ru.id, u.id";

            // Utiliza una consulta preparada para evitar la inyección SQL
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $entidad_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Mostrar la tabla de estudiantes, entidades, cédula y horas totales
            if ($result->num_rows > 0) {
                echo '<div class="table-container">';
                echo '<table class="table"> ';
                echo '<thead class="sticky-header">' ; 
                echo '<tr>
                        <th>Estudiante</th>
                        <th>Cedula</th>
                        <th>Entidad</th>
                        <th>Horas Totales</th>
                    </tr>';

                while ($row = $result->fetch_assoc()) {
                    $segundosTotales = $row['segundos_totales'];
                    $cumplioCon240Horas = ($segundosTotales >= 240 * 3600);

                    // Formatear las horas y minutos correctamente
                    $formattedHorasTotales = sprintf("%02d h %02d min", floor($segundosTotales / 3600), floor(($segundosTotales % 3600) / 60));

                    echo '<tr>
                            <td>' . $row['nombre_estudiante'] . '</td>
                            <td>' . $row['cedula_estudiante'] . '</td>
                            <td>' . $row['nombre_entidad'] . '</td>
                            <td>' . ($cumplioCon240Horas ? 'Cumplió con las 240 horas' : $formattedHorasTotales) . '</td>
                        </tr>';
                }

                echo '</table>';
                echo '</div>';
            } else {
                echo '<div class="parrafo">';
                echo '<p>No hay estudiantes registrados con horas validadas para la entidad actual.</p>';
                echo '<div>';
            }

            // Cierra la consulta preparada
            $stmt->close();
        ?>

</section>


<!--SECTION INFORMES GESTOR-->
<section class="gestor" id="gestor">
    <h2 class="heading">Informes Gestor</h2>
    <?php
        // Obtén la entidad actual desde la sesión (ajusta según la estructura de tu sesión)
        $entidadId = $_SESSION['usuario']['id'];

        // Realizar la consulta SQL para obtener los informes del gestor de la entidad actual
        $sqlInformesGestor = "SELECT i.id, i.titulo, i.fecha, i.archivo_path, i.fecha_subida, CONCAT(u.nombres, ' ', u.apellidos) AS nombre_entidad
        FROM informes_gestor i
        INNER JOIN registrar_usuarios u ON i.entidad_id = u.id
        WHERE i.entidad_id = ? AND i.estadoVistoEntidad = 'pendiente'
        ORDER BY i.fecha DESC";

        // Utiliza una consulta preparada para evitar la inyección SQL
        $stmtInformesGestor = $conn->prepare($sqlInformesGestor);
        $stmtInformesGestor->bind_param("i", $entidadId);
        $stmtInformesGestor->execute();
        $resultInformesGestor = $stmtInformesGestor->get_result();

        if ($resultInformesGestor->num_rows > 0) {
            echo '<div class="table-container">';
            echo '<table class="table">';
            echo '<thead class="sticky-header">';
            echo '<tr>';
            echo '<th>Título</th>';
            echo '<th>Fecha de Informe</th>';
            echo '<th>Archivo</th>';
            echo '<th>Estado</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Mostrar los informes del gestor en filas de la tabla
            while ($rowInformeGestor = $resultInformesGestor->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $rowInformeGestor['titulo'] . '</td>';
                echo '<td>' . $rowInformeGestor['fecha'] . '</td>';
                echo '<td><a href="/app/Gestor/../../archivos_informes_gestor/' . basename($rowInformeGestor['archivo_path']) . '" download>' . basename($rowInformeGestor['archivo_path']) . '</a></td>';
                echo '<td>';
                // Formulario con botón de eliminar
                echo '<form action="informe_leido_gestor.php" method="post">';
                echo '<input type="hidden" name="informe_id" value="' . $rowInformeGestor['id'] . '">';
                echo '<button type="submit">Eliminar</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="parrafo"';
            echo '<p>No hay informes del gestor disponibles para la entidad actual.</p>';
            echo '</div>';
        }

        // Cierra la consulta preparada
        $stmtInformesGestor->close();
    ?>

</section>


    <footer class="footer">
        <div class="footer-text">
            <p>Copyright &copy; 2023 - 4to A TDS | Todos los derechos Reservados.</p>
        </div>
        <div class="footer-iconoFlecha">
            <a href="#"><i class='bx bx-up-arrow-alt'></i></a>
        </div>
    </footer>
    
        <script src="/js/script.js"></script>

</body>

</html>