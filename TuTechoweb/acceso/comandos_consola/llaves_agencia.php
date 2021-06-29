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
  $array_acceso = [1,3,11,12,10];
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

  if ($_SERVER['REQUEST_METHOD'] == 'POST') { //si se envio datos por POST guardar los datos en variables que seran temporales

    function get_tabla($referencia) {
      $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
      return $dict[$referencia[5]];
    };

    $referencia = filter_var($_POST['referencia_form'], FILTER_SANITIZE_STRING);
    $modo = filter_var($_POST['modo'], FILTER_SANITIZE_STRING);

    $carpeta_destino = '../../bienes_inmuebles_files/' . $_COOKIE['tutechopais'] . '/' . $referencia;

    $tabla = get_tabla($referencia);
  
    
    $keys_array_docs = array_keys($_FILES);

    $temp_name = $_FILES[$keys_array_docs[0]]['tmp_name'];

    if (mime_content_type($temp_name) !== 'application/pdf') {
        $exito = "<h2>Error: Tipo de documento no es PDF</h2>";

    }else{

      if($modo == 'agregar'){

        $statement_update = $conexion->prepare(
          "UPDATE $tabla SET llave = 1, llave_holder = '', llave_last_holder = '' WHERE referencia = :referencia");
  
        $statement_update->execute(array(
            ':referencia' => $referencia,
        ));
  
        $doc_dir = $carpeta_destino . '/recepcion_llaves_conformidad.pdf';
  
  
      }elseif ($modo == 'retirar'){
        
        $statement_update = $conexion->prepare(
          "UPDATE $tabla SET llave = 0, llave_holder = '', llave_last_holder = '' WHERE referencia = :referencia");
  
        $statement_update->execute(array(
            ':referencia' => $referencia,
        ));
  
        $doc_dir = $carpeta_destino . '/devolucion_llaves_conformidad.pdf';
  
  
      };


        if(!is_dir($carpeta_destino)){
            @mkdir($carpeta_destino, 0700);
        };

        move_uploaded_file($temp_name, $doc_dir);//subimos la nueva foto con el nuevo titulo al file o lo sobreescribe si es modo edicio



        $exito = "<h2>Cambios Realizados exitosamente</h2>";
    }; 

    
  };

  //############################ CONSULTA PARA POBLAR SELECT DEPARTAMENTOS - CARGA INICIAL #########################################
  $consulta_agente =	$conexion->prepare("SELECT id, agencia_id FROM agentes WHERE usuario = :usuario");
  $consulta_agente->execute([":usuario" => $usuario]);
  $agente	=	$consulta_agente->fetch(PDO::FETCH_ASSOC);

  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {// if admin, co-admin or jefe central
    $consulta_agencias_list =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE express = 0");
    $consulta_agencias_list->execute();
    $agencias_list	=	$consulta_agencias_list->fetchAll(PDO::FETCH_ASSOC);
    
  }else {

    $consulta_agencias_list =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE id = :id AND express = 0");
    $consulta_agencias_list->execute([":id" => $agente["agencia_id"]]);
    $agencias_list	=	$consulta_agencias_list->fetchAll(PDO::FETCH_ASSOC);
  };

  $consulta_regiones =	$conexion->prepare("SELECT departamentos FROM regiones");
  $consulta_regiones->execute();
  $regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_COLUMN, 0);


}else {
  header('Location: ../login.php');
};


require 'llaves_agencia.view.php';
 ?>
