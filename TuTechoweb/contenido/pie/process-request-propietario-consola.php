<?php

if(isset($_POST["referencia_sent"])){
    // Capture selected departamento
    $referencia = $_POST["referencia_sent"];

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

  $nombre_propietario = $info_bien['propietario_nombre'] . " " . $info_bien['propietario_apellido'];
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
        <img src=\"" . str_replace("#", "%23", $foto_portada_path) . "?t=" . time() . "\" alt=\"foto\" class=\"foto_inmueble\">
        <span class=\"datos_inmueble_wrap\">
            <span class=\"datos_inmueble_top\">
                <span class=\"dato_direccion\">
                    <p>Dirección: " . $info_bien['location_tag'] . "</p>
                </span>
                <span class=\"dato_precio\">
                    <p>Precio: " . $info_bien['precio'] . " " . $moneda . $moneda_code . "</p>
                </span>
            </span>
            <span class=\"datos_inmueble_middle\">
                <span class=\"dato_tipo_inmueble\">
                    <p>Tipo: " . $info_bien['tipo_bien'] . "</p>
                </span>
                <span class=\"dato_estado\">
                    <p>Estado: " . $info_bien['estado'] . "</p>
                </span>
            </span>
            <span class=\"datos_inmueble_bottom\">
                <span class=\"dato_superficie\">
                <p>" . $superficie . "</p>
                </span>";


                if (isset($info_bien['dormitorios'])) {
                  echo"
                  <span class=\"dato_dormitorios\">
                    <p>x" . $info_bien['dormitorios'] . "</p>
                  </span>
                  ";
                };

                if (isset($info_bien['parqueos'])) {
                  echo"
                  <span class=\"dato_parqueos\">
                    <p>x" . $info_bien['parqueos'] . "</p>
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
            <h2>Todavía sín propuestas</h2>
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
    <h2 class=\"visitas_titulo\">Historial de Visitas: </h2>
    <div class=\"contenedor_visitas\">";

      if (!empty($visitas_inmueble)) {
        
        foreach ($visitas_inmueble as $visita) {
          echo"
            <div class=\"visita_wrap " . $visita['pendiente'] . "\">
              <span class=\"agente_foto\"><img src=\"" . $visita['foto'] . "?t=" . time() . "\" alt=\"\"></span>
              <span class=\"agente_info\">
                <span class=\"agente_nombre\">" . $visita['agente'] . "</span>
                <span class=\"fecha_visita\">" . $visita['fecha'] . " - " . $visita['hora'] . "</span>
              </span>
            </div>
          ";
        };
        
      } else {
        echo"<h2>Ninguna visita de momento</h2>";
      };
    
      
    echo"
    </div>
  </div>
  
  ";


  // SE CIERRA CONTENEDOR DATOS
  echo"</div>";
}
?>
