<?php

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $archivo_id = $_GET['id'];

    
    require '../config.php'; 

    $sql = "SELECT archivo FROM informe_estudiante WHERE id = $archivo_id";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $archivo_nombre = $row['archivo'];
        $archivo_ruta = $_SERVER['DOCUMENT_ROOT'] . "/archivos/$archivo_nombre";
        
        // Verificar si el archivo existe
        if (file_exists($archivo_ruta)) {
            // Configurar encabezados para la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($archivo_nombre) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivo_ruta));

            // Leer el archivo y enviarlo al navegador
            readfile($archivo_ruta);
            exit;
        } else {
            // Manejar el caso donde el archivo no existe
            echo "El archivo no está disponible. Ruta: $archivo_ruta";
        }
    } else {
        // Manejar el caso donde no se encuentra el archivo en la base de datos
        echo "No se encontró información del archivo para el ID proporcionado.";
    }

    $conn->close();
} else {
    // Manejar el caso donde no se proporciona un ID de archivo válido
    echo "ID de archivo no válido.";
}
?>
