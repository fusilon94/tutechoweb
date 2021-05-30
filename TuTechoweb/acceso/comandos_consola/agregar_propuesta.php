<?php session_start(); //si se usan $_SESSION hay que poner esto al principio


if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};
if (!isset($_SESSION['usuario'])) {//si una SESSION no ha sido definida redirigir a login.php
  header('Location: ../login.php');
};
if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
  header('Location: ../cerrar_session.php');
};

$nivel_acceso = $_SESSION['nivel_acceso'];
$array_acceso = [1,11,4,10,12,3];
if (in_array($nivel_acceso, $array_acceso) == false){//si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php
  header('Location: ../acceso.php');
}; 

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

try {
  $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};





require 'agregar_propuesta.view.php';
?>
