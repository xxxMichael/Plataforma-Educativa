<?php
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");
require_once("../Objetos/Curso.php");
if (isset($_POST['correoE']) && isset($_POST['codigo'])) {
    $correoElectronico = $_POST['correoE'];
    $codigo = $_POST['codigo'];

    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $asignatura = $usuario->obtenerAsignatura($codigo);
    $comprobar = $usuario->matricular($asignatura, $correoElectronico);
    if ($comprobar === true) {
        echo "matriculado";
    } else {
        echo "no se matriculo";
    }

}
?>