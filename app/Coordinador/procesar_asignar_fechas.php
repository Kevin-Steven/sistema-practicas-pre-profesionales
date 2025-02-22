<?php
session_start();
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $fechaInicial = $_POST['fecha_inicial'];
    $fechaFinal = $_POST['fecha_final'];
    $entidadId = $_POST['entidad'];

    // Verificar si ya existe un registro para la entidad seleccionada
    $sqlCheck = "SELECT * FROM fechas_asignadas WHERE entidad_id = $entidadId";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        // Si ya existe, actualiza el registro
        $sqlUpdate = "UPDATE fechas_asignadas SET fecha_inicial = '$fechaInicial', fecha_final = '$fechaFinal' WHERE entidad_id = $entidadId";

        if ($conn->query($sqlUpdate) === TRUE) {
            header("Location: pagPrincipalCoordinador.php#asignarFechas");
            exit;
        } else {
            header("Location: pagPrincipalCoordinador.php#asignarFechas");
            exit;
        }
    } else {
        // Si no existe, inserta un nuevo registro
        $sqlInsert = "INSERT INTO fechas_asignadas (fecha_inicial, fecha_final, entidad_id) 
                      VALUES ('$fechaInicial', '$fechaFinal', '$entidadId')";

        if ($conn->query($sqlInsert) === TRUE) {
            header("Location: pagPrincipalCoordinador.php#asignarFechas");
            exit;
        } else {
            header("Location: pagPrincipalCoordinador.php#asignarFechas");
            exit;
        }
    }
} else {
    $_SESSION['mensaje'] = 'Error: Acceso no autorizado.';
    header("Location: pagPrincipalCoordinador.php#asignarFechas");
    exit;
}

$conn->close();
?>
