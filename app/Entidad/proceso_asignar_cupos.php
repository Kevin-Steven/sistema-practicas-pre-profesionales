<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el formulario fue enviado correctamente
    if (isset($_POST["submit"])) {
        // Obtener los datos del formulario
        $nombre_empresa = $_POST["nombre_empresa"];
        $usuario_id = $_POST["usuario_id"];
        $cupo_disponible = $_POST["cupo_disponible"];

        include("../config.php"); 
        
        // Verificar si ya existe un registro con el mismo nombre de empresa
        $verificar_query = "SELECT id FROM convenios WHERE nombre_empresa = ?";
        $verificar_stmt = $conn->prepare($verificar_query);
        $verificar_stmt->bind_param("s", $nombre_empresa);
        $verificar_stmt->execute();
        $verificar_stmt->store_result();

        if ($verificar_stmt->num_rows > 0) {
            // Actualizar el cupo disponible si la empresa ya existe
            $actualizar_query = "UPDATE convenios SET cupo_disponible = cupo_disponible + ? WHERE nombre_empresa = ?";
            $actualizar_stmt = $conn->prepare($actualizar_query);
            $actualizar_stmt->bind_param("is", $cupo_disponible, $nombre_empresa);

            if ($actualizar_stmt->execute()) {
                header("Location: pagPrincipalentidad.php#cupos");
            } else {
                echo "Error al actualizar cupos: " . $actualizar_stmt->error;
                header("Location: pagPrincipalentidad.php#cupos");
            }

            $actualizar_stmt->close();
        } else {
            // Insertar un nuevo registro si la empresa no existe
            $insertar_query = "INSERT INTO convenios (nombre_empresa, cupo_disponible, entidad_id) VALUES (?, ?, ?)";
            $insertar_stmt = $conn->prepare($insertar_query);
            $insertar_stmt->bind_param("sii", $nombre_empresa, $cupo_disponible, $usuario_id);

            if ($insertar_stmt->execute()) {
                echo "Cupos asignados correctamente.";
                header("Location: pagPrincipalentidad.php#cupos");
            } else {
                echo "Error al asignar cupos: " . $insertar_stmt->error;
                header("Location: pagPrincipalentidad.php#cupos");
            }

            $insertar_stmt->close();
        }

        // Cerrar la conexión
        $verificar_stmt->close();
        $conn->close();
    } else {
        echo "Acceso no autorizado";
    }
} else {
    echo "Acceso no autorizado";
}
?>
