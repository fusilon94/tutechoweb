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

    $tutechodb_internacional = "tutechodb_internacional";

    try {
      $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


    if (isset($_POST["agenciaChoice"])) {
      $agencia_id = $_POST["agenciaChoice"];

      $consulta_info_agencia =	$conexion->prepare("SELECT * FROM agencias WHERE id=:id ");
      $consulta_info_agencia->execute([':id' => $agencia_id]);//SE PASA EL NOMBRE DEL SPONSOR
      $info_agencia = $consulta_info_agencia->fetch(PDO::FETCH_ASSOC);

      $agencia_tag = $info_agencia['departamento'] . '_' .$info_agencia['location_tag'];
      $pais = $_COOKIE['tutechopais'];

      $json_horario_path = '../../agencias/' . $pais . '/' . $agencia_tag . '/horarios.json';
      $json_excepciones_path = '../../agencias/' . $pais . '/' . $agencia_tag . '/horarios_excepciones.json';

      $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php, idioma FROM paises WHERE pais = :pais");
      $consulta_pais_info->execute([":pais" => $_COOKIE['tutechopais']]);
      $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

      if ($pais_info['idioma'] == 'es') {
        $lista_dias = [1 =>'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado'];
      }elseif ($pais_info['idioma'] == 'en') {
        $lista_dias = [1 =>'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday'];
      };//sumar acá otros idiomas

      function check_select($value, $comparison){
          if($value == $comparison){
              return 'selected';
          }else {
              return '';
          };
      };

      function check_select_disabled($value){
          if($value == 0){
              return 'disabled';
          }else {
              return '';
          };
      };

      function check_sub_label($value){
          if($value == 0){
              return 'inactivo';
          }else {
              return '';
          };
      };

      function check_sub_label_text($value){
          if($value == 0){
              return 'Inactivo';
          }else {
              return 'Activo';
          };
      };

      function check_sub_label_val($value){
        if($value == 0){
            return '0';
        }else {
            return '1';
        };
    };

      if (file_exists($json_horario_path)) {

        $json_horario = file_get_contents($json_horario_path);
        $data_horario = json_decode($json_horario, true);

        echo"
          <h2 class=\"titulo_tabla\">Horarios de Apertura</h2>
          <hr class=\"linea_naranja\">

          <div class=\"week_wrap\">
        ";

        foreach ($data_horario as $data) {
         echo"
            <div class=\"day_wrap\" name=\"" . $lista_dias[$data['day_week']] . "\">
              <h2 class=\"day_label\">" . ucfirst($lista_dias[$data['day_week']]) . "</h2>
              <div class=\"inputs_day_wrap\">
                  <span class=\"day_sub_label\">
                      <p class=\"sub_label_text\">Día</p>
                      <span class=\"check_sub_label " . check_sub_label($data['dia']['activo']) . "\">" . check_sub_label_text($data['dia']['activo']) . "</span>
                      <input type=\"hidden\" class=\"check_dia\" value=\"" . check_sub_label_val($data['dia']['activo']) . "\">
                  </span>
                  <span class=\"selects_day_wrap\">
                      <select name=\"" . $lista_dias[$data['day_week']] . "_dia_min\" id=\"" . $lista_dias[$data['day_week']] . "_dia_min\" class=\"select_day\" " . check_select_disabled($data['dia']['activo']) . ">
                          <option value=\"\"></option>
                          <option value=\"7:00\" " . check_select($data['dia']['min'], "7:00") . ">7:00</option>
                          <option value=\"7:30\" " . check_select($data['dia']['min'], "7:30") . ">7:30</option>
                          <option value=\"8:00\" " . check_select($data['dia']['min'], "8:00") . ">8:00</option>
                          <option value=\"8:30\" " . check_select($data['dia']['min'], "8:30") . ">8:30</option>
                          <option value=\"9:00\" " . check_select($data['dia']['min'], "9:00") . ">9:00</option>
                          <option value=\"9:30\" " . check_select($data['dia']['min'], "9:30") . ">9:30</option>
                          <option value=\"10:00\" " . check_select($data['dia']['min'], "10:00") . ">10:00</option>
                          <option value=\"10:30\" " . check_select($data['dia']['min'], "10:30") . ">10:30</option>
                          <option value=\"11:00\" " . check_select($data['dia']['min'], "11:00") . ">11:00</option>
                          <option value=\"11:30\" " . check_select($data['dia']['min'], "11:30") . ">11:30</option>
                          <option value=\"12:00\" " . check_select($data['dia']['min'], "12:00") . ">12:00</option>
                          <option value=\"12:30\" " . check_select($data['dia']['min'], "12:30") . ">12:30</option>
                          <option value=\"13:00\" " . check_select($data['dia']['min'], "13:00") . ">13:00</option>
                      </select>
                      <p class=\"select_day_spacer\">a</p>
                      <select name=\"" . $lista_dias[$data['day_week']] . "_dia_max\" id=\"" . $lista_dias[$data['day_week']] . "_dia_max\" class=\"select_day\" " . check_select_disabled($data['dia']['activo']) . ">
                          <option value=\"\"></option>
                          <option value=\"7:00\" " . check_select($data['dia']['max'], "7:00") . ">7:00</option>
                          <option value=\"7:30\" " . check_select($data['dia']['max'], "7:30") . ">7:30</option>
                          <option value=\"8:00\" " . check_select($data['dia']['max'], "8:00") . ">8:00</option>
                          <option value=\"8:30\" " . check_select($data['dia']['max'], "8:30") . ">8:30</option>
                          <option value=\"9:00\" " . check_select($data['dia']['max'], "9:00") . ">9:00</option>
                          <option value=\"9:30\" " . check_select($data['dia']['max'], "9:30") . ">9:30</option>
                          <option value=\"10:00\" " . check_select($data['dia']['max'], "10:00") . ">10:00</option>
                          <option value=\"10:30\" " . check_select($data['dia']['max'], "10:30") . ">10:30</option>
                          <option value=\"11:00\" " . check_select($data['dia']['max'], "11:00") . ">11:00</option>
                          <option value=\"11:30\" " . check_select($data['dia']['max'], "11:30") . ">11:30</option>
                          <option value=\"12:00\" " . check_select($data['dia']['max'], "12:00") . ">12:00</option>
                          <option value=\"12:30\" " . check_select($data['dia']['max'], "12:30") . ">12:30</option>
                          <option value=\"13:00\" " . check_select($data['dia']['max'], "13:00") . ">13:00</option>
                      </select>
                  </span>
                  <hr>
                  <span class=\"day_sub_label\">
                      <p class=\"sub_label_text\">Tarde</p>
                      <span class=\"check_sub_label " . check_sub_label($data['tarde']['activo']) . "\">" . check_sub_label_text($data['dia']['activo']) . "</span>
                      <input type=\"hidden\" class=\"check_tarde\" value=\"" . check_sub_label_val($data['tarde']['activo']) . "\">
                  </span>
                  <span class=\"selects_day_wrap\">
                      <select name=\"" . $lista_dias[$data['day_week']] . "_tarde_min\" id=\"" . $lista_dias[$data['day_week']] . "_tarde_min\" class=\"select_tarde\" " . check_select_disabled($data['tarde']['activo']) . ">
                          <option value=\"\"></option>
                          <option value=\"13:00\" " . check_select($data['tarde']['min'], "13:00") . ">13:00</option>
                          <option value=\"13:30\" " . check_select($data['tarde']['min'], "13:30") . ">13:30</option>
                          <option value=\"14:00\" " . check_select($data['tarde']['min'], "14:00") . ">14:00</option>
                          <option value=\"14:30\" " . check_select($data['tarde']['min'], "14:30") . ">14:30</option>
                          <option value=\"15:00\" " . check_select($data['tarde']['min'], "15:00") . ">15:00</option>
                          <option value=\"15:30\" " . check_select($data['tarde']['min'], "15:30") . ">15:30</option>
                          <option value=\"16:00\" " . check_select($data['tarde']['min'], "16:00") . ">16:00</option>
                          <option value=\"16:30\" " . check_select($data['tarde']['min'], "16:30") . ">16:30</option>
                          <option value=\"17:00\" " . check_select($data['tarde']['min'], "17:00") . ">17:00</option>
                          <option value=\"17:30\" " . check_select($data['tarde']['min'], "17:30") . ">17:30</option>
                          <option value=\"18:00\" " . check_select($data['tarde']['min'], "18:00") . ">18:00</option>
                          <option value=\"18:30\" " . check_select($data['tarde']['min'], "18:30") . ">18:30</option>
                          <option value=\"19:00\" " . check_select($data['tarde']['min'], "19:00") . ">19:00</option>
                      </select>
                      <p class=\"select_day_spacer\">a</p>
                      <select name=\"" . $lista_dias[$data['day_week']] . "_tarde_max\" id=\"" . $lista_dias[$data['day_week']] . "_tarde_max\" class=\"select_tarde\" " . check_select_disabled($data['tarde']['activo']) . ">
                          <option value=\"\"></option>
                          <option value=\"13:00\" " . check_select($data['tarde']['max'], "13:00") . ">13:00</option>
                          <option value=\"13:30\" " . check_select($data['tarde']['max'], "13:30") . ">13:30</option>
                          <option value=\"14:00\" " . check_select($data['tarde']['max'], "14:00") . ">14:00</option>
                          <option value=\"14:30\" " . check_select($data['tarde']['max'], "14:30") . ">14:30</option>
                          <option value=\"15:00\" " . check_select($data['tarde']['max'], "15:00") . ">15:00</option>
                          <option value=\"15:30\" " . check_select($data['tarde']['max'], "15:30") . ">15:30</option>
                          <option value=\"16:00\" " . check_select($data['tarde']['max'], "16:00") . ">16:00</option>
                          <option value=\"16:30\" " . check_select($data['tarde']['max'], "16:30") . ">16:30</option>
                          <option value=\"17:00\" " . check_select($data['tarde']['max'], "17:00") . ">17:00</option>
                          <option value=\"17:30\" " . check_select($data['tarde']['max'], "17:30") . ">17:30</option>
                          <option value=\"18:00\" " . check_select($data['tarde']['max'], "18:00") . ">18:00</option>
                          <option value=\"18:30\" " . check_select($data['tarde']['max'], "18:30") . ">18:30</option>
                          <option value=\"19:00\" " . check_select($data['tarde']['max'], "19:00") . ">19:00</option>
                      </select>
                  </span>
              </div>
          </div>
         ";
        };


        echo "
        </div>

        <span class=\"guardar_btn\">Guardar Horario</span>";



      }else {


          
        echo"
          <h2 class=\"titulo_tabla\">Horarios de Apertura</h2>
          <hr class=\"linea_naranja\">

          <div class=\"week_wrap\">
        ";

        foreach ($lista_dias as $dia) {
         echo"
            <div class=\"day_wrap\" name=\"" . $dia . "\">
              <h2 class=\"day_label\">" . ucfirst($dia) . "</h2>
              <div class=\"inputs_day_wrap\">
                  <span class=\"day_sub_label\">
                      <p class=\"sub_label_text\">Día</p>
                      <span class=\"check_sub_label\">Activo</span>
                      <input type=\"hidden\" class=\"check_dia\" value=\"1\">
                  </span>
                  <span class=\"selects_day_wrap\">
                      <select name=\"" . $dia . "_dia_min\" id=\"" . $dia . "_dia_min\" class=\"select_day\">
                          <option value=\"\"></option>
                          <option value=\"7:00\">7:00</option>
                          <option value=\"7:30\">7:30</option>
                          <option value=\"8:00\">8:00</option>
                          <option value=\"8:30\">8:30</option>
                          <option value=\"9:00\">9:00</option>
                          <option value=\"9:30\">9:30</option>
                          <option value=\"10:00\">10:00</option>
                          <option value=\"10:30\">10:30</option>
                          <option value=\"11:00\">11:00</option>
                          <option value=\"11:30\">11:30</option>
                          <option value=\"12:00\">12:00</option>
                          <option value=\"12:30\">12:30</option>
                          <option value=\"13:00\">13:00</option>
                      </select>
                      <p class=\"select_day_spacer\">a</p>
                      <select name=\"" . $dia . "_dia_max\" id=\"" . $dia . "_dia_max\" class=\"select_day\">
                      <option value=\"\"></option>
                          <option value=\"7:00\">7:00</option>
                          <option value=\"7:30\">7:30</option>
                          <option value=\"8:00\">8:00</option>
                          <option value=\"8:30\">8:30</option>
                          <option value=\"9:00\">9:00</option>
                          <option value=\"9:30\">9:30</option>
                          <option value=\"10:00\">10:00</option>
                          <option value=\"10:30\">10:30</option>
                          <option value=\"11:00\">11:00</option>
                          <option value=\"11:30\">11:30</option>
                          <option value=\"12:00\">12:00</option>
                          <option value=\"12:30\">12:30</option>
                          <option value=\"13:00\">13:00</option>
                      </select>
                  </span>
                  <hr>
                  <span class=\"day_sub_label\">
                      <p class=\"sub_label_text\">Tarde</p>
                      <span class=\"check_sub_label\">Activo</span>
                      <input type=\"hidden\" class=\"check_tarde\" value=\"1\">
                  </span>
                  <span class=\"selects_day_wrap\">
                      <select name=\"" . $dia . "_tarde_min\" id=\"" . $dia . "_tarde_min\" class=\"select_tarde\">
                          <option value=\"\"></option>
                          <option value=\"13:00\">13:00</option>
                          <option value=\"13:30\">13:30</option>
                          <option value=\"14:00\">14:00</option>
                          <option value=\"14:30\">14:30</option>
                          <option value=\"15:00\">15:00</option>
                          <option value=\"15:30\">15:30</option>
                          <option value=\"16:00\">16:00</option>
                          <option value=\"16:30\">16:30</option>
                          <option value=\"17:00\">17:00</option>
                          <option value=\"17:30\">17:30</option>
                          <option value=\"18:00\">18:00</option>
                          <option value=\"18:30\">18:30</option>
                          <option value=\"19:00\">19:00</option>
                      </select>
                      <p class=\"select_day_spacer\">a</p>
                      <select name=\"" . $dia . "_tarde_max\" id=\"" . $dia . "_tarde_max\" class=\"select_tarde\">
                          <option value=\"\"></option>
                          <option value=\"13:00\">13:00</option>
                          <option value=\"13:30\">13:30</option>
                          <option value=\"14:00\">14:00</option>
                          <option value=\"14:30\">14:30</option>
                          <option value=\"15:00\">15:00</option>
                          <option value=\"15:30\">15:30</option>
                          <option value=\"16:00\">16:00</option>
                          <option value=\"16:30\">16:30</option>
                          <option value=\"17:00\">17:00</option>
                          <option value=\"17:30\">17:30</option>
                          <option value=\"18:00\">18:00</option>
                          <option value=\"18:30\">18:30</option>
                          <option value=\"19:00\">19:00</option>
                      </select>
                  </span>
              </div>
          </div>
         ";
        };


        echo "
        </div>

        <span class=\"guardar_btn\">Guardar Horario</span>";

      };


      echo"
        <h2 class=\"titulo_tabla\">Configurar Excepciones</h2>
        <hr class=\"linea_naranja\">

        <div class=\"excepciones_config\">

            <span class=\"config_wrap\">

                <span class=\"input_config\">
                    <label for=\"config_excepcion_select\">Tipo de Excepción:</label>
                    <select name=\"config_excepcion_select\" id=\"config_excepcion_select\" class=\"config_excepcion_select\">
                        <option value=\"\"></option>
                        <option value=\"feriado\">Feriado</option>
                        <option value=\"jornada_off\">Jornada Entera OFF</option>
                        <option value=\"dia_off\">Dia OFF</option>
                        <option value=\"tarde_off\">Tarde OFF</option>
                        <option value=\"recorte\">Recorte Horario</option>
                    </select>
                </span>    
                
                <span class=\"input_config\">
                    <label for=\"config_excepcion_select\">Fecha:</label>
                    <input type=\"text\" name=\"date_picker_config\" class=\"date_picker_config\" value=\"\">
                </span>
              
                <span class=\"input_config\">
                    <label for=\"config_excepcion_select\">Descripción / Aviso:</label>
                    <input type=\"text\" name=\"descripcion_config\" class=\"descripcion_config\" value=\"\" placeholder=\"Feriados/Jornadas-> MAX 2 palabras  \">
                </span>

            </span>

            <span class=\"btn_agregar\">AGREGAR</span>

        </div>
      ";


      if (file_exists($json_excepciones_path)) {

        $json_horario_excepciones = file_get_contents($json_excepciones_path);
        $data_excepciones = json_decode($json_horario_excepciones, true);

        echo"
            <h2 class=\"titulo_tabla\">Lista de Excepciones</h2>
            <hr class=\"linea_naranja\">

            <div class=\"excepciones_list\">
            <p class=\"excepcion_tipo_label\">Excepciones en Curso</p>
            <div class=\"excepciones_curso_wrap\">
        ";

        foreach ($data_excepciones['otros'] as $fecha => $data) {
            echo"
                <span class=\"excepcion_row\">
                    <span class=\"excepcion_text\">
                        <i class=\"fas fa-circle\"></i>
                        <p class=\"fecha_text\" value=\"" . $fecha . "\">" . $fecha . "</p>
                        <p>-</p>
                        <p class=\"tipo_text\">" . $data['tipo'] . "</p>
                        <p>-</p>
                        <p class=\"descripcion_text\">" . $data['descripcion'] . "</p>
                    </span>
                    <span class=\"borrar_excepcion_wrap\">
                        <span class=\"trash_excepcion\"><i class=\"fas fa-trash-alt\"></i></span>
                        <span class=\"confirmar_borrar_excepcion\">BORRAR</span>
                    </span>
                </span>
            ";
        };

        echo"
            </div>
            <p class=\"excepcion_tipo_label\">Feriados</p>
            <div class=\"excepciones_feriados_wrap\">
        ";

        foreach ($data_excepciones['feriados'] as $fecha => $data) {
            echo"
                <span class=\"excepcion_row\">
                    <span class=\"excepcion_text\">
                        <i class=\"fas fa-circle\"></i>
                        <p class=\"fecha_text\" value=\"" . $fecha . "\">" . substr($fecha, 0, 5) . "</p>
                        <p>-</p>
                        <p class=\"descripcion_text\">" . $data['descripcion'] . "</p>
                    </span>
                    <span class=\"borrar_excepcion_wrap\">
                        <span class=\"trash_excepcion\"><i class=\"fas fa-trash-alt\"></i></span>
                        <span class=\"confirmar_borrar_excepcion\">BORRAR</span>
                    </span>
                </span>
            ";
        };

        echo"
            </div>
           </div> 
        ";

      }else {
        echo"
            <h2 class=\"titulo_tabla\">Lista de Excepciones</h2>
            <hr class=\"linea_naranja\">

            <div class=\"excepciones_list\">

            <p class=\"excepcion_tipo_label\">Excepciones en Curso</p>
            <div class=\"excepciones_curso_wrap\">

            </div>
            <p class=\"excepcion_tipo_label\">Feriados</p>
            <div class=\"excepciones_feriados_wrap\">
                    
            </div>
 
          </div>
        ";
      };
    };


};

?>
