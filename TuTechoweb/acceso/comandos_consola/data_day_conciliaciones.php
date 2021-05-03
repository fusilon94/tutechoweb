<?php


  $json_data_path = '../../data/' . $pais_selected . '/day_log.json';
  $tutechodb = "tutechodb_" . $pais_selected;

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

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

  if (empty($data[$date_to_log]['conciliaciones'])) {//CHECK SI DENTRO DE LA ENTRADA DEL DIA EXISTE LA SECCION REGISTROS, sino la crea
    $data[$date_to_log]['conciliaciones'] = array();
  };

  $tipo_inmueble = get_tabla($id_file);

  $conciliacion_id = generateRandomString(10);
  while (isset($data[$date_to_log]['conciliaciones'][$conciliacion_id])) {
    $conciliacion_id = generateRandomString(10);
  };

  // Introducimos los nuevos datos del registro al $data
  $data[$date_to_log]['conciliaciones'][$conciliacion_id] = array (
    'tipo_inmueble' => $tipo_inmueble,
    'tipo' => $conciliacion_tipo,
    'agente' => $conciliador,
    'agencia' => $agencia_asociada['agencia_id']
  );

  $json_final_data = json_encode($data);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
  file_put_contents($json_data_path, $json_final_data);

?>