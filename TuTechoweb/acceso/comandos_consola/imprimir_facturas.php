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
  $array_acceso = [1,3,10,11,12];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  } else {
    header('Location: ../acceso.php');
  }; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  if (!isset($_SESSION['id_factura'])) {
    header('Location: ../acceso.php');
  };

  $id_factura = $_SESSION['id_factura'];

  $consulta_factura_info =	$conexion->prepare(" SELECT tipo, agencia_id FROM facturas WHERE id = :id");
  $consulta_factura_info->execute([ ':id' => $id_factura ]);//SE PASA EL NOMBRE DEL SPONSOR
  $factura_info = $consulta_factura_info->fetch(PDO::FETCH_ASSOC);

  
  $tutechodb_internacional = "tutechodb_internacional";
  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_moneda =	$conexion_internacional->prepare(" SELECT moneda_string, moneda_code, moneda FROM paises WHERE pais = :pais");
  $consulta_moneda->execute([ ':pais' => $_COOKIE['tutechopais'] ]);//SE PASA EL NOMBRE DEL SPONSOR
  $moneda = $consulta_moneda->fetch(PDO::FETCH_ASSOC);




}else {
  header('Location: ../login.php');
};


require 'imprimir_facturas.view.php';
 ?>
