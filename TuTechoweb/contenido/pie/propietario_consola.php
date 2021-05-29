<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$php_view_entry_control = "algunvalor";

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

if (!isset($_SESSION['propietario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  header('Location: ../../index.php');
};

$referencia = $_SESSION['propietario'];


$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

try {
  $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};


require 'propietario_consola.view.php';
?>
