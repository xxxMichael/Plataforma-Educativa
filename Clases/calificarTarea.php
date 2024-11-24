<?php
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");

if (
    isset($_POST['calificacion']) && isset($_POST['comentario']) && isset($_POST['correoUsuario'])
    && isset($_POST['nombreTarea']) && isset($_POST['codigoAsignatura'])
) {
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];
    $correoUsuario = $_POST['correoUsuario'];
    $nombreTarea = $_POST['nombreTarea'];
    $codigoAsignatura = $_POST['codigoAsignatura'];
    $respuesta = array();

    try {
        // Crear una instancia de la conexión
        $miConexion = new Conexion();
        $usuario = new Gestor_Conexion();

        // Calificar la tarea
        $comprobar = $usuario->calificarTarea(
            $calificacion,
            $comentario,
            $correoUsuario,
            $nombreTarea,
            $codigoAsignatura
        );

        if ($comprobar) {
            echo "tarea calificada";
        } else {
            echo "no se califico";
        }


    } catch (Exception $e) {
        echo "error en el try";
    }
}
?>