<?php
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");
require_once("../Objetos/Curso.php");
if (isset($_POST['nombre']) && isset($_POST['codigoD'])) {
    $nombre = $_POST['nombre'];
    $codigo = $_POST['codigo'];
    $correoDocente = $_POST['codigoD'];

    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $nuevoCodigo = $usuario->getCodigoCursos($nombre);
    $comprobar = $usuario->insertarAsignaturas($nuevoCodigo, $codigo, $nombre, $correoDocente);
    if ($comprobar === true) {
        echo "Asignatura registrado";
    } else {
        echo "Asignatura no registrada";
    }
}
?>