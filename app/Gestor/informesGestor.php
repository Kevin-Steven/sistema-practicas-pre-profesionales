<?php
session_start();
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $tituloInforme = $_POST['tituloInforme'];
    $fechaInforme = $_POST['fechaInforme'];
    $entidadId = $_POST['entidad'];
    
    // Verificar si se seleccionó un archivo
    if (!empty($_FILES['archivo']['name'])) {
        $archivoNombre = $_FILES['archivo']['name'];
        $archivoTemp = $_FILES['archivo']['tmp_name'];
        $archivoTipo = $_FILES['archivo']['type'];

        // Modificación en la línea siguiente para utilizar __DIR__
        $ruta = __DIR__ . "/../../archivos_informes_gestor/" . $archivoNombre;

        // Crear el directorio si no existe
        if (!file_exists(__DIR__ . "/../../archivos_informes_gestor")) {
            mkdir(__DIR__ . "/../../archivos_informes_gestor", 0777, true);
        }

        // Mover el archivo al directorio de archivos
        if (move_uploaded_file($archivoTemp, $ruta)) {
            // Insertar información en la base de datos
            $sql = "INSERT INTO informes_gestor (titulo, fecha, entidad_id, archivo_path) 
                    VALUES ('$tituloInforme', '$fechaInforme', '$entidadId', '$ruta')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['mensaje'] = 'Informe subido exitosamente.';
                header("Location: /app/Gestor/pagPrincipalgestor.php#informes");
                exit;
            } else {
                // Manejar errores de MySQL
                $_SESSION['mensaje'] = 'Error al subir el informe a la base de datos: ' . mysqli_error($conn);
                header("Location: /app/Gestor/pagPrincipalgestor.php#informes");
                exit;
            }
        } else {
            // Manejar errores al mover el archivo
            $_SESSION['mensaje'] = 'Error al mover el archivo.';
            header("Location: /app/Gestor/pagPrincipalgestor.php#informes");
            exit;
        }
    } else {
        // Manejar caso en que no se seleccionó un archivo
        $_SESSION['mensaje'] = 'Error: Debes seleccionar un archivo.';
        header("Location: /app/Gestor/pagPrincipalgestor.php#informes");
        exit;
    }
} else {
    $_SESSION['mensaje'] = 'Error: Acceso no autorizado.';
    header("Location: /app/Gestor/pagPrincipalgestor.php#informes");
    exit;
}

$conn->close();
?>
