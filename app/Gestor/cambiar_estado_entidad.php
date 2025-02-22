<?php
session_start();
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén el ID del informe desde el formulario
    $idInforme = $_POST['idInforme'];

    // Lógica para actualizar el estado del informe
    $sqlActualizarEstado = "UPDATE informes_entidad SET estadoVistoGestor = 'visto' WHERE id = $idInforme";

    if ($conn->query($sqlActualizarEstado) === TRUE) {
        $_SESSION['mensaje'] = "Informe visto correctamente";
    } else {
        $_SESSION['mensaje'] = "Error al marcar el informe como visto: " . $conn->error;
    }

    // Redirecciona a la página principal con un ancla
    header("Location: /app/Gestor/pagPrincipalgestor.php#informes");
    exit();
} else {
    // Manejo de error si la solicitud no es de tipo POST
    $_SESSION['mensaje'] = "Acceso no permitido";
    header("Location: /app/Gestor/pagPrincipalgestor.php#informes");
    exit();
}
?>
