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


  $pais = 'bolivia';
  $carpeta_agencias = '../../agencias/' . $pais;
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

   // ya se inicializó el php_datetimezone en el archivo que llava/require a este php
  $date_to_log = date("d-m-Y", strtotime("yesterday"));//TOMA LA FECHA DE AYER, ya que se cargan los datos al concluir el dia

  $json_contents = file_get_contents($json_data_path);//SE TRAE EL JSON DATA A SER COMPLETADO CON NUEVOS DATOS DEL DIA
  $data = json_decode($json_contents, true);

  if (empty($data[$date_to_log])) {//CHECK SI EXISTE LA ENTRADA CORRESPONDIENTE AL DIA, sino la crea
    $data[$date_to_log] = array();
  };

  if (empty($data[$date_to_log]['visitas'])) {//CHECK SI DENTRO DE LA ENTRADA DEL DIA EXISTE LA SECCION VISITAS, sino la crea
    $data[$date_to_log]['visitas'] = array();
  };

  $agencias_files = array_diff(scandir($carpeta_agencias), array('.', '..'));// TRAE LA LISTA DE CARPETAS AGENCIAS, excluyendo las '..' y '.'

  
  // Loop a travez las agencias del pais
  foreach ($agencias_files as $agencia_file) {
    // Ignorar si la carpeta no es un directorio valido
    if (!is_dir($carpeta_agencias . '/' . $agencia_file . '/')) {
      continue;
    };
    // Ignorar si el json agentes_tareas no existe
    $json_tareas_path = $carpeta_agencias . '/' . $agencia_file . '/agentes_tareas.json';
    if (!file_exists($json_tareas_path)) {
      continue;
    };
  
    $json_tareas_contents = file_get_contents($json_tareas_path);//TRAE LAS TAREAS DE LOS AGENTES DE UNA AGENCIA
    $data_tareas = json_decode($json_tareas_contents, true);

    
    // Loop a travez las tareas de los agentes
    foreach ($data_tareas as $agente => $tareas) {

      // Filtrar solo las visitas de hoy que tengan un 'exito_check'
      $visitas_del_dia = array_filter($tareas['visita'],function($a) use($date_to_log){
        $fecha_visita = $a['fecha'];
        if ($fecha_visita == $date_to_log && $a['exito_check'] !== '') {
          return $a;
        };
        return false;
      });

      // Loop a travez de las visitas del dia
      foreach ($visitas_del_dia as $visita) {

        $visita_id = generateRandomString(10);
        while (isset($data[$date_to_log]['visitas'][$visita_id])) {
          $visita_id = generateRandomString(10);
        };

        $referencia_inmueble = $visita['referencia'];
        
        $tipo_inmueble = get_tabla($referencia_inmueble);
        $agente_id = $agente;
        $agencia = $agencia_file;
        $tiempo = $visita['tiempo'];
        $exito = $visita['exito_check'];

        // Introducimos los nuevos datos de la visita al $data
        $data[$date_to_log]['visitas'][$visita_id] = array (
          'tipo_inmueble' => $tipo_inmueble,
          'agente' => $agente_id,
          'agencia' => $agencia,
          'tiempo' => $tiempo,
          'exito' => $exito,
          'referencia' => $referencia_inmueble
        );
        
      };
      
    };

  };

  $json_final_data = json_encode($data);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
  file_put_contents($json_data_path, $json_final_data);

?>