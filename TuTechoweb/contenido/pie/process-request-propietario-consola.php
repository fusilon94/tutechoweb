<?php

function generateRandomString($length) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
};

function get_tabla($referencia) {
  $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
  return $dict[$referencia[5]];
};

// Conexion con la database

$tutechodb_internacional = "tutechodb_internacional";
try {
  $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};

$consulta_pais_info =	$conexion_internacional->prepare("SELECT moneda, moneda_code, time_zone_php FROM paises WHERE pais=:pais ");
$consulta_pais_info->execute(['pais' => $_COOKIE['tutechopais']]);//SE PASA LA REFERENCIA
$pais_info = $consulta_pais_info->fetch(PDO::FETCH_ASSOC);

$moneda = $pais_info['moneda'];
$moneda_code = $pais_info['moneda_code'];

date_default_timezone_set($pais_info['time_zone_php']);

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

try {
  $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};


if(isset($_POST["referencia_sent"])){
  // Capture selected departamento
  $referencia = $_POST["referencia_sent"];

  $tabla = get_tabla($referencia);

  // Recuperar informacion de ciudades tipo C

  if ($tabla == 'casa' || $tabla == 'departamento') {
    
    $consulta_info_bien =	$conexion->prepare("SELECT exclusivo, pre_venta, tipo_bien, precio, dormitorios, parqueos, estado, anticretico, location_tag, superficie_inmueble, propietario_nombre, propietario_apellido, agencia_registro_id FROM $tabla WHERE referencia = :referencia ");
    $consulta_info_bien->execute([':referencia' => $referencia]);
    $info_bien	=	$consulta_info_bien->fetch(PDO::FETCH_ASSOC);

    $superficie = $info_bien['superficie_inmueble'] . 'm<sup>2</sup>';

  } elseif ($tabla == 'terreno') {

    $consulta_info_bien =	$conexion->prepare("SELECT exclusivo, pre_venta, tipo_bien, precio, estado, anticretico, location_tag, superficie_terreno, superficie_terreno_medida, propietario_nombre, propietario_apellido, agencia_registro_id FROM $tabla WHERE referencia = :referencia ");
    $consulta_info_bien->execute([':referencia' => $referencia]);
    $info_bien	=	$consulta_info_bien->fetch(PDO::FETCH_ASSOC);

    if ($info_bien['superficie_terreno_medida'] == 'hect') {
      $superficie = $info_bien['superficie_terreno']/10000 . ' Hect';
    }else {
      $superficie = $info_bien['superficie_terreno'] . 'm<sup>2</sup>';
    }
    
    
  } elseif ($tabla == 'local') {

    $consulta_info_bien =	$conexion->prepare("SELECT exclusivo, pre_venta, tipo_bien, precio, parqueos, estado, anticretico, location_tag, superficie_inmueble, propietario_nombre, propietario_apellido, agencia_registro_id FROM $tabla WHERE referencia = :referencia ");
    $consulta_info_bien->execute([':referencia' => $referencia]);
    $info_bien	=	$consulta_info_bien->fetch(PDO::FETCH_ASSOC);
    
    $superficie = $info_bien['superficie_inmueble'] . 'm<sup>2</sup>';
  };

  //SE TRAE LA IMAGEN DE PORTADA DEL INMUEBLE
  $inmueble_file_path = '../../bienes_inmuebles/' . $_COOKIE['tutechopais'] . '/' . $referencia;
  $foto_portada_path = $inmueble_file_path . '/portada.jpg';


  //SE TRAEN LAS TAREAS DEL AGENTE
  $consulta_agencia =	$conexion->prepare("SELECT location_tag, departamento FROM agencias WHERE id = :id ");
  $consulta_agencia->execute([':id' => $info_bien['agencia_registro_id']]);
  $agencia	=	$consulta_agencia->fetch(PDO::FETCH_ASSOC);

  $agencia_tag = $agencia['departamento'] . "_" . $agencia['location_tag'];
  $json_tareas_path = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';

  if (file_exists($json_tareas_path)) {
    $tareas_json = json_decode(file_get_contents($json_tareas_path), true);

    $visitas_inmueble = [];
    foreach ($tareas_json as $agente_id => $datos_agente) {

      $consulta_agente =	$conexion->prepare("SELECT nombre, apellido FROM agentes WHERE id = :id ");
      $consulta_agente->execute([':id' => $agente_id]);
      $agente	=	$consulta_agente->fetch(PDO::FETCH_ASSOC);

      $agente_nombre = $agente['nombre'] . ' ' . $agente['apellido'];

      foreach ($datos_agente['visita'] as $datos_visita) {
        
        if ($datos_visita['referencia'] == $referencia) {
          
          $array_constructor = [];
          $array_constructor['agente'] = $agente_nombre;
          $array_constructor['agente_id'] = $agente_id;
          $array_constructor['fecha'] = $datos_visita['fecha'];
          $array_constructor['hora'] = $datos_visita['hora'];
          $array_constructor['pendiente'] = 'pendiente';
          $array_constructor['foto'] = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/foto_plomo_min.jpg';

          $today = new DateTime(date('d-m-Y', strtotime('today')));
          $fecha_visita = new DateTime(date("d-m-Y", strtotime($datos_visita['fecha'])));

          if ($fecha_visita < $today) {
            $array_constructor['pendiente'] = '';
          };
          

          $visitas_inmueble[] = $array_constructor;

        };

      };


    };

    function sortFunction( $a, $b ) {
        return strtotime($a["fecha"]) - strtotime($b["fecha"]);
    }
    usort($visitas_inmueble, "sortFunction");
    
  };


  $propuestas_json_path = $inmueble_file_path . '/propuestas.json';

  
  if (file_exists($propuestas_json_path)) {
    $propuestas_json = json_decode(file_get_contents($propuestas_json_path), true);
  };

  function getEtiquetaInfo($info_bien) {
    if ($info_bien['exclusivo'] == 1) {
      $etiqueta = "EXCLUSIVO";
      $etiqueta_class = "exclusivo";
      return [$etiqueta, $etiqueta_class];
    };
    if ($info_bien['pre_venta'] == 1) {
      $etiqueta = "PRE VENTA";
      $etiqueta_class = "pre_venta";
      return [$etiqueta, $etiqueta_class];
    };
    if ($info_bien['anticretico'] == 1) {
      $etiqueta = "ANTICRETICO";
      $etiqueta_class = "anticretico";
      return [$etiqueta, $etiqueta_class];
    }else {
      $etiqueta = "";
      $etiqueta_class = "hidden";
      return [$etiqueta, $etiqueta_class];
    };
  };




  // ################## HTML RESPONSE #########################

  $nombre_propietario = ucfirst($info_bien['propietario_nombre']) . " " . ucfirst($info_bien['propietario_apellido']);
  [$etiqueta, $etiqueta_class] = getEtiquetaInfo($info_bien);

  echo"
  <p class=\"bienvenida\">
    Bienvenido(a):  " . $nombre_propietario . "
  </p>
  ";

  // SE ABRE CONTENEDOR DATOS
  echo"<div class=\"contenedor_datos\">";

  echo"
  
  <div class=\"datos_left_wrap\">
    <div class=\"resumen_inmueble\">
        <span class=\"etiqueta_especial " . $etiqueta_class . "\"><p>" . $etiqueta . "</p></span>
        <span class=\"foto_container\">
          <img src=\"" . str_replace("#", "%23", $foto_portada_path) . "?t=" . time() . "\" alt=\"foto\" class=\"foto_inmueble\">
        </span>
        <span class=\"datos_inmueble_wrap\">
          <span class=\"datos_inmueble_top\">

            <span class=\"datos_inmueble_left\">
                <span class=\"dato_direccion\">
                    <p class=\"mini_label\">Dirección: </p>
                    <p class=\"mini_info\">" . ucfirst($info_bien['location_tag']) . "</p>
                </span>
                <span class=\"dato_tipo_inmueble\">
                    <p class=\"mini_label\">Tipo: </p>
                    <p class=\"mini_info\">" . ucfirst($info_bien['tipo_bien']) . "</p>
                </span>
                
            </span>
            <span class=\"datos_inmueble_right\">
                <span class=\"dato_precio\">
                    <p class=\"mini_label\">Precio: </p>
                    <p class=\"mini_info\">" . $info_bien['precio'] . " " . $moneda . $moneda_code . "</p>
                </span>
                <span class=\"dato_estado\">
                    <p class=\"mini_label\">Estado: </p>
                    <p class=\"mini_info\">" . $info_bien['estado'] . "</p>
                </span>
            </span>

          </span>  
          <span class=\"datos_inmueble_bottom\">
              <span class=\"dato_superficie\">
              <p class=\"mini_info\">" . $superficie . "</p>
              </span>";


              if (isset($info_bien['dormitorios'])) {
                echo"
                <span class=\"dato_dormitorios\">
                <img src=\"../../objetos/bed_icon.svg\" alt=\"\">
                <p class=\"mini_info\">x" . $info_bien['dormitorios'] . "</p>
                </span>
                ";
              };

              if (isset($info_bien['parqueos'])) {
                echo"
                <span class=\"dato_parqueos\">
                <img src=\"../../objetos/car_icon.svg\" alt=\"\">
                <p class=\"mini_info\">x" . $info_bien['parqueos'] . "</p>
                </span>";
              };
            
          echo"</span>
        </span>
    </div>

    <div class=\"propuestas\">

        <h2 class=\"propuestas_titulo\">Propuestas:</h2>
        <span class=\"contenedor_propuestas\">";
            
        if (isset($propuestas_json)) {

          foreach ($propuestas_json as $propuesta) {
            echo"
              <span class=\"propuesta_wrap\">
                  <span class=\"datos_propuesta_wrap\">
                      <p class=\"fecha_propuesta\">" . $propuesta['fecha'] . "</p>
                      <p class=\"monto_propuesta\">" . $propuesta['monto'] . " " . $moneda . $moneda_code . "</p>
                  </span>
                  <p class=\"propuesta_comentarios\">" .  $propuesta['comentario'] . "</p>
              </span>
            ";
          };
          
        }else{
          echo"
          <h2 class=\"ningun_resultado\">-- Todavía sín propuestas --</h2>
          ";
        };


    echo"
      </span>
    </div>
  </div>
  
  ";

  // ################### HISTORIAL DE VISITAS #########################
  echo"
  <div class=\"historial_visitas\">
    <h2 class=\"visitas_titulo\">Visitas Pendientes: </h2>
    <div class=\"contenedor_visitas\">";

      if (!empty($visitas_inmueble)) {

        if(isset(array_count_values(array_column($visitas_inmueble, 'pendiente'))['pendiente'])){

          foreach ($visitas_inmueble as $visita) {
            echo"
              <div class=\"visita_wrap\">
                <span class=\"agente_foto\"><img src=\"" . $visita['foto'] . "?t=" . time() . "\" alt=\"\"></span>
                <span class=\"agente_info\">
                  <span class=\"agente_nombre\" agente_id=\"" . $visita['agente_id'] . "\">" . $visita['agente'] . "</span>
                  <span class=\"fecha_visita\">" . $visita['fecha'] . " - " . $visita['hora'] . "</span>
                </span>
              </div>
            ";
          };

        }else{
          echo"<h2 class=\"ningun_resultado\">-- Ninguna visita pendiente --</h2>";
        };
        
      } else {
        echo"<h2 class=\"ningun_resultado\">-- Ninguna visita pendiente --</h2>";
      };
    
      
    echo"
    </div>

    <h2 class=\"visitas_titulo\">Visitas Pasadas: </h2>
    <div class=\"contenedor_visitas\">";

      if (!empty($visitas_inmueble)) {

        if(isset(array_count_values(array_column($visitas_inmueble, 'pendiente'))[''])){

          foreach ($visitas_inmueble as $visita) {
            echo"
              <div class=\"visita_wrap\">
                <span class=\"agente_foto\"><img src=\"" . $visita['foto'] . "?t=" . time() . "\" alt=\"\"></span>
                <span class=\"agente_info\">
                  <span class=\"agente_nombre\"  agente_id=\"" . $visita['agente_id'] . "\">" . $visita['agente'] . "</span>
                  <span class=\"fecha_visita\">" . $visita['fecha'] . " - " . $visita['hora'] . "</span>
                </span>
                <span class=\"action_reclamo\" title=\"Dejar un reclamo\"><i class=\"fa fa-exclamation-triangle\"></i></span>
              </div>
            ";
          };

        }else{
          echo"<h2 class=\"ningun_resultado\">-- Ningún registro de visitas --</h2>";
        };
        
      } else {
        echo"<h2 class=\"ningun_resultado\">-- Ningún registro de visitas --</h2>";
      };

    echo"
    </div>
  </div>
  ";
  // SE CIERRA CONTENEDOR DATOS
  echo"</div>";
  
} elseif (isset($_POST["referencia_reclamo_sent"]) || isset($_POST["agente_id_sent"]) || isset($_POST["agente_nombre_sent"]) || isset($_POST["fecha_sent"]) || isset($_POST["hora_sent"]) || isset($_POST["reclamo_sent"])){

  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
  
  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $referencia = $_POST["referencia_reclamo_sent"];
  $agente_id = $_POST["agente_id_sent"];
  $agente_nombre = $_POST["agente_nombre_sent"];
  $fecha_visita = $_POST["fecha_sent"];
  $hora_visita = $_POST["hora_sent"];
  $reclamo_mensaje = filter_var($_POST["reclamo_sent"], FILTER_SANITIZE_STRING);


  $consulta_agente_info =	$conexion->prepare("SELECT agencia_id FROM agentes WHERE id = :id");
  $consulta_agente_info->execute([':id' => $agente_id]);
  $agente_info	=	$consulta_agente_info->fetch(PDO::FETCH_ASSOC);

  $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
  $consulta_agencia_info->execute([':id' => $agente_info['agencia_id']]);
  $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

  $reclamos_json_path = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/reclamos.json';
  if (!file_exists($reclamos_json_path)) {
      $json_constructor = array();
      $json_data = json_encode($json_constructor);
      file_put_contents($reclamos_json_path, $json_data);
  };
  $reclamos_json = json_decode(file_get_contents($reclamos_json_path), true);

  
  $reclamo = [
    'referencia_inmueble' => $referencia,
    'agente_id' => $agente_id,
    'agente_nombre' => $agente_nombre,
    'fecha_visita' => $fecha_visita,
    'hora_visita' => $hora_visita,
    'reclamo' => $reclamo_mensaje,
    'comentario' => '',
    'visto' => 0
  ];

  $reclamo_id = generateRandomString(6);
  $reclamos_json[$reclamo_id] = $reclamo;

  
  //SE GUARDA EL DATA ACTUALIZADO EN SU DIRECTORIO
  $json_final_data = json_encode($reclamos_json);
  file_put_contents($reclamos_json_path, $json_final_data);

  echo"exito";

}elseif (isset($_POST["preg_1_sent"]) && isset($_POST["preg_2_sent"]) && isset($_POST["preg_3_sent"]) && isset($_POST['referencia_encuesta_sent'])) {

  $pregunta_marketing = $_POST["preg_1_sent"];
  $pregunta_conciliacion = $_POST["preg_2_sent"];
  $pregunta_trato_agencia = $_POST["preg_3_sent"];
  
  $referencia = $_POST["referencia_encuesta_sent"];
  $tabla = get_tabla($referencia);


  $consulta_datos =	$conexion->prepare("SELECT conciliador, agencia_registro_id FROM $tabla WHERE referencia = :referencia");
  $consulta_datos->execute([':referencia' => $referencia]);
  $datos	=	$consulta_datos->fetch(PDO::FETCH_ASSOC);

  $conciliador = $datos['conciliador'];
  $agencia_id = $datos['agencia_registro_id'];

  $statement_encuesta = $conexion->prepare(
    "UPDATE $tabla SET encuesta_propietario = :encuesta_propietario WHERE referencia = :referencia");

  $statement_encuesta->execute(array(
    ':encuesta_propietario' => 1,
    ':referencia' => $referencia
  ));

  // GESTION DE LA RESPUESTA 1 y 3

  require('../../acceso/comandos_consola/data_day_encuesta_propietario.php');

  // GESTION DE LA RESPUESTA 2
  if($conciliador !== '' && $pregunta_conciliacion == 2){ //si se registró conciliacion pero el propietario dice lo contrario

    $codigo = generateRandomString(10);
    $current_date = date("Y-m-d");

    $consulta_agencia =	$conexion->prepare("SELECT jefe_agencia_id FROM agencias WHERE id = :id");
    $consulta_agencia->execute([':id' => $agencia_id]);
    $jefe_agencia_id	=	$consulta_agencia->fetch(PDO::FETCH_COLUMN, 0);
    
    $reclamo = "Se produjo una alerta de sistema.<br>El Bien Inmueble de referencia " . $referencia . " registra tener conciliador (ID:" . $conciliador . ") pero el propietario indica que no hubo conciliación alguna.<br>Se le solicita que realice un control de la situación.<br> Este evento será registrado para futuras auditorias.";

    $statement_reclamo = $conexion->prepare(
      "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
    );

    $statement_reclamo->execute(array(
      ':codigo' => $codigo,
      ':agente_id' => $jefe_agencia_id,
      ':mensaje' => $reclamo,
      ':fecha_creacion' => $current_date,
      ':tipo' => 'reclamo_conciliacion',
      ':key_feature1' => $referencia
    ));

    require('../../acceso/comandos_consola/data_day_conciliaciones_turbias.php');
  };




  
};

?>
