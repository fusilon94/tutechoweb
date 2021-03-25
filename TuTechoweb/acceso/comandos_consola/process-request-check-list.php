<?php session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    function check_actions($condition){
        if ($condition == true) {
            return 'activo';
        }else {
            return '';
        };
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



    function to_do_constructor($array_to_do, $condition){

        

        if (!empty($array_to_do)) {

            foreach ($array_to_do as $key => $value) {
                echo"
                <span class=\"elemento_popup\" id=\"" . $key . "\" titulo=\"" . $value['titulo'] . "\"  key=\"" . $key . "\" hora=\"" . $value['hora'] . "\" fecha=\"" . $value['fecha'] . "\">

                        <span class=\"elemento_header\">

                            <span class=\"elemento_titulo\">
                                <span class=\"titulo_read\">
                                    <i class=\"fa fa-circle\"></i>
                                    <p>" . $value['titulo'] . " " . $value['fecha'] . " - " . $value['hora'] . "</p>
                                </span>
                                <span class=\"titulo_edit\">
                                    <i class=\"fa fa-circle\"></i>
                                    <input type=\"text\" class=\"titulo_text_edit\" value=\"" . $value['titulo'] . "\" placeholder=\"Titulo\">
                                    <input type=\"text\" class=\"fecha_edit\" value=\"" . $value['fecha'] . "\" placeholder=\"Fecha (opcional)\">
                                     - 
                                    <input type=\"text\" class=\"hora_edit\" value=\"" . $value['hora'] . "\" placeholder=\"Hora (opcional)\">
                                </span>
                            </span>

                            <span class=\"btn_elemento_detalle activo\">
                                <i class=\"fas fa-chevron-circle-down\"></i>
                            </span>

                            <span class=\"elemento_actions_wrap " . check_actions($condition) . "\">
                                
                                <i class=\"fas fa-ellipsis-v elemento_more\"></i>
                                <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                            </span>

                        </span>

                        <span class=\"elemento_detalle_wrap centrado\">";

                        echo"
                            <div class=\"check_list_wrap read\">";

                            foreach (json_decode($value['check_list'], true) as $key => $check_element) {
                                
                                echo"
                              
                                    <span class=\"check_element_read\" key=\"" . $key . "\">
                                        <p>" . $check_element['titulo'] . "</p>
                                        <span class=\"check_list_box\">";
                                        
                                        if($check_element['checked'] == 1){
                                           echo"
                                           <i class=\"fas fa-circle\"></i>
                                           "; 
                                        } else if($check_element['checked'] == 0){
                                            echo"
                                            <i class=\"far fa-circle\"></i>
                                           "; 
                                        };

                                echo"   </span>
                                    </span>

                                                                                       
                                ";
                            };
                            
                        echo"
                                <span class=\"btn_editar_check_list " . check_actions($condition) . "\">
                                    <i class=\"fa fa-edit\"></i>
                                    <p>Editar</p>
                                </span>
                            </div>
                            
                         <div class=\"check_list_wrap edit\">
                            <div class=\"edit_elements_wrap\">
                        ";
                        
                        foreach (json_decode($value['check_list'], true) as $check_element) {
                            echo"
                                
                                <span class=\"check_element\" estado=\"" . $check_element['checked'] . "\">
                                <i class=\"fas fa-arrows-alt handler_element\"></i>
                                <textarea rows=\"1\" oninput=\"auto_grow(this)\" style=\"height: 29px;\">" . $check_element['titulo'] . "</textarea>
                                <span class=\"borrar_check_element\"><i class=\"fas fa-times-circle\"></i></span>
                                </span>
            
                            ";
                        };


                       echo" 
                            </div>    
                                <span class=\"agregar_check_element pop_dia\">
                                    <i class=\"fas fa-plus-circle\"></i>
                                    <i class=\"fas fa-caret-right\"></i>
                                </span>

                                <span class=\"btn_guardar_cambios_check_list\">Guardar Cambios</span>
                       </div>
                       </span>


                    </span>
                ";
            };

        };


    };


    function get_check_lists($agente_id, $pais, $condition){

        $tutechodb_internacional = "tutechodb_internacional";

        try {
        $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
        } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
        };

        if ($pais == '') {
            $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';

            $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php FROM paises WHERE pais = :pais");
            $consulta_pais_info->execute([":pais" => $_COOKIE['tutechopais']]);
            $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

        }else {
            $json_path_to_do_lists = '../../agentes/' . $pais . '/' . $agente_id . '/to_do_list.json';

            $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php FROM paises WHERE pais = :pais");
            $consulta_pais_info->execute([":pais" => $pais]);
            $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);
        };

        date_default_timezone_set($pais_info['time_zone_php']);
        
        // SE TRAEN LOS TO-DO LIST PERSONALES
        $json_to_do_personal = file_get_contents($json_path_to_do_lists);
        $data_to_do_personal = json_decode($json_to_do_personal, true);

        $count_to_do = count($data_to_do_personal);//SE EXTRAEN LOS DATOS DE VACACIONES DE LA FECHA SOLIICTADA
        if ($count_to_do > 0) {
            $to_do_sin_fecha = array_filter($data_to_do_personal, function($element){
              
                if ($element['fecha'] == '') {
                    return true;
                };
            });

            $to_do_pendientes = array_filter($data_to_do_personal, function($element){

                $comparison = new DateTime(date('d-m-Y',strtotime($element['fecha'])));
                $today = new DateTime(date("d-m-Y", time()));
              
                if ($comparison >= $today) {
                    return true;
                };
            });

            $to_do_pasados = array_filter($data_to_do_personal, function($element){

                $comparison = new DateTime(date('d-m-Y',strtotime($element['fecha'])));
                $today = new DateTime(date("d-m-Y", time()));
              
                if ($comparison < $today && $element['fecha'] !== '') {
                    return true;
                };
            });



        };

        if (!empty($to_do_sin_fecha)) {

            echo"
                <h2 class=\"titutlo_section\">Generales</h2>
                <hr class=\"linea_naranja\"> 
            ";

            to_do_constructor($to_do_sin_fecha, $condition);
            
        };

        if (!empty($to_do_pendientes)) {

            echo"
                <h2 class=\"titutlo_section\">Pendientes Actuales</h2>
                <hr class=\"linea_naranja\"> 
            ";

            to_do_constructor($to_do_pendientes, $condition);
            
        };

        if (!empty($to_do_pasados)) {

            echo"
                <h2 class=\"titutlo_section\">Pendientes Pasados</h2>
                <hr class=\"linea_naranja\"> 
            ";

            to_do_constructor($to_do_pasados, $condition);
            
        };

    };


    if (isset($_POST['action_sent'])) {

        $action = $_POST['action_sent'];
        
        if ($action == 'refresh') {

            if (isset($_POST["agente_id_sent"]) && isset($_POST["pais_sent"])) {

                $agente_id = $_POST["agente_id_sent"];
                $pais = $_POST["pais_sent"];
        
                get_check_lists($agente_id, $pais, true);
        
        
            };


        }elseif ($action == 'edit') {
            
            if (isset($_POST["agente_id_sent"]) && isset($_POST['key_to_do_sent']) && isset($_POST['titulo_sent']) && isset($_POST['to_do_json_sent']) && isset($_POST['new_titulo_sent']) && isset($_POST['new_fecha_sent']) && isset($_POST['new_hora_sent'])) {

                $agente_id = $_POST["agente_id_sent"];
                $to_do_key = $_POST['key_to_do_sent'];
                $titulo = $_POST['titulo_sent'];
                $to_do_json = $_POST['to_do_json_sent'];

                $new_titulo = $_POST['new_titulo_sent'];
                $new_fecha = $_POST['new_fecha_sent'];
                $new_hora = $_POST['new_hora_sent'];
            

                $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
                $json = file_get_contents($json_path_to_do_lists);
                $data = json_decode($json, true);
                
                $to_do = $data[$to_do_key];
                if ($to_do['titulo'] == $titulo) {
                    
                    $data[$to_do_key]['check_list'] = $to_do_json;
                    $data[$to_do_key]['fecha'] = $new_fecha;
                    $data[$to_do_key]['hora'] = $new_hora;
                    $data[$to_do_key]['titulo'] = $new_titulo;

                    $data_json = json_encode($data);// transformar el array en codigo json
                    file_put_contents($json_path_to_do_lists, $data_json); // FINALMENTE se guarda el data en un Json file
                    
                    get_check_lists($agente_id, '', true);

                } else {
                    echo"error";
                };


            };

        }elseif ($action == 'check_element') {
            
            if (isset($_POST["agente_id_sent"]) && isset($_POST['action_listened']) && isset($_POST['key_check_sent']) && isset($_POST['key_to_do_sent']) && isset($_POST['titulo_sent'])) {

                $agente_id = $_POST["agente_id_sent"];
                $action = $_POST['action_listened'];
                $key_elemento = $_POST['key_check_sent'];
                $key_to_to = $_POST['key_to_do_sent'];
                $titulo = $_POST['titulo_sent'];

                $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
                $json = file_get_contents($json_path_to_do_lists);
                $data = json_decode($json, true);

                $to_do = $data[$key_to_to];
                if($to_do['titulo'] == $titulo) {
                    $array_edit = json_decode($data[$key_to_to]['check_list'], true);
                    $array_edit[$key_elemento]['checked'] = $action;
                    
                    $array_encoded = json_encode($array_edit);
                    $data[$key_to_to]['check_list'] = $array_encoded;
                    
                    $data_json = json_encode($data);// transformar el array en codigo json
                    file_put_contents($json_path_to_do_lists, $data_json); // FINALMENTE se guarda el data en un Json file
        
                    foreach (json_decode($array_encoded, true) as $check_element) {
                        echo"
                        
                            <span class=\"check_element\" estado=\"" . $check_element['checked'] . "\">
                            <textarea rows=\"1\" oninput=\"auto_grow(this)\" style=\"height: 29px;\">" . $check_element['titulo'] . "</textarea>
                            <span class=\"borrar_check_element\"><i class=\"fas fa-times-circle\"></i></span>
                            </span>
        
                        ";
                    };
                    echo" 
                        <span class=\"agregar_check_element pop_dia\">
                            <i class=\"fas fa-plus-circle\"></i>
                            <i class=\"fas fa-caret-right\"></i>
                        </span>
        
                        <span class=\"btn_guardar_cambios_check_list\">Guardar Cambios</span>
                    ";
        
                }else {
                    echo"error";
                };


            };


        }elseif ($action == 'agente_search') {
            
            if (isset($_POST["agente_id_sent"]) && isset($_POST["pais_sent"])) {

                $agente_id = $_POST["agente_id_sent"];
                $pais = $_POST["pais_sent"];
        
                get_check_lists($agente_id, $pais, false);
        
        
            };


        }elseif ($action == 'compartir') {
            
            if (isset($_POST["index_sent"]) && isset($_POST["destinatario_sent"]) && isset($_POST["titulo_sent"]) && isset($_POST['agente_id_sent'])) {

                $key_to_do = $_POST["index_sent"];
                $destinatario_id = $_POST["destinatario_sent"];
                $titulo = $_POST["titulo_sent"];
                $agente_id = $_POST['agente_id_sent'];
        
                $consulta_destinatario_existe =	$conexion->prepare("SELECT id FROM agentes WHERE id = :id");
                $consulta_destinatario_existe->execute([":id" => $destinatario_id]);
                $destinatario_existe	=	$consulta_destinatario_existe->fetch(PDO::FETCH_ASSOC);
    
                if (empty($destinatario_existe)) {
                    echo "error";
                }else{

                    $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
                    $json = file_get_contents($json_path_to_do_lists);
                    $data = json_decode($json, true);
    
                    $datos_compartidos = $data[$key_to_do];
    
                    $consulta_agente_emisor =	$conexion->prepare("SELECT id, nombre, apellido FROM agentes WHERE usuario = :usuario");
                    $consulta_agente_emisor->execute([":usuario" => $_SESSION['usuario']]);
                    $agente_emisor	=	$consulta_agente_emisor->fetch(PDO::FETCH_ASSOC);
    
                    $emisor_dato = $agente_emisor['nombre'] . ' ' . $agente_emisor['apellido'] . '(ID: ' . $agente_emisor['id'] . ')';
    
                    $mensaje = 'Check-List compartido:<br>Titulo: ' . $datos_compartidos['titulo'] . '<br>Este mensaje expira automaticamente en 7 dias';
    
                    $codigo = generateRandomString();
                    $current_date = date("Y/m/d");
                    $expiration_date = date("Y/m/d", strtotime("+ 7 day"));
    
                    $datos_feature = json_encode($datos_compartidos);
            
                    $statement_respuesta = $conexion->prepare(
                    "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, key_feature2, fecha_expiracion) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :key_feature2, :fecha_expiracion)"
                    );
            
                    $statement_respuesta->execute(array(
                    ':codigo' => $codigo,
                    ':agente_id' => $destinatario_id,
                    ':mensaje' => $mensaje,
                    ':fecha_creacion' => $current_date,
                    ':tipo' => 'check_list_compartido',
                    ':key_feature1' => $emisor_dato,
                    ':key_feature2' => $datos_feature,
                    ':fecha_expiracion' => $expiration_date
                    ));
    
                    echo "exito";
    
                };
        
              

        
            };

        }elseif ($action == 'borrar') {
           
            if (isset($_POST["key_sent"]) && isset($_POST["titulo_sent"]) && isset($_POST['agente_id_sent'])) {

                $titulo = $_POST["titulo_sent"];
                $agente_id = $_POST['agente_id_sent'];
                $key = $_POST["key_sent"];

                $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
                $json = file_get_contents($json_path_to_do_lists);
                $data = json_decode($json, true);

                $check_list = $data[$key];

                if ($check_list['titulo'] == $titulo) {
                    unset($data[$key]);

                    $data_json = json_encode($data);// transformar el array en codigo json
                    file_put_contents($json_path_to_do_lists, $data_json); // FINALMENTE se guarda el data en un Json file
                };
        
                get_check_lists($agente_id, '', true);
                
            }else {
                echo "error";
            };


        }elseif ($action == 'agregar') {
            
            if (isset($_POST["titulo_sent"]) && isset($_POST['agente_id_sent']) && isset($_POST['fecha_sent']) && isset($_POST['hora_sent']) && isset($_POST['check_list_sent'])) {

                $titulo = $_POST["titulo_sent"];
                $agente_id = $_POST['agente_id_sent'];
                $hora = $_POST['hora_sent'];
                $fecha = $_POST['fecha_sent'];
                $check_list = $_POST['check_list_sent'];


                if ($check_list == '[]') {
                    echo'error';
                }else{
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "fecha" => $fecha,
                        "titulo" => $titulo,
                        "hora" => $hora,
                        "check_list" => $check_list
                    ];
    
                    $json_path_eventos = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';

                    $json_eventos = file_get_contents($json_path_eventos);
                    $data_eventos = json_decode($json_eventos, true);

                    array_push($data_eventos, $new_element);

                    $data_json = json_encode($data_eventos);// transformar el array en codigo json
                    file_put_contents($json_path_eventos, $data_json); // FINALMENTE se guarda el data en un Json file

                    get_check_lists($agente_id, '', true);
                };

            };

        }elseif ($action == 'agregar_a_visita') {
           
            if (isset($_POST['index_sent']) && isset($_POST['agencia_tag_sent']) && isset($_POST['referencia_sent']) && isset($_POST['index_to_do_sent']) && isset($_POST['titulo_sent'])) {

                $index_visita = $_POST['index_sent'];
                $agencia_tag = $_POST['agencia_tag_sent'];
                $referencia = $_POST['referencia_sent'];
                $index_to_do = $_POST['index_to_do_sent'];
                $titulo = $_POST['titulo_sent'];

                $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';

                $consulta_agente_id = $conexion->prepare(" SELECT id FROM agentes WHERE usuario = :usuario ");
                $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
                $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);

                $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
                $data_agentes_tareas = json_decode($json_agentes_tareas, true);

                if ($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['referencia'] == $referencia) {

                    if (!isset($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['check_lists_extra'])) {
                        $data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['check_lists_extra'] = array();
                    };

                    $json_path_check_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/to_do_list.json';
                    $json_check_list_get = file_get_contents($json_path_check_lists);
                    $data_check_list_get = json_decode($json_check_list_get, true);

                    $check_list_selected = array();
                    $check_list_selected['titulo'] = $data_check_list_get[$index_to_do]['titulo'];
                    $check_list_selected['check_list'] = $data_check_list_get[$index_to_do]['check_list'];

                    array_push($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['check_lists_extra'], $check_list_selected);

                    $data_json = json_encode($data_agentes_tareas);
    
                    file_put_contents($json_path_agentes_tareas, $data_json);

                    echo"exito";
                };

                
            }else {
                echo"error";
            };



        };

    };


    


};

?>
