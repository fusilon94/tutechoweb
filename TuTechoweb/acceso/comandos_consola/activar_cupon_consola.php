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
  $array_acceso = [1,11,12];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  $db_internacional = "tutechodb_internacional";

  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $db_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };


  $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
  $consulta_paises->execute();
  $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);

  $cupones_por_validar = [];

  foreach ($paises as $pais) {

    $tutechodb = "tutechodb_" . $pais;

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $consulta_cupones_por_validar = $conexion->prepare(" SELECT nombre, label, barrio, pais FROM sponsors WHERE visibilidad = 'visible' AND cupon_agregado = 1 AND cupon_visible = 0 ");
    $consulta_cupones_por_validar->execute();
    $cupones_por_validar_especifico = $consulta_cupones_por_validar->fetchAll();


    foreach ($cupones_por_validar_especifico as $cupon) {
        $cupones_por_validar[] = $cupon;
    };

  };


}else {
  header('Location: ../login.php');
};


require 'activar_cupon_consola.view.php';
 ?>
