<?php

  function generateRandomString($length) {//GENERADOR DE RANDOM STRING
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  };

  function get_tabla($referencia) {
    $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
    return $dict[$referencia[5]];
  };


  $json_data_path = '../../data/' . $pais . '/day_log.json';
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

  $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php FROM paises WHERE pais = :pais");
  $consulta_pais_info->execute([":pais" => $pais]);
  $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

  date_default_timezone_set($pais_info['time_zone_php']);

  if (!file_exists($json_data_path)) {//CHECK SI EXISTE EL JSON DATA PAIS, sino lo crea
    $json_constructor = array();
    $json_data = json_encode($json_constructor);
    file_put_contents($json_data_path, $json_data);
  };

  $date_to_log = date("d-m-Y", strtotime("today"));//TOMA LA FECHA DE HOY, ya que se cargan los datos al concluir el dia

  $json_contents = file_get_contents($json_data_path);//SE TRAE EL JSON DATA A SER COMPLETADO CON NUEVOS DATOS DEL DIA
  $data = json_decode($json_contents, true);

  if (empty($data[$date_to_log])) {//CHECK SI EXISTE LA ENTRADA CORRESPONDIENTE AL DIA, sino la crea
    $data[$date_to_log] = array();
  };

  if (empty($data[$date_to_log]['registros'])) {//CHECK SI DENTRO DE LA ENTRADA DEL DIA EXISTE LA SECCION REGISTROS, sino la crea
    $data[$date_to_log]['registros'] = array();
  };

  $tipo_inmueble = get_tabla($referencia);
  
  $consulta_inmueble_info =	$conexion->prepare("SELECT registrador_id, agencia_registro_id, fotografo_id FROM $tipo_inmueble WHERE referencia = :referencia");
  $consulta_inmueble_info->execute([":referencia" => $referencia]);
  $inmueble_info =	$consulta_inmueble_info->fetch(PDO::FETCH_ASSOC);

  
  $registro_id = generateRandomString(10);
  while (isset($data[$date_to_log]['registros'][$registro_id])) {
    $registro_id = generateRandomString(10);
  };

  // Introducimos los nuevos datos del registro al $data
  $data[$date_to_log]['registros'][$registro_id] = array (
    'tipo_inmueble' => $tipo_inmueble,
    'agente' => $inmueble_info['registrador_id'],
    'agencia' => $inmueble_info['agencia_registro_id'],
    'fotografo' => $inmueble_info['fotografo_id']
  );

  $json_final_data = json_encode($data);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
  file_put_contents($json_data_path, $json_final_data);

?>