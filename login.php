<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/estilos.css">
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
 
             <!-- Formulario de Inicio de Sesión -->
             <form id="login-form" action="app/validarUsuario.php" method="POST">
                <h2>Iniciar sesión</h2>
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="text" id="email" name="email" required>
                    <label for="email">Correo electrónico</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock"></ion-icon></span>
                    <input type="password" id="password" name="password" required>
                    <label for="password">Contraseña</label>
                </div>

                <br>

                <button type="submit">Acceder</button>
                
               <div class="register-link">
                    <p>¿No tienes una cuenta? <a href="#" onclick="showRegisterForm()">Registrate</a></p>
                </div>
                
            </form>


            <!-- Formulario para registrarse -->
            <form id="registrar_usua-form" action="app/validarRegistro.php" method="POST" autocomplete="off" style="display: none;">
                <h2>Registrarse</h2>

                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" id="nombres" name="nombres" required>
                    <label for="nombres">Nombres</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" id="apellidos" name="apellidos" required>
                    <label for="apellidos">Apellidos</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="finger-print"></ion-icon></span>
                    <input type="text" id="cedula" name="cedula" required>
                    <label for="cedula">Cedula</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="call"></ion-icon></span>
                    <input type="text" id="telefono" name="telefono" required>
                    <label for="telefono">Teléfono</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="text" id="email" name="email" required>
                    <label for="email">Correo electrónico</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock"></ion-icon></span>
                    <input type="password" id="password" name="password" required>
                    <label for="password">Contraseña</label>
                </div>

                <button type="submit">Registrarse</button>

                <div class="register-link">
                    <p>¿Ya tienes una cuenta? <a href="#" onclick="showLoginForm()">Iniciar Sesión</a></p>
                </div>
            </form>        
        </div>
        
        
    </section>

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    
    <script>
        function showRegisterForm() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('registrar_usua-form').style.display = 'block';
        }

        function showLoginForm() {
            document.getElementById('registrar_usua-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }
    </script>
</body>
</html>