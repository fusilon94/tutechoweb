<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

session_destroy(); // destruye la SESSION actual
$_SESSION = array(); // limpia los parametros de la SESSION para que la proxima comience bien

header('Location: login.php'); //redirige al usuario a login
?>
