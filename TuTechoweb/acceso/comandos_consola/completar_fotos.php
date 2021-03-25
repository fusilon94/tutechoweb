<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
}else {
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
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


  $tutechodb_internacional = "tutechodb_internacional";

  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $consulta_agente = $conexion->prepare("SELECT id FROM agentes WHERE usuario=:usuario");
    $consulta_agente->execute(['usuario' => $usuario]);
    $agente_datos	=	$consulta_agente->fetch();

    $agente_id = $agente_datos['id'];

    // print_r($_POST);
    // print_r($_FILES);

    $referencia_received = $_POST['referencia'];
    $tabla_bien_received = $_POST['tabla_bien'];
    $modo_received = $_POST['modo_consola'];

    unset($_POST['referencia']);//PARA QUE EL ARRAY SOLO CONTENGA TITULOS
    unset($_POST['tabla_bien']);//PARA QUE EL ARRAY SOLO CONTENGA TITULOS
    unset($_POST['modo_consola']);//PARA QUE EL ARRAY SOLO CONTENGA TITULOS

    $titulos = [];// SE PONDRAN LUEGO TODOS LOS TITULOS DE LAS FOTOS
    $titulos_originales = []; //QUE SERAN COMPARADAS A LAS NUEVAS EN CASO DE TENER CREADO UN TOUR VR PARA ESTE BIEN

    $keys_array_titulos = array_keys($_POST);
    foreach ($keys_array_titulos as $key) {
      if (strpos($key, 'original') !== false) {
        $titulos_originales[] = filter_var($_POST[$key], FILTER_SANITIZE_STRING);
      }else {
        $titulos[] = filter_var($_POST[$key], FILTER_SANITIZE_STRING);
      };

    };
    // print_r($titulos);
    // print_r($titulos_originales);

    $fotos = []; // SE PONDRAN LUEGO TODOS LOS TMP-NAMES DE LAS FOTOS
    $fotos_360= []; // SE PONDRAN LUEGO TODOS LOS TMP-NAMES DE LAS FOTOS 360

    $keys_array_fotos = array_keys($_FILES); // SE TRAEN SOLO LAS ETIqUETAS DEL ARRAY PARA LUEGO FILTRARLAS
    // print_r($keys_array_fotos);
    foreach($keys_array_fotos as $key) {
      $temp_name = $_FILES[$key]['tmp_name'];

      if (mime_content_type($temp_name) !== 'image/jpeg') {
          $_SESSION['exito_bien_registrado'] = 'Error - Se intentó ingresar un documento con extension erronea';
          header('Location: ../acceso.php');
      }; 
    };
    


    foreach ($keys_array_fotos as $key) { //SE FILTRAN LAS FOTOS EN LOS DISTINTOS ARRAYS DE ARRIBA

        if (strpos($key, '360') !== false) {
          $fotos_360[] = $_FILES[$key]['tmp_name'];
        }else {
          $fotos[] = $_FILES[$key]['tmp_name'];
        };

    };

    // print_r($fotos);
    // print_r($fotos_360);

    $carpeta_destino = '../../bienes_inmuebles/' . $_COOKIE['tutechopais'] . '/';// SE CREA LA CARPETA DEL BIEN SI NO EXISTIERA
  	$directorio_bien = $carpeta_destino . $referencia_received;
    if(!is_dir($directorio_bien)){
  		@mkdir($directorio_bien, 0700);
  	};

    $directorio_img = $directorio_bien . '/fotos';// SE CREA LA CARPETA DE FOTOS SI NO EXISTIERA
    if(!is_dir($directorio_img)){
  		@mkdir($directorio_img, 0700);
  	};

    $directorio_img_360 = $directorio_bien . '/fotos_360';// SE CREA LA CARPETA DE FOTOS_360 SI NO EXISTIERA
    if(!is_dir($directorio_img_360)){
  		@mkdir($directorio_img_360, 0700);
  	};

    $VR_json_path = '..\..\bienes_inmuebles' . '\\' . $_COOKIE['tutechopais'] . '\\' . $referencia_received . '\\' . 'VR.json';
    $VR_json = [];//VERIFICAMOS SI EXISTE EL JSON VR PARA ESTE BIEN
    if (file_exists($VR_json_path)) {
      $VR_string = file_get_contents($VR_json_path);
    };

// ACA SE SUBEN LAS FOTOS y FOTOS 360 AL SERVER, SE LES CAMBIA EL NOMBRE O SE SOBREESCRIBEN SI NECESARIO #################
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



    $count = 0;
    $titulo_first_foto = '';

    foreach ($titulos as $titulo) {

      if ($fotos[$count] == '') {

          if ($titulo !== $titulos_originales[$count]) {// SOLO SE CAMBIO EL TITULO
            $old_name = $directorio_img . "/" . $titulos_originales[$count] . '.jpg';
            $new_name = $directorio_img . "/" . $titulo . '.jpg';
            rename($old_name, $new_name);//se cambia el nombre a la current foto en file
          }else {//NO SE CAMBIO NADA
            // no se hace nada
          };

      }else {

          if ($titulo !== $titulos_originales[$count]) {//SE CAMBIO LA FOTO 2D Y EL TITULO
            $old_name = $directorio_img . "/" . $titulos_originales[$count] . '.jpg';
            if (file_exists($old_name)) {
              unlink($old_name);//primero borramos la antigua foto en file
            };
            $foto_dir = $directorio_img . "/" . $titulo . '.jpg';
            move_uploaded_file($fotos[$count], $foto_dir);//subimos la nueva foto con el nuevo titulo al file
            $titulo_first_foto = $foto_dir;
          }else {//SOLO SE CAMBIO LA FOTO 2D
            $foto_dir = $directorio_img . "/" . $titulo . '.jpg';
            move_uploaded_file($fotos[$count], $foto_dir);//sobreescribimos la antigua foto con la nueva, mismo titulo en file
            $titulo_first_foto = $foto_dir;
          };

      };

      // LO MISMO PERO PARA LAS FOTOS 360, y EL UPDATE DEL VR.JSON SI EXISTE
      if ($fotos_360[$count] == '') {

          if ($titulo !== $titulos_originales[$count]) {// SOLO SE CAMBIO EL TITULO
            $old_name = $directorio_img_360 . "/" . $titulos_originales[$count] . '.jpg';
            $new_name = $directorio_img_360 . "/" . $titulo . '.jpg';
            rename($old_name, $new_name);//se cambia el nombre a la current foto en file
            if ($VR_string !== '') {
              $old_key = "~" . $titulos_originales[$count] . "~";
              $new_key = "~" . $titulo . "~";
              $old_foto_name = $titulos_originales[$count] . '.jpg';
              $new_foto_name = $titulo . '.jpg';
              $VR_new_string = str_replace(array($old_key, $old_foto_name), array($new_key, $new_foto_name), $VR_string);;
              file_put_contents($VR_json_path, $VR_new_string);
            };
          }else {//NO SE CAMBIO NADA
            // no se hace nada
          };

      }else {

          if ($titulo !== $titulos_originales[$count]) {//SE CAMBIO LA FOTO 360 Y EL TITULO
            $old_name = $directorio_img_360 . "/" . $titulos_originales[$count] . '.jpg';
            if (file_exists($old_name)) {
              unlink($old_name);//primero borramos la antigua foto en file
            };
            $foto_dir = $directorio_img_360 . "/" . $titulo . '.jpg';
            move_uploaded_file($fotos_360[$count], $foto_dir);//subimos la nueva foto con el nuevo titulo al file
            if ($VR_string !== '') {
              $old_key = "~" . $titulos_originales[$count] . "~";
              $new_key = "~" . $titulo . "~";
              $old_foto_name = $titulos_originales[$count] . '.jpg';
              $new_foto_name = $titulo . '.jpg';
              $VR_new_string = str_replace(array($old_key, $old_foto_name), array($new_key, $new_foto_name), $VR_string);;
              file_put_contents($VR_json_path, $VR_new_string);
            };
          }else {//SOLO SE CAMBIO LA FOTO 360
            $foto_dir = $directorio_img_360 . "/" . $titulo . '.jpg';
            move_uploaded_file($fotos_360[$count], $foto_dir);//sobreescribimos la antigua foto con la nueva, mismo titulo en file
          };

      };
      $count++;
    };


    $thumb_dir = $directorio_bien . '/portada.jpg';
    redimJPG($titulo_first_foto, $thumb_dir, 300, 300, 100);

    // ACA SE ARMAN EL JSON QUE CONTENDRAN LA INFO DE LAS FOTOS/FOTOS360 ##################

    $fotos_json_string = new stdClass();//se crea un array vacio sin keys definidas, asi podran ser asosiativas
    foreach ($titulos as $element) {//se adjuntan los elementos y se arma el string a ser convertido luego en json file
      $foto_extension = $element . '.jpg';
      $foto_key = "~" . $element . "~";
      $fotos_json_string->$foto_key = $foto_extension;
    };

    $fotos_json_file = json_encode($fotos_json_string, JSON_UNESCAPED_UNICODE);//convierte el string en json con caracteres utf8
    $json_path = $directorio_bien . '/fotos.json';

    file_put_contents($json_path, $fotos_json_file);//crea el file.json con el codigo json dentro, y si ya existe, lo sobreescribe


    // ACA SE BUSCA ELIMINAR TODA FOTO NO PRESENTE EN LA LISTA PERO AUN SI EN EL FILE ########################

    $all_fotos_files = glob($directorio_img . '/*'); // get all file names from fotos
    $all_fotos_360_files = glob($directorio_img_360 . '/*'); // get all file names from fotos_360

    foreach ($all_fotos_files as $foto_file) {
      $trash_info = array(($directorio_img . '/'), '.jpg' );
      $file_titulo = str_replace($trash_info, "", $foto_file);//para tener solo el nombre de la foto y no todo el path
      if(!in_array($file_titulo, $titulos)){//si el nombre de la foto NO se encuentra en la lista actualizada
        unlink($foto_file);//entonces se borra esa foto
      };
    };

    foreach ($all_fotos_360_files as $foto_file) {
      $trash_info = array(($directorio_img_360 . '/'), '.jpg' );
      $file_titulo = str_replace($trash_info, "", $foto_file);//para tener solo el nombre de la foto y no todo el path
      if(!in_array($file_titulo, $titulos)){//si el nombre de la foto NO se encuentra en la lista actualizada
        unlink($foto_file);//entonces se borra esa foto
      };
    };

    // ACA SE GUARDA LA DIRECCION DEL JSON.FILE EN LA DB ####################################

    if ($modo_received == 'first entry') {
      $current_date = date("Y/m/d");
      $statement_json = $conexion->prepare(
    		"UPDATE $tabla_bien_received SET fotos_json = 'fotos.json', validacion_fotografo = 1, fotografo_id = :fotografo_id, fecha_validacion_fotografo = :fecha_validacion_fotografo WHERE referencia = :referencia");

    	$statement_json->execute(array(
    		':referencia' => $referencia_received,
        ':fotografo_id' => $agente_id,
        ':fecha_validacion_fotografo' => $current_date
    	));

      $_SESSION['exito_bien_registrado'] = 'Fotos Registradas Exitosamente';
    };

    if ($modo_received == 'edicion') {

      $consulta_reclamo =	$conexion->prepare("SELECT revision_fotos_solicitada FROM $tabla_bien_received WHERE referencia=:referencia");
      $consulta_reclamo->execute([':referencia' => $referencia_received]);
      $reclamo	=	$consulta_reclamo->fetch(PDO::FETCH_ASSOC);

      if ($reclamo['revision_fotos_solicitada'] !== '') {
        $statement = $conexion->prepare(
      		"UPDATE $tabla_bien_received SET revision_fotos_solicitada = '' WHERE referencia = :referencia");
      	$statement->execute([':referencia' => $referencia_received]);

        $statement_borrar_reclamo =	$conexion->prepare("DELETE FROM pendientes WHERE codigo = :codigo ");
        $statement_borrar_reclamo->execute([':codigo' => $reclamo['revision_fotos_solicitada']]);

        if (file_exists($VR_json_path)) {
          $_SESSION['exito_bien_registrado'] = 'Correciones a reclamo registradas exitosamente</br><b>Tour VR puede necesitar modificaciones</b>';
        }else {
          $_SESSION['exito_bien_registrado'] = 'Correciones a reclamo registradas exitosamente';
        };
      }else {
        $consulta_autorizacion =	$conexion->prepare("SELECT edicion_fotos_autorizacion FROM $tabla_bien_received WHERE referencia=:referencia");
        $consulta_autorizacion->execute([':referencia' => $referencia_received]);
        $autorizacion	=	$consulta_autorizacion->fetch(PDO::FETCH_ASSOC);

        if ($autorizacion['edicion_fotos_autorizacion'] == 1) {
          $statement_quitar_autorizacion = $conexion->prepare(
        		"UPDATE $tabla_bien_received SET edicion_fotos_autorizacion = 0 WHERE referencia = :referencia");
        	$statement_quitar_autorizacion->execute([':referencia' => $referencia_received]);
        };

        $current_date = date("Y/m/d");

        $statement_json = $conexion->prepare(
      		"UPDATE $tabla_bien_received SET fotos_json = 'fotos.json', ultima_edicion_fotos = :ultima_edicion_fotos, editor_fotos_id = :editor_fotos_id WHERE referencia = :referencia");

      	$statement_json->execute(array(
      		':referencia' => $referencia_received,
          ':ultima_edicion_fotos' => $current_date,
          ':editor_fotos_id' => $agente_id
      	));

        if (file_exists($VR_json_path)) {
          $_SESSION['exito_bien_registrado'] = 'Fotos Modificadas Exitosamente</br><b>Tour VR puede necesitar modificaciones</b>';
        }else {
          $_SESSION['exito_bien_registrado'] = 'Fotos Modificadas Exitosamente';
        };
      };




    };


    header('Location: ../acceso.php');


  };

  if (!isset($_SESSION['referencia_bien']) || !isset($_SESSION['tabla_bien'])) {
    header('Location: ../acceso.php');
  }else {
    $referencia = $_SESSION['referencia_bien'];
    $tabla_bien = $_SESSION['tabla_bien'];
  };

  // PENSAR EN HACER UNSET DE ESTAS SESSION###### --> HECHO al finald el .view

  $consulta_contrato_info = $conexion->prepare("SELECT exclusivo FROM $tabla_bien WHERE referencia=:referencia");
  $consulta_contrato_info->execute([':referencia' => $referencia]);
  $contrato_info	= $consulta_contrato_info->fetch(PDO::FETCH_ASSOC);

  $exclusivo = $contrato_info['exclusivo'];

  $consulta_parametros_pais = $conexion_internacional->prepare("SELECT 360_activo, 360_exclusivo FROM paises WHERE pais=:pais");
  $consulta_parametros_pais->execute([':pais' => $_COOKIE['tutechopais']]);
  $parametros_pais	= $consulta_parametros_pais->fetch(PDO::FETCH_ASSOC);

  $activo_360 = $parametros_pais['360_activo'];

  $exclusivo_360 = $parametros_pais['360_exclusivo'];

  $VR_json_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $referencia . '\\' . 'VR.json';
  $VR_json = [];
  if (file_exists($VR_json_path)) {
    $VR_json = json_decode(file_get_contents($VR_json_path), true);
  };

  $json_fotos_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $referencia . '\\' . 'fotos.json';
  $modo_consola = '';
  $carpeta_destino = '../../bienes_inmuebles/' . $_COOKIE['tutechopais'] . '/';
  $directorio_bien = $carpeta_destino . urlencode($referencia);
  $fotos_edicion_count = 0;


  if (file_exists($json_fotos_path)) { //Si el json file de fotos existe, cagar las fotos en modo edicion
    $modo_consola = 'edicion';
    $fotos_json_file_found = file_get_contents($json_fotos_path);
    $fotos_found = json_decode($fotos_json_file_found, true);
    $fotos_edicion_count = count($fotos_found);

  }else {//Si el json file fotos NO existe, cargar solo tres campos de foto vacios
    $modo_consola = 'first entry';
  };

}else {
  header('Location: ../login.php');
};


require 'completar_fotos.view.php';
 ?>
