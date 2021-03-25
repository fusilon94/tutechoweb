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
  $array_acceso = [1,3,7,10,11,12];
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


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['vr_tour_string']) && isset($_POST['referencia']) && isset($_POST['tabla_bien']) && isset($_POST['modo'])) {
      $vr_tour_string = $_POST['vr_tour_string'];
      $referencia_received = urldecode($_POST['referencia']);
      $tabla_bien_received = $_POST['tabla_bien'];
      $modo_received = $_POST['modo'];
      $VR_json_path = '..\..\bienes_inmuebles' . '\\' . $_COOKIE['tutechopais'] . '\\' . $referencia_received . '\\' . 'VR.json';

      file_put_contents($VR_json_path, $vr_tour_string);

      $consulta_agente = $conexion->prepare("SELECT id, agencia_id FROM agentes WHERE usuario=:usuario");
      $consulta_agente->execute(['usuario' => $usuario]);
      $agente_datos	= $consulta_agente->fetch();

      $agente_id = $agente_datos['id'];
      $current_date = date("Y/m/d");

      if ($modo_received == 'edicion') {

        $consulta_reclamo =	$conexion->prepare("SELECT revision_vr_solicitada FROM $tabla_bien_received WHERE referencia=:referencia");
        $consulta_reclamo->execute([':referencia' => $referencia_received]);
        $reclamo	=	$consulta_reclamo->fetch(PDO::FETCH_ASSOC);

        if ($reclamo['revision_vr_solicitada'] !== '') {
          $statement_reclamo = $conexion->prepare("UPDATE $tabla_bien_received SET revision_vr_solicitada = '' WHERE referencia = :referencia");
          $statement_reclamo->execute([':referencia' => $referencia_received]);

          $statement_borrar_reclamo =	$conexion->prepare("DELETE FROM pendientes WHERE codigo = :codigo ");
          $statement_borrar_reclamo->execute([':codigo' => $reclamo['revision_vr_solicitada']]);

          $_SESSION['exito_bien_registrado'] = 'Correciones a reclamo registradas exitosamente';
        }else {
          $consulta_autorizacion =	$conexion->prepare("SELECT edicion_vr_autorizacion FROM $tabla_bien_received WHERE referencia=:referencia");
          $consulta_autorizacion->execute([':referencia' => $referencia_received]);
          $autorizacion	=	$consulta_autorizacion->fetch(PDO::FETCH_ASSOC);

          if ($autorizacion['edicion_vr_autorizacion'] == 1) {
            $statement_quitar_autorizacion = $conexion->prepare("UPDATE $tabla_bien_received SET edicion_vr_autorizacion = 0 WHERE referencia = :referencia");
            $statement_quitar_autorizacion->execute([':referencia' => $referencia_received]);
          };

          $statement_json_editar = $conexion->prepare(
            "UPDATE $tabla_bien_received SET vr_json = 'VR.json', editor_tourvr_id = :editor_tourvr_id, ultima_edicion_tourvr = :ultima_edicion_tourvr WHERE referencia = :referencia");

          $statement_json_editar->execute(array(
            ':referencia' => $referencia_received,
            ':editor_tourvr_id' => $agente_id,
            ':ultima_edicion_tourvr' => $current_date
          ));

          $_SESSION['exito_bien_registrado'] = 'Tour VR editado exitosamente';
        };


      }else {

          $statement_json = $conexion->prepare(
        		"UPDATE $tabla_bien_received SET vr_json = 'VR.json', creador_tourvr = :creador_tourvr, fecha_creacion_tourvr = :fecha_creacion_tourvr, tourvr_visibilidad = 1 WHERE referencia = :referencia");

        	$statement_json->execute(array(
            ':referencia' => $referencia_received,
            ':creador_tourvr' => $agente_id,
            ':fecha_creacion_tourvr' => $current_date
        	));

          $_SESSION['exito_bien_registrado'] = 'Tour VR creado exitosamente';
      };


      header('Location: ../acceso.php');
    };
  };

  if (!isset($_SESSION['referencia_bien']) || !isset($_SESSION['tabla_bien'])) {
    header('Location: ../acceso.php');
  };
  $referencia = $_SESSION['referencia_bien'];
  $tabla_bien = $_SESSION['tabla_bien'];

  $modo = '';

  $VR_json_path = '..\..\bienes_inmuebles' . '\\' . $_COOKIE['tutechopais'] . "\\" . $referencia . '\\' . 'VR.json';
  $VR_json = '';
  if (file_exists($VR_json_path)) {
    $VR_json = json_decode(file_get_contents($VR_json_path), true);
    $modo = 'edicion';
  }else {
    $modo = 'first_entry';
  };

  $json_fotos_path = '..\..\bienes_inmuebles' . '\\' . $_COOKIE['tutechopais'] . "\\" . $referencia . '\\' . 'fotos.json';
  $fotos_json = [];
  if (file_exists($json_fotos_path)) {
    $fotos_json = json_decode(file_get_contents($json_fotos_path), true);
  };
  $fotos_keys = array_keys($fotos_json);

  // print_r($fotos_json);

  $consulta_materiales =	$conexion->prepare("SELECT * FROM materiales_imagenes");
  $consulta_materiales->execute();
  $materiales	=	$consulta_materiales->fetchAll(PDO::FETCH_NUM);


}else {
  header('Location: ../login.php');
};


require 'crear_tourvr.view.php';
 ?>
