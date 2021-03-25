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

          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM casa WHERE location_tag=:location_tag AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_casa->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM departamento WHERE location_tag=:location_tag AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_departamento->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM local WHERE location_tag=:location_tag AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_local->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad FROM terreno WHERE location_tag=:location_tag AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_terreno->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);


        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };


  if (isset($_POST["reference_sent"])) {

        $referencia = $_POST["reference_sent"];
        $bienes = array();

          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM casa WHERE referencia=:referencia AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_casa->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM departamento WHERE referencia=:referencia AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_departamento->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM local WHERE referencia=:referencia AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_local->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag FROM terreno WHERE referencia=:referencia AND validacion_agente = 1 AND visibilidad = 'visible' ");
          $consulta_terreno->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);

        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };


  if (isset($_POST["contrato_sent"]) && isset($_POST["formulario_sent"]) && isset($_POST["fotografias_sent"]) && isset($_POST["tour_vr_sent"]) && isset($_POST["referencia_autorizar_sent"]) && isset($_POST["tabla_sent"]) ) {

      $contrato = $_POST["contrato_sent"];
      $formulario = $_POST["formulario_sent"];
      $fotografias = $_POST["fotografias_sent"];
      $tour_vr = $_POST["tour_vr_sent"];
      $referencia = $_POST["referencia_autorizar_sent"];
      $tabla = $_POST["tabla_sent"];
      $current_date = date("Y/m/d");
      $expiration_date = date("Y/m/d", strtotime("+ 7 day"));
      $autorizaciones = '';

      $consulta_agencia_id =	$conexion->prepare("SELECT agencia_registro_id FROM $tabla WHERE referencia=:referencia ");
      $consulta_agencia_id->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
      $agencia_id = $consulta_agencia_id->fetch(PDO::FETCH_ASSOC);

      $consulta_gerente =	$conexion->prepare("SELECT gerente_id FROM agencias WHERE id=:id ");
      $consulta_gerente->execute([':id' => $agencia_id['agencia_registro_id']]);//SE PASA EL BARRIO O POBLADO
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

      if ($contrato == 1) {
        $statement = $conexion->prepare("UPDATE $tabla SET edicion_contrato_autorizacion = 1 WHERE referencia = :referencia");
        $statement->execute([':referencia' => $referencia]);

        $autorizaciones .= 'Contrato -';
      };

      if ($formulario == 1) {
        $statement = $conexion->prepare("UPDATE $tabla SET edicion_form_autorizacion = 1 WHERE referencia = :referencia");
        $statement->execute([':referencia' => $referencia]);
        $autorizaciones .= ' Formulario -';
      };

      if ($fotografias == 1) {
        $statement = $conexion->prepare("UPDATE $tabla SET edicion_fotos_autorizacion = 1 WHERE referencia = :referencia");
        $statement->execute([':referencia' => $referencia]);
        $autorizaciones .= ' Fotografias -';
      };

      if ($tour_vr == 1) {
        $statement = $conexion->prepare("UPDATE $tabla SET edicion_vr_autorizacion = 1 WHERE referencia = :referencia");
        $statement->execute([':referencia' => $referencia]);
        $autorizaciones .= ' Tour VR';
      };


      $mensaje = 'Las autorizaciones de edicion siguientes fueron concedidas: ' . $autorizaciones . '</br></br>' . 'Prosiga informando a los agentes correspondientes, este mensage solo está disponible para el jefe de agencia' . '</br>' . 'Este mensage se eliminará automaticamente en 7 dias';

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
        ':key_feature1' => $referencia,
        ':fecha_expiracion' => $expiration_date
      ));

      echo "Autorizaciones enviadas exitosamente";

  };

};

?>
