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


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  //############################ LO QUE PASA SI SE AUTO-ENVIO ALGO POR METODO POST ##############################################

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento_sent = $_POST['doc_selected'];

    if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
      $tutecho_db_load = "tutechodb_" . $_POST['pais'];
      $pais_sent = $_POST['pais'];

      try {
        $conexion_load = new PDO('mysql:host=localhost;dbname=' . $tutecho_db_load . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };
    }else {
      $pais_sent = $_COOKIE['tutechopais'];
      try {
        $conexion_load = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };
    };

    function generateRandomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    };

    function redimJPG($filename, $nombreDestino, $max_width, $max_height, $calidad){
      list($orig_width, $orig_height) = getimagesize($filename);
      $width = $orig_width;
      $height = $orig_height;
      # taller
      if ($height > $max_height) {
          $width = ($max_height / $height) * $width;
          $height = $max_height;
      }
      # wider
      if ($width > $max_width) {
          $height = ($max_width / $width) * $height;
          $width = $max_width;
      }
      $image_p = imagecreatetruecolor($width, $height);
      $image = imagecreatefromjpeg($filename);
      imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
      imagejpeg($image_p, $nombreDestino, $calidad);
      return true;
    };

    if ($documento_sent == 'casa' || $documento_sent == 'departamento' || $documento_sent == 'local' || $documento_sent == 'terreno') {
      $path_required = "file_maker_process_inmueble.php";
    }else {
      $path_required = "file_maker_process_" . $documento_sent . ".php";
    };
    
    $modo = filter_var($_POST['modo'], FILTER_SANITIZE_STRING);

    require $path_required;

    if ($modo == 'first_entry') {
      $_SESSION['mesage_file'] = 'File Ingresado Exitosamente - Verificación y activación pendiente';
    } else if($modo == 'edicion'){
      if ($nivel_acceso == 1 || $nivel_acceso == 11) {
        $_SESSION['mesage_file'] = 'File Editado Exitosamente';
      } else {
        $_SESSION['mesage_file'] = 'File Editado Exitosamente - Verificación y activación pendiente';
      }; 
    };

    
    // header('Location: consola_registro_documentos.php');
  };

  // MODO EDICION ###############################################################################################################
  $modo_edicion = '';
  $id_file = '';

  if(isset($_SESSION['id_file'])){
    $modo_edicion = '_edicion';
    $id_file = $_SESSION['id_file'];
  };

  // MODO NORMAL #################################################################################################################

  if (isset($_SESSION['tipo_doc_selected']) && isset($_SESSION['pais_selected']) && isset($_SESSION['tipo_file_selected'])) {   
    $documento = $_SESSION['tipo_doc_selected'];
    $pais = $_SESSION['pais_selected'];
    $tipo_file_received = $_SESSION['tipo_file_selected'];

    $tutechodb_internacional = "tutechodb_internacional";

    try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
    };

    $consulta_moneda = $conexion->prepare(" SELECT moneda_code, moneda FROM paises WHERE pais = :pais");
    $consulta_moneda->execute([':pais' => $pais]);
    $info_moneda = $consulta_moneda->fetch(PDO::FETCH_ASSOC);

    $moneda = '(' . $info_moneda['moneda'] . ' ' . $info_moneda['moneda_code'] . ')';

    if ($tipo_file_received == 'personal') {
      $documento_mode = $_SESSION['tipo_doc_selected'] . $modo_edicion;
    }else if($tipo_file_received == 'venta' || $tipo_file_received == 'alquiler'){
      $documento_mode = 'inmueble' . $modo_edicion;
    }else {
      $documento_mode = $_SESSION['tipo_doc_selected'] . $modo_edicion;
    };
    
  }else {
    // header('Location: consola_crear_file.php');
  };

  


}else {
  header('Location: ../login.php');
};


require 'file_maker.view.php';
 ?>
