<?php
require_once "../config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar la existencia de los datos del formulario de renovación de convenio
    if (isset($_POST["anos_renovacion"])) {
        $anosRenovacion = $_POST["anos_renovacion"];

        // Obtener datos del usuario desde la base de datos
        $usuarioId = $_SESSION['usuario']['id'];
        $selectQuery = "SELECT nombres, apellidos FROM usuarios WHERE id = '$usuarioId'";
        $result = $conn->query($selectQuery);

        if ($result && $row = $result->fetch_assoc()) {
            // Datos del usuario encontrados
            $nombreEmpresa = $row['nombres'];
            $cupoDisponible = $row['apellidos'];

            // Verificar si ya existe un convenio para esa empresa
            $selectQuery = "SELECT * FROM renovacion_convenio WHERE usuario_id = '$usuarioId'";
            $result = $conn->query($selectQuery);

            if ($result && $result->num_rows > 0) {
                // Ya existe un convenio, actualizar el campo de renovación
                $updateQuery = "UPDATE renovacion_convenio SET renovacion = '$anosRenovacion' WHERE usuario_id  = '$usuarioId'";
                $conn->query($updateQuery); // Ejecutar la actualización
            } else {
                echo "Convenio ID: $usuarioId";
                $insertQuery = "INSERT INTO renovacion_convenio (renovacion, usuario_id) VALUES ('$anosRenovacion', '$usuarioId')";
                $conn->query($insertQuery); // Ejecutar la inserción
            }

            // Redirigir a la página principal de la entidad
            header("Location: pagPrincipalentidad.php#convenio");
            exit;
        } else {
            echo "Error al obtener datos del usuario: " . $conn->error;
        }
    } else {
        echo "El campo 'Número de años de renovación' es obligatorio.";
    }
} else {
    echo "Acceso no permitido.";
}

// Cerrar la conexión
$conn->close();
?>
