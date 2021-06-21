<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$php_view_entry_control = "algunvalor";

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

if (!isset($_SESSION['propietario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  header('Location: ../../index.php');
};

function get_tabla($referencia) {
  $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
  return $dict[$referencia[5]];
};

$referencia = $_SESSION['propietario'];
$tabla = get_tabla($referencia);

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

try {
  $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};

$consulta_check_encuesta =	$conexion->prepare("SELECT encuesta_propietario FROM $tabla WHERE referencia = :referencia ");
$consulta_check_encuesta->execute([':referencia' => $referencia]);//SE PASA LA REFERENCIA
$check_encuesta = $consulta_check_encuesta->fetch(PDO::FETCH_COLUMN, 0);



require 'propietario_consola.view.php';
?>
