<?php
session_start();

require '../config.php';

// Obtener el ID de estudiante desde la sesión
if (isset($_SESSION['usuario']['id'])) {
    $estudiante_id = $_SESSION['usuario']['id'];

    // Consulta para traer informes del estudiante
    $sql_informes = "SELECT * FROM informe_estudiante WHERE id = $estudiante_id";
    $resultado_informes = $conn->query($sql_informes);

    // Verificar si hay errores en la consulta
    if (!$resultado_informes) {
        echo 'Error en la consulta de informes: ' . mysqli_error($conn);
        exit;
    }

} else {
    echo 'Error: Acceso no autorizado.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiante</title>
    <link rel="stylesheet" href="/css/pagEstudiante.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="/image/a.png" type="image/png">
</head>
<body>
<header class="header">
        <a href="#" class="logo">Estudiante</a>

        <div class="bx bx-menu" id="menu-icon"></div>

        <nav class="navbar">
            <a href="#inicio" class="active">Inicio</a>
            <a href="#comienzo">Comienzo</a>
            <a href="#informes">Subir Informes</a>
            <a href="#misregistros">Mis registros</a>
            <a href="#horastotales">Horas Totales</a>
            <?php $usuario = $_SESSION['usuario']; ?>
            <a href="modificar_estudiante.php?correo=<?php echo $usuario["email"]; ?>">
                <span class="nombre-usuario">
                    <?php echo obtenerNombreCompleto($conn); ?>
                </span>
            </a>
            <a href="../../login.php"><i class='bx bx-exit'></i></a>
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

    <!--SECTION INICIO ESTUDIANTE-->
    <section class="inicio" id="inicio">
        <div class="inicio-content">
            <h1>¡Explora tu Panel de Estudiante <span>ahora!</span></h1>
            <div class="text-secund">
                <h3>Gestiona tus Prácticas</h3>
            </div>
            <p> Te damos la bienvenida a nuestra 
                plataforma dedicada a la gestión de prácticas pre-profesionales. 
                Aquí podrás realizar diversas acciones para potenciar tu experiencia. 
                Explora oportunidades laborales, sube informes y registra tus 
                actividades de manera sencilla.
            </p>
        </div>
        <img src="/image/gestion.png" alt="Imagen de fondo" class="inicio-img">
    </section>



<!-- SECTION FECHA INICIALIZACION Y FINALIZACION -->
<section class="comienzo" id="comienzo">
    <h2 class="heading">Fechas De <span>Inicio y Fin</span></h2>

    <div class="parrafo-espacio">
        <p class="parrafo">Aquí podrás visualizar la fecha de inicio y fin de tus prácticas profesionales.</p>
    </div>

    <?php
    // Obtén el ID del estudiante actual desde la sesión
    $estudianteId = $_SESSION['usuario']['id'];

    // Realiza la consulta SQL para obtener las fechas desde la tabla fechas_asignadas y registrar_usuarios para el estudiante actual
    $sqlFechas = "SELECT fa.fecha_inicial, fa.fecha_final, CONCAT(ru_entidad.nombres, ' ', ru_entidad.apellidos) as nombre_entidad
                  FROM fechas_asignadas fa
                  INNER JOIN registrar_usuarios ru_estudiante ON fa.entidad_id = ru_estudiante.entidad
                  INNER JOIN registrar_usuarios ru_entidad ON ru_estudiante.entidad = ru_entidad.id
                  WHERE ru_estudiante.id = ?";
    
    // Utiliza una consulta preparada para evitar la inyección SQL
    $stmt = $conn->prepare($sqlFechas);
    $stmt->bind_param("i", $estudianteId);
    $stmt->execute();
    $resultFechas = $stmt->get_result();

    if ($resultFechas->num_rows > 0) {
        echo '<div class="table-container">';
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha de Inicialización</th>';
        echo '<th>Fecha de Finalización</th>';
        echo '<th>Entidad</th>'; // Nueva columna para el nombre de la entidad
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($rowFecha = $resultFechas->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $rowFecha['fecha_inicial'] . '</td>';
            echo '<td>' . $rowFecha['fecha_final'] . '</td>';
            echo '<td>' . $rowFecha['nombre_entidad'] . '</td>'; // Mostrar el nombre de la entidad
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p class="parrafo">No hay fechas asignadas disponibles para este estudiante.</p>';
    }

    // Cierra la consulta preparada
    $stmt->close();
    ?>
</section>




   <!--SECTION SUBIR INFORMES-->
    <section class="informes" id="informes">
    
        <h2 class="heading">Subir <span>Informes</span></h2>

        <!-- Completa el formulario para subir informes -->
        <form action="/app/Estudiante/procesar_informes.php" method="post" enctype="multipart/form-data">

            <label for="titulo">Título del Informe:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="2" required></textarea>

            <label for="archivo">Selecciona el Archivo:</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf, .doc, .docx" required>

            <label for="fecha">Fecha del Informe:</label>
            <input type="date" id="fecha" name="fecha" required>
            
            <label for="horas">Horas trabajadas:</label>
            <input type="time" id="horas" name="horas" require>

            <div class="btn-box">
                <button type="submit" class="btn" name="submit">Subir Informe</button>
            </div>

        </form>
    </section>



    <!-- Sección Mis Registros -->
    <section class="mis-registros" id="misregistros">
        <h2 class="heading">Mis <span>Registros</span></h2>

        <div class="confirmacion" id="confirmacion-eliminar">
            <div class="mensajeConfirmacion">¿Estás seguro de eliminar este informe?</div>
            <div class="botones">
                <button class="confirmar" id="confirmar-eliminar-btn">Eliminar</button>
                <button class="cancelar" id="cancelar-eliminar-btn">Cancelar</button>
            </div>
        </div>

        <?php
        // Verificar si la variable de sesión 'id' está definida
        if (isset($_SESSION['usuario']['id'])) {
            $estudiante_id = $_SESSION['usuario']['id'];

            // Consulta para traer informes del estudiante
            $sql_informes = "SELECT * FROM informe_estudiante WHERE estudiante_id = $estudiante_id AND (estadoHoras = 'pendiente' OR estadoHoras = 'Horas Invalidas')";
            $resultado_informes = $conn->query($sql_informes);

            // Mostrar informes
            if ($resultado_informes->num_rows > 0) {
                echo '<h3>Informes Subidos</h3>';
                
                while ($row = $resultado_informes->fetch_assoc()) {
                    echo '<form method="post" action="/app/Estudiante/eliminar_informe.php" id="form-eliminar-' . $row['id'] . '">';
                    echo '<input type="hidden" name="id_informe" value="' . $row['id'] . '">';
                    echo '<p>';
                    echo '<span>Título del informe: </span>' . $row['titulo'] . '<br>';
                    echo '<span>Fecha: </span>' . $row['fecha'] . '<br>';
                    echo '<span>Archivo: </span><a href="DescargarArchivo.php?id=' . $row['id'] . '">' . $row['archivo'] . '</a><br>';
                    echo '<span>Horas trabajadas: </span>' . substr($row['hora'], 0, 5) . '<br>';
                    echo '<span>Estado de las horas: </span>' . $row['estadoHoras'] . '<br>';
                    echo '<div class="btn-box">';
                    echo '<button type="submit" class="btn boton-eliminar" value="Eliminar informe" data-informe-id="' . $row['id'] . '">Eliminar informe</button>';
                    echo '</div>';
                    echo '</p>';
                    echo '</form>';
                }
            } else {
                echo '<p>No se encontraron informes.</p>';
            }

            
        } else {
            // Manejo de error o redireccionamiento si 'id' no está definido
            echo 'Error: ID de estudiante no definido.';
        }
        ?>

        <button></button>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var confirmacionEliminar = document.getElementById('confirmacion-eliminar');
        var confirmarEliminarBtn = document.getElementById('confirmar-eliminar-btn');
        var cancelarEliminarBtn = document.getElementById('cancelar-eliminar-btn');

        var idInformeEliminar = null;

        console.log('confirmacionEliminar:', confirmacionEliminar);
        console.log('confirmarEliminarBtn:', confirmarEliminarBtn);
        console.log('cancelarEliminarBtn:', cancelarEliminarBtn);

        function mostrarConfirmacionEliminar(idInforme) {
            idInformeEliminar = idInforme;
            console.log('Mostrar confirmación para el informe con ID:', idInformeEliminar);
            confirmacionEliminar.style.display = 'block';
        }

        function ocultarConfirmacionEliminar() {
            confirmacionEliminar.style.display = 'none';
            idInformeEliminar = null;
        }

        confirmarEliminarBtn.addEventListener('click', function () {
            if (idInformeEliminar !== null) {
                var formulario = document.getElementById('form-eliminar-' + idInformeEliminar);
                
                 // Verificar si el formulario existe antes de intentar acceder a submit
                if (formulario) {
                    console.log('Formulario encontrado para el informe con ID:', idInformeEliminar);
                    formulario.submit();
                } else {
                    console.error('Formulario no encontrado para el informe con ID:', idInformeEliminar);
                }
            }
        });

        cancelarEliminarBtn.addEventListener('click', function () {
            ocultarConfirmacionEliminar();
        });

        // Agrega eventos a los botones Eliminar
        var botonesEliminar = document.querySelectorAll('.boton-eliminar');
        botonesEliminar.forEach(function (btn) {
            // Obtén el ID del informe del atributo data-informe-id del botón
            var idInforme = btn.dataset.informeId;

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                // Llama a la función mostrarConfirmacionEliminar con el ID del informe
                mostrarConfirmacionEliminar(idInforme);
            });
        });

        // Oculta la caja de confirmación al cargar la página
        ocultarConfirmacionEliminar();

        // Obtén el fragmento de la URL
        var fragmento = window.location.hash;

        // Si hay un fragmento y es igual a #misregistros, desplázate a esa sección
        if (fragmento === '#misregistros') {
            var misRegistrosSection = document.getElementById('misregistros');
            if (misRegistrosSection) {
                misRegistrosSection.scrollIntoView();
            }
        }
    });
