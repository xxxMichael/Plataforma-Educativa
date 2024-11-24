<?php
require_once("Conexion.php");
require_once("Gestor_Conexion.php");
require_once("../Objetos/Asignatura.php");
require_once("../Objetos/Tarea.php");
require_once("../Objetos/Persona.php");
require_once("../Objetos/Profesor.php");
require_once("../Objetos/Estudiante.php");
require_once("../Objetos/Notificacion.php");

class Gestor_Conexion extends Conexion
{
    private $conexion;


    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function insertarUsuario($correoElectronico, $nuevaContrasena, $nombre, $apellido, $rol, $codigoUsuario)
    {
        try {
            $sql = "INSERT INTO usuarios (correo, contraseña, nombre, apellido, rol, codigo_usuario) VALUES (:correo, :contrasena, :nombre, :apellido, :rol, :codigoUsuario)";
            $stmt = $this->conexion->getConect()->prepare($sql);
            $stmt->bindParam(":correo", $correoElectronico);
            $stmt->bindParam(":contrasena", $nuevaContrasena);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":apellido", $apellido);
            $stmt->bindParam(":rol", $rol);
            $stmt->bindParam(":codigoUsuario", $codigoUsuario); // Vincular el valor de código de usuario
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            echo "Usuario ya existente";
            return false;
        }
    }

    public function insertarCursos($codigo, $nombre)
    {
        #$nuevoEstudiante = new Estudiante($nombre, $apellido, $correo, $clave);
        try {
            $sql = "INSERT INTO cursos(codigo,nombre) VALUES (?,?);";
            $insert = $this->conexion->getConect()->prepare($sql);
            $arrData = array($codigo, $nombre);
            $resInsert = $insert->execute($arrData);
            return $resInsert;
        } catch (Exception $e) {
            echo "Curso no creado";
            return false;
        }
    }

    public function obtenerAsignatura($codigo)
    {
        $sql = "SELECT * FROM asignaturas WHERE codigo = :parametro";
        $stmt = $this->conexion->getConect()->prepare($sql);
        $stmt->bindParam(':parametro', $codigo);
        $stmt->execute();

        // Verificar si la consulta fue exitosa
        if ($stmt->rowCount() > 0) {
            // Obtener el dato
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            $codigo = $fila['codigo'];
            $nombre = $fila['nombre'];
            $codigoC = $fila['codigoCurso'];
            $correoD = $fila['correoDocente'];
            $asignatura = new Asignatura($nombre, $codigo, $correoD, $codigoC);
            return $asignatura;
        } else {
            return null;
        }
    }

    public function matricular($asignatura, $correo)
    {
        try {
            $sql = "INSERT INTO inscripciones(codigoAsignatura,codigoCurso,correoEstudiante) VALUES (?,?,?);";
            $insert = $this->conexion->getConect()->prepare($sql);
            $arrData = array($asignatura->getCodigo(), $asignatura->getCodigoCurso(), $correo);
            $resInsert = $insert->execute($arrData);
            return true;
        } catch (Exception $e) {
            echo "Error al matricular";
            return false;
        }
    }
    public function insertarAsignaturas($codigo, $curso, $nombre, $codigoD)
    {
        #$nuevoEstudiante = new Estudiante($nombre, $apellido, $correo, $clave);
        try {
            $sql = "INSERT INTO asignaturas(codigo,nombre,codigoCurso,correoDocente) VALUES (?,?,?,?);";
            $insert = $this->conexion->getConect()->prepare($sql);
            $arrData = array($codigo, $nombre, $curso, $codigoD);
            $resInsert = $insert->execute($arrData);
            return true;
        } catch (Exception $e) {
            echo "Error al crear";
            return false;
        }
    }

    public function asignarTarea($nombre, $codigoA, $descripcion, $fechaAsignacion, $correoDocente)
    {
        $resInsert = false; // Asigna un valor predeterminado

        try {
            $sql = "INSERT INTO tareas(nombre,codigoAsignatura,descripcion,fecha_asignacion,correoDocente) VALUES (?,?,?,?,?);";
            $insert = $this->conexion->getConect()->prepare($sql);
            $arrData = array($nombre, $codigoA, $descripcion, $fechaAsignacion, $correoDocente);
            $resInsert = $insert->execute($arrData);

        } catch (Exception $e) {
            echo "Error al asignar: " . $e->getMessage();
        }

        return $resInsert;
    }

    public function comprobarLogin($correo, $clave): int
    {
        $contador = 0;
        $sql = "SELECT correo, clave FROM usuarios WHERE correo = :correo";
        $consulta = $this->conexion->getConect()->prepare($sql);
        $consulta->bindParam(':correo', $correo);
        $consulta->execute();
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        if ($fila) {
            $contador++;
            $claveDB = ($fila['clave']);
            if ($clave === $claveDB) {
                $contador++;
            }
        }
        return $contador;
    }

    public function getEstudiantes()
    {
        $sql = "SELECT * FROM usuarios where tipo = 'E'";
        $execute = $this->conexion->getConect()->prepare($sql);
        $request = $execute->fetchall(PDO::FETCH_ASSOC);
        return $request;
    }

    public function getAsignaturasTareas($correo)
    {
        $sql = "SELECT asignaturas.* FROM inscripciones
        JOIN asignaturas ON inscripciones.codigoAsignatura = asignaturas.codigo
        WHERE inscripciones.correoEstudiante = :correo";

        $execute = $this->conexion->getConect()->prepare($sql);
        $execute->bindParam(':correo', $correo, PDO::PARAM_STR);
        $execute->execute();

        $resultados = $execute->fetchAll(PDO::FETCH_ASSOC);

        $asignaturas = array();
        foreach ($resultados as $resultado) {
            $asignatura = new Asignatura($resultado['nombre'], $resultado['codigo'], $resultado['correoDocente'], $resultado['codigoCurso']);
            $asignaturas[] = $asignatura;
        }

        return $asignaturas;
    }
    public function getAsignaturasE($correo)
    {
        $sql = "SELECT asignaturas.* FROM inscripciones
        JOIN asignaturas ON inscripciones.codigoAsignatura = asignaturas.codigo
        WHERE inscripciones.correoEstudiante = :correo";

        $execute = $this->conexion->getConect()->prepare($sql);
        $execute->bindParam(':correo', $correo, PDO::PARAM_STR);
        $execute->execute();

        // Obtener todas las asignaturas relacionadas con el estudiante
        $asignaturasE = $execute->fetchAll(PDO::FETCH_ASSOC);

        return $asignaturasE;
    }
    public function getAsignaturasD($correo)
    {
        $sql = "SELECT * FROM asignaturas where correoDocente= :correo;";

        $execute = $this->conexion->getConect()->prepare($sql);
        $execute->bindParam(':correo', $correo, PDO::PARAM_STR);
        $execute->execute();
        $resultados = $execute->fetchAll(PDO::FETCH_ASSOC);

        $asignaturas = array();
        foreach ($resultados as $resultado) {
            $asignatura = new Asignatura($resultado['nombre'], $resultado['codigo'], $resultado['correoDocente'], $resultado['codigoCurso']);
            $asignaturas[] = $asignatura;
        }
        return $asignaturas;
    }

    public function getTareasEnviadas($nombreTarea)
    {
        $sql = "SELECT * FROM tareasEnviadas WHERE nombreTarea = :nombreTarea;";

        $execute = $this->conexion->getConect()->prepare($sql);
        $execute->bindParam(':nombreTarea', $nombreTarea, PDO::PARAM_STR);
        $execute->execute();
        $resultados = $execute->fetchAll(PDO::FETCH_ASSOC);

        $tareas = array();
        foreach ($resultados as $resultado) {
            $tarea = new Tarea(
                $resultado['correoEstudiante'],
                $resultado['nombreTarea'],
                $resultado['codigoAsignatura'],
                $resultado['archivo'],
                $resultado['calificacion'],
                $resultado['comentario'],
                $resultado['fecha_envio']
            );
            $tareas[] = $tarea;  // Corrección: la variable debería ser $tarea, no $tareas
        }

        return $tareas;
    }

    public function getCodigoCursos($nombre)
    {
        $letras = strtoupper(substr($nombre, 0, 2));
        $consulta = $this->conexion->getConect()->prepare("SELECT MAX(SUBSTRING(codigo, 3)) as max_numero FROM cursos WHERE codigo LIKE :letras");
        $consulta->bindValue(':letras', $letras . '%');
        $consulta->execute();
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        if ($fila['max_numero'] !== null) {
            $max_numero = $fila['max_numero'];
            $nuevo_numero = str_pad($max_numero + 1, 3, '0', STR_PAD_LEFT);
            $nuevo_codigo = $letras . $nuevo_numero;
            return $nuevo_codigo;
        } else {
            $nuevo_codigo = $letras . "001";
            return $nuevo_codigo;
        }
    }

    public function actualizarUsuario($correoAnterior, $correoNuevo, $clave, $tipo)
    {
        $sql = "UPDATE usuarios SET correo=?, clave=?, tipo=? WHERE correo=?; ";
        $actualizar = $this->conexion->getConect()->prepare($sql);
        $arrData = array($correoNuevo, $clave, $tipo, $correoAnterior);
        $resExcute = $actualizar->execute($arrData);
        return $resExcute;
    }

    public function eliminarUsuario($correo)
    {
        $sql = "DELETE FROM usuarios WHERE correo = ?";
        $arrWhere = array($correo);
        $delet = $this->conexion->getConect()->prepare($sql);
        $del = $delet->execute($arrWhere);
        return $del;
    }
    public function consultarUsuario($correo)
    {
        try {
            $sql = "SELECT nombre, apellido, rol FROM usuarios WHERE correo = ?";
            $consulta = $this->conexion->getConect()->prepare($sql);
            //$consulta->bindParam(1, $correo);
            $params = [$correo];  // Este debe ser un array con los valores de los parámetros
            $consulta->execute($params);

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $nombre = $resultado['nombre'];
                $apellido = $resultado['apellido'];
                $rol = $resultado['rol'];

                // Comprobar el rol y crear el objeto correspondiente
                if ($rol == 'estudiante') {
                    $usuario = new Estudiante($nombre, $apellido, $correo, "estudiante");
                } elseif ($rol == 'docente') {
                    $usuario = new Profesor($nombre, $apellido, $correo, "docente");
                } else {
                    return array("error" => "Roldesconocido");
                }
                return $usuario->jsonSerialize();

            } else {
                return array("error" => "Usuarionoencontrado");
            }
        } catch (Exception $e) {
            return array("error" => $e->getMessage());
        }
    }

    public function insertarTarea($correoEstudiante, $nombreTarea, $codigoAsignatura, $archivo, $calificacion, $comentario, $fechaEntrega)
    {
        try {
            $sql = "INSERT INTO tareasEnviadas (correoEstudiante, nombreTarea, codigoAsignatura, archivo, calificacion, comentario,fecha_envio) VALUES (?,?,?,?,?,?,?)";
            $stmt = $this->conexion->getConect()->prepare($sql);
            $insert = $this->conexion->getConect()->prepare($sql);
            $arrData = array($correoEstudiante, $nombreTarea, $codigoAsignatura, $archivo, $calificacion, $comentario, $fechaEntrega);
            $resInsert = $insert->execute($arrData);
            return $resInsert;
        } catch (Exception $e) {
            echo "Huno un error al guardar la tarea";
            return false;
        }
    }

    public function getTareas($codigo)
    {
        $sql = "SELECT * FROM tareas where codigoAsignatura = :codigo;";

        $execute = $this->conexion->getConect()->prepare($sql);
        $execute->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $execute->execute();

        // Obtener todas las tareas relacionadas con la asignatura
        $tareasData = $execute->fetchAll(PDO::FETCH_ASSOC);

        $tareas = array();

        foreach ($tareasData as $tareaData) {
            $tareasAsignaturaArray[] = array(
                'nombre' => $tareaData['nombre'],
                'codigoAsignatura' => $tareaData['codigoAsignatura'],
                'descripcion' => $tareaData['descripcion'],
                'fecha_asignacion' => $tareaData['fecha_asignacion'],
                'correoDocente' => $tareaData['correoDocente'],
            );
        }

        return $tareasAsignaturaArray;
    }

    public function getTareasR($codigo, $nombreTarea)
    {
        $sql = "SELECT * FROM tareasEnviadas where codigoAsignatura = :codigo and nombreTarea = :nombreTarea;";

        $execute = $this->conexion->getConect()->prepare($sql);
        $execute->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $execute->bindParam(':nombreTarea', $nombreTarea, PDO::PARAM_STR);
        $execute->execute();

        // Obtener todas las tareas relacionadas con la asignatura
        $tareasData = $execute->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tareasData as $tareaData) {
            $tareasAsignaturaArray[] = array(
                'correoEstudiante' => $tareaData['correoEstudiante'],
                'nombreTarea' => $tareaData['nombreTarea'],
                'codigoAsignatura' => $tareaData['codigoAsignatura'],
                'archivo' => $tareaData['archivo'],
                'calificacion' => $tareaData['calificacion'],
                'comentario' => $tareaData['comentario'],
                'fecha_envio' => $tareaData['fecha_envio'],
            );
        }

        return $tareasAsignaturaArray;
    }

    public function calificarTarea($calificacion, $comentario, $correoUsuario, $nombreTarea, $codigoA)
    {
        $sql = "UPDATE tareasEnviadas SET calificacion = ?, comentario = ? WHERE correoEstudiante = ? AND nombreTarea = ? AND codigoAsignatura = ?;";
        $stmt = $this->conexion->getConect()->prepare($sql);

        // Bind de parámetros
        $stmt->bindParam(1, $calificacion);
        $stmt->bindParam(2, $comentario);
        $stmt->bindParam(3, $correoUsuario);
        $stmt->bindParam(4, $nombreTarea);
        $stmt->bindParam(5, $codigoA);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si alguna fila fue afectada
        if ($stmt->rowCount() > 0) {
            return true; // Tarea calificada correctamente
        } else {
            return false; // No se encontró ninguna tarea que coincida con las condiciones especificadas
        }
    }
    public function obtenerEstudiantesPorAsignatura($codigoAsignatura)
    {
        try {
            $conexion = $this->conexion->getConect();

            $consulta = $conexion->prepare("SELECT correoEstudiante FROM inscripciones WHERE codigoAsignatura = ?");
            $consulta->bindParam(1, $codigoAsignatura, PDO::PARAM_STR);
            $consulta->execute();
            $lista = array();
            // Obtiene los resultados de la consulta
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            foreach ($resultados as $resultado) {
                $lista[] = $resultado['correoEstudiante'];
            }
            return $lista;
        }
        // Cierra la consulta y la conexión
        ///$consulta->closeCursor();
        catch (Exception $e) {
            echo "Hubo un error al obtener los estudiantes por asignatura: " . $e->getMessage();
            return false;
        }
    }
    public function insertarNotificacion($nombre, $idEstudiante, $mensaje, $fecha)
    {
        try {

            // Evita la inyección de SQL usando consultas preparadas
            $sql = "INSERT INTO notificaciones (notificacion, estudiante, mensaje, leida, fecha) VALUES (?,?, ?, 0, ?)";
            $stmt = $this->conexion->getConect()->prepare($sql);

            // Bind de parámetros
            $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
            $stmt->bindParam(2, $idEstudiante, PDO::PARAM_STR);
            $stmt->bindParam(3, $mensaje, PDO::PARAM_STR);
            $stmt->bindParam(4, $fecha, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Cierra la conexión
            $stmt->closeCursor();
            //$conexion->desconectar();

            return true; // Notificación insertada correctamente
        } catch (Exception $e) {
            echo "Hubo un error al insertar la notificación: " . $e->getMessage();
            return false;
        }
    }
    public function marcarNotificacionLeida($idNotificacion, $idEstudiante)
    {

        try {

            // Evita la inyección de SQL usando consultas preparadas
            $sql = "UPDATE notificaciones SET leida = 1 WHERE notificacion= ? AND estudiante = ?";
            $stmt = $this->conexion->getConect()->prepare($sql);

            // Bind de parámetros
            $stmt->bindParam(1, $idNotificacion, PDO::PARAM_STR);
            $stmt->bindParam(2, $idEstudiante, PDO::PARAM_STR);
            // Ejecutar la consulta
            $stmt->execute();

            // Cierra la conexión
            $stmt->closeCursor();

            echo json_encode(array('error' => false, 'mensaje' => 'Notificación marcada como leída correctamente.'));
        } catch (Exception $e) {
            return $e->getMessage();
            //echo json_encode(array('error' => true, 'mensaje' => 'Hubo un error al marcar la notificación como leída: ' . $e->getMessage()));
            //die(); // Detener la ejecución para evitar enviar JSON adicional

        }
    }


    public function obtenerNotificacionesNoLeidas($idEstudiante)
    {

        ///      try {
        $sql = "SELECT * FROM notificaciones WHERE estudiante = :idEstudiante AND leida = 0;";
        $stmt = $this->conexion->getConect()->prepare($sql);
        $stmt->bindParam(':idEstudiante', $idEstudiante, PDO::PARAM_STR);
        $stmt->execute();
        // Obtener las notificaciones no leídas
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $notificaciones = array();
        foreach ($resultados as $resultado) {
            $notificacion = new Notificacion(
                $resultado['notificacion'],
                $resultado['mensaje'],
                $resultado['fecha']
            );
            $notificaciones[] = $notificacion;
        }
        return $notificaciones;
        // Devolver las notificaciones como array asociativo
        //} catch (Exception $e) {
        //   // Manejar el error y devolver un array con información del error
        // return array('error' => true, 'mensaje' => 'Error al obtener las notificaciones no leídas: ' . $e->getMessage());
        //}
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    // Decodificar el JSON recibido
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si la solicitud contiene un identificador específico para la función
    if (isset($data['accion'])) {
        // Crear una instancia de Gestor_Conexion
        $gestorConexion = new Gestor_Conexion();

        // Determinar qué función ejecutar según el identificador
        switch ($data['accion']) {
            case 'marcarNotificacionLeida':
                // Verificar si se proporcionan los parámetros necesarios
                if (isset($data['idNotificacion']) && isset($data['idEstudiante'])) {
                    // Llamar a la función marcarNotificacionLeida
                    $gestorConexion->marcarNotificacionLeida($data['idNotificacion'], $data['idEstudiante']);
                } else {
                    // Devolver un mensaje de error si faltan parámetros
                    echo json_encode(array('error' => true, 'mensaje' => 'Faltan parámetros para la función.'));
                }
                break;

            // Puedes agregar más bloques para otras funciones si es necesario

            default:
                // Devolver un mensaje de error si el identificador no coincide con ninguna función
                echo json_encode(array('error' => true, 'mensaje' => 'Identificador de función no válido.'));
                break;
        }
    } else {
        // Devolver un mensaje de error si no se proporciona un identificador de función
        echo json_encode(array('error' => true, 'mensaje' => 'Identificador de función no proporcionado.'));
    }
}

?>