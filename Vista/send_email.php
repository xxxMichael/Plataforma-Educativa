<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['correo-electronico'])) {
    // Obtén el correo electrónico desde la solicitud POST
    $correoElectronico = $_POST['correo-electronico'];
  //  $correoElectronico = 'michael27sec@gmail.com';

    // Conecta a la base de datos
    $servername = "127.0.0.1";
$username = "root";
$password = "davidgiler21";
$dbname = "proyecto_fundamentos";

    try {
        
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Genera el código de verificación
        $codigoVerificacion = generarCodigoVerificacion($conexion);    //  $codigoVerificacion='aaAa12A';
        $email = '';
        $nombre = 'Plataforma Educativa UTA';
        $destino = $correoElectronico;
        $asunto = 'Código de Verificación Plataforma Educativa';

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
                    font-family: "Arial", sans-serif;
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
                    margin-left: 42px;
                    margin-right: -2px;
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
    
            <h1>Bienvenido a la Plataforma Educativa UTA</h1>
            <p>Gracias por unirte a nuestra plataforma educativa. Estamos emocionados de tenerte con nosotros.</p>
            
            <p>A continuación, encontrarás tu código de verificación:</p>
            
            <div class="codigo">' . $codigoVerificacion . '</div>
            
            <p>Utiliza este código para acceder y disfrutar de todos los recursos educativos que ofrecemos.</p>
    
            <p>"La educación es el pasaporte para el futuro, el mañana pertenece a aquellos que se preparan para él hoy." - Malcolm X</p>
           
            <p>¡Gracias nuevamente y feliz aprendizaje!</p>
        </body>
    </html>
';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: $nombre <$email>\r\n";
        $headers .= "Return-path: $destino\r\n";

        // Inserta el usuario en la base de datos solo si el código de verificación se generó correctamente
        //  if ($codigoVerificacion) {
        // AQUÍ PUEDES AGREGAR EL CÓDIGO PARA INSERTAR EL USUARIO EN LA BASE DE DATOS
        // Utiliza $correoElectronico y $codigoVerificacion en la consulta SQL
        // Ejemplo: INSERT INTO usuarios (correo_electronico, codigo_verificacion) VALUES ('$correoElectronico', '$codigoVerificacion');

        // Envía el correo electrónico
        if (mail($destino, $asunto, $cuerpo, $headers)) {
       //     echo "Email enviado correctamente a $destino";
          //  error_log("Código de verificación generado: $codigoVerificacion");
            echo $codigoVerificacion; // Devuelve el código de verificación generado

        } else {
            echo "Error al enviar el correo a $destino";
        }
        //} else {
       // echo "Error al generar el código de verificación";
        // }
    } catch (PDOException $e) {
        // Maneja el error de conexión
        echo "Error en la conexión: " . $e->getMessage();
        error_log("Código de verificación generado: $codigoVerificacion");

    }
   // error_log("Código de verificación generado: $codigoVerificacion");
   // echo '<script>';
//echo 'console.log("Mensaje desde PHP");';
  //  echo '</script>';
} else {
    echo "Correo electrónico no recibido.";
    error_log("Código de verificación generado: $codigoVerificacion");

}
function generarCodigoVerificacion($conexion)
{
    $codigoVerificacion = generarCodigoAleatorio(6);
    if($conexion!=null){
    try {
        // Genera un código aleatorio de 6 caracteres entre letras y números
        

        // Verifica si el código ya existe en la base de datos
        $sql = "SELECT codigo_usuario FROM usuarios WHERE codigo_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$codigoVerificacion]);

        // Utiliza rowCount() para verificar si el código ya existe
        if ($stmt->rowCount() > 0) {
            // Si ya existe, genera uno nuevo recursivamente
            return generarCodigoVerificacion($conexion);
        } else {
            // Si es único, devuelve el código
            return $codigoVerificacion;
        }
    } catch (PDOException $e) {
        // Maneja el error de conexión
        echo "Error en la conexión: " . $e->getMessage();
        return null;
    }
}else{
    return $codigoVerificacion;
}
}


function generarCodigoAleatorio($longitud)
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';

    // Utiliza una función más segura para generar números aleatorios
    $caracteresLength = strlen($caracteres);
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[random_int(0, $caracteresLength - 1)];
    }

    return $codigo;
}

?>