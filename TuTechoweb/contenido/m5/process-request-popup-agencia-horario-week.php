<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

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


    if (isset($_POST["agenciaChoice"]) && isset($_POST["week_count_sent"])) {

      $agencia_id = $_POST["agenciaChoice"];
      $week_count = $_POST["week_count_sent"];

      $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
      $consulta_agencia_info->execute([":id" => $agencia_id]);
      $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

      $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

      $json_path_horario = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/horarios.json';

      $json_path_excepciones = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/horarios_excepciones.json';

      $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php, idioma FROM paises WHERE pais = :pais");
      $consulta_pais_info->execute([":pais" => $_COOKIE['tutechopais']]);
      $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

      date_default_timezone_set($pais_info['time_zone_php']);

      $current_date = date("d/m/Y");

      if ($pais_info['idioma'] == 'es') {
        $lista_dias = [1 =>'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado'];
      }elseif ($pais_info['idioma'] == 'en') {
        $lista_dias = [1 =>'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday'];
      };//sumar acá otros idiomas

      if ($week_count > 0) {
        $week_factor = "+" . $week_count . " weeks";
        $current_week = [
            1 => date('d/m/Y',strtotime($week_factor, strtotime('monday this week'))),
            2 => date('d/m/Y',strtotime($week_factor, strtotime('tuesday this week'))),
            3 => date('d/m/Y',strtotime($week_factor, strtotime('wednesday this week'))),
            4 => date('d/m/Y',strtotime($week_factor, strtotime('thursday this week'))),
            5 => date('d/m/Y',strtotime($week_factor, strtotime('friday this week'))),
            6 => date('d/m/Y',strtotime($week_factor, strtotime('saturday this week')))
          ];
      }else {
        $current_week = [
            1 => date('d/m/Y',strtotime('monday this week')),
            2 => date('d/m/Y',strtotime('tuesday this week')),
            3 => date('d/m/Y',strtotime('wednesday this week')),
            4 => date('d/m/Y',strtotime('thursday this week')),
            5 => date('d/m/Y',strtotime('friday this week')),
            6 => date('d/m/Y',strtotime('saturday this week'))
          ];
      };

      

      

        if (file_exists($json_path_horario) && file_exists($json_path_excepciones)) {
          $json_horario = file_get_contents($json_path_horario);
          $data_horario = json_decode($json_horario, true);

          $json_excepciones = file_get_contents($json_path_excepciones);
          $data_excepciones = json_decode($json_excepciones, true);


          function fill_sub_horario($data, $momento, $dia_hoy, $data_excepciones){
            
            $excepcion_parcial = false;

            foreach ($data_excepciones['otros'] as $fecha_excepcion => $info) {
              if ($dia_hoy == $fecha_excepcion && $momento == 'dia' && $info['tipo'] == 'dia_off') {
                $excepcion_parcial = true;
              }elseif ($dia_hoy == $fecha_excepcion && $momento == 'tarde' && $info['tipo'] == 'tarde_off') {
                $excepcion_parcial = true;
              };
            };

            if ($excepcion_parcial == true) {
              
              return "Cerrado*";

            }else {

              if ($data[$momento]['activo'] == 1) {
                return $data[$momento]['min'] . " - " . $data[$momento]['max'];
              }elseif ($data[$momento]['activo'] == 0) {
                return "Cerrado";
              };

            };            

          };

          function check_current_day($current_date, $comparison){
            if ($current_date == $comparison) {
              return "current_day";
            }else {
              return;
            }
          };

          function check_past_day($current_date, $comparison){
            $date1 = strtotime(str_replace("/", "-", $current_date));// Los "-", indican fecha europea, osea d-m-Y
            $date2 = strtotime(str_replace("/", "-", $comparison));// Los "-", indican fecha europea, osea d-m-Y
            if ($date2 <  $date1) {
              return "past_day";
            }else {
              return;
            }
          };

          function fill_avisos($data_excepciones, $current_week, $current_date){
            $excepciones_text = '';
            foreach ($data_excepciones['otros'] as $fecha_excepcion => $info) {

              if (in_array($fecha_excepcion, $current_week)) {

                $date1 = strtotime(str_replace("/", "-", $fecha_excepcion));// Los "-", indican fecha europea, osea d-m-Y
                $date2 = strtotime(str_replace("/", "-", $current_date));// Los "-", indican fecha europea, osea d-m-Y

                if ($date2 <= $date1) {

                  if ($info['tipo'] == 'dia_off') {
                    $excepciones_text .= $fecha_excepcion . ": Cerrado en la mañana (" . $info['descripcion'] . ")</br>";
                  }elseif ($info['tipo'] == 'tarde_off') {
                    $excepciones_text .= $fecha_excepcion . ": Cerrado en la tarde (" . $info['descripcion'] . ")</br>";
                  }elseif ($info['tipo'] == 'recorte') {
                    $excepciones_text .= $fecha_excepcion . ": Horario excepcional (" . $info['descripcion'] . ")</br>";
                  };

                };
              };
            };
            if ($excepciones_text !== '') {
              $excepciones_text = "<p class='aviso_tittle'>*Aviso:</p></br><p class='aviso_text'>" . $excepciones_text . "</p>";
            };
            return $excepciones_text;
          };

          function fill_horario($data, $lista_dias, $current_week, $current_date, $data_excepciones){

            if ($data['dia']['activo'] == 0 && $data['tarde']['activo'] == 0) {
                return "
                <span class=\"tabla_column " . check_current_day($current_date, $current_week[$data['day_week']]) . " " . check_past_day($current_date, $current_week[$data['day_week']]) . "\">
                  <span class=\"tabla_titulo_dia\">" . ucfirst($lista_dias[$data['day_week']]) . "</br>" . substr($current_week[$data['day_week']], 0, 5) . "</span>
                  <span class=\"tabla_horario_dia\" style=\"height: 100%; font-size: 1.2em; text-align: center\">Cerrado</span>
                </span>
              ";
            }else {
              $dia_off = '';
              $tipo_excepcion = '';
              foreach ($data_excepciones['feriados'] as $fecha_excepcion => $info) {
                if (substr($current_week[$data['day_week']], 0, 5) == substr($fecha_excepcion, 0, 5)) {
                  $dia_off = $info['descripcion'];
                  $tipo_excepcion = 'feriado';
                };
              };
              foreach ($data_excepciones['otros'] as $fecha_excepcion => $info) {
                if ($current_week[$data['day_week']] == $fecha_excepcion && $info['tipo'] == 'jornada_off') {
                  $dia_off = $info['descripcion'];
                  $tipo_excepcion = 'otro';
                };
              };

              if ($dia_off == '') {
                return "
                  <span class=\"tabla_column " . check_current_day($current_date, $current_week[$data['day_week']]) . " " . check_past_day($current_date, $current_week[$data['day_week']]) . "\">
                    <span class=\"tabla_titulo_dia\">" . ucfirst($lista_dias[$data['day_week']]) . "</br>" . substr($current_week[$data['day_week']], 0, 5) . "</span>
                    <span class=\"tabla_horario_dia\">" . fill_sub_horario($data, 'dia', $current_week[$data['day_week']], $data_excepciones) . "</span>
                    <hr>
                    <span class=\"tabla_horario_tarde\">" . fill_sub_horario($data, 'tarde', $current_week[$data['day_week']], $data_excepciones) . "</span>
                  </span>
                ";
              }
              else if ($tipo_excepcion == 'feriado') {
                return "
                  <span class=\"tabla_column " . check_current_day($current_date, $current_week[$data['day_week']]) . " " . check_past_day($current_date, $current_week[$data['day_week']]) . "\">
                    <span class=\"tabla_titulo_dia\">" . ucfirst($lista_dias[$data['day_week']]) . "</br>" . substr($current_week[$data['day_week']], 0, 5) . "</span>
                    <span class=\"tabla_horario_dia\" style=\"height: 100%; font-size: 1.2em; text-align: center\">" . $dia_off . "</span>
                  </span>
                ";
              }else{
                return "
                  <span class=\"tabla_column " . check_current_day($current_date, $current_week[$data['day_week']]) . " " . check_past_day($current_date, $current_week[$data['day_week']]) . "\">
                    <span class=\"tabla_titulo_dia\">" . ucfirst($lista_dias[$data['day_week']]) . "</br>" . substr($current_week[$data['day_week']], 0, 5) . "</span>
                    <span class=\"tabla_horario_dia\" style=\"height: 100%; font-size: 1.2em; text-align: center\" >Cerrado " . $dia_off . "</span>
                  </span>
                ";
              };
              
            };

          };

          function make_horario_contenido($data_horario, $lista_dias, $current_week, $current_date, $data_excepciones){
              $horario_contenido_constructor = "";
            foreach ($data_horario as $data) {
                $horario_contenido_constructor .= fill_horario($data, $lista_dias, $current_week, $current_date, $data_excepciones);
            };
            return $horario_contenido_constructor;
          };

          $tabla_horario_contenido = "<div class=\"tabla_horarios\">" . make_horario_contenido($data_horario, $lista_dias, $current_week, $current_date, $data_excepciones) . "<input type=\"hidden\" class=\"horario_week_count\" value=\"" . $week_count . "\"></div>";


  
          $avisos_contenido = fill_avisos($data_excepciones, $current_week, $current_date);

          $data_back = ["week" => $tabla_horario_contenido, "avisos" => $avisos_contenido];

          echo json_encode($data_back, JSON_UNESCAPED_UNICODE);//convierte el string en json con caracteres utf8
                  
        };
      
    };


};

?>
