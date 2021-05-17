<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_SESSION['usuario'])) {//si una SESSION no ha sido definida redirigir a login.php
  header('Location: ../login.php');
};
if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
  header('Location: ../cerrar_session.php');
};

if(isset($_POST["referencia_sent"]) || isset($_POST['action_sent'])){

  function get_tabla($referencia) {
    $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
    if(isset($referencia[5])) {
      if (isset($dict[$referencia[5]])){
        return $dict[$referencia[5]];
      };
    };
    return '';
    
  };
  
  $referencia = filter_var($_POST['referencia_sent'], FILTER_SANITIZE_STRING);
  $tabla = get_tabla($referencia);
  if ($tabla == '') {
    echo"error";
  }else{
      
    $action_sent = $_POST['action_sent'];
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
    
    

    $consulta_datos_pais =	$conexion_internacional->prepare("SELECT moneda, moneda_code, time_zone_php FROM paises WHERE pais = :pais");
    $consulta_datos_pais->execute([':pais' => $_COOKIE['tutechopais']]);
    $datos_pais	=	$consulta_datos_pais->fetch(PDO::FETCH_ASSOC);
  
    $moneda = $datos_pais['moneda'] . $datos_pais['moneda_code'];
    
    date_default_timezone_set($datos_pais['time_zone_php']);
  
  
    $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
    $consulta_agente_id->execute([':usuario' => $_SESSION['usuario']]);
    $agente_info	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);
  
    $agente_id = $agente_info['id'];
  
    $consulta_inmueble =	$conexion->prepare("SELECT referencia, precio FROM $tabla WHERE referencia = :referencia");
    $consulta_inmueble->execute([':referencia' => $referencia]);
    $inmueble	= $consulta_inmueble->fetch(PDO::FETCH_ASSOC);
  
  
    if (empty($inmueble)) {
        echo"error";
    }else{
  
      $propuestas_json_path = '../../bienes_inmuebles/' . $_COOKIE['tutechopais'] . '/' . $referencia . '/propuestas.json';
  
      if (!file_exists($propuestas_json_path)) {
          $json_constructor = array();
          $json_data = json_encode($json_constructor);
          file_put_contents($propuestas_json_path, $json_data);
      };
  
      $propuestas_json = json_decode(file_get_contents($propuestas_json_path), true);
  
      function show_propuestas($propuestas_json, $moneda, $agente_id){
        if (empty($propuestas_json)) {
          echo"
          <h2 style=\"margin: 1em; color: #585858;\">Ningúna propuesta registrada</h2>
          ";
        }else{
          foreach ($propuestas_json as $id => $propuesta) {
            echo"
              <div class=\"linea_propuesta_wrap\" key=\"" . $id . "\">
                <span class=\"col fecha\">" . $propuesta['fecha'] . "</span>
                <span class=\"col propuesta\">" . $propuesta['monto'] . " " . $moneda . "</span>
                <span class=\"col actions\">";
    
              if($propuesta['agente'] == $agente_id){
                echo"
                    <span class=\"propuesta_action editar\"><i class=\"fa fa-edit\"></i></span>
                    <span class=\"propuesta_action borrar\"><i class=\"fa fa-trash-alt\"></i></span>
                    ";
              };
                echo"
                </span>
              </div>
            ";
          };
        };
       
      };
      
      if ($action_sent == 'first_entry') {
  
        show_propuestas($propuestas_json, $moneda, $agente_id);
      
      }elseif ($action_sent == 'nueva_propuesta') {
  
        $contactos_json_path = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/contactos_personales.json';
        if (!file_exists($contactos_json_path)) {
            $json_constructor = array();
            $json_data = json_encode($json_constructor);
            file_put_contents($contactos_json_path, $json_data);
        };
        $contactos_json = json_decode(file_get_contents($contactos_json_path), true);
  
        echo"
          <h2>Agregar Propuesta</h2>
          <div class=\"form-group\">
            <i class=\"icono izquierda fa fa-hashtag\"></i>
            <input type=\"text\" name=\"referencia_form\" class=\"referencia_form\" value=\"" . $referencia  . "\" readonly>
          </div>
  
          <div class=\"form-group\">
            <i class=\"icono izquierda fa fa-user\"></i>
            <select class=\"lista_contactos_form\">
              <option value=\"\">Seleccione un Cliente</option>";
  
            foreach ($contactos_json as $contacto) {
              echo"
                  <option value=\"" . $contacto['nombre'] . "\" telefono=\"" . $contacto['telefono'] . "\">" . $contacto['nombre'] . " " . $contacto['telefono'] . "</option>
              ";
            };
  
          echo"
            </select>
          </div>
  
          <div style=\"display : flex\">
              <div class=\"form-group\">
                  <i class=\"icono izquierda\" style=\"font-size: 1.2em\">$</i>
                  <input type=\"text\" name=\"propuesta_monto_form\" class=\"propuesta_monto_form\" placeholder=\"Monto Propuesta\" value=\"\">
              </div>
  
              <span class=\"precio_actual_form\"><p>Precio Actual:</p><p>" . $inmueble['precio'] . " " . $moneda . "</p></span>
          </div>
  
          <div class=\"form-group\">
              <textarea name=\"propuesta_comentario\" id=\"propuesta_comentario\" rows=\"1\" class=\"propuesta_comentario\" oninput=\"auto_grow(this)\" placeholder=\"Comentarios - OPCIONAL\"></textarea>
          </div>
  
          <span class=\"error_message_form\"
              <i class=\"fas fa-exclamation-circle\"></i>
              <p></p>
          </span>

          <input class=\"modo\" type=\"hidden\" value=\"guardar_propuesta\">
          <input class=\"propuesta_id\" type=\"hidden\" value=\"\">
        
        ";
  
        
      }elseif ($action_sent == 'editar_propuesta') {
        if(isset($_POST["referencia_sent"]) || isset($_POST['propuesta_id_sent'])){
          
          $contactos_json_path = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/contactos_personales.json';
          if (!file_exists($contactos_json_path)) {
              $json_constructor = array();
              $json_data = json_encode($json_constructor);
              file_put_contents($contactos_json_path, $json_data);
          };
          $contactos_json = json_decode(file_get_contents($contactos_json_path), true);

          function select_contacto($contacto, $compare){
            if ($contacto == $compare) {
              return 'selected';
            };
            return '';
          };

          $propuesta = $propuestas_json[$_POST["propuesta_id_sent"]];
    
          echo"
            <h2>Editar Propuesta</h2>
            <div class=\"form-group\">
              <i class=\"icono izquierda fa fa-hashtag\"></i>
              <input type=\"text\" name=\"referencia_form\" class=\"referencia_form\" value=\"" . $_POST["referencia_sent"]  . "\" readonly>
            </div>
    
            <div class=\"form-group\">
              <i class=\"icono izquierda fa fa-user\"></i>
              <select class=\"lista_contactos_form\">
                <option value=\"\">Seleccione un Cliente</option>";
    
              foreach ($contactos_json as $contacto) {
                echo"
                    <option value=\"" . $contacto['nombre'] . "\" telefono=\"" . $contacto['telefono'] . "\" " . select_contacto($contacto['nombre'], $propuesta['contacto']) . ">" . $contacto['nombre'] . " " . $contacto['telefono'] . "</option>
                ";
              };
    
            echo"
              </select>
            </div>
    
            <div style=\"display : flex\">
                <div class=\"form-group\">
                    <i class=\"icono izquierda\" style=\"font-size: 1.2em\">$</i>
                    <input type=\"text\" name=\"propuesta_monto_form\" class=\"propuesta_monto_form\" placeholder=\"Monto Propuesta\" value=\"" . $propuesta['monto']  . "\">
                </div>
    
                <span class=\"precio_actual_form\"><p>Precio Actual:</p><p>" . $inmueble['precio'] . " " . $moneda . "</p></span>
            </div>
    
            <div class=\"form-group\">
                <textarea name=\"propuesta_comentario\" id=\"propuesta_comentario\" rows=\"1\" class=\"propuesta_comentario\" oninput=\"auto_grow(this)\" placeholder=\"Comentarios - OPCIONAL\">" . $propuesta['comentario'] . "</textarea>
            </div>
    
            <span class=\"error_message_form\"
                <i class=\"fas fa-exclamation-circle\"></i>
                <p></p>
            </span>

            <input class=\"modo\" type=\"hidden\" value=\"guardar_edicion\">
            <input class=\"propuesta_id\" type=\"hidden\" value=\"" . $_POST['propuesta_id_sent'] . "\">
          
          ";

        };
      }elseif ($action_sent == 'guardar_propuesta') {
        if(isset($_POST["cliente"]) || isset($_POST['monto']) || isset($_POST['comentario']) || isset($_POST['telefono'])){
            
          function generateRandomString($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
          };
          
          $random_id = generateRandomString(6);

          while (isset($propuestas_json[$random_id])) {
            $random_id = generateRandomString(6);
          };

          $current_date = date("d-m-Y");

          $nueva_propuesta = [
            "fecha" => $current_date,
            "agente" =>  $agente_id,
            "monto" => $_POST['monto'],
            "contacto" => $_POST["cliente"],
            "telefono" => $_POST['telefono'],
            "comentario" => $_POST['comentario']
          ];

          $propuestas_json[$random_id] = $nueva_propuesta;

          $json_final_data = json_encode($propuestas_json);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
          file_put_contents($propuestas_json_path, $json_final_data);
        
          show_propuestas($propuestas_json, $moneda, $agente_id);

        };

      }elseif ($action_sent == 'guardar_edicion') {
        if(isset($_POST["propuesta_id_sent"]) || isset($_POST["cliente"]) || isset($_POST['monto']) || isset($_POST['comentario']) || isset($_POST['telefono'])){

          $propuesta_original = $propuestas_json[$_POST["propuesta_id_sent"]];

          $propuesta_editada = [
            "fecha" => $propuesta_original['fecha'],
            "agente" =>  $agente_id,
            "monto" => $_POST['monto'],
            "contacto" => $_POST["cliente"],
            "telefono" => $_POST['telefono'],
            "comentario" => $_POST['comentario']
          ];

          $propuestas_json[$_POST["propuesta_id_sent"]] = $propuesta_editada;

          $json_final_data = json_encode($propuestas_json);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
          file_put_contents($propuestas_json_path, $json_final_data);
        
          show_propuestas($propuestas_json, $moneda, $agente_id);

        };
      }elseif ($action_sent == 'borrar_propuesta') {
        if(isset($_POST["referencia_sent"]) || isset($_POST['propuesta_id_sent'])){

          unset($propuestas_json[$_POST["propuesta_id_sent"]]);

          $json_final_data = json_encode($propuestas_json);//SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
          file_put_contents($propuestas_json_path, $json_final_data);

          show_propuestas($propuestas_json, $moneda, $agente_id);

        };
      };
      
    };
    
  
  };
  

} else {
  echo"error";
};

      

?>
