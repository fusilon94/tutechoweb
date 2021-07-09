<?php


  $json_data_path = '../../data/' . $pais_selected . '/day_log.json';

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

  
  if (empty($data[$date_to_log]['cierres'])) {//CHECK SI DENTRO DE LA ENTRADA DEL DIA EXISTE LA SECCION REGISTROS, sino la crea
    $data[$date_to_log]['cierres'] = array();
  };

  $cierre_id = generateRandomString(10);
  while (isset($data[$date_to_log]['cierres'][$cierre_id])) {
    $cierre_id = generateRandomString(10);
  };


  // DATOS INMBUEBLE
  $consulta_datos_inmueble = $conexion_load->prepare("SELECT departamento, ciudad, barrio, estado, anticretico, agencia_registro_id, conciliacion_tipo, conciliacion_fecha_limite, comision_factor FROM $tabla WHERE referencia = :referencia");
  $consulta_datos_inmueble->execute([':referencia' => $referencia]);
  $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);

  $reservado = 0; 
  if ($datos_inmueble['conciliacion_tipo'] == '1 Mes') {
    $fecha_limite = new DateTime(date('d-m-Y', strtotime($datos_inmueble['conciliacion_fecha_limite'])));
    $fecha_actual = new DateTime(date('d-m-Y', strtotime('today')));

    $reservado = ($fecha_actual > $fecha_limite ? 0 : 1);

  };

  $agencia = $datos_inmueble['agencia_registro_id'];
  $estado_inmueble = $datos_inmueble['estado'];

  // Introducimos los nuevos datos del registro al $data
  if ($modo == 'anulacion') {

    $data[$date_to_log]['cierres'][$cierre_id] = array (
      'tipo_cierre' => $modo,
      'estado_inmueble' => $estado_inmueble,
      'tipo_inmueble' => $tabla,
      'conciliador' => '',
      'agencia' => $agencia,
      'reservado' => $reservado,
      'precio_final' => '',
      'comision' => [
        'agencia' => '',
        'agente' => '',
        'conciliador' => ''
      ],
      'localizacion' => [
        'departamento' => $datos_inmueble['departamento'],
        'ciudad' => $datos_inmueble['ciudad'],
        'barrio' => $datos_inmueble['barrio']
      ],
      'referencia' => $referencia,
    );

  } else {

    $comision_factor = floatval($datos_inmueble['comision_factor']);

    if ($comision_factor <= 1) {
      $comision = $precio_inmueble * $comision_factor;
    } else {
      $comision = $comision_factor;
    };

    if ($datos_inmueble['conciliacion_tipo'] == '10%') {
      $comision_agencia = $comision * 0.4;
      $comision_concilador = $comision * 0.1;
    } else {
      $comision_agencia = $comision * 0.5;
      $comision_concilador = 0;
    };

    $comision_agente = $comision * 0.5;
   
    $data[$date_to_log]['cierres'][$cierre_id] = array (
      'tipo_cierre' => $modo,
      'estado_inmueble' => $estado_inmueble,
      'tipo_inmueble' => $tabla,
      'conciliador' => $agente_cierre,
      'agencia' => $agencia,
      'reservado' => $reservado,
      'precio_final' => $precio_inmueble,
      'comision' => [
        'agencia' => $comision_agencia,
        'agente' => $comision_agente,
        'conciliador' => $comision_concilador
      ],
      'localizacion' => [
        'departamento' => $datos_inmueble['departamento'],
        'ciudad' => $datos_inmueble['ciudad'],
        'barrio' => $datos_inmueble['barrio']
      ],
      'referencia' => $referencia,
    );

  };
  


  $json_final_data = json_encode($data);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
  file_put_contents($json_data_path, $json_final_data);

?>