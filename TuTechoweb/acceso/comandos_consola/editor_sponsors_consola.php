<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$tutechodb = '';

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
}else {
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,11,5,12];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php



  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  //############################ LO QUE PASA SI SE AUTO-ENVIO ALGO POR METODO POST ##############################################

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editor_sponsor_nombre = $_POST['editor_sponsor_nombre'];
    $_SESSION['editar_nombre'] = $editor_sponsor_nombre;
    header('Location: registro_sponsor.php');
  };


  //############################ CONSULTA PARA POBLAR SELECT DEPARTAMENTOS - CARGA INICIAL #########################################
  $consulta_regiones =	$conexion->prepare("SELECT departamentos FROM regiones");
  $consulta_regiones->execute();
  $regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_COLUMN, 0);




}else {
  header('Location: ../login.php');
};


require 'editor_sponsors_consola.view.php';
 ?>
