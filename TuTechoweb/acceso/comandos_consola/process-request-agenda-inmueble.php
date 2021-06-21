<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    $db_internacional = "tutechodb_internacional";

    try {
        $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $db_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
    };

    $consulta_pais = $conexion_internacional->prepare(" SELECT time_zone_php FROM paises WHERE pais = :pais ");
    $consulta_pais->execute([":pais" => $_COOKIE['tutechopais']]);
    $pais_info = $consulta_pais->fetch(PDO::FETCH_ASSOC);

    date_default_timezone_set($pais_info['time_zone_php']);

    function get_tabla($referencia) {
        $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
        return $dict[$referencia[5]];
    };


    if (isset($_POST['referencia_sent'])) {

        $referencia = $_POST['referencia_sent'];
        $tabla = get_tabla($referencia);
        $nivel_acceso = $_SESSION['nivel_acceso'];

        $consulta_agente_id = $conexion->prepare(" SELECT id, agencia_id FROM agentes WHERE usuario = :usuario ");
        $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
        $agente_info = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);

        $consulta_agencia_id =	$conexion->prepare("SELECT agencia_registro_id FROM $tabla WHERE referencia = :referencia ");
        $consulta_agencia_id->execute([':referencia' =>  $referencia]);
        $agencia_id = $consulta_agencia_id->fetch(PDO::FETCH_COLUMN, 0);


        if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12 || $agente_info['agencia_id'] == $agencia_id) {

            // SE TRAE LA FICHA RESUMEN DEL INMUEBLE

            $consulta_info_inmueble = $conexion->prepare(" SELECT direccion, direccion_complemento, tipo_bien, estado, pre_venta, anticretico, llave FROM $tabla WHERE referencia = :referencia ");
            $consulta_info_inmueble->execute([":referencia" => $referencia]);
            $info_inmueble = $consulta_info_inmueble->fetch(PDO::FETCH_ASSOC);

            function key_check($key){
                if ($key == 1) {
                    return "activo";
                }else {
                    return "";
                };
            };

            function check_estado($estado, $pre_venta, $anticretico){
                if ($estado == 'En Venta') {
                    if ($pre_venta == 1) {
                        return "Pre-Venta";
                    }else {
                        return "En Venta";
                    };
                }else {
                    if ($anticretico == 1) {
                        return "Anticretico";
                    }else {
                        return "En Alquiler";
                    };
                };
            };


            echo"
                <div class=\"inmueble_resumen\">

                    <span class=\"resumen_key " . key_check($info_inmueble['llave']) . "\" title=\"Requiere llave\"><i class=\"fas fa-key\"></i></span>

                    <img class=\"resumen_foto\" src=\"../../bienes_inmuebles/" . $_COOKIE['tutechopais'] . "/" . str_replace("#", "%23", $referencia) . "/portada.jpg?t=" . time() . "\">
                    <div class=\"resumen_info_wrap\">
                    
                        <p class=\"resumen_info\"><b>Dirección:</b> {$info_inmueble['direccion']}</p>
                        <p class=\"resumen_info\"><b>Complemento:</b> {$info_inmueble['direccion_complemento']}</p>
                        <span class=\"resumen_info_pack\">
                            <p class=\"resumen_info_min\"><b>Tipo:</b>" . ucfirst($info_inmueble['tipo_bien']) . "</p>
                            <p class=\"resumen_info_min\"><b>Estado:</b> " . check_estado($info_inmueble['estado'], $info_inmueble['pre_venta'], $info_inmueble['anticretico']) . "</p>
                        </span>

                    </div>
                </div>
            
            ";
            // SE TRAEN LAS VISITAS DEL INMUEBLE
            
            $consulta_agencia_info = $conexion->prepare(" SELECT departamento, location_tag FROM agencias WHERE id = :id ");
            $consulta_agencia_info->execute([":id" => $agencia_id]);
            $agencia_info = $consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

            $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

            $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';

            if (!file_exists($json_path_agentes_tareas)) {
                echo"<h2>Este Bien Inmueble NO tiene visitas agendadas</h2>";
            }else{

                $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
                $data_agentes_tareas = json_decode($json_agentes_tareas, true);


                // se filtran las tareas para solo traer aquellas que corresponden al bien inmueble en cuestion, y solo aquellas pendientes
                $lista_reducida = array();

                foreach ($data_agentes_tareas as $key => $tareas_agente) {

                  foreach ($tareas_agente['visita'] as $visita) {

                    $visita_fecha = new DateTime(date('d-m-Y',strtotime($visita['fecha'])));
                    $today = new DateTime(date("d-m-Y", time()));

                    if ($visita['referencia'] == $referencia && $visita_fecha >= $today) {
                        $visita['agente'] = $key;
                        $lista_reducida[] =  $visita;
                    };

                  }; 

                };

                if (empty($lista_reducida)) {
                    echo"<h2>Este Bien Inmueble NO tiene visitas agendadas</h2>";
                }else{
                    // se ordenan las visitas por fecha creciente y hora creciente

                    uasort($lista_reducida,function($a,$b) {

                        preg_match_all('!\d+!', $a['hora'], $a_matches);
                        $a_hora = intval(implode('', $a_matches[0]));

                        preg_match_all('!\d+!', $b['hora'], $b_matches);
                        $b_hora = intval(implode('', $b_matches[0]));
                        
                        return $a_hora - $b_hora;

                    });//ordena el array segun hora

                    uasort($lista_reducida,function($a,$b) {

                        $a_fecha = new DateTime(date('d-m-Y', strtotime($a['fecha'])));
                        $b_fecha = new DateTime(date("d-m-Y", strtotime($b['fecha'])));
                        
                        if ($a_fecha > $b_fecha) {
                            return 1;
                        }elseif ($a_fecha < $b_fecha) {
                            return -1;
                        }elseif ($a_fecha == $b_fecha) {
                            return 0;
                        };

                    });//re-ordena el array segun fecha

                    // se divide el array en subarrays correspondientes a visitas por dias
                    $lista_particionada = array();

                    foreach ($lista_reducida as $visita) {
                        $keys = array_keys($lista_particionada);
                        if(in_array($visita['fecha'], $keys)){
                            $lista_particionada[$visita['fecha']][] =  $visita;
                        }else{
                            $lista_particionada[$visita['fecha']] = array();
                            $lista_particionada[$visita['fecha']][] =  $visita;
                        };
                    };

                    //Se construye la lista de visitas separado por fechas, mostrando 'hoy' y 'mañana' para los primeros dias

                    foreach ($lista_particionada as $key => $dia) {

                        $key_fecha = new DateTime(date('d-m-Y',strtotime($key)));
                        $today = new DateTime(date("d-m-Y", time()));
                        $tomorrow = new DateTime(date("d-m-Y", strtotime("Tomorrow")));

                        if ($key_fecha == $today) {
                            echo "<h2 class=\"day_label\">HOY</h2><hr/>";
                        }elseif ($key_fecha == $tomorrow) {
                            echo "<h2 class=\"day_label\">MAÑANA</h2><hr/>";
                        }else {
                            echo "<h2 class=\"day_label\">" . $key . "</h2><hr/>";
                        };

                        foreach ($dia as $visita_elemento) {
                            $consulta_agente_info =	$conexion->prepare("SELECT nombre, apellido FROM agentes WHERE id = :id ");
                            $consulta_agente_info->execute([':id' =>  $visita_elemento['agente']]);
                            $agente_info = $consulta_agente_info->fetch(PDO::FETCH_ASSOC);

                            $nombre = $agente_info['nombre'] . " " . $agente_info['apellido'];

                            echo"
                            <span class=\"visita_wrap\">
                                <img src=\"../../agentes/" . $_COOKIE['tutechopais'] . "/" . $visita_elemento['agente'] . "/foto_plomo_min.jpg\" class=\"visita_foto\" title=\"" . $visita_elemento['agente'] . "\">
                                <span class=\"visita_datos\">
                                    <p><b>Agente:</b> " . $nombre . "</p>
                                    <p><b>Hora:</b> " . $visita_elemento['hora'] . "</p>
                                </span>
                                
                            </span>

                            ";
                        };
                    }
                };

                



            };

        }else{
            echo"<h2>Este Bien Inmueble no fue registrado po tu Agencia.<br>Agenda NO visible.</h2>";
        };



    };
    

?>
