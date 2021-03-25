<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};


if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


  if (isset($_POST["barrio_sent"])) {

        $barrio = $_POST["barrio_sent"];
        $bienes = array();

          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM casa WHERE location_tag=:location_tag AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_casa->execute([':location_tag' => $barrio]);
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM departamento WHERE location_tag=:location_tag AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_departamento->execute([':location_tag' => $barrio]);
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM local WHERE location_tag=:location_tag AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_local->execute([':location_tag' => $barrio]);
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM terreno WHERE location_tag=:location_tag AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_terreno->execute([':location_tag' => $barrio]);
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);


        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };


  if (isset($_POST["reference_sent"])) {

        $referencia = $_POST["reference_sent"];
        $bienes = array();

          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM casa WHERE referencia=:referencia AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_casa->execute([':referencia' => $referencia]);
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM departamento WHERE referencia=:referencia AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_departamento->execute([':referencia' => $referencia]);
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM local WHERE referencia=:referencia AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_local->execute([':referencia' => $referencia]);
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM terreno WHERE referencia=:referencia AND validacion_agente = 1 AND reactivacion_autorizacion = 0 AND inactivacion_autorizacion = 0 ");
          $consulta_terreno->execute([':referencia' => $referencia]);
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);

        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };


  if (isset($_POST["referencia_autorizar_sent"]) && isset($_POST["tabla_autorizar_sent"])) {
    $referencia_autorizar = $_POST["referencia_autorizar_sent"];
    $tabla_autorizar = $_POST["tabla_autorizar_sent"];
    $current_date = date("Y/m/d");
    $expiration_date = date("Y/m/d", strtotime("+ 7 day"));;

    $consulta_agencia_id =	$conexion->prepare("SELECT agencia_registro_id FROM $tabla_autorizar WHERE referencia=:referencia ");
    $consulta_agencia_id->execute([':referencia' => $referencia_autorizar]);
    $agencia_id = $consulta_agencia_id->fetch(PDO::FETCH_ASSOC);

    $consulta_gerente =	$conexion->prepare("SELECT gerente_id FROM agencias WHERE id=:id ");
    $consulta_gerente->execute([':id' => $agencia_id['agencia_registro_id']]);
    $gerente = $consulta_gerente->fetch(PDO::FETCH_ASSOC);

    function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    };

    $consulta_visibilidad =	$conexion->prepare("SELECT visibilidad FROM $tabla_autorizar WHERE referencia=:referencia ");
    $consulta_visibilidad->execute([':referencia' => $referencia_autorizar]);
    $visibilidad = $consulta_visibilidad->fetch(PDO::FETCH_ASSOC);


    if ($visibilidad['visibilidad'] == 'visible') {
      $statement = $conexion->prepare("UPDATE $tabla_autorizar SET inactivacion_autorizacion = 1 WHERE referencia = :referencia");
      $statement->execute([':referencia' => $referencia_autorizar]);

      $mensaje = 'La autorizacion de INACTIVACIÓN fue concedida' . '</br></br>' . 'Prosiga USTED con la inactivacion y luego informe al agente correspondiente' . '</br>' . 'Este mensage se eliminará automaticamente en 7 dias';
      $codigo = generateRandomString();

      $statement_respuesta = $conexion->prepare(
       "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, fecha_expiracion) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :fecha_expiracion)"
      );

      $statement_respuesta->execute(array(
        ':codigo' => $codigo,
        ':agente_id' => $gerente['gerente_id'],
        ':mensaje' => $mensaje,
        ':fecha_creacion' => $current_date,
        ':tipo' => 'autorizacion',
        ':key_feature1' => $referencia_autorizar,
        ':fecha_expiracion' => $expiration_date
      ));

      echo "Autorizacion de INACTIVACION enviada exitosamente";

    };
    if ($visibilidad['visibilidad'] == 'no_visible') {
      $statement = $conexion->prepare("UPDATE $tabla_autorizar SET reactivacion_autorizacion = 1 WHERE referencia = :referencia");
      $statement->execute([':referencia' => $referencia_autorizar]);

      $mensaje = 'La autorizacion de REACTIVACIÓN fue concedida' . '</br></br>' . 'Prosiga USTED con la reactivacion y luego informe al agente correspondiente' . '</br>' . 'Este mensage se eliminará automaticamente en 7 dias';
      $codigo = generateRandomString();

      $statement_respuesta = $conexion->prepare(
       "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, fecha_expiracion) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :fecha_expiracion)"
      );

      $statement_respuesta->execute(array(
        ':codigo' => $codigo,
        ':agente_id' => $gerente['gerente_id'],
        ':mensaje' => $mensaje,
        ':fecha_creacion' => $current_date,
        ':tipo' => 'autorizacion',
        ':key_feature1' => $referencia_autorizar,
        ':fecha_expiracion' => $expiration_date
      ));

      echo "Autorizacion de REACTIVACION enviada exitosamente";

    };


  };


};

?>
