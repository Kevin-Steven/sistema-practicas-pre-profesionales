<?php
require_once "config.php";

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$usuario = $_SESSION['usuario'];


$consulta = "SELECT nombres, apellidos, cedula, telefono, contraseña FROM registrar_usuarios WHERE email = '{$usuario['email']}'";

$resultado_usuario = mysqli_query($conn, $consulta); // Utilizando la conexión $conn

if ($resultado_usuario) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    if ($fila_usuario) {
        $nombres = $fila_usuario['nombres'];
        $apellidos = $fila_usuario['apellidos'];
        $cedula = $fila_usuario['cedula'];
        $telefono = $fila_usuario['telefono'];
        $contrasena = $fila_usuario['contraseña'];

        // Asigna los valores a $datos_usuario con los datos reales obtenidos de la base de datos
        $datos_usuario = [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'cedula' => $cedula,
            'telefono' => $telefono,
            'email' => $usuario['email'],
            'contrasena' => $contrasena // Se recomienda no mostrar la contraseña
        ];
    } 
} 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar usuario</title>
    <link rel="stylesheet" href="/css/modificar.css">
    <link rel="icon" href="/image/a.png" type="image/png">
</head>
<body>
    <section>
    <div class="login-box">
    <?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_SESSION["mensaje"])): ?>
    <div class="mensaje-error">
        <p><?= $_SESSION["mensaje"] ?></p>
    </div>
    <?php
    // Limpiar el mensaje después de mostrarlo
    unset($_SESSION["mensaje"]);
    ?>
<?php endif; ?>
        <form id="modificar-form" action="validarModificacion.php" method="POST" autocomplete="off">
        <h2>Editar Perfil</h2>
        
        <div class="input-box">
            <span class="icon"><ion-icon name="person"></ion-icon></span>
            <label for="nuevos_nombres">Nombres:</label>
            <input type="text" id="nuevos_nombres" name="nuevos_nombres" required value="<?php echo $nombres; ?>"><br>
        </div>

        <div class="input-box">
            <span class="icon"><ion-icon name="person"></ion-icon></span>
            <label for="nuevos_apellidos">Apellidos:</label>
            <input type="text" id="nuevos_apellidos" name="nuevos_apellidos" required value="<?php echo $apellidos; ?>"><br>
        </div>

        <div class="input-box">
            <span class="icon"><ion-icon name="finger-print"></ion-icon></span>
            <label for="nueva_cedula">Cédula:</label>
            <input type="text" id="nueva_cedula" name="nueva_cedula" required value="<?php echo $cedula; ?>"><br>
        </div>

        <div class="input-box">
            <span class="icon"><ion-icon name="call"></ion-icon></span>
            <label for="nuevo_telefono">Teléfono:</label>
            <input type="text" id="nuevo_telefono" name="nuevo_telefono" required value="<?php echo $telefono; ?>"><br>
        </div>

        <div class="input-box">
            <span class="icon"><ion-icon name="mail"></ion-icon></span>
            <label for="nuevo_correo">Correo Electrónico:</label>
            <input type="email" id="nuevo_correo" name="nuevo_correo" required value="<?php echo $usuario['email']; ?>"><br>
        </div>

        <div class="input-box">
            <span class="icon"><ion-icon name="lock"></ion-icon></span>
            <label for="nueva_contrasena">Contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" required value="<?php echo isset($datos_usuario['contrasena']) ? $datos_usuario['contrasena'] : ''; ?>"><br><br>
        </div>
    
        <button type="submit">Actualizar información</button>
        
        <div class="volver">
            <a  href="pagPrincipal.php">Volver</a>
        </div>
    </form>
    </section>
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
</body>
</html>