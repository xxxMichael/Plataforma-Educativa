<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Simular clic en el enlace de inicio de sesión con Google después de que la página se haya cargado
            document.getElementById('googleLoginLink').click();
        });

        function saveEmailToLocalStorage(email) {
            // Guardar el correo en el caché del navegador usando localStorage
            localStorage.setItem('correousuario', email);
            console.log(localStorage.getItem('correousuario'));
        }

        function verificarCorreo() {
            // Obtener el correo electrónico desde localStorage
            var userEmail = localStorage.getItem('correousuario');

            // Verificar si el correo está registrado
            $.ajax({
                type: "POST",
                url: "verificar_correo.php",
                data: { accion: "verificar_correo", "correo-electronico": userEmail },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (!data.registrado) {
                        // Mostrar un mensaje de notificación que cubra toda la pantalla
                        mostrarMensaje("Su usuario no se encuentra registrado. Por favor, regístrese para poder usar inicio rápido.");
                        // Después de 4 segundos, ocultar el mensaje y redirigir a la página de índice
                        setTimeout(function () {
                            ocultarMensaje();
                            window.location.href = "index.html";
                        }, 4000);
                    }else{
                        window.location.href = "inicio.html";

                    }
                },
                error: function () {
                    // Manejar errores de la solicitud AJAX aquí
                    console.error("Error al verificar el correo.");
                }
            });
        }

        function mostrarMensaje(mensaje) {
            // Crear un elemento div para mostrar el mensaje
            var mensajeDiv = $('<div class="alert alert-danger mensaje-pantalla" role="alert">' + mensaje + '</div>');
            // Añadir el elemento al cuerpo del documento
            $('body').append(mensajeDiv);

            // Ocultar la información del usuario
            $('.panel-default').hide();
        }

        function ocultarMensaje() {
            // Ocultar el mensaje eliminando el elemento del DOM
            $('.mensaje-pantalla').remove();
            // Mostrar la información del usuario
            $('.panel-default').show();
        }
    </script>
    <style>
        .mensaje-pantalla {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 0, 0, 0.8);
            /* Fondo rojo semi-transparente */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
        }
    </style>
</head>

<body>
    <div class="container">
        <br />
        <h2 align="center"></h2>
        <br />
        <div class="panel panel-default">
            <?php
            include('config.php');
            $login_button = "";

            if (isset($_GET["code"])) {
                $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

                if (!isset($token['error'])) {
                    $google_client->setAccessToken($token['access_token']);

                    $google_service = new Google_Service_Oauth2($google_client);
                    $data = $google_service->userinfo->get();

                    if (!empty($data['given_name'])) {
                        $_SESSION['user_first_name'] = $data['given_name'];
                    }

                    if (!empty($data['family_name'])) {
                        $_SESSION['user_last_name'] = $data['family_name'];
                    }

                    if (!empty($data['email'])) {
                        $_SESSION['user_email_address'] = $data['email'];
                        // Llamada a la función JavaScript para guardar el correo en localStorage
                        echo '<script>saveEmailToLocalStorage("' . $data['email'] . '"); verificarCorreo();</script>';
                    }

                    if (!empty($data['gender'])) {
                        $_SESSION['user_gender'] = $data['gender'];
                    }

                    if (!empty($data['picture'])) {
                        $_SESSION['user_image'] = $data['picture'];
                    }
                }
                // Falta una llave de cierre para la condición if(isset($_SESSION['access_token']))
            }
            if (!isset($_SESSION))
                $login_button = '<a id="googleLoginLink" href="' . $google_client->createAuthUrl() . '">Presione este link si no empieza el inicio de sesión automaticamente</a>';

            if ($login_button == '') {
                //  echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
                // echo '<img src="' . $_SESSION["user_image"] . '" class="img-responsive img-circle img-thumbnail" />';
                //echo '<h3><b>Name:</b>' . $_SESSION['user_first_name'] . ' ' . (isset($_SESSION['user_last_name']) ? $_SESSION['user_last_name'] : '') . '</h3>';
                //     echo '<h3><b>Email :</b>' . (isset($_SESSION['user_email_address']) ? $_SESSION['user_email_address'] : '') . '</h3>';
                // echo '<h3><a href="logout.php">Logout</a></h3></div>';
            } else {
                echo '<div align="center">' . $login_button . '</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>