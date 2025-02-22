<?php
session_start();
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $estudiante_id = $_SESSION['usuario']['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $horas = $_POST['horas'];

    // Verificar si se seleccionó un archivo
    if (!empty($_FILES['archivo']['name'])) {
        $archivo_nombre = $_FILES['archivo']['name'];
        $archivo_temp = $_FILES['archivo']['tmp_name'];
        $archivo_tipo = $_FILES['archivo']['type'];

        // Modificación en la línea siguiente para utilizar __DIR__
        $ruta = __DIR__ . "/../../archivos/" . $archivo_nombre;

        // Crear el directorio si no existe
        if (!file_exists(__DIR__ . "/../../archivos")) {
            mkdir(__DIR__ . "/../../archivos", 0777, true);
        }

        // Mover el archivo al directorio de archivos
        if (move_uploaded_file($archivo_temp, $ruta)) {
            // Insertar información en la base de datos
            $sql = "INSERT INTO informe_estudiante (estudiante_id, titulo, descripcion, fecha, hora, archivo, ruta) 
                    VALUES ('$estudiante_id', '$titulo', '$descripcion', '$fecha', '$horas', '$archivo_nombre', '$ruta')";

            if ($conn->query($sql) === TRUE) {
                header("Location: /app/Estudiante/pagEstudiante.php?res=success#informes");
                exit;
            } else {
                // Manejar errores de MySQL
                echo 'Error de MySQL: ' . mysqli_error($conn);
            }
        } else {
            // Manejar errores al mover el archivo
            echo 'Error al mover el archivo.';
        }
    } else {
        // Manejar caso en que no se seleccionó un archivo
        echo 'Error: Debes seleccionar un archivo.';
    }
} else {
    echo 'Error: Acceso no autorizado.';
    exit;
}

$conn->close();
?>
