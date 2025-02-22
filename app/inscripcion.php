<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_inscripcion'])) {
    $carrera = $_POST['carrera'];
    $nivel = $_POST['nivel'];
    $estado = $_POST['estado'];

    if (!empty($carrera) && !empty($nivel)) {
        $email = $_SESSION['usuario']['email'];

        $sql = "UPDATE registrar_usuarios SET carrera = '$carrera', nivel = '$nivel', estado = '$estado' WHERE email = '$email'";

        if ($conn->query($sql) === TRUE) {
            // Almacenar el mensaje de confirmación en la variable de sesión
            $_SESSION["mensaje_confirmacion"] = 'Le informamos que su suscripción ha sido realizada correctamente. Una vez que su solicitud sea aceptada, podrá iniciar sesión en el portal de estudiantes con sus datos de acceso. Si su solicitud no es aceptada, recibirá un mensaje en este apartado.';
            
            // Redireccionar a la página principal con el ancla "#inscripcion"
            header("Location: pagPrincipal.php#inscripcion");
            exit();
        } else {
            echo "Error al actualizar datos: " . $conn->error;
            echo "<br>Consulta ejecutada: " . $sql;
        }
    } else {
        // Almacenar el mensaje de denegación en la variable de sesión
        $_SESSION["solicitud_denegada"] = 'Lamentamos informarle que su solicitud ha sido denegada.';
        
        // Redireccionar a la página principal con el ancla "#inscripcion"
        header("Location: pagPrincipal.php#inscripcion");
        exit();
    }
}
?>