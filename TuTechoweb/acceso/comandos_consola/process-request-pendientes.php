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

    $nivel_acceso = $_SESSION['nivel_acceso'];

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    function filtro($var, $id){
      $array_id = explode('-', $var['agente_id']);
      if (in_array($id, $array_id)) {
        return true;
      };
    };

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

  if (isset($_POST['codigo_sent']) && isset($_POST['pais_sent'])) {
    $codigo = $_POST['codigo_sent'];
    $pais = $_POST['pais_sent'];
    $tutechodb_especifico = "tutechodb_" . strtolower($pais);

    try {
      $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $statement = $conexion_especifica->prepare(
     "UPDATE pendientes SET visto = 1 WHERE codigo = :codigo"
    );
    $statement->execute(array(
      ':codigo' => $codigo
    ));

  };


  if (isset($_POST['codigo_borrar_sent']) && isset($_POST['pais_sent'])) {
    $codigo_borrar = $_POST['codigo_borrar_sent'];
    $pais = $_POST['pais_sent'];
    $tutechodb_especifico = "tutechodb_" . strtolower($pais);

    try {
      $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $statement = $conexion_especifica->prepare(
     "UPDATE pendientes SET borrado = 1 WHERE codigo = :codigo"
    );
    $statement->execute(array(
      ':codigo' => $codigo_borrar
    ));
  };

  if (isset($_POST['codigo_rehacer_sent']) && isset($_POST['pais_sent'])) {
    $codigo_rehacer = $_POST['codigo_rehacer_sent'];
    $pais = $_POST['pais_sent'];
    $tutechodb_especifico = "tutechodb_" . strtolower($pais);

    try {
      $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $statement = $conexion_especifica->prepare(
     "UPDATE pendientes SET borrado = 0 WHERE codigo = :codigo"
    );
    $statement->execute(array(
      ':codigo' => $codigo_rehacer
    ));
  };

  // CODIGO PARA AGREGAR EL CHECK-LIST COMPARTIDO POR OTRO USUARIO

  if (isset($_POST['codigo_check_list_sent'])) {
    
    $codigo_sent = $_POST['codigo_check_list_sent'];

    $consulta_check_list_info =	$conexion->prepare("SELECT key_feature2 FROM pendientes WHERE codigo = :codigo ");
    $consulta_check_list_info->execute([":codigo" => $codigo_sent]);
    $check_list_info	=	$consulta_check_list_info->fetch(PDO::FETCH_ASSOC);

    if (!empty($check_list_info)) {
      
      $check_list_array = json_decode($check_list_info['key_feature2'], true);

      $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario ");
      $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
      $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

      $json_to_do_path = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/to_do_list.json';

      if (file_exists($json_to_do_path) == false) {
        $json_constructor = array();

        $json_data = json_encode($json_constructor);

        file_put_contents($json_to_do_path, $json_data);
        
      };

      $json_to_do_lists = file_get_contents($json_to_do_path);
      $data_to_do_list = json_decode($json_to_do_lists, true);

      
      array_push($data_to_do_list, $check_list_array);// se incorpora el array del nuevo contacto a la lista de contactos

      $data_json = json_encode($data_to_do_list);// transformar el array en codigo json

      file_put_contents($json_to_do_path, $data_json); // FINALMENTE se guarda el data en un Json file
        
      

      echo "Check-List Agregado Exitosamente";// haya o no le decimos que funcionó

    };

  };

  // CODIGO PARA AGREGAR EL CONTACTO RECIBIDO A TU LISTA DE CONTACTOS
  if(isset($_POST['codigo_contacto_sent'])){

    $codigo_sent = $_POST['codigo_contacto_sent'];

    $consulta_contacto_info =	$conexion->prepare("SELECT key_feature2 FROM pendientes WHERE codigo = :codigo ");
    $consulta_contacto_info->execute([":codigo" => $codigo_sent]);
    $contacto_info	=	$consulta_contacto_info->fetch(PDO::FETCH_ASSOC);

    if (!empty($contacto_info)) {
      
      $contacto_array = json_decode($contacto_info['key_feature2'], true);

      $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario ");
      $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
      $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

      $json_contactos_path = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/contactos_personales.json';

      if (file_exists($json_contactos_path) == false) {
        $json_constructor = array();

        $json_data = json_encode($json_constructor);

        file_put_contents($json_contactos_path, $json_data);
        
      };

      $json_contactos_personales = file_get_contents($json_contactos_path);
      $data_contactos_personales = json_decode($json_contactos_personales, true);
      
      $error = false;
      // Verificamos que el contacto no exista ya
      foreach ($data_contactos_personales as $key => $value) {
        if ($value['telefono'] == $contacto_array['telefono']) {
          $error = true;
        };
      };

      if ($error == false) {
        array_push($data_contactos_personales, $contacto_array);// se incorpora el array del nuevo contacto a la lista de contactos

        usort($data_contactos_personales,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
  
        $data_json = json_encode($data_contactos_personales);// transformar el array en codigo json
  
        file_put_contents($json_contactos_path, $data_json); // FINALMENTE se guarda el data en un Json file
        
      };

      echo "Contacto Agregado Exitosamente";// haya o no le decimos que funcionó

    };
    
  };

  //CODIGO PARA ACEPTAR SOLICITUD DE TRANSFERENCIA DE LLAVES

  if(isset($_POST['transferencia_llave_respuesta']) && isset($_POST['codigo_pendiente'])){

    $respuesta = $_POST['transferencia_llave_respuesta'];
    $codigo_pendiente =  $_POST['codigo_pendiente'];

    function generateRandomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    };

    $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario ");
    $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
    $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

    $consulta_datos_solicitud =	$conexion->prepare("SELECT key_feature2 FROM pendientes WHERE codigo = :codigo ");
    $consulta_datos_solicitud->execute([":codigo" => $codigo_pendiente]);
    $datos_solicitud_json	=	$consulta_datos_solicitud->fetch(PDO::FETCH_COLUMN, 0);

    $datos_solicitud = json_decode($datos_solicitud_json, true);
    $tabla = get_tabla($datos_solicitud['referencia']);

    

    if ($respuesta == 0) {//se negó la transferencia de llave(s)

      // Borramos el pendiente
      $borrar_agente =	$conexion->prepare("DELETE FROM pendientes WHERE codigo = :codigo");
      $borrar_agente->execute(array(':codigo' => $codigo_pendiente));

      $mensaje = "Se NEGÓ la transferencia de Llave(s) del inmueble Referencia: " . $datos_solicitud['referencia'];
    }elseif ($respuesta == 1) {//se aceptó la transferencia de llave(s)

      //se actualiza los datos del holder llaves
      $statement_json = $conexion->prepare(
        "UPDATE $tabla SET llave_holder = :llave_holder, llave_last_holder = :llave_last_holder WHERE referencia = :referencia");

      $statement_json->execute(array(
          ':llave_holder' => $datos_solicitud['llave_holder'],
          ':llave_last_holder' => $datos_solicitud['llave_last_holder'],
          ':referencia' => $datos_solicitud['referencia']
      ));

      // Borramos el pendiente
      $borrar_agente =	$conexion->prepare("DELETE FROM pendientes WHERE codigo = :codigo");
      $borrar_agente->execute(array(':codigo' => $codigo_pendiente));

      $mensaje = "Se ACEPTÓ la transferencia de Llave(s) del inmueble Referencia: " . $datos_solicitud['referencia'];
    };

    $current_date = date("Y/m/d");
    $codigo = generateRandomString(10);

    $statement_respuesta = $conexion->prepare(
      "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
    );

    $statement_respuesta->execute(array(
      ':codigo' => $codigo,
      ':agente_id' => $datos_solicitud['llave_last_holder'],
      ':mensaje' => $mensaje,
      ':fecha_creacion' => $current_date,
      ':tipo' => 'mensaje_interno',
      ':key_feature1' => $datos_solicitud['llave_holder']
    ));

    echo "Respuesta Enviada Exitosamente";

  };


  if (isset($_POST["tipo_pendiente_sent"]) && isset($_POST["agente_id_sent"])) {

    $agente_id = $_POST["agente_id_sent"];

    if ($_POST["tipo_pendiente_sent"] == 'nuevos') {

      if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {

        $tutechodb_internacional = "tutechodb_internacional";

        try {
          $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
        } catch (PDOException $e) { //en caso de error de conexion repostarlo
          echo "Error: " . $e->getMessage();
        };

        $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
        $consulta_paises->execute();
        $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);

        $pendientes_agente = [];
        $array_pendientes_grupo = [];

        foreach ($paises as $pais) {

          $tutechodb = "tutechodb_" . $pais;

          try {
            $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
          } catch (PDOException $e) { //en caso de error de conexion repostarlo
            echo "Error: " . $e->getMessage();
          };

          $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
          $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
          $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

          $consulta_pendientes_agente =	$conexion->prepare("SELECT codigo, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 0 AND agente_id = :agente_id AND borrado = 0 ORDER BY fecha_creacion DESC ");
          $consulta_pendientes_agente->execute([":agente_id" => $agente_id['id']]);
          $pendientes_agente_especial	=	$consulta_pendientes_agente->fetchAll(PDO::FETCH_ASSOC);

          foreach ($pendientes_agente_especial as $pendiente) {
            $pendientes_agente[] = $pendiente;
          };

          $consulta_pendientes_grupo =	$conexion->prepare("SELECT codigo, agente_id, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 1 ORDER BY fecha_creacion DESC ");
          $consulta_pendientes_grupo->execute();
          $pendientes_grupo_especial	=	$consulta_pendientes_grupo->fetchAll(PDO::FETCH_ASSOC);

          foreach ($pendientes_grupo_especial as $pendiente) {
            if (filtro($pendiente, $agente_id['id'])) {
              $array_pendientes_grupo[] = $pendiente;
            };
          };

        };

      }else {

        $consulta_pendientes_agente =	$conexion->prepare("SELECT codigo, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 0 AND agente_id = :agente_id AND borrado = 0 ORDER BY fecha_creacion DESC ");
        $consulta_pendientes_agente->execute([":agente_id" => $agente_id]);
        $pendientes_agente	=	$consulta_pendientes_agente->fetchAll(PDO::FETCH_ASSOC);

        $consulta_pendientes_grupo =	$conexion->prepare("SELECT codigo, agente_id, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 1 ORDER BY fecha_creacion DESC ");
        $consulta_pendientes_grupo->execute();
        $pendientes_grupo	=	$consulta_pendientes_grupo->fetchAll(PDO::FETCH_ASSOC);

        // function filtro($var, $id){
        //   $array_id = explode('-', $var['agente_id']);
        //   if (in_array($id, $array_id)) {
        //     return true;
        //   };
        // };

        $array_pendientes_grupo = [];

        foreach ($pendientes_grupo as $pendiente) {
          if (filtro($pendiente, $agente_id)) {
            $array_pendientes_grupo[] = $pendiente;
          };
        };

      };

      

      foreach ($pendientes_agente as $pendiente_agente) {
        echo"<div class=\"pendiente\" id=\"" . $pendiente_agente['codigo'] . "\">
          <div class=\"pendiente_wrapper";
          if ($pendiente_agente['visto'] == 0) {
            echo " no_leido";
          };
          echo"\">
            <span class=\"etiqueta";
            if ($pendiente_agente['tipo'] == 'reclamo') {
              echo " reclamo";
            };
            if ($pendiente_agente['tipo'] == 'anuncio') {
              echo " anuncio";
            };
            if ($pendiente_agente['tipo'] == 'autorizacion') {
              echo " autorizacion";
            };

            if ($pendiente_agente['tipo'] == 'agente_validado') {
              echo " agente_validado";
            };
            if ($pendiente_agente['tipo'] == 'reclamo_file') {
              echo " reclamo_file";
            };
            if ($pendiente_agente['tipo'] == 'inmueble_validado') {
              echo " inmueble_validado";
            };
            if ($pendiente_agente['tipo'] == 'contacto_compartido') {
              echo " contacto_compartido";
            };
            if ($pendiente_agente['tipo'] == 'check_list_compartido') {
              echo " check_list_compartido";
            };
            if ($pendiente_agente['tipo'] == 'mensaje_interno') {
              echo " mensaje_interno";
            };
            if ($pendiente_agente['tipo'] == 'transferencia_llave') {
              echo " transferencia_llave";
            };

        echo"\">
              " . ucfirst($pendiente_agente['tipo']) . "
            </span>
            <span class=\"tag_corner\">
            <span class=\"pais_tag\">" . ucfirst($pendiente_agente['pais']) . "</span>
            <span class=\"fecha_creacion\">" . "&nbsp/&nbsp" . $pendiente_agente['fecha_creacion'] . "</span>
            </span>
            <span class=\"pendiente_contenido\">";
           if ($pendiente_agente['tipo'] == 'reclamo' || $pendiente_agente['tipo'] == 'autorizacion' || $pendiente_agente['tipo'] == 'inmueble_validado'){
                echo "Referencia: " . $pendiente_agente['key_feature1'] . "</br>";
            }elseif ($pendiente_agente['tipo'] == 'reclamo_file') {
              echo "Tipo:&nbsp" . $pendiente_agente['key_feature2'] . "</br>File ID:&nbsp" . $pendiente_agente['key_feature1'] . "</br>";
            }elseif ($pendiente_agente['tipo'] == 'agente_validado') {
              echo "File ID:&nbsp" . $pendiente_agente['key_feature1'] . "</br>";
            }elseif ($pendiente_agente['tipo'] == 'contacto_compartido' || $pendiente_agente['tipo'] == 'mensaje_interno' || $pendiente_agente['tipo'] == 'check_list_compartido' || $pendiente_agente['tipo'] == 'transferencia_llave') {
              echo "Enviado por:&nbsp" . $pendiente_agente['key_feature1'] . "</br></br>";
            };
              echo nl2br($pendiente_agente['mensaje']);
          echo"</span>
          </div>
          <div class=\"pendiente_btn\">";
            if ($pendiente_agente['tipo'] == 'reclamo' || $pendiente_agente['tipo'] == 'reclamo_file'){
              echo"<i class=\"fas fa-question-circle\"></i>";
            };
            if ($pendiente_agente['tipo'] == 'anuncio' || $pendiente_agente['tipo'] == 'autorizacion' || $pendiente_agente['tipo'] == 'agente_validado' || $pendiente_agente['tipo'] == 'inmueble_validado' || $pendiente_agente['tipo'] == 'contacto_compartido' || $pendiente_agente['tipo'] == 'mensaje_interno' || $pendiente_agente['tipo'] == 'check_list_compartido' || $pendiente_agente['tipo'] == 'transferencia_llave'){
              echo"<i class=\"fas fa-times-circle\"></i>";
            };
          echo"</div>
        </div>";
      };



      foreach ($array_pendientes_grupo as $pendiente_grupo) {
        echo"<div class=\"pendiente\" id=\"" . $pendiente_grupo['codigo'] . "\">
          <div class=\"pendiente_wrapper\">
            <span class=\"etiqueta";
            if ($pendiente_grupo['tipo'] == 'anuncio') {
              echo " anuncio";
            };

        echo"\">
              " . ucfirst($pendiente_grupo['tipo']) . "
            </span>
            <span class=\"tag_corner\">
            <span class=\"pais_tag\">" . ucfirst($pendiente_grupo['pais']) . "</span>
            <span class=\"fecha_creacion\">" . "&nbsp/&nbsp" . $pendiente_grupo['fecha_creacion'] . "</span>
            </span>
            <span class=\"pendiente_contenido\">";
              echo nl2br($pendiente_grupo['mensaje']);
          echo"</span>
          </div>
          <div class=\"pendiente_btn\">
          <i class=\"fas fa-question-circle\"></i>
          </div>
        </div>";
      };

    };

    if ($_POST["tipo_pendiente_sent"] == 'borrados') {

      if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {

        $tutechodb_internacional = "tutechodb_internacional";

        try {
          $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
        } catch (PDOException $e) { //en caso de error de conexion repostarlo
          echo "Error: " . $e->getMessage();
        };

        $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
        $consulta_paises->execute();
        $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);

        $pendientes_agente = [];

        foreach ($paises as $pais) {

          $tutechodb = "tutechodb_" . $pais;

          try {
            $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
          } catch (PDOException $e) { //en caso de error de conexion repostarlo
            echo "Error: " . $e->getMessage();
          };

          $consulta_pendientes_agente =	$conexion->prepare("SELECT codigo, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 0 AND agente_id = :agente_id AND borrado = 1 ORDER BY fecha_creacion DESC");
          $consulta_pendientes_agente->execute([":agente_id" => $agente_id]);
          $pendientes_agente_especial	=	$consulta_pendientes_agente->fetchAll(PDO::FETCH_ASSOC);

          foreach ($pendientes_agente_especial as $pendiente) {
            $pendientes_agente[] = $pendiente;
          };

        };

      }else {
        $consulta_pendientes_agente =	$conexion->prepare("SELECT codigo, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 0 AND agente_id = :agente_id AND borrado = 1 ORDER BY fecha_creacion DESC");
        $consulta_pendientes_agente->execute([":agente_id" => $agente_id]);
        $pendientes_agente	=	$consulta_pendientes_agente->fetchAll(PDO::FETCH_ASSOC);
      };

      

      foreach ($pendientes_agente as $pendiente_agente) {
        echo"<div class=\"pendiente\" id=\"" . $pendiente_agente['codigo'] . "\">
          <div class=\"pendiente_wrapper\">
            <span class=\"etiqueta";
            if ($pendiente_agente['tipo'] == 'reclamo') {
              echo " reclamo";
            };
            if ($pendiente_agente['tipo'] == 'anuncio') {
              echo " anuncio";
            };
            if ($pendiente_agente['tipo'] == 'autorizacion') {
              echo " autorizacion";
            };
            if ($pendiente_agente['tipo'] == 'agente_validado') {
              echo " agente_validado";
            };
            if ($pendiente_agente['tipo'] == 'reclamo_file') {
              echo " reclamo_file";
            };
            if ($pendiente_agente['tipo'] == 'inmueble_validado') {
              echo " inmueble_validado";
            };
            if ($pendiente_agente['tipo'] == 'contacto_compartido') {
              echo " contacto_compartido";
            };
            if ($pendiente_agente['tipo'] == 'check_list_compartido') {
              echo " check_list_compartido";
            };
            if ($pendiente_agente['tipo'] == 'mensaje_interno') {
              echo " mensaje_interno";
            };
            if ($pendiente_agente['tipo'] == 'transferencia_llave') {
              echo " transferencia_llave";
            };
        echo"\">
              " . ucfirst($pendiente_agente['tipo']) . "
            </span>
            <span class=\"tag_corner\">
              <span class=\"pais_tag\">" . ucfirst($pendiente_agente['pais']) . "</span>
              <span class=\"fecha_creacion\">" . "&nbsp/&nbsp" . $pendiente_agente['fecha_creacion'] . "</span>
            </span>
            <span class=\"pendiente_contenido\">";
            if ($pendiente_agente['tipo'] == 'reclamo' || $pendiente_agente['tipo'] == 'autorizacion' || $pendiente_agente['tipo'] == 'inmueble_validado'){
              echo "Referencia: " . $pendiente_agente['key_feature1'] . "</br>";
            }elseif ($pendiente_agente['tipo'] == 'reclamo_file') {
              echo "Tipo:&nbsp" . $pendiente_agente['key_feature2'] . "</br>File ID:&nbsp" . $pendiente_agente['key_feature1'] . "</br>";
            }elseif ($pendiente_agente['tipo'] == 'agente_validado') {
              echo "File ID:&nbsp" . $pendiente_agente['key_feature1'] . "</br>";
            }elseif ($pendiente_agente['tipo'] == 'contacto_compartido' || $pendiente_agente['tipo'] == 'mensaje_interno' || $pendiente_agente['tipo'] == 'check_list_compartido' || $pendiente_agente['tipo'] == 'transferencia_llave') {
              echo "Enviado por:&nbsp" . $pendiente_agente['key_feature1'] . "</br></br>";
            };
              echo nl2br($pendiente_agente['mensaje']);
          echo"</span>
          </div>
          <div class=\"pendiente_btn\">
            <i class=\"fas fa-redo-alt\"></i>
          </div>
        </div>";
      };

    };

  };


};

?>
