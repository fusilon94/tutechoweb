<?php
  // DATOS INMBUEBLE
  $consulta_agencia_id = $conexion_load->prepare("SELECT anticretico, pre_venta, estado, tipo_bien, agencia_registro_id, comision_factor, location_tag, referencia, departamento FROM $tabla WHERE referencia = :referencia");
  $consulta_agencia_id->execute([':referencia' => $referencia]);
  $inmueble_info = $consulta_agencia_id->fetch(PDO::FETCH_ASSOC);

  $usuario = $_SESSION['usuario'];

  $consulta_agente_id = $conexion_load->prepare(" SELECT id FROM agentes WHERE usuario = :usuario AND activo = 1 ");
  $consulta_agente_id->execute([":usuario" => $usuario]);
  $agente_id = $consulta_agente_id->fetch(PDO::FETCH_COLUMN, 0);

  if ($nivel_acceso == 10) {
    $agente_express = $agente_id;
  }else{
    $agente_express = '';
  };

  $id_factura = generateRandomString(10);
  $fecha_actual = date("d-m-Y", strtotime("today"));//TOMA LA FECHA DE HOY, ya que se cargan los datos al concluir el dia

  $comision_factor = floatval($inmueble_info['comision_factor']);

  if ($comision_factor <= 1) {
    $comision_string = 'Comision Agencia: ' . $comision_factor*100 . '% del precio final';
    $comision_calculo = $precio_inmueble*$comision_factor;
  } else {
    $comision_string = 'Comision Agencia: monto fijo';
    $comision_calculo = $comision_factor;
  };

  if ($inmueble_info['estado'] == 'En Venta') {
    if ($inmueble_info['pre_venta'] == 1) {
      $descripcion = 'Intermediación en la Pre-Venta de un inmueble (' . $inmueble_info['tipo_bien'] . ') ubicada en ' . $inmueble_info['location_tag'] . ' - ' . $inmueble_info['departamento'] . '</br>' . $comision_string . '</br>REF:' . $inmueble_info['referencia'] . '';
    }else{
      $descripcion = 'Intermediación en la Venta de un inmueble (' . $inmueble_info['tipo_bien'] . ') ubicada en ' . $inmueble_info['location_tag'] . ' - ' . $inmueble_info['departamento'] . '</br>' . $comision_string . '</br>REF:' . $inmueble_info['referencia'] . '';
    };
  }elseif($inmueble_info['estado'] == 'En Alquiler'){
    if ($inmueble_info['anticretico'] == 1) {
      $descripcion = 'Intermediación para el Anticretico de un inmueble (' . $inmueble_info['tipo_bien'] . ') ubicada en ' . $inmueble_info['location_tag'] . ' - ' . $inmueble_info['departamento'] . '</br>' . $comision_string . '</br>REF:' . $inmueble_info['referencia'] . '';
    }else{
      $descripcion = 'Intermediación en el Alquiler de un inmueble (' . $inmueble_info['tipo_bien'] . ') ubicada en ' . $inmueble_info['location_tag'] . ' - ' . $inmueble_info['departamento'] . '</br>' . $comision_string . '</br>REF:' . $inmueble_info['referencia'] . '';
    };
  };
  

  $detalle = json_encode(array(
    'cantidad' => '1', 
    'descripcion' => $descripcion, 
    'precio_unitario' => $comision_calculo, 
    'sub_total' => $comision_calculo)
  );

  
  $statement = $conexion_load->prepare(
    "INSERT INTO facturas (id, agencia_id, agente_express, fecha, tipo, referencia_inmueble, detalle, monto) VALUES (:id, :agencia_id, :agente_express, :fecha, :tipo, :referencia_inmueble, :detalle, :monto)"
  );


  $statement->execute(array(
    ':id' => $id_factura,
    ':agencia_id' => $inmueble_info['agencia_registro_id'],
    ':agente_express' => $agente_express,
    ':fecha' => $fecha_actual,
    ':tipo' => 'intermediacion',
    ':referencia_inmueble' => $referencia,
    ':detalle' => $detalle,
    ':monto' => $precio_inmueble
  ));  


?>