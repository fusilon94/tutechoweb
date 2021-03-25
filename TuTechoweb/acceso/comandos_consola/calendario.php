<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,2,3,4,5,6,7,10,11,12];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  $tutechodb_internacional = "tutechodb_internacional";
  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
  $consulta_paises->execute();
  $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);

  if ($nivel_acceso == 1 || $nivel_acceso == 11) {
    $js_file = 'admin';
  }elseif ($nivel_acceso == 12) {
    $js_file = 'jefe_central';
  }elseif ($nivel_acceso == 3) {
    $js_file = 'jefe_local';
  }elseif ($nivel_acceso == 2) {
    $js_file = 'franquiciado';
  }elseif ($nivel_acceso == 4) {
    $js_file = 'agente';
  }elseif ($nivel_acceso == 10) {
    $js_file = 'agente_express';
  }elseif ($nivel_acceso == 7) {
    $js_file = 'fotografo';
  }elseif ($nivel_acceso == 5) {
    $js_file = 'agente_sponsor';
  }elseif ($nivel_acceso == 6) {
    $js_file = 'agente_inversiones';
  };

  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
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

  function fileCheckAgencia($json_path){
    if (!file_exists($json_path)) {
      $json_constructor = array('anuncio' => array(), 'evento' => array());
      $json_data = json_encode($json_constructor);
      file_put_contents($json_path, $json_data);
    };
  };

  function fileCheck_jefe_tarea($json_path){
    if (!file_exists($json_path)) {
      $json_constructor = array('cita' => array(), 'salida' => array());
      $json_data = json_encode($json_constructor);
      file_put_contents($json_path, $json_data);
    };
  };  


  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12 || $nivel_acceso == 2) {

    if ($nivel_acceso == 2) {

      $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
      $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
      $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);


      $consulta_agencias =	$conexion->prepare("SELECT id, express, location_tag, departamento FROM agencias WHERE franquicia = 1 AND franquiciante_id = :franquiciante_id");
      $consulta_agencias->execute([':franquiciante_id' => $agente_id['id']]);
      $agencias	=	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

    }else {

      $consulta_agencias =	$conexion->prepare("SELECT id, express, location_tag, departamento FROM agencias");
      $consulta_agencias->execute();
      $agencias	=	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

    };

    foreach ($agencias as $agencia) {
      
      $agencia_tag = $agencia['departamento'] . '_' .$agencia['location_tag'];

      $json_path_eventos = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/eventos.json';
      fileCheckAgencia($json_path_eventos);
      
      $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
      fileCheck($json_path_agentes_tareas);   

      $json_path_jefe_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/jefe_tareas.json';
      fileCheck_jefe_tarea($json_path_jefe_tareas);
      
      if ($agencia['express'] == 0) {
        $json_path_turnos_agencia = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/turnos_agencia.json';
        fileCheck($json_path_turnos_agencia);

      };

    };
    $agencia_tag = '';

  }else {

    $consulta_agencia_id =	$conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
    $consulta_agencia_id->execute([":usuario" => $_SESSION['usuario']]);
    $agencia_id	=	$consulta_agencia_id->fetch(PDO::FETCH_ASSOC);

    $consulta_agencia =	$conexion->prepare("SELECT id, express, location_tag, departamento FROM agencias WHERE id = :id");
    $consulta_agencia->execute([':id' => $agencia_id['agencia_id']]);
    $agencia	=	$consulta_agencia->fetch(PDO::FETCH_ASSOC);

    $agencia_tag = $agencia['departamento'] . '_' .$agencia['location_tag'];


    $json_path_eventos = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/eventos.json';
    fileCheckAgencia($json_path_eventos);


    $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
    fileCheck($json_path_agentes_tareas);

    $json_path_jefe_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/jefe_tareas.json';
    fileCheck_jefe_tarea($json_path_jefe_tareas);


    if ($agencia['express'] == 0) {

      $json_path_turnos_agencia = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/turnos_agencia.json';
      fileCheck($json_path_turnos_agencia);
      
    };

  };


  $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
  $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
  $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

  $json_path_eventos_personal = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/eventos_personal.json';
  fileCheck($json_path_eventos_personal);

  $json_path_todo_list = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/to_do_list.json';
  fileCheck($json_path_todo_list);

  $json_path_vacaciones = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/vacaciones.json';
  fileCheck($json_path_vacaciones);


}else {
  header('Location: ../login.php');
};


require 'calendario.view.php';
 ?>
