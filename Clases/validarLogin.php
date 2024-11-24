<?php
require_once("../Conexion/Conexion.php");
require_once("../Conexion/Gestor_Conexion.php");

// Verificar si se recibieron datos del formulario
if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $usuarioFormulario = $_POST['usuario'];
    $contrasenaFormulario = $_POST['contrasena'];

    $miConexion = new Conexion();
    $usuario = new Gestor_Conexion();

    $valor = $usuario->comprobarLogin($usuarioFormulario, $contrasenaFormulario);

    if ($valor === 1) {
        http_response_code(401); // Unauthorized
    } elseif ($valor === 2) {
        http_response_code(200); // OK
    } else {
        http_response_code(404); // Not Found
    }
} else {
    http_response_code(400); // Bad Request
}
?>