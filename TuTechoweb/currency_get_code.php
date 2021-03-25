<?php

// https://currencyscoop.com/supported-currencies

  $tutechodb = "tutechodb_internacional";

  try {
  $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
  };

  $consulta_paises = $conexion->prepare(" SELECT pais, moneda_code, fecha_cambio_update FROM paises");
  $consulta_paises->execute();
  $paises = $consulta_paises->fetchAll(PDO::FETCH_ASSOC);

  if ($paises[0]['fecha_cambio_update'] !== date('Y-m-d')) {

    // set API Endpoint and access key (and any options of your choice)
    $endpoint = 'latest';
    $api_key = 'f02fa54d0b129d0230089935c965046c';

    // Base currency and other currencies for which we want the rates
    $base = 'USD';


    // Initialize CURL:
    $ch = curl_init('https://api.currencyscoop.com/v1/'.$endpoint.'?api_key='.$api_key.'&base='.$base.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Store the data:
    $json = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response:
    $jsonRates = json_decode($json, true);

    $exchangeRates = $jsonRates['response']['rates'];

    $current_date = date('y-m-d');

    foreach ($paises as $pais) {

        $statement = $conexion->prepare(
            "UPDATE paises SET fecha_cambio_update = :fecha_cambio_update, cambio_dolar = :cambio_dolar WHERE pais=:pais"
           );
           $statement->execute(array(
           ':pais' => $pais['pais'],
           ':fecha_cambio_update' => $current_date,
           ':cambio_dolar' => $exchangeRates[$pais['moneda_code']]
           ));

    };

    
    

  };



 ?>
