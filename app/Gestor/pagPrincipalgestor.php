<?php
session_start();

require '../config.php';

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} else {
    $mensaje = '';
}
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
    <link rel="stylesheet" href="/css/pagPrincipalgestor.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="/image/a.png" type="image/png">
</head>

<div class="login-box">
<body class="pag-principal">

    <!-- Encabezado -->
    <header class="header">
        <a href="#" class="logo">Gestor</a>

        <div class="bx bx-menu" id="menu-icon"></div>

        <nav class="navbar">
            <a href="#inicio" class="active">Inicio</a>
            <a href="#convenios" >Convenios</a>
            <a href="#solicitudes">Solicitudes</a>
            <a href="#gestor">Asignación</a>
            <a href="#monitorear">Monitoriar</a>
            <a href="#estudiantes">Estudiantes</a>
            <a href="#informes">Informes</a>
            <?php $usuario = $_SESSION['usuario']; ?>
            <a href="modificar_gestor.php?correo=<?php echo $usuario["email"]; ?>">
                <span class="nombre-usuario">
                    <?php echo obtenerNombreCompleto($conn); ?>
                </span>
            <a href="../../login.php"><i class='bx bx-exit'></i></a>
            </a>
        </nav>
    </header>

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

    <section class="inicio" id="inicio">
        <div class="inicio-content">
            <h1>Gestión de <span>Prácticas</span></h1>
            <div class="text-secund">
                <h3>Gestiona y Supervisa</h3>
            </div>
            <p>Bienvenido a nuestra plataforma especializada en la gestión de 
                prácticas pre-profesionales. Conectamos la excelencia académica 
                con oportunidades laborales significativas, sirviendo como el 
                enlace entre estudiantes talentosos y empresas e instituciones 
                que buscan impulsar el crecimiento profesional.
            </p>
            
        </div>
        <img src="/image/gestor.png" alt="Imagen de fondo" class="inicio-img">
    </section>

    <section class="Convenio" id="convenios">
         <h2 class="heading">Convenios</h2>

         <div class="ofertas-container">
         <?php

            $sql = "SELECT c.id, c.nombre_empresa, c.cupo_disponible, r.renovacion, u.nombres AS entidad
            FROM convenios c
            LEFT JOIN renovacion_convenio r ON c.id = r.id 
            LEFT JOIN usuarios u ON r.usuario_id = u.id";

            $result = $conn->query($sql);

            if ($result) {

            while ($row = $result->fetch_assoc()) {

                echo '<div class="ofertas-caja">';

                echo '<h3>' . $row['nombre_empresa'] . '</h3>';
                
                // Verificar si hay cupos disponibles
                if (isset($row['cupo_disponible']) && $row['cupo_disponible'] > 0) {
                    echo '<p>Cupos: ' . $row['cupo_disponible'] . '</p>';
                } else {
                    echo '<p>No hay cupos disponibles</p>';
                }

                if (isset($row['renovacion']) && $row['renovacion'] !== null) {
                echo '<p>Convenio: ' . $row['renovacion'] . ' años</p>';
                } else {
                echo '<p>Sin renovación</p>';
                }

                echo '</div>';

            }

            } else {
            echo "Error: " . $conn->error;
            }

        ?>
    </div>
    </section>

    <!--SECTION DE ACEPTAR O RECHAZAR SOLICITUDES DE LOS ESTUDIANTES-->
    <section class="aceptar_rechazar" id="solicitudes">
        <h2 class="heading">Solicitudes <span class="colorS">Pendientes</span></h2>
            
        <div class="actividades-container">
            <?php
                // Realizar una consulta para obtener las solicitudes pendientes
                $sqlSolicitudes = "SELECT * FROM registrar_usuarios WHERE estado = 'pendiente' AND rol = 'visitante'";
                $resultSolicitudes = $conn->query($sqlSolicitudes);

                if ($resultSolicitudes->num_rows > 0) {
                    // Mostrar las solicitudes pendientes
                    while ($solicitud = $resultSolicitudes->fetch_assoc()) {
                        echo '<div class="solicitud-caja">';
                        echo '<p><span>Nombres: </span>' . $solicitud['nombres'] . '</p>';
                        echo '<p><span>Apellidos: </span>' . $solicitud['apellidos'] . '</p>';
                        echo '<p><span>Email: </span>' . $solicitud['email'] . '</p>';
                        echo '<p><span>Carrera: </span>' . $solicitud['carrera'] . '</p>';
                        echo '<p><span>Nivel: </span>' . $solicitud['nivel'] . '</p>';


                        echo '<form action="procesar_solicitud.php" method="post">';
                        echo '<input type="hidden" name="id_usuario" value="' . $solicitud['id'] . '">';
                        echo '<div class="botones">';
                        echo '<div class="boton_aceptar">';
                        echo '<input type="submit" name="aceptar_solicitud" value="Aceptar">';
                        echo '</div>';
                        echo '<div class="boton_rechazar">';
                        echo '<input type="submit" name="rechazar_solicitud" value="Rechazar">';
                        echo '</div>';
                        echo '</div>';
                        echo '</form>';

                        echo '</div>';
                    }
                } else {
                    echo '<p>No hay solicitudes pendientes.</p>';
                }
            ?>
        </div>
    </section>

    <section class="gestor" id="gestor">
        <h2 class="heading">Asignación de Estudiantes a Entidades</h2>
            <div class="formulario-asignacion">
            <h3>Lista de estudiantes y entidades</h3>

            <form action="proceso_asignacion.php" method="post">

                <div class="input-box">
                <label for="estudiante">Estudiante:</label>
                <select id="estudiante" name="estudiante">
                    <?php
                    $sql = "SELECT id, nombres, apellidos FROM registrar_usuarios WHERE rol = 'estudiante' AND pertenece = 'ninguno'";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['id'].'">'.$row['nombres'].' '.$row['apellidos'].'</option>';
                        }
                    } else {
                        echo '<option value="" disabled selected>No hay estudiantes</option>';
                    }
                    ?>
                </select>
                </div>

                <div class="input-box">
                <label for="convenio">Entidad:</label>  
                <select id="convenio" name="entidad">
                    <?php
                    // Seleccionar solo usuarios con rol 'entidad'
                    $sql = "SELECT u.id, CONCAT(u.nombres, ' ', u.apellidos) AS entidad_nombre
                            FROM registrar_usuarios u
                            JOIN convenios c ON u.id = c.entidad_id AND c.cupo_disponible > 0
                            WHERE u.rol = 'entidad'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Mostrar opciones de entidades con cupos disponibles
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['id'].'">'.$row['entidad_nombre'].'</option>';
                        }
                    } else {
                        // Mostrar mensaje cuando no hay entidades con cupos disponibles
                        echo '<option value="" disabled selected>No hay cupos disponibles</option>';
                    }
                    ?>
                </select>
                </div>

                <div class="input-box">
                <label for="detalles">Detalles:</label>
                <textarea id="detalles" name="detalles"></textarea>
                </div>

                <div class="btn-box">
                    <button class="btn" type="submit" name="asignar">Asignar</button>
                </div>
            </form>

            </div>

    </section>



