<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$tutechodb = '';

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
  $array_acceso = [1,2,3,11,12];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  if (isset($_SESSION['tipo_file_selected']) && isset($_SESSION['tipo_doc_selected']) && isset($_SESSION['pais_selected'])) {
   
    $tipo_file_selected = $_SESSION['tipo_file_selected'];
    $tipo_doc_selected = $_SESSION['tipo_doc_selected'];

    if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
      $pais_selected = $_SESSION['pais_selected'];
      $tutechodb = "tutechodb_" . $pais_selected;
    }else if($nivel_acceso == 2 || $nivel_acceso == 3){
      $pais_selected = $_SESSION['pais_selected']; // DEFINIR CON SESSION SE PAIS !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    };
    
    
  }else {
    header('Location: consola_registro_documentos.php');
  };

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $carpeta_inicio = '';
  $list_of_files = ["*.jpg", "*.pdf", $pais_selected];

  if ($nivel_acceso == 3) {
    $consulta_agencia = $conexion->prepare(" SELECT agencia_id FROM agentes WHERE usuario = :usuario");
    $consulta_agencia->execute([":usuario" => $_SESSION['usuario']]);
    $agencias = $consulta_agencia->fetchAll(PDO::FETCH_COLUMN);
  };

  if ($nivel_acceso == 2) {
    $consulta_franquiciado_id = $conexion->prepare(" SELECT id FROM agentes WHERE usuario = :usuario");
    $consulta_franquiciado_id->execute([":usuario" => $_SESSION['usuario']]);
    $franquiciado_id = $consulta_franquiciado_id->fetch(PDO::FETCH_ASSOC);

    $consulta_franquicia_agencias = $conexion->prepare(" SELECT id FROM agencias WHERE franquiciante_id = :franquiciante_id");
    $consulta_franquicia_agencias->execute([":franquiciante_id" => $franquiciado_id['id']]);
    $agencias = $consulta_franquicia_agencias->fetchAll(PDO::FETCH_COLUMN);
  };



  if ($tipo_file_selected == 'personal') {

    $carpeta_inicio = 'agentes/' . $pais_selected . '/';

    $tipos_personal = [
      "jefe_agencia_central" => 12,
      "agente_inversiones" => 6,
      "jefe_agencia_local" => 3,
      "agente_express" => 10,
      "agente_sponsor" => 5,
      "agente_inmobiliario" => 4,
      "fotografo" => 7
    ];

    $nivel_required = $tipos_personal[$tipo_doc_selected] ;

      if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
        $consulta_datos = $conexion->prepare(" SELECT id FROM agentes WHERE activo = 1 AND nivel_acceso = $nivel_required");
        $consulta_datos->execute();
        $datos = $consulta_datos->fetchAll(PDO::FETCH_COLUMN);

      }elseif ($nivel_acceso == 2 || $nivel_acceso == 3) {
        $in  = str_repeat('?,', count($agencias) - 1) . '?';

        $consulta_datos = $conexion->prepare(" SELECT id FROM agentes WHERE activo = 1 AND nivel_acceso = $nivel_required AND agencia_id IN ($in)");
        $consulta_datos->execute($agencias);
        $datos = $consulta_datos->fetchAll(PDO::FETCH_COLUMN);
      };

      foreach ($datos as $key => $dato) {
        $list_of_files[] = $dato;
      };

  } else if ($tipo_file_selected == 'venta') {

    $carpeta_inicio = 'bienes_inmuebles_files/' . $pais_selected . '/';

    if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
      $consulta_datos = $conexion->prepare(" SELECT referencia FROM $tipo_doc_selected WHERE visibilidad = 'visible' AND estado = 'En Venta'");
      $consulta_datos->execute();
      $datos = $consulta_datos->fetchAll(PDO::FETCH_COLUMN);

    }elseif ($nivel_acceso == 2 || $nivel_acceso == 3) {
      $in  = str_repeat('?,', count($agencias) - 1) . '?';

      $consulta_datos = $conexion->prepare(" SELECT referencia FROM $tipo_doc_selected WHERE visibilidad = 'visible' AND estado = 'En Venta' AND agencia_registro_id IN ($in)");
      $consulta_datos->execute($agencias);
      $datos = $consulta_datos->fetchAll(PDO::FETCH_COLUMN);
    };

    foreach ($datos as $key => $dato) {
      $list_of_files[] = $dato;
    };


  } else if ($tipo_file_selected == 'alquiler') {

    $carpeta_inicio = 'bienes_inmuebles_files/' . $pais_selected . '/';

    if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
      $consulta_datos = $conexion->prepare(" SELECT referencia FROM $tipo_doc_selected WHERE visibilidad = 'visible' AND estado = 'En Alquiler'");
      $consulta_datos->execute();
      $datos = $consulta_datos->fetchAll(PDO::FETCH_COLUMN);

    }elseif ($nivel_acceso == 2 || $nivel_acceso == 3) {
      $in  = str_repeat('?,', count($agencias) - 1) . '?';

      $consulta_datos = $conexion->prepare(" SELECT referencia FROM $tipo_doc_selected WHERE visibilidad = 'visible' AND estado = 'En Alquiler' AND agencia_registro_id IN ($in)");
      $consulta_datos->execute($agencias);
      $datos = $consulta_datos->fetchAll(PDO::FETCH_COLUMN);
    };

    foreach ($datos as $key => $dato) {
      $list_of_files[] = $dato;
    };

  };


}else {
  header('Location: ../login.php');
};



$entry_folder_path = '/' . $carpeta_inicio;


$entry_val = 'some_value';

$salir_url = 'consola_detalle_file.php';

require 'tinyfilemanager.php';
 ?>
