<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    

    if (isset($_POST['atractivo_sent']) && isset($_POST['bien_tutecho_sent']) && isset($_POST['referencia_sent']) && isset($_POST['tabla_sent']) && isset($_POST['pais_sent'])) {

      $referencia = $_POST['referencia_sent'];
      $tabla = strtolower($_POST['tabla_sent']);
      $atractivo = $_POST['atractivo_sent']/100;
      $bien_tutecho = $_POST['bien_tutecho_sent'];
      $pais = $_POST['pais_sent'];

      // Conexion con la database
      $tutechodb = "tutechodb_" . $pais;

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

      $tutechodb_internacional = "tutechodb_internacional";

      try {
        $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

      $consulta_currency_exchange = $conexion_internacional->prepare(" SELECT cambio_dolar FROM paises WHERE pais = :pais");
      $consulta_currency_exchange->execute([':pais' => $pais]);
      $currency_exchange = $consulta_currency_exchange->fetch(PDO::FETCH_ASSOC);

      $cambio = $currency_exchange['cambio_dolar'];

      $Km1_compra = array(
        (round(70000*$cambio)) => (round(15000*$cambio)),
        (round(100000*$cambio)) => (round(22000*$cambio)),
        (round(130000*$cambio)) => (round(30000*$cambio)),
        (round(160000*$cambio)) => (round(35000*$cambio)),
        (round(200000*$cambio)) => (round(45000*$cambio)),
        (round(250000*$cambio)) => (round(60000*$cambio)),
        (round(350000*$cambio)) => (round(80000*$cambio)),
        (round(500000*$cambio)) => (round(100000*$cambio)),
        (round(750000*$cambio)) => (round(150000*$cambio)),
        (round(1000000*$cambio)) => (round(200000*$cambio))
      );
      $Km1_renta = array(
        (round(300*$cambio)) => (round(80*$cambio)),
        (round(400*$cambio)) => (round(90*$cambio)),
        (round(500*$cambio)) => (round(100*$cambio)),
        (round(600*$cambio)) => (round(110*$cambio)),
        (round(700*$cambio)) => (round(120*$cambio)),
        (round(900*$cambio)) => (round(130*$cambio)),
        (round(1100*$cambio)) => (round(140*$cambio)),
        (round(1300*$cambio)) => (round(155*$cambio)),
        (round(1500*$cambio)) => (round(170*$cambio))
      );
      $Km1_anticretico = array(
        (round(30000*$cambio)) => (round(10000*$cambio)),
        (round(40000*$cambio)) => (round(12000*$cambio)),
        (round(60000*$cambio)) => (round(15000*$cambio)),
        (round(80000*$cambio)) => (round(17000*$cambio)),
        (round(100000*$cambio)) => (round(20000*$cambio)),
        (round(120000*$cambio)) => (round(25000*$cambio)),
        (round(150000*$cambio)) => (round(30000*$cambio)),
        (round(200000*$cambio)) => (round(40000*$cambio)),
        (round(300000*$cambio)) => (round(50000*$cambio)),
        (round(500000*$cambio)) => (round(80000*$cambio))
      );
      $Km2_compra = array(
        (round(70000*$cambio)) => (round(10000*$cambio)),
        (round(100000*$cambio)) => (round(15000*$cambio)),
        (round(130000*$cambio)) => (round(17000*$cambio)),
        (round(160000*$cambio)) => (round(20000*$cambio)),
        (round(200000*$cambio)) => (round(22000*$cambio)),
        (round(250000*$cambio)) => (round(25000*$cambio)),
        (round(350000*$cambio)) => (round(30000*$cambio)),
        (round(500000*$cambio)) => (round(380000*$cambio)),
        (round(750000*$cambio)) => (round(500000*$cambio)),
        (round(1000000*$cambio)) => (round(800000*$cambio))
      );
      $Km2_renta = array(
        (round(300*$cambio)) => (round(50*$cambio)),
        (round(400*$cambio)) => (round(55*$cambio)),
        (round(500*$cambio)) => (round(60*$cambio)),
        (round(600*$cambio)) => (round(65*$cambio)),
        (round(700*$cambio)) => (round(70*$cambio)),
        (round(900*$cambio)) => (round(80*$cambio)),
        (round(1100*$cambio)) => (round(100*$cambio)),
        (round(1300*$cambio)) => (round(120*$cambio)),
        (round(1500*$cambio)) => (round(150*$cambio))
      );
      $Km2_anticretico = array(
        (round(30000*$cambio)) => (round(5000*$cambio)),
        (round(40000*$cambio)) => (round(7000*$cambio)),
        (round(60000*$cambio)) => (round(10000*$cambio)),
        (round(80000*$cambio)) => (round(15000*$cambio)),
        (round(100000*$cambio)) => (round(20000*$cambio)),
        (round(120000*$cambio)) => (round(25000*$cambio)),
        (round(150000*$cambio)) => (round(30000*$cambio)),
        (round(200000*$cambio)) => (round(35000*$cambio)),
        (round(300000*$cambio)) => (round(40000*$cambio)),
        (round(500000*$cambio)) => (round(50000*$cambio))
      );

      if ($tabla == "casa" || $tabla == "departamento") {

        $consulta_info_bien = $conexion->prepare(" SELECT exclusivo, precio, base_imponible, interior_estado, jardin_estado, estado, anticretico, superficie_inmueble, agencia_registro_id, location_tag, avaluo, gestion_acordada, comision, mapa_coordenada_lat, mapa_coordenada_lng, contrato_especial FROM $tabla WHERE referencia = :referencia");
        $consulta_info_bien->execute([':referencia' => $referencia]);
        $info_bien = $consulta_info_bien->fetch(PDO::FETCH_ASSOC);

      };
      if ($tabla == "local") {

        $consulta_info_bien = $conexion->prepare(" SELECT exclusivo, precio, base_imponible, interior_estado, jardin_estado, estado, anticretico, espacios, agencia_registro_id, location_tag, avaluo, gestion_acordada, comision, mapa_coordenada_lat, mapa_coordenada_lng, contrato_especial FROM $tabla WHERE referencia = :referencia");
        $consulta_info_bien->execute([':referencia' => $referencia]);
        $info_bien = $consulta_info_bien->fetch(PDO::FETCH_ASSOC);

      };
      if ($tabla == "terreno") {

        $consulta_info_bien = $conexion->prepare(" SELECT exclusivo, precio, base_imponible, jardin_estado, estado, anticretico, superficie_terreno, agencia_registro_id, location_tag, avaluo, gestion_acordada, comision, mapa_coordenada_lat, mapa_coordenada_lng, contrato_especial FROM $tabla WHERE referencia = :referencia");
        $consulta_info_bien->execute([':referencia' => $referencia]);
        $info_bien = $consulta_info_bien->fetch(PDO::FETCH_ASSOC);

      };



      if($info_bien['anticretico'] == 1){
        $cap_busqueda = 'cap_anticretico';
      }else {

          if ($tabla == 'casa') {
            if ($info_bien['estado'] == 'En Venta') {
              $cap_busqueda = 'cap_compra_casa';
            }else {
              $cap_busqueda = 'cap_renta_casa';
            };
          };
          if ($tabla == 'departamento') {
            if ($info_bien['estado'] == 'En Venta') {
              $cap_busqueda = 'cap_compra_departamento';
            }else {
              $cap_busqueda = 'cap_renta_departamento';
            };
          };
          if ($tabla == 'local') {
            if ($info_bien['estado'] == 'En Venta') {
              $cap_busqueda = 'cap_compra_local';
            }else {
              $cap_busqueda = 'cap_renta_local';
            };
          };
          if ($tabla == 'terreno') {
            if ($info_bien['estado'] == 'En Venta') {
              $cap_busqueda = 'cap_compra_terreno';
            }else {
              $cap_busqueda = 'cap_renta_terreno';
            };
          };

      };


      $location_tag = $info_bien['location_tag'];


      $consulta_info_agencia = $conexion->prepare(" SELECT $cap_busqueda, modo_de_trabajo, mapa_coordenada_lat, mapa_coordenada_lng FROM agencias WHERE id = :id");
      $consulta_info_agencia->execute([':id' => $info_bien['agencia_registro_id']]);
      $info_agencia = $consulta_info_agencia->fetch(PDO::FETCH_ASSOC);

      $consulta_info_competencia = $conexion->prepare(" SELECT precio FROM $tabla WHERE location_tag = :location_tag AND NOT referencia = :referencia");
      $consulta_info_competencia->execute([':location_tag' => $info_bien['location_tag'], ':referencia' => $referencia]);
      $info_competencia = $consulta_info_competencia->fetchAll(PDO::FETCH_ASSOC);

      $Pm = 0;
      $n = count($info_competencia);
      if($n > 5){
        $suma_precios = 0;
        foreach ($info_competencia as $bien_competidor) {
          $suma_precios = $suma_precios + $bien_competidor['precio'];
        };
        $Pm = round($suma_precios/$n);//los precios en db ya estan en la moneda correcta
      };

      function distance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $distance_in_km = $dist * 60 * 1.1515 * 1.609344;
          return $distance_in_km;
        }
      }

      // ############################ CALCULO DE LA FACILIDAD DE VENTA #############################################

      $exclusivo = $info_bien['exclusivo'];
      $PV = $info_bien['precio'];
      $cap_compra = $info_agencia[$cap_busqueda];// en la DB ya esta en la moneda correcta
      $PA = $info_bien['avaluo'];// en la DB ya esta en la moneda correcta
      $Km1 = 0;

      if ($info_bien['anticretico'] == 1) {
        foreach ($Km1_anticretico as $max_range => $value) {
          if ($PV <= $max_range) {
            $Km1 = $value;
            break;
          };
        };
        if ($PV > (round(500000*$cambio))) {
          $Km1 = (round(100000*$cambio));
        };
      }elseif ($info_bien['estado'] == 'En Venta') {
        foreach ($Km1_compra as $max_range => $value) {
          if ($PV <= $max_range) {
            $Km1 = $value;
            break;
          };
        };
        if ($PV > (round(1000000*$cambio))) {
          $Km1 = (round(300000*$cambio));
        };
      }else {
        foreach ($Km1_renta as $max_range => $value) {
          if ($PV <= $max_range) {
            $Km1 = $value;
            break;
          };
        };
        if ($PV > (round(1500*$cambio))) {
          $Km1 = (round(200*$cambio));
        };
      }

      $Km2 = 0;
      if ($info_bien['anticretico'] == 1) {
        foreach ($Km2_anticretico as $max_range => $value) {
          if ($PV <= $max_range) {
            $Km2 = $value;
            break;
          };
        };
        if ($PV > (round(500000*$cambio))) {
          $Km2 = (round(100000*$cambio));
        };
      }elseif ($info_bien['estado'] == 'En Venta') {
        foreach ($Km2_compra as $max_range => $value) {
          if ($PV <= $max_range) {
            $Km2 = $value;
            break;
          };
        };
        if ($PV > (round(1000000*$cambio))) {
          $Km2 = (round(100000*$cambio));
        };
      }else {
        foreach ($Km2_renta as $max_range => $value) {
          if ($PV <= $max_range) {
            $Km2 = $value;
            break;
          };
        };
        if ($PV > (round(1500*$cambio))) {
          $Km2 = (round(170*$cambio));
        };
      };


      $impacto_cap = (1*($cap_compra - $PV))/($Km1 + abs($cap_compra - $PV));
      $impacto_PA = (1*($PA - $PV))/($Km2 + abs($PA - $PV));
      if ($n > 5) {
        $impacto_demanda = ($Pm - $PV)/$Pm;
      }else {
        $impacto_demanda = 0;
      };

      if ($n > 2) {
        $impacto_oferta = (1-(1/($n - 1)))*(-1);
      }else {
        $impacto_oferta = 0;
      };

      $impacto_estado = 0;

      $check_estado = array(
        'A estrenar' => 1,
        'Excelente estado' => 0.8,
        'Buen estado' => 0.6,
        'Trabajos necesarios' => 0.4,
        'A renovar' => 0.2
      );

      if (isset($info_bien['interior_estado'])) {//estamos en casas, departamentos o locales
        if ($info_bien['jardin_estado'] == 'Sin exteriores') {//el inmueble NO cuenta con exteriores
          $impacto_estado = $check_estado[$info_bien['interior_estado']];
        }else {//el inmueble SI cuenta con exteriores
          $impacto_estado = ($check_estado[$info_bien['interior_estado']]+$check_estado[$info_bien['jardin_estado']])/2;
        };
      }else{//estamos en terrenos
        $impacto_estado = $check_estado[$info_bien['interior_estado']];
      };


      $facilidad_venta = (0.2*$exclusivo) + (0.175*$impacto_cap) + (0.1*$impacto_PA) + (0.175*$impacto_demanda) + (0.05*$impacto_oferta) + (0.125*$impacto_estado) + (0.175*$atractivo);

      if ($info_bien['anticretico'] == 1) {

        $impacto_comision = (1*$info_bien['comision'])/((round(1500*$cambio)) + $info_bien['comision']);

      }elseif ($info_bien['estado'] == 'En Venta') {

        $impacto_comision = (1*$info_bien['comision'])/((round(3000*$cambio)) + $info_bien['comision']);

      }else {

        $impacto_comision = (1*$info_bien['comision'])/((round(500*$cambio)) + $info_bien['comision']);

      };
      
      $impacto_gestion = $info_bien['gestion_acordada'];

      $lat_agencia = $info_agencia['mapa_coordenada_lat'];
      $lon_agencia = $info_agencia['mapa_coordenada_lng'];
      $lat_inmueble = $info_bien['mapa_coordenada_lat'];
      $lon_inmueble = $info_bien['mapa_coordenada_lng'];

      $distancia = distance($lat_agencia, $lon_agencia, $lat_inmueble, $lon_inmueble);
      $distancias_valores = array(
        1 => 0,
        3 => 0.2,
        5 => 0.4,
        8 => 0.6,
        10 => 0.8
     );
     $impacto_distacia = 0;
     foreach ($distancias_valores as $max_range => $value) {
       if ($distancia <= $max_range) {
         $impacto_distacia = $value;
         break;
       };
     };
     if ($distancia > 10) {
       $impacto_distacia = 1;
     };

      $sup_inmueble_valores = array(
       50 => 0,
       100 => 0.2,
       130 => 0.4,
       180 => 0.6,
       230 => 0.8
      );
      $espacios_valores = array(
        1 => 0,
        3 => 0.2,
        5 => 0.4,
        7 => 0.6,
        8 => 0.8
      );
      $sup_terreno_valores = array(
       0.5 => 0,
       1 => 0.2,
       4 => 0.4,
       10 => 0.6,
       30 => 0.8
      );

     $impacto_tiempo = 0;
     if ($tabla == 'casa' || $tabla == 'departamento') {

         foreach ($sup_inmueble_valores as $max_range => $value) {
           if ($info_bien['superficie_inmueble'] <= $max_range) {
             $impacto_tiempo = $value;
             break;
           };
         };
         if ($distancia > 230) {
           $impacto_tiempo = 1;
         };

     } elseif ($tabla == 'local') {

         foreach ($espacios_valores as $max_range => $value) {
           if ($info_bien['espacios'] <= $max_range) {
             $impacto_tiempo = $value;
             break;
           };
         };
         if ($distancia > 8) {
           $impacto_tiempo = 1;
         };

     } elseif ($tabla == 'terreno') {

         foreach ($sup_terreno_valores as $max_range => $value) {
           if (($info_bien['superficie_terreno']/10000) <= $max_range) {
             $impacto_tiempo = $value;
             break;
           };
         };
         if ($distancia > 30) {
           $impacto_tiempo = 1;
         };

     };
     if ($info_bien['exclusivo'] == 1) {
       $ponderacion_costos = 0.4;
     }else {
       $ponderacion_costos = 0.5;
     };
     $impacto_costos = ((2*$info_bien['exclusivo']) + (2*$impacto_distacia) + (1*$impacto_tiempo))/5;

     $nivel_ganancia = $impacto_comision + (0.1*$impacto_gestion) - ($ponderacion_costos*$impacto_costos);


     $importancia = $bien_tutecho + $info_bien['contrato_especial'];

     // CALCULO DEL TUTECHO SCORE !!!!!!!!!!!!!!!!!!!!!

     $pond_FV = array(
       1 => 0.4,
       2 => 0.35,
       3 => 0.5,
       4 => 0.6,
       5 => 0.65,
    );
      $pond_NG = array(
        1 => 0.6,
        2 => 0.65,
        3 => 0.5,
        4 => 0.4,
        5 => 0.35,
     );
       $pond_Imp = array(
         1 => 0.2,
         2 => 0.1,
         3 => 0.15,
         4 => 0.2,
         5 => 0.15,
      );

      $modo = $info_agencia['modo_de_trabajo'];

     $tutecho_score = ($pond_FV[$modo]*$facilidad_venta) + ($pond_NG[$modo]*$nivel_ganancia) + ($pond_Imp[$modo]*$importancia);

    $statement = $conexion->prepare(
     "UPDATE $tabla SET tutecho_score = :tutecho_score, visibilidad = :visibilidad, tutecho_bien = :tutecho_bien, atractivo = :atractivo WHERE referencia=:referencia"
    );

    $statement->execute(array(
    ':referencia' => $referencia,
    ':tutecho_score' => $tutecho_score,
    ':visibilidad' => 'visible',
    ':tutecho_bien' => $bien_tutecho,
    ':atractivo' => $atractivo
    ));

    require('data_day_registros.php');

    echo "Bien Inmueble activado exitosamente";

};


    if (isset($_POST['reclamo_sent']) && isset($_POST['destino_registrador_sent']) && isset($_POST['destino_fotografo_sent']) && isset($_POST['destino_creador_vr_sent']) && isset($_POST['referencia_sent']) && isset($_POST['tabla_sent']) && isset($_POST['pais_sent'])) {

      $referencia = $_POST['referencia_sent'];
      $tabla = $_POST['tabla_sent'];
      $reclamo = filter_var($_POST['reclamo_sent'], FILTER_SANITIZE_STRING);
      $destino_registrador_sent = $_POST['destino_registrador_sent'];
      $destino_fotografo_sent = $_POST['destino_fotografo_sent'];
      $destino_creador_vr_sent = $_POST['destino_creador_vr_sent'];
      $current_date = date("Y/m/d");
      $pais = $_POST['pais_sent'];

      // Conexion con la database
      $tutechodb = "tutechodb_" . $pais;

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };


      function generateRandomString() {
          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
          $charactersLength = strlen($characters);
          $randomString = '';
          for ($i = 0; $i < 10; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
          }
          return $randomString;
      };


      if ($destino_registrador_sent == 1 && $reclamo !== '') {
          $codigo = generateRandomString();

          $statement = $conexion->prepare(
           "UPDATE $tabla SET revision_form_solicitada = :revision_form_solicitada WHERE referencia=:referencia"
          );
          $statement->execute(array(
          ':referencia' => $referencia,
          ':revision_form_solicitada' => $codigo
          ));

          $consulta_agente_id = $conexion->prepare(" SELECT registrador_id FROM $tabla WHERE referencia = :referencia ");
          $consulta_agente_id->execute([':referencia' => $referencia]);//SE PASA EL ID DEL AGENTE
          $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);


          $statement_reclamo = $conexion->prepare(
           "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
          );

          $statement_reclamo->execute(array(
            ':codigo' => $codigo,
            ':agente_id' => $agente_id['registrador_id'],
            ':mensaje' => $reclamo,
            ':fecha_creacion' => $current_date,
            ':tipo' => 'reclamo',
            ':key_feature1' => $referencia
          ));
      };


      if ($destino_fotografo_sent == 1 && $reclamo !== '') {
          $codigo = generateRandomString();

          $statement = $conexion->prepare(
           "UPDATE $tabla SET revision_fotos_solicitada = :revision_fotos_solicitada WHERE referencia=:referencia"
          );
          $statement->execute(array(
          ':referencia' => $referencia,
          ':revision_fotos_solicitada' => $codigo
          ));

          $consulta_agente_id = $conexion->prepare(" SELECT fotografo_id FROM $tabla WHERE referencia = :referencia ");
          $consulta_agente_id->execute([':referencia' => $referencia]);//SE PASA EL ID DEL AGENTE
          $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);


          $statement_reclamo = $conexion->prepare(
           "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
          );

          $statement_reclamo->execute(array(
            ':codigo' => $codigo,
            ':agente_id' => $agente_id['fotografo_id'],
            ':mensaje' => $reclamo,
            ':fecha_creacion' => $current_date,
            ':tipo' => 'reclamo',
            ':key_feature1' => $referencia
          ));
      };

      if ($destino_creador_vr_sent == 1 && $reclamo !== '') {
          $codigo = generateRandomString();

          $statement = $conexion->prepare(
           "UPDATE $tabla SET revision_vr_solicitada = :revision_vr_solicitada WHERE referencia=:referencia"
          );
          $statement->execute(array(
          ':referencia' => $referencia,
          ':revision_vr_solicitada' => $codigo
          ));

          $consulta_agente_id = $conexion->prepare(" SELECT creador_tourvr FROM $tabla WHERE referencia = :referencia ");
          $consulta_agente_id->execute([':referencia' => $referencia]);//SE PASA EL ID DEL AGENTE
          $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);


          $statement_reclamo = $conexion->prepare(
           "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
          );

          $statement_reclamo->execute(array(
            ':codigo' => $codigo,
            ':agente_id' => $agente_id['creador_tourvr'],
            ':mensaje' => $reclamo,
            ':fecha_creacion' => $current_date,
            ':tipo' => 'reclamo',
            ':key_feature1' => $referencia
          ));
      };


      echo "Reclamo registrado exitosamente";
    };






};

?>
