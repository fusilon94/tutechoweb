<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,2,3,10,11,12];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_selected = filter_var($_POST['id_selected'], FILTER_SANITIZE_STRING); //sanitizar el texto y reducirlo a minusculas
    $tipo_doc_selected = filter_var(strtolower($_POST['tipo_doc_selected']), FILTER_SANITIZE_STRING);
    $pais_selected = filter_var(strtolower($_POST['pais_selected']), FILTER_SANITIZE_STRING);
    $tipo_file_selected = filter_var(strtolower($_POST['tipo_file_selected']), FILTER_SANITIZE_STRING);

    $_SESSION['tipo_doc_selected'] = $tipo_doc_selected;
    $_SESSION['id_file'] = $id_selected;
    $_SESSION['pais_selected'] = $pais_selected;
    $_SESSION['tipo_file_selected'] = $tipo_file_selected;

    // print_r($_SESSION['tipo_doc_selected']);
    // print_r($_SESSION['id_file']);
    // print_r($_SESSION['pais_selected']);

    header('Location: file_maker.php');

  };

  $tutechodb_agente = "tutechodb_" . $_COOKIE['tutechopais'];

  try {
    $conexion_agente = new PDO('mysql:host=localhost;dbname=' . $tutechodb_agente . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_agente_id = $conexion_agente->prepare(" SELECT id FROM agentes WHERE usuario = :usuario AND activo = 1 ");
  $consulta_agente_id->execute([":usuario" => $usuario]);
  $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);


  $db_internacional = "tutechodb_internacional";

  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $db_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };


    $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
    $consulta_paises->execute();
    $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);


    $files_agentes = [];
    $files_inmuebles = [];

  foreach ($paises as $pais) {

    $tutechodb = "tutechodb_" . $pais;

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $consulta_files_agentes = $conexion->prepare(" SELECT id, cargo, pais FROM agentes WHERE edicion = :edicion ");
    $consulta_files_agentes->execute([':edicion' => $agente_id['id']]);
    $files_agente_pais = $consulta_files_agentes->fetchAll(PDO::FETCH_ASSOC);

    foreach ($files_agente_pais as $file) {
        $files_agentes[] = $file;
    };


    $consulta_files_inmuebles_borradores_casa = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM borradores_casa WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_borradores_casa->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_borradores_casa = $consulta_files_inmuebles_borradores_casa->fetchAll(PDO::FETCH_ASSOC);

    $consulta_files_inmuebles_casa = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM casa WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_casa->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_casa = $consulta_files_inmuebles_casa->fetchAll(PDO::FETCH_ASSOC);

    $consulta_files_inmuebles_borradores_departamento = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM borradores_departamento WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_borradores_departamento->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_borradores_departamento = $consulta_files_inmuebles_borradores_departamento->fetchAll(PDO::FETCH_ASSOC);

    $consulta_files_inmuebles_departamento = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM departamento WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_departamento->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_departamento = $consulta_files_inmuebles_departamento->fetchAll(PDO::FETCH_ASSOC);

    $consulta_files_inmuebles_borradores_local = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM borradores_local WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_borradores_local->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_borradores_local = $consulta_files_inmuebles_borradores_local->fetchAll(PDO::FETCH_ASSOC);

    $consulta_files_inmuebles_local = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM local WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_local->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_local = $consulta_files_inmuebles_local->fetchAll(PDO::FETCH_ASSOC);

    $consulta_files_inmuebles_borradores_terreno = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM borradores_terreno WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_borradores_terreno->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_borradores_terreno = $consulta_files_inmuebles_borradores_terreno->fetchAll(PDO::FETCH_ASSOC);

    $consulta_files_inmuebles_terreno = $conexion->prepare(" SELECT referencia, tipo_bien, ciudad, pais, estado FROM terreno WHERE validacion_jefe_agencia = 0 AND edicion_file = :edicion_file ");
    $consulta_files_inmuebles_terreno->execute([":edicion_file" => $agente_id['id']]);
    $files_inmuebles_pais_terreno = $consulta_files_inmuebles_terreno->fetchAll(PDO::FETCH_ASSOC);

    foreach ($files_inmuebles_pais_borradores_casa as $file) {
      $files_inmuebles[] = $file;
    };

    foreach ($files_inmuebles_pais_casa as $file) {
      $files_inmuebles[] = $file;
    };

    foreach ($files_inmuebles_pais_borradores_departamento as $file) {
      $files_inmuebles[] = $file;
    };

    foreach ($files_inmuebles_pais_departamento as $file) {
      $files_inmuebles[] = $file;
    };

    foreach ($files_inmuebles_pais_borradores_local as $file) {
      $files_inmuebles[] = $file;
    };

    foreach ($files_inmuebles_pais_local as $file) {
      $files_inmuebles[] = $file;
    };

    foreach ($files_inmuebles_pais_borradores_terreno as $file) {
      $files_inmuebles[] = $file;
    };

    foreach ($files_inmuebles_pais_terreno as $file) {
      $files_inmuebles[] = $file;
    };
    


  };


}else {
  header('Location: ../login.php');
};

$mesaje_file = '';

if (isset($_SESSION['mesage_file'])) {
	$mesaje_file .= $_SESSION['mesage_file'];
	unset($_SESSION['mesage_file']);
};


require 'modificar_file_consola.view.php';
 ?>
