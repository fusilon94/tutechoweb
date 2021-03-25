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
  $array_acceso = [1,2,3,11,12];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };


  // CARGA INICIAL ##############################################################################################################

  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {

    $consulta_agencias =	$conexion->prepare("SELECT id, location_tag FROM agencias");
    $consulta_agencias->execute();
    $agencias	=	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($nivel_acceso == 2) {

    $consulta_franquiciante =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
    $consulta_franquiciante->execute([":usuario" => $_SESSION['usuario']]);
    $franquiciante	=	$consulta_franquiciante->fetch();

    $consulta_agencias =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE franquiciante_id = :franquiciante_id");
    $consulta_agencias->execute([':franquiciante_id' => $franquiciante[0]]);
    $agencias	=	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($nivel_acceso == 3) {
    $consulta_agencia_id =	$conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
    $consulta_agencia_id->execute([":usuario" => $_SESSION['usuario']]);
    $agencia_id	=	$consulta_agencia_id->fetch();

    $consulta_agencia_especifica =	$conexion->prepare("SELECT * FROM agencias WHERE id = :id");
    $consulta_agencia_especifica->execute([":id" => $agencia_id[0]]);
    $agencia_especifica	=	$consulta_agencia_especifica->fetch(PDO::FETCH_ASSOC);
  };



}else {
  header('Location: ../login.php');
};


require 'horarios_agencia.view.php';
 ?>
