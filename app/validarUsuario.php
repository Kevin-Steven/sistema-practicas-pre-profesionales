<?php
require_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar la existencia de los datos del formulario de inicio de sesión
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $loginEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $loginPassword = $_POST["password"];

        // Consulta preparada para buscar el usuario por email y contraseña en la tabla usuarios
        $stmt = $conn->prepare("SELECT id, email, contraseña, rol FROM usuarios WHERE email = ? AND contraseña = ?");
        $stmt->bind_param("ss", $loginEmail, $loginPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Almacena toda la fila del resultado en $_SESSION['usuario']
            $_SESSION['usuario'] = $result->fetch_assoc();
            
            // Redirigir según el rol del usuario
            switch ($_SESSION["usuario"]["rol"]) {
                case 'visitante' :
                    header("Location: /app/pagPrincipal.php");
                    break;
                case 'estudiante':
                    header("Location: /app/Estudiante/pagEstudiante.php");
                    break;
                case 'gestor':
                    header("Location: /app/Gestor/pagPrincipalgestor.php");
                    break;
                case 'entidad':
                    header("Location: /app/Entidad/pagPrincipalentidad.php");
                    break;
                case 'coordinador':
                    header("Location: /app/Coordinador/pagPrincipalCoordinador.php");
                    break;
                default:
                    // Si el rol no coincide con ninguno de los casos, redirige a una página por defecto o muestra un mensaje de error
                    header("Location: /app/pagPrincipal.php");
                    break;
            }
            exit;
        } else {
            // Usuario no válido, mostrar mensaje de error
            $_SESSION["mensaje"] = "Datos incorrectos.";
            header("Location: ../login.php");   // Ajusta la página de destino según tus necesidades
            exit;
        }

    } else {
        $_SESSION["mensaje"] = "Todos los campos son obligatorios.";
        header("Location: ../login.php");  // Ajusta la extensión si es necesario
        exit;
    }
} else {
    // Redirigir a la página de inicio si se intenta acceder directamente a login.php sin enviar el formulario
    header("Location: ../login.php");  // Ajusta la extensión si es necesario
    exit;
}

// Cerrar conexión
$conn->close();

// Cierre de sesión
session_write_close();
?>
