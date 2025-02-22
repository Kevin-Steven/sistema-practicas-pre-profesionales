<?php
// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['informe_id'])) {
    require '../config.php';

    // Obtiene el ID del informe desde el formulario
    $informe_id = $_POST['informe_id'];

    // Actualiza el estadoVistoEntidad del informe a 'visto'
    $sqlActualizarInforme = "UPDATE informes_gestor SET estadoVistoEntidad = 'visto' WHERE id = ?";
    
    // Utiliza una consulta preparada para evitar la inyección SQL
    $stmt = $conn->prepare($sqlActualizarInforme);
    $stmt->bind_param("i", $informe_id);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        // Redirige de nuevo a la página de informes del gestor
        header("Location: pagPrincipalentidad.php#gestor");
        exit;
    } else {
        // Maneja errores en la actualización del estado
        echo "Error al actualizar el estado del informe: " . $conn->error;
    }

    // Cierra la conexión y la consulta preparada
    $stmt->close();
    $conn->close();
} else {
    // Si se intenta acceder directamente a este script sin un formulario POST, redirige a la página principal del gestor
    header("Location: pagPrincipalentidad.php#gestor");
    exit;
}
?>
