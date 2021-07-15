<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,3,10,11,12];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_factura = filter_var($_POST['id_factura'], FILTER_SANITIZE_STRING); //sanitizar el texto y reducirlo a minusculas
    $tipo_factura = filter_var(strtolower($_POST['tipo_factura']), FILTER_SANITIZE_STRING);
    $referencia_inmueble = filter_var(strtolower($_POST['referencia_inmueble']), FILTER_SANITIZE_STRING);
    $fecha_factura = filter_var(strtolower($_POST['fecha_factura']), FILTER_SANITIZE_STRING);

    if ($id_factura !== '' && $tipo_factura !== '' && $fecha_factura !== '') {
      $_SESSION['id_factura'] = $id_factura;
      header('Location: imprimir_facturas.php');
    }else{
      header('Location: ../acceso.php');
    };
    

  };

  $tutechodb_agente = "tutechodb_" . $_COOKIE['tutechopais'];

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb_agente . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

    $consulta_agente_id = $conexion->prepare(" SELECT id, agencia_id FROM agentes WHERE usuario = :usuario AND activo = 1 ");
    $consulta_agente_id->execute([":usuario" => $usuario]);
    $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);

    if ($nivel_acceso == 10) {//SOLO LAS FACTURAS DE ESTE AGENTE EXPRESS
        $consulta_facturas_pendientes = $conexion->prepare(" SELECT id, tipo, fecha, referencia_inmueble FROM facturas WHERE impreso = 0 AND agente_express = :agente_express AND agencia_id = :agencia_id");
        $consulta_facturas_pendientes->execute([':agente_express' => $agente_id['id'], ':agencia_id' => $agente_id['agencia_id']]);
        $facturas_pendientes = $consulta_facturas_pendientes->fetchAll(PDO::FETCH_ASSOC);
    }elseif ($nivel_acceso == 3) {//TODAS LAS FACTURAS DE SU AGENCIA
        $consulta_facturas_pendientes = $conexion->prepare(" SELECT id, tipo, fecha, referencia_inmueble FROM facturas WHERE impreso = 0 AND agencia_id = :agencia_id");
        $consulta_facturas_pendientes->execute([':agencia_id' => $agente_id['agencia_id']]);
        $facturas_pendientes = $consulta_facturas_pendientes->fetchAll(PDO::FETCH_ASSOC);
    }else{//TODAS LAS FACTURAS DEL PAIS EN EL QUE SE CONECTÓ
        $consulta_facturas_pendientes = $conexion->prepare(" SELECT id, tipo, fecha, referencia_inmueble FROM facturas WHERE impreso = 0 ");
        $consulta_facturas_pendientes->execute();
        $facturas_pendientes = $consulta_facturas_pendientes->fetchAll(PDO::FETCH_ASSOC);
    };

    uasort($facturas_pendientes,function($a,$b) {

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

    


}else {
  header('Location: ../login.php');
};

$mesaje_file = '';

if (isset($_SESSION['mesage_file'])) {
	$mesaje_file .= $_SESSION['mesage_file'];
	unset($_SESSION['mesage_file']);
};


require 'imprimir_facturas_consola.view.php';
 ?>
