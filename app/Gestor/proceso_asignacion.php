<?php
require '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['asignar'])) {
        // Obtener los datos del formulario
        $idEstudiante = $_POST['estudiante'];
        $idEntidad = $_POST['entidad'];
        $detalles = $_POST['detalles'];

        // Verificar si hay estudiantes disponibles
        $sqlVerificarEstudiantes = "SELECT COUNT(*) as total_estudiantes FROM registrar_usuarios WHERE rol = 'estudiante' AND pertenece = 'ninguno'";
        $resultEstudiantes = $conn->query($sqlVerificarEstudiantes);

        if ($resultEstudiantes->num_rows > 0) {
            $rowEstudiantes = $resultEstudiantes->fetch_assoc();
            $totalEstudiantes = $rowEstudiantes['total_estudiantes'];

            if ($totalEstudiantes > 0) {
                // Verificar si hay cupo disponible
                $sqlVerificarCupo = "SELECT cupo_disponible FROM convenios WHERE entidad_id = $idEntidad";
                $result = $conn->query($sqlVerificarCupo);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $cupoDisponible = $row['cupo_disponible'];

                    if ($cupoDisponible > 0) {

                        // Actualizar el campo 'entidad' en la tabla 'usuarios'
                        $sqlActualizarUsuarios = "UPDATE usuarios SET pertenece = 'pertenece', entidad = $idEntidad WHERE id = $idEstudiante";

                        if ($conn->query($sqlActualizarUsuarios) === FALSE) {
                            $_SESSION["mensaje_error"] = "Error al actualizar el campo entidad en la tabla usuarios: " . $conn->error;
                        }

                        // Actualizar el campo 'entidad' en la tabla 'registrar_usuarios'
                        $sqlActualizarRegistroUsuarios = "UPDATE registrar_usuarios SET pertenece = 'pertenece',  entidad = $idEntidad WHERE id = $idEstudiante";

                        if ($conn->query($sqlActualizarRegistroUsuarios) === FALSE) {
                            $_SESSION["mensaje_error"] = "Error al actualizar el campo entidad en la tabla registrar_usuarios: " . $conn->error;
                        }

                        // Restar un cupo en la tabla de convenios
                        $sqlRestarCupo = "UPDATE convenios SET cupo_disponible = cupo_disponible - 1 WHERE entidad_id = $idEntidad";

                        if ($conn->query($sqlRestarCupo) === FALSE) {
                            $_SESSION["mensaje_error"] = "Error al restar el cupo disponible en la tabla convenios: " . $conn->error;
                        }

                       // $_SESSION["mensaje_confirmacion"] = "Asignación realizada exitosamente.";
                    } else {
                        $_SESSION["mensaje_error"] = "No hay cupo disponible para asignar más estudiantes.";
                    }
                } else {
                    $_SESSION["mensaje_error"] = "Error al obtener el cupo disponible de la entidad.";
                }
            } else {
                $_SESSION["mensaje_error"] = "No hay estudiantes disponibles para asignar.";
            }
        } else {
            $_SESSION["mensaje_error"] = "Error al verificar la disponibilidad de estudiantes.";
        }

        // Redireccionar de nuevo a la página del gestor
        header("Location: pagPrincipalGestor.php#gestor");
        exit();
    }
}
?>
