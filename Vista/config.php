<?php
//start session on web page session_start();
require_once 'vendor/autoload.php';

$google_client = new Google_Client();
$google_client->setClientId('');
$google_client->setClientSecret('');
$google_client->setRedirectUri('http://localhost/Proyecto_Final/vista/index.php');
$google_client->addScope('email');
$google_client->addScope('profile');
?>
config.php