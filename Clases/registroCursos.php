<?php
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");
require_once("../Objetos/Curso.php");
if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $curso = new Curso($nombre);

    $comprobar = $usuario->insertarCursos($curso->getCodigo(), $curso->getNombre());
    if ($comprobar == true) {
        echo "Curso registrado";
    } else {
        echo "Curso no registrado";
    }
}
?>