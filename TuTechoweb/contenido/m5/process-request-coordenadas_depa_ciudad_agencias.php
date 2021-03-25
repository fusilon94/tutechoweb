<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


      if (isset($_POST["departamentoChoice"])) {
        $departamento_choice = $_POST["departamentoChoice"];

        $consulta_coordenadas_departamento =	$conexion->prepare("SELECT latitud, longitud, zoom FROM regiones WHERE departamentos=:departamentos ");
        $consulta_coordenadas_departamento->execute(['departamentos' => $departamento_choice]);//SE PASA EL NOMBRE DEL DEPARTAMENTO
        $coordenadas_departamento = $consulta_coordenadas_departamento->fetch(PDO::FETCH_ASSOC);


        echo "
        <input type=\"hidden\" name=\"mapa_coordenada_lat\" id=\"mapa_coordenada_lat\" class=\"\" value=\"" . $coordenadas_departamento['latitud'] . "\">
        <input type=\"hidden\" name=\"mapa_coordenada_lng\" id=\"mapa_coordenada_lng\" class=\"\" value=\"" . $coordenadas_departamento['longitud'] . "\">
        <input type=\"hidden\" name=\"mapa_zoom\" id=\"mapa_zoom\" class=\"\" value=\"" . $coordenadas_departamento['zoom'] . "\">
        ";

      };

      if (isset($_POST["ciudadesChoice"])) {
        $ciudad_choice = $_POST["ciudadesChoice"];

        $consulta_coordenadas_ciudad =	$conexion->prepare("SELECT latitud, longitud, zoom FROM ciudades WHERE ciudad=:ciudad ");
        $consulta_coordenadas_ciudad->execute(['ciudad' => $ciudad_choice]);//SE PASA EL NOMBRE DE LA CIUDAD
        $coordenadas_ciudad = $consulta_coordenadas_ciudad->fetch(PDO::FETCH_ASSOC);

        echo "
        <input type=\"hidden\" name=\"mapa_coordenada_lat\" id=\"mapa_coordenada_lat\" class=\"\" value=\"" . $coordenadas_ciudad['latitud'] . "\">
        <input type=\"hidden\" name=\"mapa_coordenada_lng\" id=\"mapa_coordenada_lng\" class=\"\" value=\"" . $coordenadas_ciudad['longitud'] . "\">
        <input type=\"hidden\" name=\"mapa_zoom\" id=\"mapa_zoom\" class=\"\" value=\"" . $coordenadas_ciudad['zoom'] . "\">
        ";

      };


};

?>
