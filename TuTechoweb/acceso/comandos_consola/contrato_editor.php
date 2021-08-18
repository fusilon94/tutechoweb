<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

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
  $array_acceso = [1,3,11,12];
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

  if (isset($_SESSION['tipo_selected']) && isset($_SESSION['pais_selected']) && isset($_SESSION['sub_tipo_selected'])) {
      $tipo_selected = $_SESSION['tipo_selected'];
      $pais_selected = $_SESSION['pais_selected'];
      $sub_tipo_selected = $_SESSION['sub_tipo_selected'];
  }else {
      // header('Location: consola_contratos_personal.php');
  };

  $consulta_agente_id =	$conexion->prepare(" SELECT id FROM agentes WHERE usuario = :usuario AND activo = 1");
  $consulta_agente_id->execute([
  ':usuario' => $usuario
  ]);//SE PASA EL NOMBRE DEL SPONSOR
  $agente_id =$consulta_agente_id->fetch();




}else {
  header('Location: ../login.php');
};


require 'contrato_editor.view.php';
 ?>
