<?php
// eliminar_actividad.php

session_start();
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_actividad'])) {
    $id_actividad = $_POST['id_actividad'];

    $sql = "DELETE FROM registro_actividades WHERE id = '$id_actividad'";

    if ($conn->query($sql) === TRUE) {

        header("Location: /app/Estudiante/pagEstudiante.php?res=success_delete_activity");
        exit;
    } else {
        echo 'Error de MySQL: ' . mysqli_error($conn);
    }
} else {
    echo 'Error: Acceso no autorizado.';
    exit;
}

$conn->close();
?>
