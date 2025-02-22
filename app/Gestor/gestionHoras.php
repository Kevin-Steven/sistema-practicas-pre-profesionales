<?php
session_start();
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_POST['id_estudiante'];
    $id_actividad = $_POST['id_actividad'];

    if ($_POST['accion'] === 'validar') {
        // Lógica para validar las horas (actualiza el campo estadoHoras en informe_estudiante)
        $sqlActualizarEstadoHoras = "UPDATE informe_estudiante SET estadoHoras = 'Horas Validadas' WHERE id = $id_actividad";

        if ($conn->query($sqlActualizarEstadoHoras) === TRUE) {
            $_SESSION['mensaje'] = "Horas validadas correctamente";
        } else {
            $_SESSION['mensaje'] = "Error al validar las horas: " . $conn->error;
        }
    } elseif ($_POST['accion'] === 'denegar') {
        // Lógica para denegar las horas (actualiza el campo estadoHoras en informe_estudiante)
        $sqlActualizarEstadoHoras = "UPDATE informe_estudiante SET estadoHoras = 'Horas Invalidas' WHERE id = $id_actividad";

        if ($conn->query($sqlActualizarEstadoHoras) === TRUE) {
            $_SESSION['mensaje'] = "Horas denegadas correctamente";
        } else {
            $_SESSION['mensaje'] = "Error al denegar las horas: " . $conn->error;
        }
    }

    header("Location: /app/Gestor/pagPrincipalgestor.php#monitorear");
    exit();
} else {
    // Manejo de error si la solicitud no es de tipo POST
    $_SESSION['mensaje'] = "Acceso no permitido";
    header("Location: /app/Gestor/pagPrincipalgestor.php#monitorear");
    exit();
}
?>

