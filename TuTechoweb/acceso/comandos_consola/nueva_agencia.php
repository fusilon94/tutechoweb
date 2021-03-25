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
  $array_acceso = [1];

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

    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $barrio = '';
    if (isset($_POST['barrio'])) {
      $barrio = $_POST['barrio'];
    };
    $direccion = filter_var($_POST['direccion'], FILTER_SANITIZE_STRING);
    $complemento = filter_var($_POST['direccion_complemento'], FILTER_SANITIZE_STRING);
    $telefono = $_POST['telefono'];
    $nit = $_POST['nit'];
    $latitud = $_POST['mapa_coordenada_lat'];
    $longitud = $_POST['mapa_coordenada_lng'];
    $zoom = $_POST['mapa_zoom'];
    $modo = $_POST['modo'];

    $location_tag = '';
    if (isset($_POST['barrio'])) {
      $location_tag = $barrio;
    }else {
      $location_tag = $ciudad;
    };

    $directorio_foto = '../../agencias/' . $_SESSION['cookie_pais'] . '/' . $departamento . "_" . $location_tag;
    $foto_destino = $directorio_foto . "/foto_agencia.jpg";
    $foto_destino2 = $directorio_foto . "/foto_agencia_frontis.jpg";

    if ($modo == 'first_entry') {
      function generateRandomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
      };

      $id = generateRandomString(10);

      $statement = $conexion->prepare(
       "INSERT INTO agencias (id, departamento, ciudad, barrio, location_tag, direccion, direccion_complemento, telefono, NIT, mapa_coordenada_lat, mapa_coordenada_lng, mapa_zoom) VALUES (:id, :departamento, :ciudad, :barrio, :location_tag, :direccion, :direccion_complemento, :telefono, :NIT, :mapa_coordenada_lat, :mapa_coordenada_lng, :mapa_zoom)"
      );

      $statement->execute(array(
        ':id' => $id,
        ':departamento' => $departamento,
        ':ciudad' => $ciudad,
        ':barrio' => $barrio,
        ':location_tag' => $location_tag,
        ':direccion' => $direccion,
        ':direccion_complemento' => $complemento,
        ':telefono' => $telefono,
        ':NIT' => $nit,
        ':mapa_coordenada_lat' => $latitud,
        ':mapa_coordenada_lng' => $longitud,
        ':mapa_zoom' => $zoom
      ));

      $consulta_numero_agencias_departamento =	$conexion->prepare("SELECT agencias FROM regiones WHERE departamentos = :departamentos");
      $consulta_numero_agencias_departamento->execute([':departamentos' => $departamento]);
      $numero_agencias_departamento	=	$consulta_numero_agencias_departamento->fetch();

      $cantidad_agencias_departamento = intval($numero_agencias_departamento[0]) + 1;

      $statement_agencias_departamento = $conexion->prepare(
       "UPDATE regiones SET agencias = :agencias WHERE departamentos = :departamentos"
      );

      $statement_agencias_departamento->execute(array(
        ':agencias' => $cantidad_agencias_departamento,
        ':departamentos' => $departamento));


      $consulta_numero_agencias_ciudad =	$conexion->prepare("SELECT agencias FROM ciudades WHERE ciudad = :ciudad");
      $consulta_numero_agencias_ciudad->execute([':ciudad' => $ciudad]);
      $numero_agencias_ciudad	=	$consulta_numero_agencias_ciudad->fetch();

      $cantidad_agencias_ciudad = intval($numero_agencias_ciudad[0]) + 1;

      $statement_agencias_ciudad = $conexion->prepare(
       "UPDATE ciudades SET agencias = :agencias WHERE ciudad = :ciudad"
      );

      $statement_agencias_ciudad->execute([
        ':agencias' => intval($cantidad_agencias_ciudad),
        ':ciudad' => $ciudad]);

      

      if(!is_dir($directorio_foto)){//POR SI ACASO NO EXISTIERA LA CARPETA DONDE PONER LOS LOGOS
        @mkdir($directorio_foto, 0700);
      };

      move_uploaded_file($_FILES['foto']['tmp_name'], $foto_destino);
      move_uploaded_file($_FILES['foto2']['tmp_name'], $foto_destino2);

      $_SESSION['exito_bien_registrado'] = 'Agencia creada exitosamente';
    };

    if ($modo == 'edicion') {

      $id = $_SESSION['agencia_edit'];

      if(!is_dir($directorio_foto)){//POR SI ACASO NO EXISTIERA LA CARPETA DONDE PONER LOS LOGOS
        $consulta_agencia_old_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
        $consulta_agencia_old_info->execute([':id' => $id]);
        $agencia_old_info	=	$consulta_agencia_old_info->fetch(PDO::FETCH_ASSOC);

        $old_directorio = '../../agencias/' . $agencia_old_info['departamento'] . "_" . $agencia_old_info['location_tag'];

          if(is_dir($old_directorio)){
          rename($old_directorio, $directorio_foto);
          };
      };

      if (isset($_FILES['foto']['name'])) {
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto_destino);
      };

      $statement = $conexion->prepare(
       "UPDATE agencias SET departamento = :departamento, ciudad = :ciudad, barrio = :barrio, location_tag = :location_tag, direccion = :direccion, direccion_complemento = :direccion_complemento, telefono = :telefono, NIT = :NIT, mapa_coordenada_lat = :mapa_coordenada_lat, mapa_coordenada_lng = :mapa_coordenada_lng, mapa_zoom = :mapa_zoom WHERE id = :id"
      );

      $statement->execute(array(
        ':id' => $id,
        ':departamento' => $departamento,
        ':ciudad' => $ciudad,
        ':barrio' => $barrio,
        ':location_tag' => $location_tag,
        ':direccion' => $direccion,
        ':direccion_complemento' => $complemento,
        ':telefono' => $telefono,
        ':NIT' => $nit,
        ':mapa_coordenada_lat' => $latitud,
        ':mapa_coordenada_lng' => $longitud,
        ':mapa_zoom' => $zoom
      ));

      unset($_SESSION['agencia_edit']);
      $_SESSION['exito_bien_registrado'] = 'Agencia editada exitosamente';
    };

    header('Location: ../acceso.php');
  };


  // CARGA INICIAL ##############################################################################################################
  $consulta_regiones =	$conexion->prepare("SELECT departamentos FROM regiones");
  $consulta_regiones->execute();
  $regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_COLUMN, 0);

  // MODO EDICION ###############################################################################################################
  $modo_edicion = '';

  if (isset($_SESSION['agencia_edit'])) {
    $agencia_id = $_SESSION['agencia_edit'];

    $consulta_agencia_info =	$conexion->prepare("SELECT * FROM agencias WHERE id =:id");
    $consulta_agencia_info->execute([':id' => $agencia_id]);
    $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

    $modo_edicion = 'activado';
  };

  function fill_edit_info($info, $agencia){
    if (isset($agencia[$info]) && $agencia[$info] !== '') {
      echo $agencia[$info];
    };
  };


}else {
  header('Location: ../login.php');
};


require 'nueva_agencia.view.php';
 ?>
