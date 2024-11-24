<?php
function generarCodigoVerificacion($conexion) {
    try {
        // Consulta para obtener el último código de usuario
        $sql = "SELECT MAX(codigo_usuario) as max_codigo FROM usuarios";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Incrementa el código de usuario en 1
        $ultimoCodigo = $resultado['max_codigo'];
        $nuevoCodigo = $ultimoCodigo + 1;

        // Formatea el código con el prefijo "E"
        $codigoVerificacion = 'E' . str_pad($nuevoCodigo, 5, '0', STR_PAD_LEFT);

        return $codigoVerificacion;
    } catch (PDOException $e) {
        // Lanza una excepción para manejar el error de conexión
        throw new Exception("Error en la conexión: " . $e->getMessage());
    }
}
?>
