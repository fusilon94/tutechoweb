<?php
  // DATOS INMBUEBLE
  $consulta_agencia_id = $conexion_load->prepare("SELECT agencia_registro_id FROM $tabla WHERE referencia = :referencia");
  $consulta_agencia_id->execute([':referencia' => $referencia]);
  $agencia_id = $consulta_agencia_id->fetch(PDO::FETCH_COLUMN, 0);

  $id_factura = generateRandomString(10);
  $fecha_actual = date("d-m-Y", strtotime("today"));//TOMA LA FECHA DE HOY, ya que se cargan los datos al concluir el dia


  $statement = $conexion_load->prepare(
    "INSERT INTO facturas (id, agencia_id, fecha, tipo, referencia_inmueble, detalle, monto) VALUES (:id, :agencia_id, :fecha, :tipo, :referencia_inmueble, :detalle, :monto)"
  );

  $detalle = json_encode(array(
    'cantidad' => '1', 
    'descripcion' => 'Intermediación Inmobiliaria', 
    'precio_unitario' => $precio_inmueble, 
    'sub_total' => $precio_inmueble)
  );

  $statement->execute(array(
    ':id' => $id_factura,
    ':agencia_id' => $agencia_id,
    ':fecha' => $fecha_actual,
    ':tipo' => 'intermediacion',
    ':referencia_inmueble' => $referencia,
    ':detalle' => $detalle,
    ':monto' => $precio_inmueble
  ));  


?>