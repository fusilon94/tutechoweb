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

  //############################ CONSULTA PARA POBLAR SELECT DEPARTAMENTOS - CARGA INICIAL #########################################
  $consulta_agente =	$conexion->prepare("SELECT id, agencia_id FROM agentes WHERE usuario = :usuario");
  $consulta_agente->execute([":usuario" => $usuario]);
  $agente	=	$consulta_agente->fetch(PDO::FETCH_ASSOC);

  if ($agente["agencia_id"] == "0") {// if admin
    $consulta_agencias_list =	$conexion->prepare("SELECT id, location_tag FROM agencias");
    $consulta_agencias_list->execute();
    $agencias_list	=	$consulta_agencias_list->fetchAll(PDO::FETCH_ASSOC);
    
  }elseif ($agente["agencia_id"] == "1") {//if franquiciado
    $consulta_agencias_list =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE franquiciante_id = :franquiciante_id");
    $consulta_agencias_list->execute([":franquiciante_id" => $agente["id"]]);
    $agencias_list	=	$consulta_agencias_list->fetchAll(PDO::FETCH_ASSOC);
  }else {
    $consulta_agencia =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
    $consulta_agencia->execute([":id" => $agente["agencia_id"]]);
    $agencia	=	$consulta_agencia->fetch(PDO::FETCH_ASSOC);

    $consulta_agencias_list =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE departamento = :departamento");
    $consulta_agencias_list->execute([":departamento" => $agencia["departamento"]]);
    $agencias_list	=	$consulta_agencias_list->fetchAll(PDO::FETCH_ASSOC);
  };



}else {
  header('Location: ../login.php');
};


require 'inventario.view.php';
 ?>
