<?php
ob_start("ob_gzhandler");
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");

if (isset($_POST['correoUsuario'])) {
    $correoUsuario = $_POST["correoUsuario"];
    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $asignaturas = $usuario->getAsignaturasD($correoUsuario);
    $tareas = array();

    foreach ($asignaturas as $asignatura) {
        $codigoAsignatura = $asignatura->getCodigo();
        $nombreA = $asignatura->getNombre();
        $tareasAsignatura = $usuario->getTareas($codigoAsignatura);

        // Create an array to store tasks separately
        $tareasAsignaturaArray = array();

        foreach ($tareasAsignatura as $tarea) {
            // Extract task parameters
            $nombreTarea = $tarea['nombre'];
            $codigoAsignaturaTarea = $tarea['codigoAsignatura'];
            $descripcionTarea = $tarea['descripcion'];
            $fechaAsignacionTarea = $tarea['fecha_asignacion'];
            $correoDocenteTarea = $tarea['correoDocente'];

            // Add task parameters to the task array
            $tareasAsignaturaArray[] = array(
                'nombre' => $nombreTarea,
                'codigoAsignatura' => $codigoAsignaturaTarea,
                'descripcion' => $descripcionTarea,
                'fecha_asignacion' => $fechaAsignacionTarea,
                'correoDocente' => $correoDocenteTarea,
            );
        }

        // Add assignment and its tasks to the main tasks array
        $tareas[] = array(
            'nombreAsignatura' => $nombreA,
            'codigoAsignatura' => $codigoAsignatura,
            'tareas' => $tareasAsignaturaArray,
        );
    }

    // Configura las cabeceras para indicar que la respuesta es JSON
    header('Content-Type: application/json');

    // Devuelve las tareas como JSON;
    echo json_encode($tareas);
}
?>