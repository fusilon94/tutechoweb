<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
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


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $key_selected = filter_var($_POST['key_selected'], FILTER_SANITIZE_STRING); //sanitizar el texto y reducirlo a minusculas
    $agencia_tag_selected = filter_var($_POST['agencia_tag_selected'], FILTER_SANITIZE_STRING);
    $referencia_selected = filter_var($_POST['referencia_selected'], FILTER_SANITIZE_STRING);

    $datos = [
      "key" => $key_selected,
      "agencia_tag" => $agencia_tag_selected,
      "referencia" => $referencia_selected
    ];

    $_SESSION['visita_datos'] = $datos;

    header('Location: visita_inmueble.php');

  }else {
    if (isset($_SESSION['visita_datos'])) {
      unset($_SESSION['visita_datos']);
    };
  };

  $db_tutecho = "tutechodb_" . $_COOKIE['tutechopais'];

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $db_tutecho . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $db_internacional = "tutechodb_internacional";

  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $db_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_pais = $conexion_internacional->prepare(" SELECT time_zone_php FROM paises WHERE pais = :pais ");
  $consulta_pais->execute([":pais" => $_COOKIE['tutechopais']]);
  $pais_info = $consulta_pais->fetch(PDO::FETCH_ASSOC);

  date_default_timezone_set($pais_info['time_zone_php']);

  $consulta_agente_id = $conexion->prepare(" SELECT id, agencia_id FROM agentes WHERE usuario = :usuario ");
  $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
  $agente_info = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);

  $consulta_agencia_info = $conexion->prepare(" SELECT departamento, location_tag FROM agencias WHERE id = :id ");
  $consulta_agencia_info->execute([":id" => $agente_info['agencia_id']]);
  $agencia_info = $consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

  $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';

  if (!file_exists($json_path_agentes_tareas)) {
    $_SESSION['exito_bien_registrado'] = "Ninguna visita agendada";
    header('Location: ../acceso.php');
  };

  $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
  $data_agentes_tareas = json_decode($json_agentes_tareas, true);

  $visitas_tot = array_filter($data_agentes_tareas[$agente_info['id']]['visita'], function($element) {

    $visita_fecha = new DateTime(date('d-m-Y',strtotime($element['fecha'])));
    $today = new DateTime(date("d-m-Y", time()));

    if ($visita_fecha >= $today) {
        return true;
    };
  });

  uasort($visitas_tot,function($a,$b) {

    preg_match_all('!\d+!', $a['hora'], $a_matches);
    $a_hora = intval(implode('', $a_matches[0]));

    preg_match_all('!\d+!', $b['hora'], $b_matches);
    $b_hora = intval(implode('', $b_matches[0]));
    
    return $a_hora - $b_hora;
  
  });//ordena el array segun hora

  uasort($visitas_tot,function($a,$b) {

    $a_fecha = new DateTime(date('d-m-Y', strtotime($a['fecha'])));
    $b_fecha = new DateTime(date("d-m-Y", strtotime($b['fecha'])));
    
    if ($a_fecha > $b_fecha) {
      return 1;
    }elseif ($a_fecha < $b_fecha) {
      return -1;
    }elseif ($a_fecha == $b_fecha) {
     return 0;
    };
  
  });//re-ordena el array segun fecha
  
    function get_inmueble_tipo($referencia){

    if (strpos($referencia, 'C') !== false) {
      return "casa";
    } else {
      if (strpos($referencia, 'D') !== false) {
        return "departamento";
      } else {
        if (strpos($referencia, 'L') !== false) {
          return "local";
        } else {
          if (strpos($referencia, 'T') !== false) {
            return "terreno";
          };
        };
      };
    };

  };

  function get_inmueble_location($referencia, $conexion){

    $tabla = get_inmueble_tipo($referencia);

    $consulta_location = $conexion->prepare(" SELECT location_tag FROM $tabla WHERE referencia = :referencia ");
    $consulta_location->execute([":referencia" => $referencia]);
    $location = $consulta_location->fetch(PDO::FETCH_ASSOC);

    return $location['location_tag'];
  };

  
  
}else {
  header('Location: ../login.php');
};


require 'visita_inmueble_consola.view.php';
 ?>
