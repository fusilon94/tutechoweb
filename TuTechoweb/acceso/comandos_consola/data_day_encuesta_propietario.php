<?php

  $pais_selected = $_COOKIE['tutechopais'];

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

  // ya se inicializó el php_datetimezone en el archivo que llava/require a este php
  $date_to_log = date("d-m-Y", strtotime("today"));//TOMA LA FECHA DE HOY, ya que se cargan los datos al concluir el dia

  $json_contents = file_get_contents($json_data_path);//SE TRAE EL JSON DATA A SER COMPLETADO CON NUEVOS DATOS DEL DIA
  $data = json_decode($json_contents, true);

  if (empty($data[$date_to_log])) {//CHECK SI EXISTE LA ENTRADA CORRESPONDIENTE AL DIA, sino la crea
    $data[$date_to_log] = array();
  };

  if (empty($data[$date_to_log]['encuesta_marketing'])) {//CHECK SI DENTRO DE LA ENTRADA DEL DIA EXISTE LA SECCION REGISTROS, sino la crea
    $data[$date_to_log]['encuesta_marketing'] = array();
  };

  if (empty($data[$date_to_log]['encuesta_agencia_trato'])) {//CHECK SI DENTRO DE LA ENTRADA DEL DIA EXISTE LA SECCION REGISTROS, sino la crea
    $data[$date_to_log]['encuesta_agencia_trato'] = array();
  };

  $encuesta_marketing_id = generateRandomString(10);
  while (isset($data[$date_to_log]['encuesta_marketing'][$encuesta_marketing_id])) {
    $encuesta_marketing_id = generateRandomString(10);
  };

  $encuesta_agencia_trato_id = generateRandomString(10);
  while (isset($data[$date_to_log]['encuesta_agencia_trato'][$encuesta_agencia_trato_id])) {
    $encuesta_agencia_trato_id = generateRandomString(10);
  };

   //definimos bibliotecas de valores

   $respuesta_marketing_list = [
       1 => 'Agente',
       2 => 'Amigos',
       3 => 'Internet',
       4 => 'Televisión',
       5 => 'Otros'
    ];

    $respuesta_agencia_trato_list = [
        1 => 'Bueno',
        2 => 'Malo',
     ];


  // Introducimos los nuevos datos del registro al $data
  $data[$date_to_log]['encuesta_marketing'][$encuesta_marketing_id] = array (
    'respuesta' => $respuesta_marketing_list[$pregunta_marketing],
    'agencia' => $agencia_id,
    'referencia' => $referencia
  );

  // Introducimos los nuevos datos del registro al $data
  $data[$date_to_log]['encuesta_agencia_trato'][$encuesta_agencia_trato_id] = array (
    'respuesta' => $respuesta_agencia_trato_list[$pregunta_trato_agencia],
    'agencia' => $agencia_id,
    'referencia' => $referencia
  );

  $json_final_data = json_encode($data);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
  file_put_contents($json_data_path, $json_final_data);

?>