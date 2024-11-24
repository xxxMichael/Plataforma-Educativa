<?php
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");

if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['codigoAsignatura']) && isset($_POST['correoUsuario'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $codigoA = $_POST['codigoAsignatura'];
    $correoUsuario = $_POST['correoUsuario'];

    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $fechaActual = new DateTime();
    $fechaString = $fechaActual->format('Y-m-d');
    $valor = $usuario->asignarTarea($nombre, $codigoA, $descripcion, $fechaString, $correoUsuario);

    if ($valor === true) {
        $estudiantes = $usuario->obtenerEstudiantesPorAsignatura($codigoA);
        $mensaje = "Nueva tarea:.$descripcion";
        
        foreach ($estudiantes as $estudiante) {
            $usuario->insertarNotificacion($nombre ,$estudiante, $mensaje, $fechaString);
        }
        echo "<script>alert('se a enviado su tarea'); window.location='../Vista/inicio.html'</script>";
    } else {
        echo "tarea no asignada";
    }


}



?>