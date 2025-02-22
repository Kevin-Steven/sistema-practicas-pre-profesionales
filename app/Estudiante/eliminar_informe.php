<?php
session_start();
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_informe'])) {
    $id_informe = $_POST['id_informe'];

    // Obtén la ruta del archivo asociado al informe antes de eliminarlo de la base de datos
    $sql_select = "SELECT ruta FROM informe_estudiante WHERE id = '$id_informe'";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ruta_archivo = $row['ruta'];

        // Asegúrate de que la ruta del archivo esté en formato correcto
        $ruta_archivo = str_replace('\\', '/', $ruta_archivo);

        // Obten la carpeta raíz del proyecto
        $root_folder = dirname(__DIR__);

        // Construye la ruta completa al archivo utilizando realpath
        $archivo_completo = realpath($root_folder . '/' . $ruta_archivo);

        // Agrega mensajes de depuración
        echo 'Ruta Archivo: ' . $ruta_archivo . '<br>';
        echo 'Root Folder: ' . $root_folder . '<br>';

        // Construye la ruta completa al archivo utilizando realpath solo en la parte relativa
        $ruta_relativa = '/../../archivos/' . $ruta_archivo;
        $archivo_completo = realpath(__DIR__ . $ruta_relativa);

        // Verifica que la ruta sea válida antes de intentar eliminar el archivo
        if ($archivo_completo && file_exists($archivo_completo)) {
            // Intenta eliminar el archivo
            if (unlink($archivo_completo)) {
                // Continúa con la eliminación en la base de datos
                $sql_delete = "DELETE FROM informe_estudiante WHERE id = '$id_informe'";
                if ($conn->query($sql_delete) === TRUE) {
                    header("Location: /app/Estudiante/pagEstudiante.php?res=success_delete&mensaje=Informe+eliminado+exitosamente#misregistros");
                    exit;
                } else {
                    echo 'Error de MySQL: ' . mysqli_error($conn);
                }
            } else {
                echo 'Error al intentar eliminar el archivo. Posible problema de permisos.';
            }
        } else {
            echo 'Error al eliminar el archivo. Ruta: ' . $archivo_completo;
        }
    } else {
        echo 'No se encontró el informe asociado.';
    }
} else {
    echo 'Error: Acceso no autorizado.';
    exit;
}

$conn->close();
?>
