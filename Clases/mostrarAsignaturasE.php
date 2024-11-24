<?php
ob_start("ob_gzhandler");
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");
if (isset($_POST['correoUsuario'])) {
    $correoUsuario = $_POST["correoUsuario"];
    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $asignaturas = $usuario->getAsignaturasE($correoUsuario);

    // Configura las cabeceras para indicar que la respuesta es JSON
    header('Content-Type: application/json');

    // Devuelve las asignaturas como JSON
    echo json_encode($asignaturas);
}
?>