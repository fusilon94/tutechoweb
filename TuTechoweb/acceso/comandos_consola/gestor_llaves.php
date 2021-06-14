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
  $array_acceso = [1,3,4,10,11,12];
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

  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {// if admin, co-admin or jefe central
    $consulta_agencias_list =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE express = 0");
    $consulta_agencias_list->execute();
    $agencias_list	=	$consulta_agencias_list->fetchAll(PDO::FETCH_ASSOC);
    
  }else {

    $consulta_agencias_list =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE id = :id AND express = 0");
    $consulta_agencias_list->execute([":id" => $agente["agencia_id"]]);
    $agencias_list	=	$consulta_agencias_list->fetchAll(PDO::FETCH_ASSOC);
  };

  $consulta_regiones =	$conexion->prepare("SELECT departamentos FROM regiones");
  $consulta_regiones->execute();
  $regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_COLUMN, 0);


}else {
  header('Location: ../login.php');
};


require 'gestor_llaves.view.php';
 ?>
