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
  $localizacion = 'agentes';

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };


  $consulta_consola_herramientas =	$conexion->prepare(" SELECT nombre, logo, destino FROM consola WHERE nivel_acceso LIKE :nivel_acceso AND localizacion=:localizacion AND activo = 1");
  $consulta_consola_herramientas->execute([
  'nivel_acceso' => "%," . $nivel_acceso . ",%",
  'localizacion' => $localizacion
  ]);//SE PASA EL NOMBRE DEL SPONSOR
  $consola_herramientas =$consulta_consola_herramientas->fetchAll();

  $tutechodb_internacional = "tutechodb_internacional";

  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };
  
  $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
  $consulta_paises->execute();
  $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);
  
}else {
  header('Location: ../login.php');
};


require 'agentes_consola.view.php';
 ?>
