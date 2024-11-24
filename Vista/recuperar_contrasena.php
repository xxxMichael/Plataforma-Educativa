<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function generarCodigoAleatorio($longitud)
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';

    $caracteresLength = strlen($caracteres);
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[random_int(0, $caracteresLength - 1)];
    }

    return $codigo;
}

function generarCodigoVerificacion($conexion)
{
    try {
        $codigoVerificacion = generarCodigoAleatorio(6);

        $sql = "SELECT codigo_usuario FROM usuarios WHERE codigo_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$codigoVerificacion]);

        if ($stmt->rowCount() > 0) {
            return generarCodigoVerificacion($conexion);
        } else {
            return $codigoVerificacion;
        }
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
        return null;
    }
}

function actualizarContrasena($conexion, $correoElectronico, $nuevaContrasena)
{
    try {
        // Actualizar la contraseña en la base de datos sin aplicar hash
        $sqlUpdate = "UPDATE usuarios SET contraseña = :nuevaContrasena WHERE correo = :correo";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':nuevaContrasena', $nuevaContrasena);
        $stmtUpdate->bindParam(':correo', $correoElectronico);
        $stmtUpdate->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
        return false;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST["accion"]; // Nuevo parámetro para determinar la acción

    if ($accion === "enviar_codigo") {
        // Acción para enviar el código de verificación
        $correoElectronico = $_POST["correo-recuperacion"];

        $servername = "127.0.0.1";
        $username = "root";
        $password = "davidgiler21";
        $dbname = "proyecto_fundamentos";

        try {
            $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM usuarios WHERE correo = :correo";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":correo", $correoElectronico);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                $codigoUsuario = generarCodigoVerificacion($conexion);

                $sqlInsert = "UPDATE usuarios SET codigo_usuario = :codigoUsuario WHERE correo = :correo";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->bindParam(':codigoUsuario', $codigoUsuario);
                $stmtInsert->bindParam(':correo', $correoElectronico);
                $stmtInsert->execute();

                $email = '';
                $nombre = 'Plataforma Educativa UTA';
                $destino = $correoElectronico;
                $asunto = 'Código de Usuario para Cambio de Contraseña';

                $cuerpo = '
                <html>
                <head>
            <title>Bienvenido a la Plataforma Educativa UTA</title>
            <style>
                /* Estilos para resaltar el código */
                .codigo {
                    background-color: #FF0000; /* Fondo rojo */
                    color: #FFFFFF; /* Texto blanco */
                    padding: 10px;
                    font-family: "Courier New", monospace; /* Cambia la fuente a Courier New o cualquier fuente monoespaciada */
                    letter-spacing: 2px; /* Añade espaciado entre letras para mejorar la legibilidad */                   
                     font-size: 18px; /* Tamaño del texto */
                    border-radius: 5px; /* Bordes redondeados */
                    text-align: center; /* Texto centrado */
                    margin: 0 auto 20px; /* Centra el div y agrega margen inferior */
                }
                body {
                    background-color: #F5F5F5; /* Fondo gris claro para todo el cuerpo */
                    font-family: "Arial", sans-serif;
                    text-align: center; /* Texto centrado en todo el cuerpo */
                    padding: 20px; /* Espaciado interno */
                    margin: 0; /* Elimina los márgenes predeterminados del cuerpo */
                }
                h1 {
                    color: #333333; /* Texto oscuro */
                    font-size: 36px; /* Tamaño del texto del encabezado */
                    text-align: center;
                }
                p {
                    color: #333333; /* Texto oscuro */
                    font-size: 20px; /* Tamaño del texto del párrafo */
                    margin: 0 0 20px; /* Márgenes inferior para los párrafos */
                }
                #contenedor-encabezado {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 10px; /* Espaciado interno del encabezado */
                    margin-bottom: 20px; /* Márgenes inferiores para separar del resto del contenido */
                    margin-left: 30px;
                 
                }
                #logo-izquierdo img,
                #logo-derecho img {
                    width: 80px;
                    height: auto;
                    margin-right: 10px; /* Márgenes derechos para separar las imágenes del texto */
                }
                #texto-central {
                    text-align: center; 
                    color: #333333; /* Texto oscuro para el encabezado */
                }
                #texto-central h2,
                #texto-central h3 {
                    margin: 0;
                    font-size: 24px; /* Tamaño del texto del encabezado secundario */
                }
            </style>
        </head>
        <body>
        <div id="contenedor-encabezado">
            <div id="logo-izquierdo">
                <img
                    src="https://sistemaseducaciononline.uta.edu.ec/pluginfile.php/1/theme_adaptable/adaptablemarkettingimages/0/logo_uta.png"
                    alt=""
                    width="80"
                    height="80"
                />
            </div>
            <div id="texto-central">
                <h2>PLATAFORMA EDUCATIVA INSTITUCIONAL</h2>
                <h3>FACULTAD DE INGENIERÍA EN SISTEMAS,<br> ELECTRÓNICA E INDUSTRIAL</h3>
            </div>
            <div id="logo-derecho">
                <img
                    src="https://sistemaseducaciononline.uta.edu.ec/pluginfile.php/1/theme_adaptable/adaptablemarkettingimages/0/sistemas_sello.png"
                    alt="."
                    width="80"
                    height="64"
                />
            </div>
        </div>

        <h1>Cambio de Contraseña en la Plataforma Educativa UTA</h1>
        <p>Estimado usuario,</p>
        <p>Le enviamos este correo para proporcionarle un código necesario para cambiar su contraseña en nuestra plataforma educativa.</p>
        
        <p>Su código de usuario es: <span class="codigo">' . $codigoUsuario . '</span></p>
        
        <style>
            /* Estilos para resaltar el código */
            .codigo {
                background-color: #FF0000; /* Fondo rojo */
                color: #FFFFFF; /* Texto blanco */
                padding: 10px;
                font-family: "Courier New", monospace; /* Cambia la fuente a Courier New o cualquier fuente monoespaciada */
                letter-spacing: 2px; /* Añade espaciado entre letras para mejorar la legibilidad */                   
                font-size: 18px; /* Tamaño del texto */
                border-radius: 5px; /* Bordes redondeados */
                text-align: center; /* Texto centrado */
                margin: 0 auto 20px; /* Centra el div y agrega margen inferior */
            }
        </style>

        <p>Utilice este código para realizar el cambio de contraseña en la plataforma.</p>

        <p>¡Gracias y continúe disfrutando de nuestros servicios educativos!</p>
    </body>
            </html>
            ';

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=utf-8\r\n";
                $headers .= "From: $nombre <$email>\r\n";
                $headers .= "Return-path: $destino\r\n";

                if (mail($destino, $asunto, $cuerpo, $headers)) {
                    http_response_code(200); // Código de respuesta OK
                    echo json_encode(["message" => "Correo enviado correctamente.", "codigo" => $codigoUsuario]);
                } else {
                    http_response_code(500); // Código de respuesta de error interno del servidor
                    echo json_encode(["error" => "Error al enviar el correo a $destino"]);
                }
            } else {
                http_response_code(404); // Código de respuesta no encontrado
                echo json_encode(["error" => "El correo electrónico no está registrado."]);
            }
        } catch (PDOException $e) {
            http_response_code(500); // Código de respuesta de error interno del servidor
            echo json_encode(["error" => "Error en la conexión: " . $e->getMessage()]);
        }
    } elseif ($accion === "actualizar_contrasena") {
        // Acción para actualizar la contraseña
        $correoElectronico = $_POST["correo-recuperacion"];
        $nuevaContrasena = $_POST["nueva-contrasena"];
        $codigoRecibido = $_POST["codigo-verificacion"];

        $servername = "127.0.0.1";
        $username = "root";
        $password = "davidgiler21";
        $dbname = "proyecto_fundamentos";

        try {
            $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM usuarios WHERE correo = :correo AND codigo_usuario = :codigo";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":correo", $correoElectronico);
            $stmt->bindParam(":codigo", $codigoRecibido);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Verificación exitosa, actualizar la contraseña
                if (actualizarContrasena($conexion, $correoElectronico, $nuevaContrasena)) {
                    http_response_code(200); // Código de respuesta OK
                    echo json_encode(["message" => "Contraseña actualizada correctamente."]);
                } else {
                    http_response_code(500); // Código de respuesta de error interno del servidor
                    echo json_encode(["error" => "Error al actualizar la contraseña."]);
                }
            } else {
                http_response_code(400); // Código de respuesta de solicitud incorrecta
                echo json_encode(["error" => "Verificación fallida. Código incorrecto o correo no válido."]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
        }
    } else {
        http_response_code(400); // Código de respuesta de solicitud incorrecta
        echo json_encode(["error" => "Solicitud incorrecta. Acción no válida."]);
    }
} else {
    http_response_code(400); // Código de respuesta de solicitud incorrecta
    echo json_encode(["error" => "Solicitud incorrecta."]);
}
?>