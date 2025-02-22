<?php
session_start();
include("../config.php"); 

if (isset($_POST['visto']) && isset($_POST['informe_id'])) {
    $informe_id = $_POST['informe_id'];

    // Realizar la actualización del estado en la base de datos
    $sql_update_estado = "UPDATE informe_estudiante SET estadoVistoEntidad = 'visto' WHERE id = ?";
    $stmt = $conn->prepare($sql_update_estado);
    $stmt->bind_param("i", $informe_id);
    $stmt->execute();
    $stmt->close();

    // Redirigir a la página principal u otra página después de procesar
    header("Location: pagPrincipalentidad.php#monitorear");
    exit();
} else {
    // Manejo de error si no se proporcionan datos válidos
    echo "Error: Datos no válidos.";
}
?>