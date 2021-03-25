<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
}else {
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
};

if (isset($_SESSION['usuario']) && isset($_SESSION['visita_datos'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [4,10];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  function getNumberFormat($numero) {
        
    if ($numero !== '') {
        preg_match_all('!\d+!', $numero, $matches);

        $string_telefono = implode($matches[0]);
        
        
        $digits = ltrim($string_telefono, '0');
        
        return $digits;
    }else{
        return '';
    };
  };

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

  $consulta_pais = $conexion_internacional->prepare(" SELECT moneda_code, moneda FROM paises WHERE pais = :pais ");
  $consulta_pais->execute([":pais" => $_COOKIE['tutechopais']]);
  $pais_info = $consulta_pais->fetch(PDO::FETCH_ASSOC);

  $agencia_tag = $_SESSION['visita_datos']['agencia_tag'];
  $visita_key = $_SESSION['visita_datos']['key'];
  $referencia = $_SESSION['visita_datos']['referencia'];

  if (strpos($referencia, 'C') !== false) {
    $tabla = "casa";
  } else {
    if (strpos($referencia, 'D') !== false) {
        $tabla = "departamento";
    } else {
      if (strpos($referencia, 'L') !== false) {
        $tabla = "local";
      } else {
        if (strpos($referencia, 'T') !== false) {
            $tabla = "terreno";
        }else {
            header('Location: ../acceso.php');
        };
      };
    };
  };

  //Se trae el Id del Usuario conectado
  $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
  $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
  $agente_id =	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);


  //Traer la informacion para el armado de la Ficha Visita

    //Datos del Inmueble
    $consulta_inmueble =	$conexion->prepare("SELECT * FROM $tabla WHERE referencia = :referencia");
    $consulta_inmueble->execute([":referencia" => $referencia]);
    $inmueble = $consulta_inmueble->fetch(PDO::FETCH_ASSOC);

    //Datos de Inventario
    $consulta_inventario =	$conexion->prepare("SELECT id ,item, dimensiones, estado, comentarios, fecha_retiro FROM inventario WHERE localizacion = :localizacion");
    $consulta_inventario->execute([":localizacion" => $referencia]);
    $inventario = $consulta_inventario->fetchAll(PDO::FETCH_ASSOC);

    //Datos del Registrador del inmueble
    $consulta_registrador =	$conexion->prepare("SELECT id, nombre, apellido, contacto FROM agentes WHERE id = :id");
    $consulta_registrador->execute([":id" => $inmueble['registrador_id']]);
    $registrador = $consulta_registrador->fetch(PDO::FETCH_ASSOC);

    //Buscar que datos extra fueron enlazados a esta visita
    $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
    $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
    $data_agentes_tareas = json_decode($json_agentes_tareas, true);

    $datos_visita = $data_agentes_tareas[$agente_id['id']]['visita'][$visita_key];

    if (!empty($datos_visita['contactos_extra'])) {
        $contactos_extra = $datos_visita['contactos_extra'];
    };

    if (!empty($datos_visita['check_lists_extra'])) {
        $check_lists_extra = $datos_visita['check_lists_extra'];
    };

}else {
  header('Location: ../login.php');
};


require 'visita_inmueble.view.php';
 ?>
