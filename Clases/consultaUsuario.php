<?php
require_once("../Conexion/Gestor_Conexion.php");

// Manejar la solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correoUsuario = $_POST["correo"];

    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();

    // Consultar la información del usuario
    $datosUsuario = $usuario->consultarUsuario($correoUsuario);

    // Verificar si hubo un error en la consulta
    if ($datosUsuario === false) {
        http_response_code(500); // Código de error del servidor
        echo json_encode(array("error" => "Error en la consulta de usuario"));
    } elseif ($datosUsuario === null) {
        // Aquí, puedes decidir si devolver un error o simplemente un usuario vacío
        echo json_encode(array("error" => "Usuario no encontrado"));
    } else {
        // Devolver los datos del usuario como JSON
        echo json_encode($datosUsuario);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Solicitud no válida"));
}
?>