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


  if (isset($_POST["barrio_sent"]) && isset($_POST["agencia_sent"])) {

        $barrio = $_POST["barrio_sent"];
        $agencia = $_POST["agencia_sent"];
        $bienes = array();

        if ($agencia !== '0') {//acceso fotografo y jefe de agencia
          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM casa WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_casa->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM departamento WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_departamento->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM local WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_local->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM terreno WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_terreno->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);

        }else {//acceso admin
          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM casa WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_casa->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM departamento WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_departamento->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM local WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_local->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, reactivacion_autorizacion, inactivacion_autorizacion FROM terreno WHERE location_tag=:location_tag AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_terreno->execute([':location_tag' => $barrio]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);
        };


        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };


  if (isset($_POST["reference_sent"]) && isset($_POST["agencia_sent"])) {

        $referencia = $_POST["reference_sent"];
        $agencia = $_POST["agencia_sent"];
        $bienes = array();

        if ($agencia !== '0') {//acceso fotografo y jefe de agencia
          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM casa WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_casa->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM departamento WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_departamento->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM local WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_local->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM terreno WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND agencia_registro_id = :agencia_registro_id AND vr_json = 'VR.json' ");
          $consulta_terreno->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);

        }else {//acceso admin
          $consulta_casa =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM casa WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_casa->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM departamento WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_departamento->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM local WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_local->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, tipo_bien, visibilidad, location_tag, reactivacion_autorizacion, inactivacion_autorizacion FROM terreno WHERE referencia=:referencia AND validacion_agente = 1 AND validacion_fotografo = 1 AND vr_json = 'VR.json' ");
          $consulta_terreno->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);
        };

        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };




};

?>
