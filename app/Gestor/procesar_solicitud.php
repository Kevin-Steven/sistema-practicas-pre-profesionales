<?php
require '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['aceptar_solicitud'])) {
        // Procesar la solicitud de aceptar
        $idUsuario = $_POST['id_usuario'];

        // Actualizar el estado y el rol del usuario en registrar_usuarios
        $sqlActualizar = "UPDATE registrar_usuarios SET pertenece ='ninguno', estado = 'aceptado', rol = 'estudiante' WHERE id = $idUsuario";

        if ($conn->query($sqlActualizar) === TRUE) {
            // Actualizar también en la tabla usuarios
            $sqlActualizarUsuarios = "UPDATE usuarios SET pertenece ='ninguno', estado = 'aceptado', rol = 'estudiante' WHERE id = $idUsuario";
            
            if ($conn->query($sqlActualizarUsuarios) === FALSE) {
                $_SESSION["mensaje_error"] = "Error al actualizar la solicitud en la tabla usuarios: " . $conn->error;
            } else {
                //$_SESSION["mensaje_confirmacion"] = "Solicitud aceptada exitosamente.";
            }
        } else {
            $_SESSION["mensaje_error"] = "Error al actualizar la solicitud en la tabla registrar_usuarios: " . $conn->error;
        }
    } elseif (isset($_POST['rechazar_solicitud'])) {
        // Procesar la solicitud de rechazar
        $idUsuario = $_POST['id_usuario'];

        // Actualizar el estado del usuario en registrar_usuarios
        $sqlRechazar = "UPDATE registrar_usuarios SET estado = 'rechazado' WHERE id = $idUsuario";

        if ($conn->query($sqlRechazar) === FALSE) {
            $_SESSION["mensaje_error"] = "Error al actualizar la solicitud en la tabla registrar_usuarios: " . $conn->error;
        } else {
            // Actualizar también en la tabla usuarios
            $sqlRechazarUsuarios = "UPDATE usuarios SET estado = 'rechazado' WHERE id = $idUsuario";

            if ($conn->query($sqlRechazarUsuarios) === FALSE) {
                $_SESSION["mensaje_error"] = "Error al actualizar la solicitud en la tabla usuarios: " . $conn->error;
            } else {
                $_SESSION["mensaje_confirmacion"] = "Solicitud rechazada. Cualquier duda o inconveniente comuniquese con su Gestor de Practicas";
            }
        }
    }

    // Redireccionar de nuevo a la página del gestor
    header("Location: pagPrincipalGestor.php#solicitudes");
    exit();
}
?>
