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


    if (isset($_POST["tipo_bien_Choice"]) && isset($_POST["estado_bien_Choice"]) && isset($_POST["precio_max_Choice"]) && isset($_POST["superficie_min_Choice"]) && isset($_POST["conditions_sent"]) && isset($_POST["parameters_sent"])){

      $tipo_bien = $_POST["tipo_bien_Choice"];
      $estado_bien = $_POST["estado_bien_Choice"];
      $precio_max = $_POST["precio_max_Choice"];
      $superficie_min = $_POST["superficie_min_Choice"];

      if ($tipo_bien == 'casa') {
        $string_request = "SELECT referencia, tipo_bien, estado, departamento, precio, dormitorios, parqueos, superficie_inmueble, exclusivo, pre_venta, anticretico, mapa_coordenada_lat, mapa_coordenada_lng FROM casa WHERE estado = ? AND precio <= ? AND superficie_inmueble >= ? AND visibilidad = 'visible' ";
        $array_to_execute = [$estado_bien, $precio_max, $superficie_min];

        if ($_POST["conditions_sent"][0] !== "default" && $_POST["parameters_sent"][0] !== "default") {
          $conditions = "AND " . implode(" AND ", $_POST["conditions_sent"]);
          $parameters = $_POST["parameters_sent"];

          $string_request .= $conditions;
          $array_to_execute = array_merge($array_to_execute, $parameters);
        };

        $consulta_bienes_inmuebles =	$conexion->prepare($string_request);
        $consulta_bienes_inmuebles->execute($array_to_execute);//SE PASA EL BARRIO O POBLADO
        $bienes_inmuebles = $consulta_bienes_inmuebles->fetchAll(PDO::FETCH_ASSOC);
      };
      if ($tipo_bien == 'departamento') {
        $string_request = "SELECT referencia, tipo_bien, estado, departamento, precio, dormitorios, parqueos, superficie_inmueble, exclusivo, pre_venta, anticretico, mapa_coordenada_lat, mapa_coordenada_lng FROM departamento WHERE estado = ? AND precio <= ? AND superficie_inmueble >= ? AND visibilidad = 'visible' ";
        $array_to_execute = [$estado_bien, $precio_max, $superficie_min];

        if ($_POST["conditions_sent"][0] !== "default" && $_POST["parameters_sent"][0] !== "default") {
          $conditions = "AND " . implode(" AND ", $_POST["conditions_sent"]);
          $parameters = $_POST["parameters_sent"];

          $string_request .= $conditions;
          $array_to_execute = array_merge($array_to_execute, $parameters);
        };

        $consulta_bienes_inmuebles =	$conexion->prepare($string_request);
        $consulta_bienes_inmuebles->execute($array_to_execute);//SE PASA EL BARRIO O POBLADO
        $bienes_inmuebles = $consulta_bienes_inmuebles->fetchAll(PDO::FETCH_ASSOC);
      };
      if ($tipo_bien == 'local') {
        $string_request = "SELECT referencia, tipo_bien, estado, departamento, precio, parqueos, superficie_inmueble, exclusivo, pre_venta, anticretico, mapa_coordenada_lat, mapa_coordenada_lng FROM local WHERE estado = ? AND precio <= ? AND superficie_inmueble >= ? AND visibilidad = 'visible' ";
        $array_to_execute = [$estado_bien, $precio_max, $superficie_min];

        if ($_POST["conditions_sent"][0] !== "default" && $_POST["parameters_sent"][0] !== "default") {
          $conditions = "AND " . implode(" AND ", $_POST["conditions_sent"]);
          $parameters = $_POST["parameters_sent"];

          $string_request .= $conditions;
          $array_to_execute = array_merge($array_to_execute, $parameters);
        };

        $consulta_bienes_inmuebles =	$conexion->prepare($string_request);
        $consulta_bienes_inmuebles->execute($array_to_execute);//SE PASA EL BARRIO O POBLADO
        $bienes_inmuebles = $consulta_bienes_inmuebles->fetchAll(PDO::FETCH_ASSOC);
      };
      if ($tipo_bien == 'terreno') {
        $string_request = "SELECT referencia, tipo_bien, estado, superficie_terreno_medida, departamento, precio, superficie_terreno, exclusivo, pre_venta, anticretico, mapa_coordenada_lat, mapa_coordenada_lng FROM terreno WHERE estado = ? AND precio <= ? AND superficie_terreno >= ? AND visibilidad = 'visible' ";
        $array_to_execute = [$estado_bien, $precio_max, $superficie_min];

        if ($_POST["conditions_sent"][0] !== "default" && $_POST["parameters_sent"][0] !== "default") {
          $conditions = "AND " . implode(" AND ", $_POST["conditions_sent"]);
          $parameters = $_POST["parameters_sent"];

          $string_request .= $conditions;
          $array_to_execute = array_merge($array_to_execute, $parameters);
        };

        $consulta_bienes_inmuebles =	$conexion->prepare($string_request);
        $consulta_bienes_inmuebles->execute($array_to_execute);//SE PASA EL BARRIO O POBLADO
        $bienes_inmuebles = $consulta_bienes_inmuebles->fetchAll(PDO::FETCH_ASSOC);
      };

      $resultados = array();

      foreach ($bienes_inmuebles as $bien_inmueble) {
        $departamento = $bien_inmueble['departamento'];
        if (!array_key_exists($departamento, $resultados)) {
          $resultados[$departamento] = array();
        };
        $resultados[$departamento][] = $bien_inmueble;
      };

      echo json_encode($resultados);


    }else {
      echo "error de php";
    };

    // print_r($_POST["conditions_sent"]);


};

?>
