<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
}else {
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,2,3,4,5,6,7,8,10,11,12];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };


  $tutechodb_internacional = "tutechodb_internacional";
  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {

    $consulta_paises =	$conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
    $consulta_paises->execute();
    $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN);


    $consulta_agencias =	$conexion->prepare(" SELECT id, location_tag FROM agencias ");
    $consulta_agencias->execute();
    $agencias = $consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($nivel_acceso == 2) {

    $consulta_agente_id =	$conexion->prepare(" SELECT id FROM agentes WHERE usuario = :usuario ");
    $consulta_agente_id->execute([':usuario' => $usuario]);
    $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);
    
    $consulta_agencias =	$conexion->prepare(" SELECT id, location_tag FROM agencias WHERE franquiciante_id = :franquiciante_id ");
    $consulta_agencias->execute([':franquiciante_id' => $agente_id['id']]);
    $agencias = $consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

  }else {

    $consulta_agente_agencia_id =	$conexion->prepare(" SELECT agencia_id FROM agentes WHERE usuario = :usuario ");
    $consulta_agente_agencia_id->execute([':usuario' => $usuario]);
    $agente_agencia_id = $consulta_agente_agencia_id->fetch(PDO::FETCH_ASSOC);

    $consulta_agencias =	$conexion->prepare(" SELECT id, location_tag FROM agencias ");
    $consulta_agencias->execute();
    $agencias = $consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

  };



}else {
  header('Location: ../login.php');
};


require 'libreta_contactos.view.php';
 ?>
