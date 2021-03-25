<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$tutechodb = '';

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
  $array_acceso = [1,2,3,11,12];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_file_selected = filter_var(strtolower($_POST['tipo_file_select']), FILTER_SANITIZE_STRING);
    $tipo_doc_selected = filter_var(strtolower($_POST['tipo_doc_select']), FILTER_SANITIZE_STRING);
    $pais_selected = filter_var(strtolower($_POST['pais_select']), FILTER_SANITIZE_STRING);

    $_SESSION['tipo_file_selected'] = $tipo_file_selected;
    $_SESSION['tipo_doc_selected'] = $tipo_doc_selected;
    $_SESSION['pais_selected'] = $pais_selected;

    $contrato_path = 'Location: file_visualizer.php';

    header($contrato_path);
  };

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_pais_agente = $conexion->prepare(" SELECT pais FROM agentes WHERE usuario = :usuario ");
  $consulta_pais_agente->execute([":usuario" => $_SESSION['usuario']]);
  $pais_agente = $consulta_pais_agente->fetch(PDO::FETCH_ASSOC);


  $tutechodb_internacional = "tutechodb_internacional";

  try {
    $conexion_db_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_paises = $conexion_db_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
  $consulta_paises->execute();
  $paises = $consulta_paises->fetchAll(PDO::FETCH_ASSOC);




}else {
  header('Location: ../login.php');
};


require 'consola_detalle_file.view.php';
 ?>
