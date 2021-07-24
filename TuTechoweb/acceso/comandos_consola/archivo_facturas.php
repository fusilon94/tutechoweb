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
  $array_acceso = [1,3,10,11,12];
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

  //Se trae el Id del Usuario conectado
  $consulta_agente_id =	$conexion->prepare("SELECT id, agencia_id FROM agentes WHERE usuario = :usuario");
  $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
  $agente_info =	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
    $consulta_agencias=	$conexion->prepare("SELECT id, location_tag FROM agencias");
    $consulta_agencias->execute();
    $agencias =	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

    $facturas = [];
  }else{
    $consulta_agencias=	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE id = :id");
    $consulta_agencias->execute([":id" => $agente_info['agencia_id']]);
    $agencias =	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC); 

    $consulta_facturas=	$conexion->prepare("SELECT id, fecha_impresion, tipo, referencia_inmueble, monto, numero_factura, codigo_control, numero_autorizacion FROM facturas WHERE impreso = 1 AND anulado = 0 AND agencia_id = :agencia_id");
    $consulta_facturas->execute([':agencia_id' => $agente_info['agencia_id']]);
    $facturas =	$consulta_facturas->fetchAll(PDO::FETCH_ASSOC);
  };

  $consulta_pais_info =	$conexion_internacional->prepare("SELECT moneda, moneda_code FROM paises WHERE pais = :pais");
  $consulta_pais_info->execute([":pais" => $_COOKIE['tutechopais']]);
  $pais_info =	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);


}else {
  header('Location: ../login.php');
};


require 'archivo_facturas.view.php';
 ?>
