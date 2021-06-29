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


  if (isset($_POST["barrio_sent"]) && isset($_POST["agencia_id_sent"])) {

          $barrio = $_POST["barrio_sent"];
          $agencia_id = $_POST["agencia_id_sent"];
          $bienes = array();

          $consulta_casa =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder FROM casa WHERE location_tag=:location_tag AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id");
          $consulta_casa->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder FROM departamento WHERE location_tag=:location_tag AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id");
          $consulta_departamento->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder FROM local WHERE location_tag=:location_tag AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id");
          $consulta_local->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder FROM terreno WHERE location_tag=:location_tag AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id");
          $consulta_terreno->execute([':location_tag' => $barrio, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);


        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };


  if (isset($_POST["reference_sent"]) && isset($_POST["agencia_id_sent"])) {

        $referencia = $_POST["reference_sent"];
        $agencia_id = $_POST["agencia_id_sent"];
        $bienes = array();

          $consulta_casa =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder FROM casa WHERE referencia=:referencia AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id ");
          $consulta_casa->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $casa = $consulta_casa->fetchAll();

          $consulta_departamento =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder FROM departamento WHERE referencia=:referencia AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id ");
          $consulta_departamento->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $departamento = $consulta_departamento->fetchAll();

          $consulta_local =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder FROM local WHERE referencia=:referencia AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id ");
          $consulta_local->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $local = $consulta_local->fetchAll();

          $consulta_terreno =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder FROM terreno WHERE referencia=:referencia AND validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id ");
          $consulta_terreno->execute([':referencia' => $referencia, ':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
          $terreno = $consulta_terreno->fetchAll();

          $bienes = array_merge($casa, $departamento, $local, $terreno);

        if ($bienes !== '') {
          echo json_encode($bienes);
        };

  };

  if (isset($_POST["direccion_sent"]) && isset($_POST["agencia_id_sent"])) {

    $direccion_key = "%" . $_POST["direccion_sent"] . "%";
    $agencia_id = $_POST["agencia_id_sent"];

    $bienes = array();

    $consulta_casa =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder, agencia_registro_id FROM casa WHERE ((direccion LIKE ?) AND validacion_agente = 1) OR ((direccion_complemento LIKE ?) AND validacion_agente = 1) ");
    $consulta_casa->execute([$direccion_key, $direccion_key]);//SE PASA EL BARRIO O POBLADO
    $casa = $consulta_casa->fetchAll();

    $consulta_departamento =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder, agencia_registro_id FROM departamento WHERE ((direccion LIKE ?) AND validacion_agente = 1) OR ((direccion_complemento LIKE ?) AND validacion_agente = 1) ");
    $consulta_departamento->execute([$direccion_key, $direccion_key]);//SE PASA EL BARRIO O POBLADO
    $departamento = $consulta_departamento->fetchAll();

    $consulta_local =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder, agencia_registro_id FROM local WHERE ((direccion LIKE ?) AND validacion_agente = 1) OR ((direccion_complemento LIKE ?) AND validacion_agente = 1) ");
    $consulta_local->execute([$direccion_key, $direccion_key]);//SE PASA EL BARRIO O POBLADO
    $local = $consulta_local->fetchAll();

    $consulta_terreno =	$conexion->prepare("SELECT referencia, estado, visibilidad, location_tag, llave, llave_holder, agencia_registro_id FROM terreno WHERE ((direccion LIKE ?) AND validacion_agente = 1) OR ((direccion_complemento LIKE ?) AND validacion_agente = 1 ) ");
    $consulta_terreno->execute([$direccion_key, $direccion_key]);//SE PASA EL BARRIO O POBLADO
    $terreno = $consulta_terreno->fetchAll();

    $bienes_sin_filtrar = array_merge($casa, $departamento, $local, $terreno);

    $bienes = array_filter($bienes_sin_filtrar, function($elemento) use ($agencia_id){
        return $elemento['agencia_registro_id'] == $agencia_id;
    });


    if ($bienes !== '') {
      echo json_encode($bienes);
    };

  };

  if ( isset($_POST["agencia_llavero_sent"]) ) {

      $agencia_id = $_POST["agencia_llavero_sent"];
      $bienes = array();

      $consulta_casa =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder, location_tag FROM casa WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1 ");
      $consulta_casa->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
      $casa = $consulta_casa->fetchAll();

      $consulta_departamento =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder, location_tag FROM departamento WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1 ");
      $consulta_departamento->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
      $departamento = $consulta_departamento->fetchAll();

      $consulta_local =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder, location_tag FROM local WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1 ");
      $consulta_local->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
      $local = $consulta_local->fetchAll();

      $consulta_terreno =	$conexion->prepare("SELECT referencia, estado, visibilidad, llave, llave_holder, location_tag FROM terreno WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1 ");
      $consulta_terreno->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
      $terreno = $consulta_terreno->fetchAll();

      $bienes = array_merge($casa, $departamento, $local, $terreno);


    if ($bienes !== '') {
      echo json_encode($bienes);
    };

  };





};

?>
