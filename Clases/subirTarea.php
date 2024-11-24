<?php
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");

header('Content-Type: application/json');

if (
    isset($_POST['correoUsuario']) &&
    isset($_POST['nombreTarea']) &&
    isset($_POST['codigoAsignatura']) &&
    isset($_FILES['archivo'])
) {
    $correoUsuario = $_POST['correoUsuario'];
    $nombreTarea = $_POST['nombreTarea'];
    $codigoAsignatura = $_POST['codigoAsignatura'];
    $nombreBase = basename($_FILES['archivo']["name"]);
    $nombreFinal = date("m-d-y") . "-" . date("H-i-s") . "-" . $nombreBase;
    $ruta = "../archivos/" . $nombreFinal;

    // Mover archivo y verificar la operación
    if (move_uploaded_file($_FILES['archivo']["tmp_name"], $ruta)) {
        $miConexion = new Conexion();
        $usuario = new Gestor_Conexion();
        $fechaActual = new DateTime();
        $fechaString = $fechaActual->format('Y-m-d');

        // Insertar en la base de datos
        $comprobar = $usuario->insertarTarea($correoUsuario, $nombreTarea, $codigoAsignatura, $ruta, 0, "", $fechaString);
        if ($comprobar) {
            // Tarea subida con éxito
            echo json_encode(['success' => true, 'message' => 'Tarea subida con éxito']);
        } else {
            // Error al insertar en la base de datos
            echo json_encode(['success' => false, 'message' => 'Error: La tarea no se subió a la base de datos']);
        }
    } else {
        // Error al mover el archivo
        echo json_encode(['success' => false, 'message' => 'Error: El archivo no se movió correctamente']);
    }
} else {
    // Datos del formulario no proporcionados correctamente
    echo json_encode(['success' => false, 'message' => 'Error: Campos del formulario no proporcionados correctamente']);
}
?>