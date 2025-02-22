<?php
require_once "config.php";
session_start();

$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$cedula = $_POST['cedula'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para insertar datos en la base de datos
$sql = "INSERT INTO registrar_usuarios (nombres, apellidos, cedula, telefono, email, contraseña) VALUES ('$nombres', '$apellidos', '$cedula', '$telefono', '$email', '$password')";

if ($conn->query($sql) === TRUE) {

    header("Location: ../login.php");  // Ajusta la extensión si es necesario
    exit;
} else {
    echo "Error al registrar: " . $sql . "<br>" . $conn->error;
    header("Location: ../login.php");   // Ajusta la página de destino según tus necesidades
    exit;
}

// Cerrar la conexión
$conn->close();

?>