<section class="monitorearEstudiantes" id="monitorear">
    <h2 class="heading">Monitorear <span class="color_c">Estudiantes</span></h2>

    <div class="confirmacion" id="confirmacion-validar">
        <div class="mensajeConfirmacion">¿Estás seguro de validar las horas?</div>
        <div class="botones">
            <button class="confirmar" id="confirmar-validar-btn">Confirmar</button>
            <button class="cancelar" id="cancelar-validar-btn">Cancelar</button>
        </div>
    </div>

    <div class="confirmacion" id="confirmacion-denegar">
        <div class="mensajeConfirmacion">¿Estás seguro de denegar las horas?</div>
        <div class="botones">
            <button class="confirmar" id="confirmar-denegar-btn">Confirmar</button>
            <button class="cancelar" id="cancelar-denegar-btn">Cancelar</button>
        </div>
    </div>

    <div class="monitorear-container">
        <?php
            $sqlMonitorear = "SELECT 
            ru.id AS id_estudiante,
            ru.nombres AS nombre_estudiante, 
            ru.apellidos AS apellido_estudiante,
            ie.id AS id_actividad,
            ie.titulo AS titulo_informe, 
            ie.archivo AS archivo_informe, 
            ie.fecha AS fecha_informe, 
            ie.hora AS hora_actividad,
            CONCAT(ue.nombres, ' ', ue.apellidos) AS nombre_entidad,
            ie.estadoHoras
            FROM registrar_usuarios ru
            LEFT JOIN informe_estudiante ie ON ru.id = ie.estudiante_id
            LEFT JOIN usuarios ue ON ru.entidad = ue.id
            WHERE ru.rol = 'estudiante' AND ie.estadoHoras = 'pendiente'
            ORDER BY ru.id, ie.fecha DESC, ie.hora DESC";

            $resultMonitorear = $conn->query($sqlMonitorear);

            if ($resultMonitorear->num_rows > 0) {
                echo '<div class="actividades-informes-container">';
                echo '<h3>Actividades e Informes de Estudiantes</h3>';
                echo '<div class="table-container">';
                echo '<table class="table">';
                echo '<tr>';
                echo '<th>Estudiante</th>';
                echo '<th>Título Informe</th>';
                echo '<th>Archivo Informe</th>';
                echo '<th>Fecha Informe</th>';
                echo '<th>Horas</th>';
                echo '<th>Entidad</th>';
                echo '<th>Validar Horas</th>';
                echo '<th>Denegar Horas</th>';
                echo '</tr>';

                $studentsData = array();

                while ($rowMonitorear = $resultMonitorear->fetch_assoc()) {
                    $studentId = $rowMonitorear['id_estudiante'];

                    if (!isset($studentsData[$studentId])) {
                        $studentsData[$studentId] = array(
                            'nombre_estudiante' => $rowMonitorear['nombre_estudiante'],
                            'apellido_estudiante' => $rowMonitorear['apellido_estudiante'],
                            'actividades' => array()
                        );
                    }

                    $studentsData[$studentId]['actividades'][] = array(
                        'titulo_informe' => $rowMonitorear['titulo_informe'],
                        'archivo_informe' => $rowMonitorear['archivo_informe'],
                        'fecha_informe' => $rowMonitorear['fecha_informe'],
                        'hora_actividad' => $rowMonitorear['hora_actividad'],
                        'nombre_entidad' => $rowMonitorear['nombre_entidad'],
                        'estadoHoras' => $rowMonitorear['estadoHoras'],
                        'id_actividad' => $rowMonitorear['id_actividad']
                    );
                }

                foreach ($studentsData as $studentId => $studentData) {
                    foreach ($studentData['actividades'] as $index => $actividad) {
                        echo '<tr>';
                        echo '<td>' . $studentData['nombre_estudiante'] . ' ' . $studentData['apellido_estudiante'] . '</td>';
                        echo '<td>' . $actividad['titulo_informe'] . '</td>';
                        echo '<td><a href="/app/Estudiante/DescargarArchivo.php?id=' . $actividad['id_actividad'] . '">' . $actividad['archivo_informe'] . '</a></td>';
                        echo '<td>' . $actividad['fecha_informe'] . '</td>';
                        echo '<td>' . substr($actividad['hora_actividad'], 0, 5) . '</td>';
                        echo '<td>' . $actividad['nombre_entidad'] . '</td>';
                        echo '<td>';
                        echo '<form class="formulario-gestion-horas" method="post" action="gestionHoras.php" id="form-validar-' . $studentId . '-' . $actividad['id_actividad'] . '">';
                        echo '<input type="hidden" name="id_estudiante" value="' . $studentId . '">';
                        echo '<input type="hidden" name="id_actividad" value="' . $actividad['id_actividad'] . '">';
                        echo '<input type="hidden" name="accion" value="validar">';
                        echo '<button class="botones validar-btn" name="validar">Validar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '<td>';
                        echo '<form class="formulario-gestion-horas" method="post" action="gestionHoras.php" id="form-denegar-' . $studentId . '-' . $actividad['id_actividad'] . '">';
                        echo '<input type="hidden" name="id_estudiante" value="' . $studentId . '">';
                        echo '<input type="hidden" name="id_actividad" value="' . $actividad['id_actividad'] . '">';
                        echo '<input type="hidden" name="accion" value="denegar">';
                        echo '<button class="botones denegar-btn" name="denegar">Denegar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }

                echo '</table>';
                echo '</div>';
                echo '</div>';
                echo '<div class="mensaje">' . (isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '') . '</div>';
            } else {
                echo '<p>No hay actividades ni informes registrados.</p>';
                unset($_SESSION['mensaje']);
            }
        ?>
   
    </div>
</section>


<!--SECTION LISTADO ESTUDIANTES-->
<section class="listado_estudiantes" id="estudiantes">
  <h2 class="heading">Listado de <span class="color_c">Estudiantes</span></h2>

    <?php
        // Definir el límite de horas
        $limiteHoras = 240;

        // Realizar la consulta SQL para obtener estudiantes, entidades y horas totales validadas
        $sql = "SELECT
            CONCAT(ru.nombres, ' ', ru.apellidos) AS nombre_estudiante,
            CONCAT(u.nombres, ' ', u.apellidos) AS nombre_entidad,
            SEC_TO_TIME(SUM(TIME_TO_SEC(ie.hora))) AS horas_totales
        FROM
            registrar_usuarios ru
        LEFT JOIN usuarios u ON ru.entidad = u.id
        LEFT JOIN informe_estudiante ie ON ru.id = ie.estudiante_id AND ie.estadoHoras = 'Horas Validadas'
        WHERE
            ru.rol = 'estudiante'
        GROUP BY
            ru.id, u.id
        ORDER BY
            ru.id, u.id";

        $result = $conn->query($sql);

        // Mostrar el listado de estudiantes, entidades y horas totales validadas
        if ($result->num_rows > 0) {
            echo '<div class="table-container">';
            echo '<table class="table">';
            echo '<thead class="sticky-header">'; 
            echo '<tr>
                <th>Estudiante</th>
                <th>Entidad</th>
                <th>Horas Totales</th>
            </tr>';

            while ($row = $result->fetch_assoc()) {
                if (isset($row['horas_totales'])) {
                    // Convertir horas totales a formato numérico
                    $horasTotalesNumericas = (int)$row['horas_totales'];
                    
                    if ($horasTotalesNumericas >= $limiteHoras) {
                        echo '<tr>
                            <td>' . $row['nombre_estudiante'] . '</td>
                            <td>' . $row['nombre_entidad'] . '</td>
                            <td>Cumplió con las 240 horas</td>
                        </tr>';
                    } else {
                        // Obtener horas y minutos separados
                        list($horas, $minutos) = explode(':', $row['horas_totales']);
            
                        echo '<tr>
                            <td>' . $row['nombre_estudiante'] . '</td>
                            <td>' . $row['nombre_entidad'] . '</td>
                            <td>' . $horas . ' h ' . $minutos . ' min</td>
                        </tr>';
                    }
                } else {
                    // Manejar el caso en el que 'horas_totales' no está definido
                    echo '<tr>
                        <td>' . $row['nombre_estudiante'] . '</td>
                        <td>' . $row['nombre_entidad'] . '</td>
                        <td>00 h 00 min</td>
                    </tr>';
                }
            }

            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="parrafo"';
            echo '<p>No hay estudiantes registrados con horas validadas.</p>';
            echo '</div>';
        }
    ?>
</section>



<!--SECTION INFORMES GESTOR-->
<section class="informes" id="informes">
    <h2 class="heading">Subir <span>informes</span></h2>

    <?php
        // Realizar la consulta SQL para obtener las entidades
        $sqlEntidades = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre_entidad FROM usuarios WHERE rol = 'entidad'";
        $resultEntidades = $conn->query($sqlEntidades);
    ?>

    <form action="/app/Gestor/informesGestor.php" method="post" enctype="multipart/form-data">

        <div class="input-box"> 
            <label for="tituloInforme">Título del Informe:</label>
            <input type="text" id="tituloInforme" name="tituloInforme" required>
        </div>

        <div class="input-box">
            <label for="fechaInforme">Fecha del Informe:</label>
            <input class="fecha" type="date" id="fechaInforme" name="fechaInforme" required>
        </div>

        <div class="input-box">
            <label for="entidad">Entidad:</label>
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

        <div class="input-box">
            <label for="archivo">Seleccionar Archivo:</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf, .doc, .docx" required>
        </div>

            <div class="btn-box">
                <button type="submit" class="btn" name="submit">Enviar Informe</button>
            </div>
    </form>

</section>




<!--SECTION INFORMES DE LAS ENTIDADES-->
<section class="informes-entidad" id="informes">
    <h2 class="heading">Informes de <span>Entidades</span></h2>

    <?php
    // Realizar la consulta SQL para obtener los informes de las entidades con el nombre de la entidad
    $sqlInformesEntidad = "SELECT i.id, i.titulo, i.archivo_path, i.fecha_informe, CONCAT(u.nombres, ' ', u.apellidos) AS nombre_entidad
    FROM informes_entidad i
    INNER JOIN usuarios u ON i.entidad_id = u.id
    WHERE i.estadoVistoGestor = 'pendiente'
    ORDER BY i.fecha_informe DESC";

    $resultInformesEntidad = $conn->query($sqlInformesEntidad);

    if ($resultInformesEntidad->num_rows > 0) {
        echo '<div class="table-container">';
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Título</th>';
        echo '<th>Fecha de Informe</th>';
        echo '<th>Nombre de la Entidad</th>';
        echo '<th>Nombre del Archivo</th>';
        echo '<th>Estado</th>'; // Nueva columna
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Mostrar los informes de las entidades en filas de la tabla
        while ($rowInformeEntidad = $resultInformesEntidad->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $rowInformeEntidad['titulo'] . '</td>';
            echo '<td>' . $rowInformeEntidad['fecha_informe'] . '</td>';
            echo '<td>' . $rowInformeEntidad['nombre_entidad'] . '</td>';
            // Enlace de descarga directa en el nombre del archivo
            echo '<td><a href="' . $rowInformeEntidad['archivo_path'] . '" download>' . basename($rowInformeEntidad['archivo_path']) . '</a></td>';
            echo '<td>';
            echo '<form action="cambiar_estado_entidad.php" method="post">';
            echo '<input type="hidden" name="idInforme" value="' . $rowInformeEntidad['id'] . '">';
            echo '<button type="submit">Eliminar</button>';
            echo '</form>';
            echo '</td>'; // Botón Eliminar dentro del formulario
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="parrafo"';
        echo '<p>No hay informes de entidades disponibles.</p>';
        echo '</div>';
    }
    ?>
</section>



<script>
         document.addEventListener("DOMContentLoaded", function () {
        var confirmacionValidar = document.getElementById('confirmacion-validar');
        var confirmarValidarBtn = document.getElementById('confirmar-validar-btn');
        var cancelarValidarBtn = document.getElementById('cancelar-validar-btn');

        var confirmacionDenegar = document.getElementById('confirmacion-denegar');
        var confirmarDenegarBtn = document.getElementById('confirmar-denegar-btn');
        var cancelarDenegarBtn = document.getElementById('cancelar-denegar-btn');

        function configurarFormulario(formulario, accion) {
            formulario.elements['accion'].value = accion;
            confirmacionValidar.style.display = 'none'; // Oculta la ventana de confirmación
            confirmacionDenegar.style.display = 'none'; // Oculta la ventana de confirmación para denegar
            formulario.submit(); // Envía el formulario
        }

        // Agrega eventos a los botones Validar
        var botonesValidar = document.querySelectorAll('.botones.validar-btn');
        botonesValidar.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                // Muestra la ventana de confirmación para Validar
                confirmacionValidar.style.display = 'block';
                confirmarValidarBtn.dataset.formulario = btn.closest('form').id; // Almacena el ID del formulario
            });
        });

        // Agrega un evento al hacer clic en el botón Confirmar Validar
        confirmarValidarBtn.addEventListener('click', function () {
            var formularioId = confirmarValidarBtn.dataset.formulario;
            var formulario = document.getElementById(formularioId);
            configurarFormulario(formulario, 'validar');
        });

        cancelarValidarBtn.addEventListener('click', function () {
            var formularioId = confirmarValidarBtn.dataset.formulario;
            var formulario = document.getElementById(formularioId);
            confirmacionValidar.style.display = 'none'; // Oculta la ventana de confirmación
            formulario.elements['accion'].value = ''; // Restablece el valor del campo accion
        });

        // Agrega eventos a los botones Denegar
        var botonesDenegar = document.querySelectorAll('.botones.denegar-btn');
        botonesDenegar.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                // Muestra la ventana de confirmación para Denegar
                confirmacionDenegar.style.display = 'block';
                confirmarDenegarBtn.dataset.formulario = btn.closest('form').id; // Almacena el ID del formulario
            });
        });

        // Agrega un evento al hacer clic en el botón Confirmar Denegar
        confirmarDenegarBtn.addEventListener('click', function () {
            var formularioId = confirmarDenegarBtn.dataset.formulario;
            var formulario = document.getElementById(formularioId);
            configurarFormulario(formulario, 'denegar');
        });

        // Agrega un evento al hacer clic en el botón Cancelar Denegar
        cancelarDenegarBtn.addEventListener('click', function () {
            confirmacionDenegar.style.display = 'none'; // Oculta la ventana de confirmación
        });
    });
</script>

    
     
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