</script>

    </section>


<!-- SECTION HORAS TOTALES -->
<section class="horasTotales" id="horastotales">

    <h2 class="heading">Días <span>trabajados</span></h2>

        <div class="table-container">
            <table class="table">
            <thead class="sticky-header">
                <tr>
                    <th>Informe</th>
                    <th>Fecha</th>
                    <th>Horas Trabajadas</th>
                </tr>
            </thead>

            <tbody>
                    <?php
                        // Verificar si la variable de sesión 'usuario' y 'id' están definidas
                        if (isset($_SESSION['usuario']['id'])) {
                            $estudiante_id = $_SESSION['usuario']['id'];

                            // Consulta SQL para obtener las horas validadas de los informes del estudiante actual
                            $sql = "SELECT ie.titulo AS informe, ie.fecha, ie.hora
                                    FROM informe_estudiante ie
                                    WHERE ie.estudiante_id = $estudiante_id AND ie.estadoHoras = 'Horas Validadas'";

                            $result = $conn->query($sql);

                            // Variables para acumular las horas y minutos trabajados del estudiante actual
                            $horasTotales = 0;
                            $minutosTotales = 0;

                            // Comprobar si hay resultados
                            if ($result->num_rows > 0) {
                                // Output de los datos de la consulta
                                while ($row = $result->fetch_assoc()) {
                                    // Dividir el formato de tiempo "HH:MM:SS" y sumar las horas y minutos
                                    list($horas, $minutos, $segundos) = explode(':', $row["hora"]);
                                    $horasTotales += $horas;
                                    $minutosTotales += $minutos;

                                    // Ajustar si los minutos superan 60
                                    $horasTotales += floor($minutosTotales / 60);
                                    $minutosTotales = $minutosTotales % 60;

                                    echo "<tr>
                                            <td>".$row["informe"]."</td>
                                            <td>".$row["fecha"]."</td>
                                            <td>{$row["hora"]}</td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No hay informes validados disponibles.</td></tr>";
                            }

                            // Mostrar el total de horas y minutos del estudiante actual y verificar el límite
                            echo "<tr>
                                    <td colspan='2'><strong>Total Horas Trabajadas</strong></td>
                                    <td>{$horasTotales} h {$minutosTotales} min</td>
                                </tr>";

                            // Verificar el nuevo límite de 240 horas para el estudiante actual
                            $totalMinutos = $horasTotales * 60 + $minutosTotales;
                            if ($totalMinutos >= 240 * 60) {
                                echo "<tr><td colspan='3'><strong>Cumplió con las 240 horas.</strong></td></tr>";
                            }
                        } else {
                            // Manejo de error o redireccionamiento si 'usuario' o 'id' no están definidos
                            echo 'Error: Usuario o ID de estudiante no definidos.';
                        }
                    ?>
            </tbody>
        </table>
    </div>
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
<?php
