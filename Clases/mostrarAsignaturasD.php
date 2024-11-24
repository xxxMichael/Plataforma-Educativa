<?php
ob_start("ob_gzhandler");
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");
if (isset($_POST['correoUsuario'])) {
    $correoUsuario = $_POST["correoUsuario"];
    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $asignaturas = $usuario->getAsignaturasD($correoUsuario);
    //var_dump($asignaturas);
    // Configura las cabeceras para indicar que la respuesta es JSON
    $asignaturasArray = array_map(function ($asignatura) {
        return $asignatura->toArray();
    }, $asignaturas);

    // Configura las cabeceras para indicar que la respuesta es JSON
    header('Content-Type: application/json');

    // Devuelve las asignaturas como JSON
    echo json_encode($asignaturasArray);
    die;
}
?>