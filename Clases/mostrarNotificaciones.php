<?php
ob_start("ob_gzhandler");
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");
if (isset($_POST['correoUsuario'])) {
    $correoUsuario = $_POST["correoUsuario"];
    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $notificaciones = $usuario->obtenerNotificacionesNoLeidas($correoUsuario);

    $notificacionesArray = array_map(function ($notificacion) {
        return $notificacion->toArray();
    }, $notificaciones);

    // Configura las cabeceras para indicar que la respuesta es JSON
    header('Content-Type: application/json');
    //var_dump($notificaciones);
    // Devuelve las asignaturas como JSON
    echo json_encode($notificacionesArray);
}
?>