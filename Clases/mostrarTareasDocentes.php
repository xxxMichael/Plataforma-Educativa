<?php
ob_start("ob_gzhandler");
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");

if (isset($_POST['nombreTarea'])) {
    $nombreTarea = $_POST['nombreTarea'];
    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();
    $consulta = $usuario->getTareasEnviadas($nombreTarea); // Asegúrate de que $correoUsuario esté definido antes de esto
    $tareas = array();

    // Recorre las tareas obtenidas y agrega los datos específicos al array
    foreach ($consulta as $tarea) {
        // Accede a las propiedades de la clase Tarea con la flecha (->)
        $correoEstudiante = $tarea->getCorreoEstudiante();
        $nombreTarea = $tarea->getNombreTarea();
        $codigoAsignatura = $tarea->getCodigoAsignatura();
        $archivo = $tarea->getArchivo();
        $calificacion = $tarea->getCalificacion();
        $comentario = $tarea->getComentario();
        $fecha_envio = $tarea->getFechaEnvio();

        // Agrega los datos de la tarea al array $tareas
        $tareas[] = array(
            'correoEstudiante' => $correoEstudiante,
            'nombreTarea' => $nombreTarea,
            'codigoAsignatura' => $codigoAsignatura,
            'archivo' => $archivo,
            'calificacion' => $calificacion,
            'comentario' => $comentario,
            'fecha_envio' => $fecha_envio
        );
    }

    // Configura las cabeceras para indicar que la respuesta es JSON
    header('Content-Type: application/json');

    // Devuelve las tareas como JSON
    echo json_encode($tareas);
    exit();
}
?>