<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "127.0.0.1";
$username = "root";
$password = "davidgiler21";
$dbname = "proyecto_fundamentos";

// Verificar si se envió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Crear la conexión usando PDO
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener datos del formulario
        $accion = $_POST["accion"];

        if ($accion === "login") {
            // Solicitud de inicio de sesión
            $username = $_POST["usuario"];
            $password = $_POST["contrasena"];

            // Consulta preparada para evitar inyección SQL
            $sql = "SELECT * FROM usuarios WHERE correo = :usuario AND contraseña = :contrasena";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":usuario", $username);
            $stmt->bindParam(":contrasena", $password);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                http_response_code(200); // Código de respuesta OK
                exit();
            } else {

                http_response_code(401); // Código de respuesta no autorizado
                exit();
            }
        } elseif ($accion === "registro") {
            $correoElectronico = $_POST["correo-electronico-registro"];
            $nuevaContrasena = $_POST["nueva-contrasena"];
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $rol = $_POST["rol"];
            $codigoUsuario = $_POST["codigo-usuario"]; // Obtener el valor de código de usuario desde el formulario

            // Verificar si el usuario ya existe
            $sqlUsuarioExistente = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";
            $stmtUsuarioExistente = $conexion->prepare($sqlUsuarioExistente);
            $stmtUsuarioExistente->bindParam(":correo", $correoElectronico);
            $stmtUsuarioExistente->execute();

            if ($stmtUsuarioExistente->fetchColumn() > 0) {
                http_response_code(409); // Código de respuesta de conflicto (usuario ya existe)
                echo json_encode(["error" => "Usuario ya registrado"]);
                exit();
            }

            // Si no existe, proceder con la inserción
            $sql = "INSERT INTO usuarios (correo, contraseña, nombre, apellido, rol, codigo_usuario) VALUES (:correo, :contrasena, :nombre, :apellido, :rol, :codigoUsuario)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":correo", $correoElectronico);
            $stmt->bindParam(":contrasena", $nuevaContrasena);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":apellido", $apellido);
            $stmt->bindParam(":rol", $rol);
            $stmt->bindParam(":codigoUsuario", $codigoUsuario); // Vincular el valor de código de usuario

            $stmt->execute();

            http_response_code(200); // Código de respuesta OK
            exit();
        } else {
            // Acción no válida
            http_response_code(400); // Código de respuesta de solicitud incorrecta
            exit();
        }
    } catch (PDOException $e) {
        $error_message = "Error en la conexión: " . $e->getMessage();
        http_response_code(500); // Código de respuesta de error interno del servidor
        echo json_encode(["error" => $error_message]);
        exit();
    }
}
?>