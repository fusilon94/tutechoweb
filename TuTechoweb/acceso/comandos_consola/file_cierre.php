<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$file_maker_entry = 'some_value';

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
  $array_acceso = [1,3,10,11,12];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  

  //############################ LO QUE PASA SI SE AUTO-ENVIO ALGO POR METODO POST ##############################################

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['modo']) || !isset($_POST['doc_selected']) || !isset($_POST['pais'])) {
      header('Location: consola_registro_documentos.php');
    };


    $pais_selected = $_COOKIE['tutechopais'];
    if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
      $pais_selected = $_POST['pais'];
    };

    try {
      $conexion_internacional = new PDO('mysql:host=localhost;dbname=tutechodb_internacional;charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };
    $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php FROM paises WHERE pais = :pais");
    $consulta_pais_info->execute([":pais" => $pais_selected]);
    $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);
    date_default_timezone_set($pais_info['time_zone_php']);




    try {
      $conexion_load = new PDO('mysql:host=localhost;dbname=' . "tutechodb_" . $pais_selected  . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    function get_tabla($referencia) {
      $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
      return $dict[$referencia[5]];
    };

    function DeleteFiles($path){
      if (is_dir($path) === true){
          $files = array_diff(scandir($path), array('.', '..'));
          foreach ($files as $file){
            DeleteFiles(realpath($path) . '/' . $file);
          };
          return rmdir($path);
      }else if (is_file($path) === true){
          return unlink($path);
      };
      return false;
    };


    $modo = $_POST['modo'];
    $referencia = $_POST['doc_selected'];
    $tabla = get_tabla($referencia);

    if ($modo == 'anulacion') {
      
      // Se inactiva y se pone en no visible el bien
      $statement = $conexion_load->prepare(
        "UPDATE $tabla SET visibilidad = 'no_visible', inactivo = 1, anulado = 1 WHERE referencia = :referencia");
      $statement->execute(array(
        ':referencia' => $referencia
      ));

      
    } else {// conclusion
      if (!isset($_POST['precio_inmueble']) || !isset($_POST['agente_cierre'])) {
        header('Location: consola_registro_documentos.php');
      };
      

      $precio_inmueble = filter_var($_POST['precio_inmueble'], FILTER_SANITIZE_NUMBER_INT);
      $agente_cierre = filter_var($_POST['agente_cierre'], FILTER_SANITIZE_STRING);
      $fecha = date("d-m-Y", time());
        
      // Se inactiva y se pone en no visible el bien
      $statement = $conexion_load->prepare(
        "UPDATE $tabla SET visibilidad = 'no_visible', inactivo = 1, agente_cierre = :agente_cierre, fecha_cierre = :fecha_cierre WHERE referencia = :referencia");
      $statement->execute(array(
        ':agente_cierre' => $agente_cierre,
        ':fecha_cierre' => $fecha,
        ':referencia' => $referencia
      ));

    };

    $carpeta_destino = '../../bienes_inmuebles_files/' . $pais_selected . '/' . $referencia;

    $keys_array_docs = array_keys($_FILES);
  
    foreach ($keys_array_docs as $doc) { //SE FILTRAN LAS FOTOS EN LOS DISTINTOS ARRAYS DE ARRIBA
      $temp_name = $_FILES[$doc]['tmp_name'];
      if ($temp_name !== '') {
        if (mime_content_type($temp_name) !== 'application/pdf') {
            $_SESSION['exito_bien_registrado'] = 'Error - Se intentó ingresar un documento con extension erronea';
            header('Location: ../acceso.php');
        };
      }else{
        $_SESSION['exito_bien_registrado'] = 'Error - Se intentó ingresar un documento con extension erronea';
        header('Location: ../acceso.php');
      };
    };

    foreach ($keys_array_docs as $doc) { //SE FILTRAN LAS FOTOS EN LOS DISTINTOS ARRAYS DE ARRIBA
      
      $temp_name = $_FILES[$doc]['tmp_name'];
  
      if (mime_content_type($temp_name) == 'application/pdf') {
          $doc_dir = $carpeta_destino . '/' . $doc . '.pdf';
      };
  
      if(!is_dir($carpeta_destino)){
          @mkdir($carpeta_destino, 0700);
      };
  
      move_uploaded_file($temp_name, $doc_dir);//subimos la nueva foto con el nuevo titulo al file o lo sobreescribe si es modo edicion
    };
    
    
    // Se elimina la carpeta de fotos asociada al bien
    $file_photos_path = '../../bienes_inmuebles/' . $pais_selected . '/' . $referencia; // A CAMBIAR CUANDO USEMOS S3 BUCKET AMAZON AWS

    DeleteFiles($file_photos_path);

    if ($modo == 'anulacion') {
      $_SESSION['mesage_file'] = "El File Inmueble fue ANULADO correctamente";
    }else{
      $_SESSION['mesage_file'] = "Cierre de File Inmueble Exitoso";
    };

    function generateRandomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    };

    //DATA CIERRE
    require 'data_day_cierres.php';

    if ($modo !== 'anulacion') {

      //AGREGAR FACTURA
      $facturacion = 'facturacion_cierre_' . $pais_selected . '.php';
      require $facturacion;
      
    };


    
    header('Location: consola_registro_documentos.php');
    
  };


  if (isset($_SESSION['file_cierre'])) {   
    
    $file = $_SESSION['file_cierre']['file'];
    $pais = $_SESSION['file_cierre']['pais'];
    $tipo_cierre = $_SESSION['file_cierre']['tipo'];
    $tabla = $_SESSION['file_cierre']['tabla'];

    $tutechodb = "tutechodb_" . $pais;

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

    $consulta_moneda = $conexion_internacional->prepare(" SELECT moneda_code, moneda FROM paises WHERE pais = :pais");
    $consulta_moneda->execute([':pais' => $pais]);
    $info_moneda = $consulta_moneda->fetch(PDO::FETCH_ASSOC);

    $moneda = $info_moneda['moneda'] . ' ' . $info_moneda['moneda_code'];

    if ($tipo_cierre == 'anulacion') {
      $file_mode = 'anulacion';
    }else if($tipo_cierre == 'cierre'){
      $file_mode = 'conclusion';
    };

    $consulta_agencia_id =	$conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
    $consulta_agencia_id->execute([':usuario' => $_SESSION['usuario']]);
    $agencia_id	=	$consulta_agencia_id->fetch(PDO::FETCH_ASSOC);

    $consulta_agencia_express =	$conexion->prepare("SELECT express FROM agencias WHERE id = :id");
    $consulta_agencia_express->execute([':id' => $agencia_id['agencia_id']]);
    $agencia_express	=	$consulta_agencia_express->fetch(PDO::FETCH_ASSOC);

    $agencia_tipo = $agencia_express['express'];

    
  }else {
    header('Location: consola_registro_documentos.php');
  };


}else {
  header('Location: ../login.php');
};


require 'file_cierre.view.php';
?>
