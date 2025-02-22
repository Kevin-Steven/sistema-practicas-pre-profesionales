<?php
require_once "config.php";

// Constantes para mensajes
define("MSG_CAMPOS_OBLIGATORIOS", "Todos los campos son obligatorios.");
define("MSG_EMAIL_INVALIDO", "El correo electrónico no es válido.");
define("MSG_CONTRASENA_CORTA", "La contraseña debe tener al menos 8 caracteres.");
define("MSG_CONTRASENAS_NO_COINCIDEN", "Las contraseñas no coinciden.");
define("MSG_REGISTRO_EXITOSO", "Usuario registrado exitosamente.");
define("MSG_ERROR_REGISTRO", "Error al registrar el usuario.");

function redirigir($ubicacion) {
    header("Location: $ubicacion");
    exit;
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar la existencia de los datos del formulario
    if (
        isset($_POST["register-email"]) &&
        isset($_POST["register-password"]) &&
        isset($_POST["confirm-password"])
    ) {
        // Recuperar y limpiar datos del formulario de registro
        $email = filter_var($_POST["register-email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["register-password"];
        $confirmPassword = $_POST["confirm-password"];

        // Validar los datos del formulario
        if (empty($email) || empty($password) || empty($confirmPassword)) {
            $_SESSION["mensaje"] = MSG_CAMPOS_OBLIGATORIOS;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["mensaje"] = MSG_EMAIL_INVALIDO;
        } elseif (strlen($password) < 8) {
            $_SESSION["mensaje"] = MSG_CONTRASENA_CORTA;
        } elseif ($password !== $confirmPassword) {
            $_SESSION["mensaje"] = MSG_CONTRASENAS_NO_COINCIDEN;
        } else {
            // Hash de la contraseña (deberías utilizar un método seguro para almacenar contraseñas en producción)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if ($hashedPassword === false) {
                $errorInfo = error_get_last();
                $_SESSION["mensaje"] = "Error al generar el hash de la contraseña. Detalles: " . $errorInfo['message'];
            } else {
                // Intentar realizar la inserción en la base de datos
                try {
                    // Consulta preparada para la inserción en la base de datos
                    $sql = "INSERT INTO usuarios (email, contraseña) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);

                    // Vincular parámetros
                    $stmt->bind_param("ss", $email, $hashedPassword);

                    // Ejecutar la consulta
                    if ($stmt->execute()) {
                        $_SESSION["mensaje"] = MSG_REGISTRO_EXITOSO;
                        redirigir("registro_exitoso.php");
                    } else {
                        $_SESSION["mensaje"] = MSG_ERROR_REGISTRO;
                    }

                    // Cerrar declaración
                    $stmt->close();
                } catch (mysqli_sql_exception $e) {
                    $_SESSION["mensaje"] = MSG_ERROR_REGISTRO . " Detalles: " . $e->getMessage();
                }
            }
        }

        // Redirigir de nuevo a la página de registro
        redirigir("registro.php");
    } else {
        // Manejar el caso en que los datos del formulario no están definidos
        $_SESSION["mensaje"] = "Error: Datos del formulario no definidos.";
        redirigir("registro.php");
    }
} else {
    // Redirigir a la página de inicio si se intenta acceder directamente a register.php sin enviar el formulario
    redirigir("index.html");
}

// Cerrar conexión
$conn->close();

// Cierre de sesión
session_write_close();
?>
