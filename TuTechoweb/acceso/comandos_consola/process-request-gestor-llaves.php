<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

function get_tabla($referencia) {
  $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
  return $dict[$referencia[5]];
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_agente =	$conexion->prepare("SELECT id, agencia_id, nombre, apellido FROM agentes WHERE usuario = :usuario");
  $consulta_agente->execute([":usuario" => $_SESSION['usuario']]);
  $agente	=	$consulta_agente->fetch(PDO::FETCH_ASSOC);


  if (isset($_POST["referencia_retirar_sent"])) {
    $referencia = $_POST["referencia_retirar_sent"];

    $tabla = get_tabla($referencia);


    $consulta_retirar_llave =	$conexion->prepare("UPDATE $tabla SET llave_holder = :llave_holder WHERE referencia = :referencia");
    $consulta_retirar_llave->execute([
        ':llave_holder' => $agente['id'],
        ':referencia' => $referencia
    ]);

    echo"exito";
        

  }elseif (isset($_POST["agencia_llaves_sent"])) {
    $agencia_id = $_POST["agencia_llaves_sent"];

    $bienes = array();

    $consulta_casa =	$conexion->prepare("SELECT referencia, estado, llave_holder, llave_last_holder FROM casa WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1");
    $consulta_casa->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
    $casa = $consulta_casa->fetchAll();

    $consulta_departamento =	$conexion->prepare("SELECT referencia, estado, llave_holder, llave_last_holder FROM departamento WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1");
    $consulta_departamento->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
    $departamento = $consulta_departamento->fetchAll();

    $consulta_local =	$conexion->prepare("SELECT referencia, estado, llave_holder, llave_last_holder FROM local WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1");
    $consulta_local->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
    $local = $consulta_local->fetchAll();

    $consulta_terreno =	$conexion->prepare("SELECT referencia, estado, llave_holder, llave_last_holder FROM terreno WHERE validacion_agente = 1 AND agencia_registro_id = :agencia_registro_id AND llave = 1");
    $consulta_terreno->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL BARRIO O POBLADO
    $terreno = $consulta_terreno->fetchAll();

    $bienes = array_merge($casa, $departamento, $local, $terreno);

    echo json_encode($bienes);
    
  }elseif (isset($_POST['action_requested'])) {

    $action_requested = $_POST['action_requested'];
    
    if ($action_requested == 'lista_llaves_agente') {

      $bienes = array();

      $consulta_casa =	$conexion->prepare("SELECT referencia FROM casa WHERE validacion_agente = 1 AND llave = 1 AND llave_holder = :llave_holder");
      $consulta_casa->execute([':llave_holder' => $agente['id']]);//SE PASA EL BARRIO O POBLADO
      $casa = $consulta_casa->fetchAll();

      $consulta_departamento =	$conexion->prepare("SELECT referencia FROM departamento WHERE validacion_agente = 1 AND llave = 1 AND llave_holder = :llave_holder");
      $consulta_departamento->execute([':llave_holder' => $agente['id']]);//SE PASA EL BARRIO O POBLADO
      $departamento = $consulta_departamento->fetchAll();

      $consulta_local =	$conexion->prepare("SELECT referencia FROM local WHERE validacion_agente = 1 AND llave = 1 AND llave_holder = :llave_holder");
      $consulta_local->execute([':llave_holder' => $agente['id']]);//SE PASA EL BARRIO O POBLADO
      $local = $consulta_local->fetchAll();

      $consulta_terreno =	$conexion->prepare("SELECT referencia FROM terreno WHERE validacion_agente = 1 AND llave = 1 AND llave_holder = :llave_holder");
      $consulta_terreno->execute([':llave_holder' => $agente['id']]);//SE PASA EL BARRIO O POBLADO
      $terreno = $consulta_terreno->fetchAll();

      $bienes = array_merge($casa, $departamento, $local, $terreno);

      echo json_encode($bienes);

    }elseif (condition) {
      # code...
    };
    
  }elseif (isset($_POST['referencia_retorno_sent'])) {
    $referencia = $_POST['referencia_retorno_sent'];

    $tabla = get_tabla($referencia);

    $statement_json = $conexion->prepare(
      "UPDATE $tabla SET llave_holder = '', llave_last_holder = :llave_last_holder WHERE referencia = :referencia");

    $statement_json->execute(array(
        ':llave_last_holder' => $agente['id'],
        ':referencia' => $referencia
    ));

    echo "exito";


  }elseif (isset($_POST['check_key_sent'])) {
    $referencia = $_POST['check_key_sent'];
    $tabla = get_tabla($referencia);

    $consulta_llave =	$conexion->prepare("SELECT llave_holder FROM $tabla WHERE referencia = :referencia");
    $consulta_llave->execute([':referencia' => $referencia]);//SE PASA EL BARRIO O POBLADO
    $datos_llave = $consulta_llave->fetch();


    if ($datos_llave['llave_holder'] == $agente['id']){//mostrar herramienta de traspaso de llave

      $consulta_lista_agentes =	$conexion->prepare("SELECT id, nombre, apellido FROM agentes WHERE activo = 1 AND disponible = 1 AND nivel_acceso = 4 AND agencia_id = :agencia_id AND id != :id");
      $consulta_lista_agentes->execute([':agencia_id' => $agente['agencia_id'], ':id' => $agente['id']]);//SE PASA EL BARRIO O POBLADO
      $lista_agentes = $consulta_lista_agentes->fetchAll();

      
      
      echo"
        <h2>Transferir Llave(s) a otro Agente?</h2>
        <span class=\"agentes_lista_wrap\">
          <select id='agentes_lista' style='width: 200px;'>
            <option value=''>Selecciona Agente</option>";

            foreach ($lista_agentes as $contacto) {
                echo"<option value=\"" . $contacto['id'] . "\">" . $contacto['nombre'] . " " . $contacto['apellido'] . " (ID:" . $contacto['id'] . ")</option>";
            }; 

        echo"</select>
        </span>

        <span class=\"btn_tranferir\" referencia=\"$referencia\">Transferir Llave(s)</span>
      ";

    }else{//mostrar ficha contacto agente holder

      function getNumberFormat($numero) {
        preg_match_all('!\d+!', $numero, $matches);
        $string_telefono = implode($matches[0]);
        $digits = ltrim($string_telefono, '0');
        return $digits;
      };
      
      $consulta_agente_contacto =	$conexion->prepare("SELECT id, nombre, apellido, contacto FROM agentes WHERE id = :id");
      $consulta_agente_contacto->execute([":id" => $datos_llave['llave_holder']]);
      $agente_contacto	=	$consulta_agente_contacto->fetch(PDO::FETCH_ASSOC);

      $id = $agente_contacto['id'];
      $nombre_agente = $agente_contacto['nombre']  . ' ' . $agente_contacto['apellido'];
      $numero = $agente_contacto['contacto'];
      $stripped_numero = getNumberFormat($numero);
      
      echo"
        <div class=\"agente_wrap\" data=\"{$id}\">
          <img src=\"../../agentes/{$_COOKIE['tutechopais']}/{$id}/foto_blanco.jpg?t=1623618118\" alt=\"Foto\" class=\"foto_agente\">
          <span class=\"info_agente_wrap\">
            <p class=\"nombre_agente\">{$nombre_agente}</p>
            <span class=\"contacto_agente\">

              <p class=\"agente_telefono\">{$numero}</p>
            </span>

            <span class=\"btns_popup_wrap\">
              <a class=\"popup_contacto_call_btn\" href=\"tel:{$stripped_numero}\"><p>Llamar</p></a>
              <a href=\"https://api.whatsapp.com/send?phone={$stripped_numero}\" class=\"popup_contacto_whatsapp_btn activo\" target=\"_blank\">
                  <span class=\"fa-stack icon_stacks_whatsapp\">
                      <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                      <i class=\"fa fa-circle\"></i>
                  </span>
                  <p>WhatsApp</p>
                </a>
            </span>
            <span id=\"agregar_contacto\" class=\"estado_agente\" referencia=\"{$referencia}\">Agregar a mi visita</span>
          </span>
        </div>
      ";

    };
    
  }elseif (isset($_POST['transfer_key_sent']) && isset($_POST['agente_transferir_sent'])) {

    function generateRandomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    };

    $referencia = $_POST['transfer_key_sent'];
    $agente_transferir = $_POST['agente_transferir_sent'];

    $emisor_dato = $agente['nombre'] . ' ' . $agente['apellido'] . '(ID: ' . $agente['id'] . ')'; 

    $consulta_check_pendientes =	$conexion->prepare("SELECT agente_id FROM pendientes WHERE tipo = :tipo AND key_feature3 = :key_feature3 AND key_feature1 = :key_feature1");
    $consulta_check_pendientes->execute([":tipo" => 'transferencia_llave', ":key_feature3" => $referencia, ":key_feature1" => $emisor_dato]);
    $check_pendientes	=	$consulta_check_pendientes->fetch(PDO::FETCH_COLUMN, 0);
    
    if ($check_pendientes == '') {
      $solicitud_id = generateRandomString(10);
      $mensaje = 'Se le solicita aceptar la transferencia de la(s) llave(s) del inmueble de Referencia:' . $referencia;
      $current_date = date("Y/m/d");
      $datos_utiles = array('referencia' => $referencia, 'llave_holder' => $agente_transferir, 'llave_last_holder' => $agente['id']);

      $datos_to_send = json_encode($datos_utiles);

      $statement_solicitud = $conexion->prepare(
        "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :key_feature2, :key_feature3)"
      );

      $statement_solicitud->execute(array(
        ':codigo' => $solicitud_id,
        ':agente_id' => $agente_transferir,
        ':mensaje' => $mensaje,
        ':fecha_creacion' => $current_date,
        ':tipo' => 'transferencia_llave',
        ':key_feature1' => $emisor_dato,
        ':key_feature2' => $datos_to_send,
        ':key_feature3' => $referencia
      ));

      echo "exito";
    }else {
      echo "pendiente";
    };

  }elseif ( isset($_POST['key_holder_id_sent']) && isset($_POST['index_visita_sent']) && isset($_POST['agencia_tag_visita_sent']) && isset($_POST['referencia_visita_sent']) ) {

    $key_agente_id = $_POST['key_holder_id_sent'];
    $agencia_tag = $_POST['agencia_tag_visita_sent'];
    $referencia = $_POST['referencia_visita_sent'];
    $index_visita = $_POST['index_visita_sent'];
    $agente_id = $agente['id'];

    $consulta_agente_contacto =	$conexion->prepare("SELECT nombre, apellido, contacto FROM agentes WHERE id = :id");
    $consulta_agente_contacto->execute([":id" => $key_agente_id]);
    $agente_contacto	=	$consulta_agente_contacto->fetch(PDO::FETCH_ASSOC);

    $nombre_agente = $agente_contacto['nombre']  . ' ' . $agente_contacto['apellido'];
    $src = "../../agentes/{$_COOKIE['tutechopais']}/{$key_agente_id}/foto_blanco.jpg";
    $numero = $agente_contacto['contacto'];


    $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
    $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
    $data_agentes_tareas = json_decode($json_agentes_tareas, true);


    $contacto_selected = array();
    $contacto_selected['nombre'] = $nombre_agente;
    $contacto_selected['src'] = $src;
    $contacto_selected['telefono'] = $numero;
    $contacto_selected['info'] = 'Llave';


    if (!isset($data_agentes_tareas[$agente_id]['visita'][$index_visita]['contactos_extra'])) {
      $data_agentes_tareas[$agente_id]['visita'][$index_visita]['contactos_extra'] = array();
    };

    array_push($data_agentes_tareas[$agente_id]['visita'][$index_visita]['contactos_extra'], $contacto_selected);// se incorpora el array del nuevo contacto a la lista de contactos

    $data_json = json_encode($data_agentes_tareas);
    file_put_contents($json_path_agentes_tareas, $data_json);

    echo"exito";

  };




};

?>
