<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


    $consulta_coordenadas_agencias =	$conexion->prepare("SELECT id, location_tag, mapa_coordenada_lat, mapa_coordenada_lng, mapa_zoom, express FROM agencias ");
    $consulta_coordenadas_agencias->execute();//SE PASA EL NOMBRE DEL DEPARTAMENTO
    $coordenadas_agencias = $consulta_coordenadas_agencias->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode($coordenadas_agencias);


};

?>
