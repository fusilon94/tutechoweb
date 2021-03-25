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
  $array_acceso = [1];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  //############################ LO QUE PASA SI SE AUTO-ENVIO ALGO POR METODO POST ##############################################

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agencia_selected = $_POST['agencia'];
    $_SESSION['agencia_edit'] = $agencia_selected;

    $consulta_agencia_express =	$conexion->prepare("SELECT express FROM agencias WHERE id = :id");
    $consulta_agencia_express->execute([':id' => $agencia_selected]);
    $agencia_express	=	$consulta_agencia_express->fetch(PDO::FETCH_ASSOC);

    if ($agencia_express['express'] == 1) {
      header('Location: nueva_agencia_express.php');
    }else {
      header('Location: nueva_agencia.php');
    };

    
  };


  // CARGA INICIAL ##############################################################################################################

    $consulta_agencias =	$conexion->prepare("SELECT id, location_tag FROM agencias");
    $consulta_agencias->execute();
    $agencias	=	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);


}else {
  header('Location: ../login.php');
};


require 'consola_editar_agencia.view.php';
 ?>
