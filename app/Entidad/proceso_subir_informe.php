<?php
require_once "../config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar la existencia de los datos del formulario
    if (isset($_POST["titulo_informe"]) && isset($_POST["descripcion_informe"]) && isset($_POST["fecha_informe"])) {
        $tituloInforme = $_POST["titulo_informe"];
        $descripcionInforme = $_POST["descripcion_informe"];
        $fechaInforme = $_POST["fecha_informe"];
        
        // Verificar si se ha cargado un archivo
        if(isset($_FILES["archivo_informe"]) && $_FILES["archivo_informe"]["error"] == 0) {
            // Obtener información del archivo
            $archivoNombre = $_FILES["archivo_informe"]["name"];
            $archivoTipo = $_FILES["archivo_informe"]["type"];
            $archivoTamaño = $_FILES["archivo_informe"]["size"];
            $archivoTemp = $_FILES["archivo_informe"]["tmp_name"];

            // Ruta de la carpeta donde se guardarán los archivos
            $carpetaArchivos = "../../archivos_informes_entidad/";

            // Guardar el archivo en la carpeta en tu servidor
            $rutaArchivo = $carpetaArchivos . $archivoNombre;
            move_uploaded_file($archivoTemp, $rutaArchivo);

            // Insertar datos en la base de datos
            $usuarioId = $_SESSION['usuario']['id'];
            $insertQuery = "INSERT INTO informes_entidad (entidad_id, titulo, descripcion, archivo_path, fecha_informe, fecha_subida) VALUES ('$usuarioId', '$tituloInforme', '$descripcionInforme', '$rutaArchivo', '$fechaInforme', CURRENT_TIMESTAMP)";
            
            if ($conn->query($insertQuery)) {
                // Redirigir después de una inserción exitosa
                header("Location: pagPrincipalentidad.php#informes");
                exit;
            } else {
                echo "Error al insertar en la base de datos: " . $conn->error;
            }
        } else {
            echo "Error: No se ha seleccionado ningún archivo.";
        }
    } else {
        echo "Error: Todos los campos son obligatorios.";
    }
} else {
    echo "Acceso no permitido.";
}

// Cerrar la conexión
$conn->close();
?>
