<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_internacional";

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


    if (isset($_POST['pais_selected'])) {

      $pais_selected = $_POST['pais_selected'];
      
      $consulta_datos_pais =	$conexion->prepare("SELECT * FROM paises WHERE pais = :pais ");
      $consulta_datos_pais->execute([":pais" => $pais_selected]);
      $datos_pais = $consulta_datos_pais->fetch(PDO::FETCH_ASSOC);

    }else {

      $consulta_datos_pais =	$conexion->prepare("SELECT * FROM paises WHERE pais = :pais ");
      $consulta_datos_pais->execute([":pais" => $_COOKIE['tutechopais']]);
      $datos_pais = $consulta_datos_pais->fetch(PDO::FETCH_ASSOC);

    };

    echo json_encode($datos_pais);


};

?>
