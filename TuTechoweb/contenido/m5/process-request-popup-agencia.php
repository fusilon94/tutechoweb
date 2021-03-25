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


    if (isset($_POST["agenciaChoice"])) {

      $agencia_id = $_POST["agenciaChoice"];

      $consulta_agentes_disponibles =	$conexion->prepare("SELECT id, nombre, apellido, contacto, disponible FROM agentes WHERE activo = 1 AND (nivel_acceso = 4 OR nivel_acceso = 10) AND agencia_id = :agencia_id ORDER BY disponible DESC");
      $consulta_agentes_disponibles->execute([":agencia_id" => $agencia_id]);
      $agentes_disponibles	=	$consulta_agentes_disponibles->fetchAll(PDO::FETCH_ASSOC);

      $consulta_agencia_info =	$conexion->prepare("SELECT id, departamento, ciudad, location_tag, direccion, direccion_complemento, telefono, mapa_coordenada_lat, mapa_coordenada_lng, mapa_zoom, express FROM agencias WHERE id = :id");
      $consulta_agencia_info->execute([":id" => $agencia_id]);
      $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

      $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

      $json_path_horario = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/horarios.json';

      $json_path_excepciones = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/horarios_excepciones.json';

      $json_path_tabla_precios = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/tabla_precios.json';

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
      

      $current_week = [
        1 => date('d/m/Y',strtotime('monday this week')),
        2 => date('d/m/Y',strtotime('tuesday this week')),
        3 => date('d/m/Y',strtotime('wednesday this week')),
        4 => date('d/m/Y',strtotime('thursday this week')),
        5 => date('d/m/Y',strtotime('friday this week')),
        6 => date('d/m/Y',strtotime('saturday this week'))
      ];
  

      // print_r($current_week);

      echo "
      <div class=\"popup_agencia\">
        <span class=\"popup_agencia_cerrar\"><i class=\"fa fa-times\"></i></span>
        <div class=\"header_popup_agencia\">
          <div class=\"flexslider foto_popup_agencia\">
            <ul class=\"slides\">
              <li>
                <img src=\"../../agencias/" . $_COOKIE['tutechopais'] . "/" . $agencia_info['departamento'] ."_" . $agencia_info['location_tag'] . "/foto_agencia.jpg\" alt=\"Sin Foto\">
              </li>
              <li>
                <img src=\"../../agencias/" . $_COOKIE['tutechopais'] . "/" . $agencia_info['departamento'] ."_" . $agencia_info['location_tag'] . "/foto_agencia_frontis.jpg\" alt=\"Sin Foto\">
              </li>
            </ul>
          </div>
          <div class=\"localizacion_agencia_container\">
            <div class=\"mapa_popup_agencia\">
              <div class=\"wrap_mapa_popup_agencia\">
                <div id=\"mapid_config_popup\" style=\"height:100%; width:100%;\"></div>
              </div>
              <input type=\"hidden\" id=\"popup_agencia_lat\" name=\"popup_agencia_lat\" value=\"" . $agencia_info['mapa_coordenada_lat'] . "\">
              <input type=\"hidden\" id=\"popup_agencia_lng\" name=\"popup_agencia_lng\" value=\"" . $agencia_info['mapa_coordenada_lng'] . "\">
              <input type=\"hidden\" id=\"popup_agencia_zoom\" name=\"popup_agencia_zoom\" value=\"" . $agencia_info['mapa_zoom'] . "\">
              <input type=\"hidden\" id=\"popup_agencia_express\" name=\"popup_agencia_express\" value=\"" . $agencia_info['express'] . "\">
            </div>
            <div class=\"informaciones_popup_agencia\">";
            if ($agencia_info['express'] == 1 && $agencia_info['direccion'] == '' && $agencia_info['telefono'] == '') {
              echo"
                <span class=\"dirreccion_popup_agencia\">
                  <span class=\"fa-stack icon_stacks_marker\">
                    <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                    <i class=\"fa fa-circle\"></i>
                  </span>
                  <span class=\"direccion_popup_texts\">
                    <p>Agencia EXPRESS</p>
                    <p>" . $agencia_info['location_tag'] . " - " . (($agencia_info['location_tag'] == $agencia_info['ciudad']) ? "" : $agencia_info['ciudad']) . " (" . strtoupper($_COOKIE['tutechopais']) . ")</p>
                  </span>
                </span>
                <span class=\"telefono_popup_agencia\">
                  <span class=\"contacto_icons\">
                    <span class=\"fa-stack icon_stacks_mobile\">
                      <i class=\"fa fa-mobile fa-stack-2x\"></i>
                      <i class=\"fa fa-bookmark fa-stack-1x\"></i>
                      <i class=\"fa fa-square\"></i>
                    </span>
                    <span class=\"fa-stack icon_stacks_whatsapp\">
                      <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                      <i class=\"fa fa-circle\"></i>
                    </span>
                  </span>
                  <p class=\"btn_ver_agentes\">Lista de Agentes <i class=\"fa fa-arrow-down\"></i></p>
                </span>
              ";
            }else {
              if ($agencia_info['direccion'] !== '') {
                echo"
                  <span class=\"dirreccion_popup_agencia\">
                    <span class=\"fa-stack icon_stacks_marker\">
                      <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                      <i class=\"fa fa-circle\"></i>
                    </span>
                    <span class=\"direccion_popup_texts\">
                      <p>" . $agencia_info['direccion'] . "</p>
                      <p>" . $agencia_info['direccion_complemento'] . "</p>
                      <p>" . $agencia_info['location_tag'] . " - " . (($agencia_info['location_tag'] == $agencia_info['ciudad']) ? "" : $agencia_info['ciudad']) . " (" . strtoupper($_COOKIE['tutechopais']) . ")</p>
                    </span>
                  </span>
                ";
              };

              if ($agencia_info['telefono'] !== '') {
                echo"
                  <span class=\"telefono_popup_agencia\">
                    <span class=\"contacto_icons\">
                      <span class=\"fa-stack icon_stacks_mobile\">
                        <i class=\"fa fa-mobile fa-stack-2x\"></i>
                        <i class=\"fa fa-bookmark fa-stack-1x\"></i>
                        <i class=\"fa fa-square\"></i>
                      </span>
                      <span class=\"fa-stack icon_stacks_whatsapp\">
                        <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                        <i class=\"fa fa-circle\"></i>
                      </span>
                    </span><p>" . $agencia_info['telefono'] . "</p>
                  </span>
                ";
              };            
            };
              
              echo"<span class=\"contacto_tutecho\">
                <span class=\"fa-stack icon_stacks_marker\">
                  <i class=\"fa fa-envelope-square fa-stack-1x\"></i>
                  <i class=\"fa fa-square\"></i>
                </span>
                <p>contacto-" . $_COOKIE['tutechopais'][0] . $_COOKIE['tutechopais'][1] . "@tutecho.com</p>
              </span>
            </div>
          </div>
        </div>
        <span class=\"popup_titulo_agencia\">
        <p>TuTecho Agencia " . (($agencia_info['express'] == 1) ? "EXPRESS " : "") . "- " . $agencia_info['location_tag'] . "</p>
        <hr class=\"barra_agencia_popup\">
        </span>";

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
              echo"
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
                echo"
                  <span class=\"tabla_column " . check_current_day($current_date, $current_week[$data['day_week']]) . " " . check_past_day($current_date, $current_week[$data['day_week']]) . "\">
                    <span class=\"tabla_titulo_dia\">" . ucfirst($lista_dias[$data['day_week']]) . "</br>" . substr($current_week[$data['day_week']], 0, 5) . "</span>
                    <span class=\"tabla_horario_dia\">" . fill_sub_horario($data, 'dia', $current_week[$data['day_week']], $data_excepciones) . "</span>
                    <hr>
                    <span class=\"tabla_horario_tarde\">" . fill_sub_horario($data, 'tarde', $current_week[$data['day_week']], $data_excepciones) . "</span>
                  </span>
                ";
              }
              else if ($tipo_excepcion == 'feriado') {
                echo"
                  <span class=\"tabla_column " . check_current_day($current_date, $current_week[$data['day_week']]) . " " . check_past_day($current_date, $current_week[$data['day_week']]) . "\">
                    <span class=\"tabla_titulo_dia\">" . ucfirst($lista_dias[$data['day_week']]) . "</br>" . substr($current_week[$data['day_week']], 0, 5) . "</span>
                    <span class=\"tabla_horario_dia\" style=\"height: 100%; font-size: 1.2em; text-align: center\">" . $dia_off . "</span>
                  </span>
                ";
              }else{
                echo"
                  <span class=\"tabla_column " . check_current_day($current_date, $current_week[$data['day_week']]) . " " . check_past_day($current_date, $current_week[$data['day_week']]) . "\">
                    <span class=\"tabla_titulo_dia\">" . ucfirst($lista_dias[$data['day_week']]) . "</br>" . substr($current_week[$data['day_week']], 0, 5) . "</span>
                    <span class=\"tabla_horario_dia\" style=\"height: 100%; font-size: 1.2em; text-align: center\" >Cerrado " . $dia_off . "</span>
                  </span>
                ";
              };
              
            };

          };

          echo " <div class=\"opciones_wrap\">
                  <div class=\"tabla_horario_wrap\">
                    <p class=\"tabla_horario_titulo\">- Horarios de Atención -</p>
                    <div class=\"tabla_horarios\">";

                  foreach ($data_horario as $data) {

                    fill_horario($data, $lista_dias, $current_week, $current_date, $data_excepciones);

                  };

          echo"<input type=\"hidden\" class=\"horario_week_count\" value=\"0\">";

                  
            echo "</div>
                </div>

                <span class=\"week_btn next\">
                  <i class=\"fa fa-chevron-circle-right\"></i>
                  <p>Siguiente</p>
                </span>

                <span class=\"week_btn preview\">
                  <i class=\"fa fa-chevron-circle-left\"></i>
                  <p>Atrás</p>
                </span>

            </div>

            <span class=\"aviso_wrap\">
              " . fill_avisos($data_excepciones, $current_week, $current_date) . "
            </span>";
       
        };

        if (file_exists($json_path_tabla_precios)){
          echo"
          <div class=\"btn_tabla_precios_wrap\">
          <span class=\"btn_tabla_precios\"><p>Tabla de Precios</p></span>
          </div>
          ";
        };

        echo"
        <span class=\"popup_titulo_agencia\">
        <p class=\"titulo_agentes\">Nuestros Agentes</p>
        <hr class=\"barra_agencia_popup\">
        </span>

          <div class=\"agentes_container\">";

          foreach ($agentes_disponibles as $agente) {
            echo"
              <div class=\"agente_wrap " . (($agente['disponible'] == 1) ? "disponible" : "") . "\" data=\"" . $agente['id'] . "\">
                <img src=\"../../agentes/" . $_COOKIE['tutechopais'] . "/" . $agente['id'] . "/foto_blanco.jpg?t=" . time() . "\" alt=\"Foto\" class=\"foto_agente\">
                <span class=\"info_agente_wrap\">
                  <p class=\"nombre_agente\">" . $agente['nombre'] . " " . $agente['apellido'] . "</p>";
                  if ($agente['contacto'] !== '') {
                    echo"
                      <span class=\"contacto_agente\">
                        <span class=\"fa-stack icon_stacks_whatsapp\">
                          <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                          <i class=\"fa fa-circle\"></i>
                        </span>
                        <p class=\"agente_telefono\">" . $agente['contacto'] . "</p>
                      </span>
                    ";
                  };
                  
                  echo"<span class=\"estado_agente " . (($agente['disponible'] == 1) ? "disponible" : "") . "\">" . (($agente['disponible'] == 1) ? "DISPONIBLE" : "NO DISPONIBLE") . "</span>
                </span>
              </div>
            ";
          };
        

      echo"</div>
      
      </div>
        ";
       
      
    };

    if (isset($_POST['agente_id_sent'])) {
     
      $agente_id = $_POST['agente_id_sent'];
      
      $consulta_agente_info =	$conexion->prepare("SELECT id, nombre, apellido, contacto, email, genero FROM agentes WHERE id = :id AND activo = 1");
      $consulta_agente_info->execute([":id" => $agente_id]);
      $agente_info	=	$consulta_agente_info->fetch(PDO::FETCH_ASSOC);

      $foto_agente_path = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_info['id'] . '/foto_blanco.jpg';

      if (file_exists($foto_agente_path)) {
        $foto_src = $foto_agente_path . "?t=" . time();
      }else {
        $foto_src = "../../objetos/" . $agente_info['genero'] . "_icono_min_blue.svg";
      };


      function getNumberFormat($numero) {
        
        if ($numero !== '') {
            preg_match_all('!\d+!', $numero, $matches);

            $string_telefono = implode($matches[0]);
            
            
            $digits = ltrim($string_telefono, '0');
            
            return $digits;
        }else{
            return '';
        };
      };
      
      $agente_nombre = $agente_info['nombre'] . ' ' . $agente_info['apellido'];

      echo"
      <span class=\"contacto_tag\">Contacto</span>
      <div class=\"popup_cabecera\">

          <span class=\"popup_cabecera_titulo\">
              <img src=\"" . $foto_src . "\" alt=\"Foto\">
              <p>" . $agente_nombre . "</p>
          </span>

      </div>

      <hr class=\"cabecera_line\">

      <div class=\"popup_contacto_contenido\">
          <div class=\"popup_contacto_email\">
              <i class=\"fas fa-at\"></i>
              <p>" . $agente_info['email'] . "</p>
              <span class=\"btns_popup_wrap\">
                  <a href='mailto: " . $agente_info['email'] . "' class=\"popup_contacto_mail_btn activo\">
                      <i class=\"fa fa-envelope\"></i>
                      <p>Mail</p>
                  </a>
              </span>
          </div>
          <div class=\"popup_contacto_telefono\">
              <i class=\"fa fa-phone\"></i>
              <p>" . $agente_info['contacto'] . "</p>
              <span class=\"btns_popup_wrap\">
                  <a class=\"popup_contacto_call_btn\" href=\"tel:" . getNumberFormat($agente_info['contacto']) . "\"><p>Llamar</p></a>
                  <a href=\"https://api.whatsapp.com/send?phone=" . getNumberFormat($agente_info['contacto']) . "\" class=\"popup_contacto_whatsapp_btn activo\" target=\"_blank\">
                      <span class=\"fa-stack icon_stacks_whatsapp\">
                          <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                          <i class=\"fa fa-circle\"></i>
                      </span>
                      <p>WhatsApp</p>
                  </a>
              </span>
          </div>
      </div>
      
      ";
            
    };








      
};




?>
