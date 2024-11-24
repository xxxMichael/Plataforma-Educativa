<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "127.0.0.1";
$username = "root";
$password = "davidgiler21";
$dbname = "proyecto_fundamentos";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     $accion = $_POST["accion"];

        if ($accion === "verificar_correo") {
            $correoElectronico = $_POST["correo-electronico"];

            $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":correo", $correoElectronico);

            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                echo json_encode(["registrado" => true]);
            } else {
                echo json_encode(["registrado" => false]);
            }
            exit();
            
       } else {
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
