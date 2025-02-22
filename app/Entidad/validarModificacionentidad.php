<?php
require_once "../config.php";

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Verificar si se enviaron los datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y sanitizarlos (aquí es donde podrías validarlos también)
    $nuevos_nombres = mysqli_real_escape_string($conn, $_POST['nuevos_nombres']);
    $nuevos_apellidos = mysqli_real_escape_string($conn, $_POST['nuevos_apellidos']);
    $nueva_cedula = mysqli_real_escape_string($conn, $_POST['nueva_cedula']);
    $nuevo_telefono = mysqli_real_escape_string($conn, $_POST['nuevo_telefono']);
    $nuevo_correo = mysqli_real_escape_string($conn, $_POST['nuevo_correo']);
    $nueva_contrasena =  $_POST['nueva_contrasena'];

    // Query para actualizar los datos en la base de datos
    $actualizar_query = "UPDATE registrar_usuarios SET nombres = '$nuevos_nombres', apellidos = '$nuevos_apellidos', cedula = '$nueva_cedula', telefono = '$nuevo_telefono', email = '$nuevo_correo', contraseña = '$nueva_contrasena' WHERE email = '{$usuario['email']}'";

    if (mysqli_query($conn, $actualizar_query)) {
        // Obtener los nuevos datos del usuario
        $consulta_actualizada = "SELECT * FROM registrar_usuarios WHERE email = '$nuevo_correo'";
        $resultado_actualizado = mysqli_query($conn, $consulta_actualizada);
    
        if ($resultado_actualizado) {
            // Asegúrate de que se encontró el usuario
            if (mysqli_num_rows($resultado_actualizado) > 0) {
                $usuario_actualizado = mysqli_fetch_assoc($resultado_actualizado);
                $_SESSION['usuario'] = $usuario_actualizado;
            } else {
                echo "No se encontró al usuario actualizado.";
                // Puedes manejar esto de acuerdo a tu lógica de negocio
            }
        } else {
            echo "Error al obtener los datos actualizados: " . mysqli_error($conn);
        }
    
        // Redirigir a alguna página de éxito o mostrar un mensaje de éxito
        header("Location: pagPrincipalentidad.php");
        exit;
    } else {
        // Manejar el error si la actualización falla
        echo "Error al actualizar los datos: " . mysqli_error($conn);
    }
}
?>