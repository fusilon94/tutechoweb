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
  $array_acceso = [1,3,7,10,11,12];
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

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $referencia = $_POST['bien_referencia'];
    $tipo_bien = $_POST['bien_tabla'];
    $_SESSION['referencia_bien'] = $referencia;
    $_SESSION['tabla_bien'] = $tipo_bien;
    header('Location: crear_tourvr.php');
  };

  //############################ CONSULTA PARA POBLAR SELECT DEPARTAMENTOS - CARGA INICIAL #########################################
  $consulta_regiones =	$conexion->prepare("SELECT departamentos FROM regiones");
  $consulta_regiones->execute();
  $regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_COLUMN, 0);

  //############################ CONSULTA AGENTE Y AGENCIA - CARGA INICIAL #########################################
  $consulta_agente = $conexion->prepare("SELECT id, agencia_id FROM agentes WHERE usuario=:usuario");
  $consulta_agente->execute(['usuario' => $usuario]);
  $agente_datos	= $consulta_agente->fetch();

  $agente_id = $agente_datos['id'];
  $agencia_id = $agente_datos['agencia_id'];

}else {
  header('Location: ../login.php');
};


require 'editar_tourvr_consola.view.php';
 ?>
