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


  $tutechodb_internacional = "tutechodb_internacional";
  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  // Function that checks if a json file exists, and if not, it creates the file
  function fileCheck($json_path) {
    if (!file_exists($json_path)) {
      $json_constructor = array();
      $json_data = json_encode($json_constructor);
      file_put_contents($json_path, $json_data);
    };
  };

  if ($nivel_acceso == 1 || $nivel_acceso == 11) {

    $consulta_paises =	$conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
    $consulta_paises->execute();
    $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN);


  };

  $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
  $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
  $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

  $json_path_todo_list = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/to_do_list.json';
  fileCheck($json_path_todo_list);



}else {
  header('Location: ../login.php');
};


require 'check_list.view.php';
 ?>
