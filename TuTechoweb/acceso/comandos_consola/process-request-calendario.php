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

    $consulta_pais_info =	$conexion_internacional->prepare("SELECT idioma FROM paises WHERE pais = :pais");
    $consulta_pais_info->execute([":pais" => $_COOKIE['tutechopais']]);
    $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

    if ($pais_info['idioma'] == 'es') {
        $lista_meses = ['January' =>'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];
    }elseif ($pais_info['idioma'] == 'en') {
        $lista_meses = ['January' =>'January', 'February' => 'February', 'March' => 'March', 'April' => 'April', 'May' => 'May', 'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December'];
    };//sumar acá otros idiomas

    if ($pais_info['idioma'] == 'es') {
        $lista_dias_nombres = ['Monday' =>'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];
    }elseif ($pais_info['idioma'] == 'en') {
        $lista_dias_nombres = ['Monday' =>'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
    };//sumar acá otros idiomas


    function day_fill_events($date, $agencia_tag, $agente_id, $extra){

        $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

        try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
        } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
        };


        // SE LISTA LOS PATHS DE LOS DIFERENTES JSON QUE HAY QUE CONSULTAR
        $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
        $json_path_eventos_personales = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/eventos_personal.json';

        $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
        $json_path_eventos_agencia = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/eventos.json';

        $json_path_turnos_agencia = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/turnos_agencia.json';
        $json_path_vacaciones = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/vacaciones.json';

        $json_path_jefe_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/jefe_tareas.json';

        // SE CONSULTA EL NIVEL DE ACCESO DEL AGENTE SOLICITADO
        $consulta_agente_info =	$conexion->prepare("SELECT nivel_acceso FROM agentes WHERE id = :id");
        $consulta_agente_info->execute([":id" => $agente_id]);
        $agente_info = $consulta_agente_info->fetch(PDO::FETCH_ASSOC);

        $nivel_acceso = $agente_info['nivel_acceso'];

        if ($nivel_acceso == 4) {//AGENTE INMO TIENEN TAREAS Y TURNOS EN AGENCIA

            $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
            $data_agentes_tareas = json_decode($json_agentes_tareas, true);

            $json_agente_turnos = file_get_contents($json_path_turnos_agencia);
            $data_agente_turnos = json_decode($json_agente_turnos, true);


        } elseif ($nivel_acceso == 10 || $nivel_acceso == 7) {//AGENTES EXPRESS Y Fotografo SOLO TIENEN TAREAS

            $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
            $data_agentes_tareas = json_decode($json_agentes_tareas, true);

        }elseif ($nivel_acceso == 3) {
            
            $json_agentes_tareas = file_get_contents($json_path_jefe_tareas);
            $data_agentes_tareas = json_decode($json_agentes_tareas, true);

        };

        if ($agencia_tag !== '') {
            // SE TRAEN LOS EVENTOS Y ANUNCIOS DE AGENCIA
            $json_eventos_agencia = file_get_contents($json_path_eventos_agencia);
            $data_eventos_agencia = json_decode($json_eventos_agencia, true);
        };
        

        // SE TRAEN LOS EVENTOS PERSONALES
        $json_eventos_personal = file_get_contents($json_path_eventos_personales);
        $data_eventos_personal = json_decode($json_eventos_personal, true);

        // SE TRAEN LOS TO-DO LIST PERSONALES
        $json_to_do_personal = file_get_contents($json_path_to_do_lists);
        $data_to_do_personal = json_decode($json_to_do_personal, true);

        // SE TRAEN LAS VACACIONES PERSONALES
        $json_vacaciones = file_get_contents($json_path_vacaciones);
        $data_vacaciones = json_decode($json_vacaciones, true);

        if ($nivel_acceso == 7) {
            $count_registro_fotografo = 0;

            foreach ($data_agentes_tareas as $data_agente) {
                $count_registro_fotografo += count($data_agente['registro']);
            };

            if ($count_registro_fotografo > 0) {

                $array_count_registro_fotografo = array();

                foreach ($data_agentes_tareas as $agente_key => $data_agente) {

                    foreach ($data_agente['registro'] as $value) {

                        if ($value['fecha'] == $date) {

                            $new_element = $value;
                            $new_element['agente_id'] = $agente_key;
    
                            array_push($array_count_registro_fotografo, $new_element);
    
                        };
                        
                    };
                    
                };
                
            };
            
        };

        if ($nivel_acceso == 10 || $nivel_acceso == 4) {//SE SEPARAN LAS TAREAS DE TIPO REGISTRO Y VISITA DE LOS AGENTES Y AGENTES EXPRESS DE LA FECHA SOLICITADA

            if (isset($data_agentes_tareas[$agente_id])) {
                $count_registro = count($data_agentes_tareas[$agente_id]['registro']);
                if ($count_registro > 0) {
                    $array_count_registro = array_filter($data_agentes_tareas[$agente_id]['registro'], function($element) use ($date){
            
                        if ($element['fecha'] == $date) {
                            return true;
                        };
                    });
                };


                $count_visita = count($data_agentes_tareas[$agente_id]['visita']);
                if ($count_visita > 0) {
                    $array_count_visita = array_filter($data_agentes_tareas[$agente_id]['visita'], function($element) use ($date){
                    
                        if ($element['fecha'] == $date) {
                            return true;
                        };
                    });
                };
            };
           
        };

        if ($nivel_acceso == 3) {//SE SEPARAN LAS TAREAS DE TIPO REGISTRO Y VISITA DE LOS AGENTES Y AGENTES EXPRESS DE LA FECHA SOLICITADA

            if (isset($data_agentes_tareas)) {
                $count_cita = count($data_agentes_tareas['cita']);
                if ($count_cita > 0) {
                    $array_count_cita = array_filter($data_agentes_tareas['cita'], function($element) use ($date){
            
                        if ($element['fecha'] == $date) {
                            return true;
                        };
                    });
                };


                $count_salida = count($data_agentes_tareas['salida']);
                if ($count_salida > 0) {
                    $array_count_salida = array_filter($data_agentes_tareas['salida'], function($element) use ($date){
                    
                        if ($element['fecha'] == $date) {
                            return true;
                        };
                    });
                };
            };
           
        };
        

        if ($nivel_acceso == 4) {//SE EXTRAEN LOS DATOS DE TURNO DE LA FECHA SOLICITADA
            $count_agente_turnos = count($data_agente_turnos);
            if ($count_agente_turnos > 0) {
                $array_agente_turnos = array_filter($data_agente_turnos, function($key) use ($date){
               
                    if ($key == $date) {
                        return true;
                    };
                }, ARRAY_FILTER_USE_KEY);
            };
        };

        if (isset($data_eventos_agencia)) {
            $count_anuncios_agencia = count($data_eventos_agencia['anuncio']);//SE EXTRAEN LOS ANUNCIOS DE AGENCIA DE LA FECHA SOLICITADA
            if ($count_anuncios_agencia > 0) {
                $array_anuncios_agencia = array_filter($data_eventos_agencia['anuncio'], function($element) use ($date){
                    if ($element['fecha'] == $date) {
                        return true;
                    };
                });
            };

            $count_eventos_agencia = count($data_eventos_agencia['evento']);//SE EXTRAEN LOS EVENTOS DE AGENCIA DE LA FECHA SOLICITADA
            if ($count_eventos_agencia > 0) {
                $array_eventos_agencia = array_filter($data_eventos_agencia['evento'], function($element) use ($date){
            
                    if ($element['fecha'] == $date) {
                        return true;
                    };
                });
                
                //EXTRAEN LOS EVENTOS ANIVERASIRO RECURRENTES DE AGENCIA
                $array_aniversarios_recurrentes_agencia = array_filter($data_eventos_agencia['anuncio'], function($element) use ($date) {
                    if (substr($element['fecha'], 0, -5) == substr($date, 0, -5) && $element['tipo'] == 'aniversario') {
                        if ($element['recurrente'] == 1) {
                            return true;
                        };
                    };
                });
            };

        };
                

        $count_eventos_personal = count($data_eventos_personal);//SE EXTRAEN LOS EVENTOS PERSONALES DE LA FECHA SOLICITADA
        if ($count_eventos_personal > 0) {
            $array_eventos_personal = array_filter($data_eventos_personal, function($element) use ($date) {
                if ($element['fecha'] == $date) {
                    return true;
                };
            });
            //EXTRAEN LOS EVENTOS ANIVERASIRO RECURRENTES PERSONALES
            $array_aniversarios_recurrentes_personal = array_filter($data_eventos_personal, function($element) use ($date) {
                if (substr($element['fecha'], 0, -5) == substr($date, 0, -5) && $element['tipo'] == 'aniversario') {
                    if ($element['recurrente'] == 1) {
                        return true;
                    };
                };
            });
        };

        $count_vacaciones = count($data_vacaciones);//SE EXTRAEN LOS DATOS DE VACACIONES DE LA FECHA SOLIICTADA
        if ($count_vacaciones > 0) {
            $array_vacaciones = array_filter($data_vacaciones, function($element) use ($date){
              
                if ($element == $date) {
                    return true;
                };
            });
        };

        $count_to_do = count($data_to_do_personal);//SE EXTRAEN LOS DATOS DE VACACIONES DE LA FECHA SOLIICTADA
        if ($count_to_do > 0) {
            $array_to_do = array_filter($data_to_do_personal, function($element) use ($date){
              
                if ($element['fecha'] == $date) {
                    return true;
                };
            });
        };

        if (isset($array_eventos_personal)) {//SE EXTRAEN LOS EVENTOS DE VIAJES PERSONALES DE LA FECHA
            $viajes_personal = count(array_filter($array_eventos_personal, function($element) {
                if ($element['tipo'] == 'viaje') {
                    return true;
                };
            }));
            if ($viajes_personal > 0) {
                $viajes_personal_count = $viajes_personal;
            };
        };
        
        if (isset($array_eventos_agencia)) {//SE EXTRAEN LOS DATOS DE VIAJES DE AGENCIA DE LA FECHA
            $viajes_agencia = count(array_filter($array_eventos_agencia, function($element) {
                if ($element['tipo'] == 'viaje') {
                    return true;
                };
            }));
            if ($viajes_agencia > 0) {
                $viajes_agencia_count = $viajes_agencia;
            };
        };
        
        if (isset($array_eventos_personal)) {//SE EXTRAEN LOS DATOS DE COMIDAS PERSONALES DE LA FECHA
            $comidas_personal = count(array_filter($array_eventos_personal, function($element) {
                if ($element['tipo'] == 'comida') {
                    return true;
                };
            }));
            if ($comidas_personal > 0) {
                $comidas_personal_count = $comidas_personal;
            };
        };
        
        if (isset($array_eventos_agencia)) {//SE EXTRAEN LOS DATOS DE COMIDAS DE LA AGENCIA DE LA FECHA
            $comidas_agencia = count(array_filter($array_eventos_agencia, function($element) {
                if ($element['tipo'] == 'comida') {
                    return true;
                };
            }));
            if ($comidas_agencia > 0) {
                $comidas_agencia_count = $comidas_agencia;
            };
        };
       
        if (isset($array_eventos_personal)) {//SE EXTRAEN LOS DATOS DE ANIVERSARIOS PERSONALES DE LA FECHA
            $aniversarios_personal = count(array_filter($array_eventos_personal, function($element) {
                if ($element['tipo'] == 'aniversario') {
                    return true;
                };
            }));

            if ($aniversarios_personal > 0) {
                $aniversarios_personal_count = $aniversarios_personal;
            };
        };
        
        if (isset($array_eventos_agencia)) {//SE EXTRAEN LOS DATOS DE ANIVERSARIOS DE AGENCIA DE LA FECHA
            $aniversarios_agencia = count(array_filter($array_eventos_agencia, function($element) {
                if ($element['tipo'] == 'aniversario') {
                    return true;
                };
            }));

            if ($aniversarios_agencia > 0) {
                $aniversarios_agencia_count = $aniversarios_agencia;
            };
        };

        if (isset($array_aniversarios_recurrentes_agencia)) {//SE EXTRAEN LOS DATOS DE ANIVERSARIOS RECURRENTES DE AGENCIA DE LA FECHA
            $aniversarios_recurrentes_agencia = count($array_aniversarios_recurrentes_agencia);

            if ($aniversarios_recurrentes_agencia > 0) {
                $aniversarios_recurrentes_agencia_count = $aniversarios_recurrentes_agencia;
            };
        };

        if (isset($array_aniversarios_recurrentes_personal)) {//SE EXTRAEN LOS DATOS DE ANIVERSARIOS RECURRENTES PERSONALES DE LA FECHA
            $aniversarios_recurrentes_personal = count($array_aniversarios_recurrentes_personal);
            if ($aniversarios_recurrentes_personal > 0) {
                $aniversarios_recurrentes_personal_count = $aniversarios_recurrentes_personal;
            };
        };
        
        if ($extra == 'tabla') {
        
            // CABECERA
            $cabecera_count = 1;//COUNT PARA ORDENAR LOS EVENTOS DE CABECERA SEGUN SU NIVEL DE IMPORTANCIA, Y PARA LA CORRECTA MAQUETACION RESPONSIVE
            
            //SE ARMA LA CABECERA DEL CUADRO DIA
            echo"
            <span class=\"header_space\">";
            
            if (isset($array_agente_turnos)) {
                if (!empty($array_agente_turnos)) {
                    if ($array_agente_turnos[0] == $agente_id) {
                        echo"
                            <i class=\"fas fa-briefcase cabecera_" . $cabecera_count . "\"></i>
                        ";
        
                        $cabecera_count += 1;
                    };
                };
            };
            
            if (isset($array_vacaciones)) {
                if (!empty($array_vacaciones)) {
                    echo"
                        <img src=\"../../objetos/vacacion_min.svg\" alt=\"\" class=\"vacacion_icon cabecera_" . $cabecera_count . "\">
                    ";
                    $cabecera_count += 1;
                };
            };
            

            if (isset($viajes_personal_count)) {          
                echo"
                    <i class=\"fas fa-plane cabecera_" . $cabecera_count . "\"></i>
                ";
                $cabecera_count += 1;
            }elseif (isset($viajes_agencia_count)) {
                echo"
                    <i class=\"fas fa-plane cabecera_" . $cabecera_count . "\"></i>
                ";
                $cabecera_count += 1;
            };
            
            

            if (isset($comidas_personal_count)) {
                echo"
                    <i class=\"fas fa-utensils cabecera_" . $cabecera_count . "\"></i>
                ";
                $cabecera_count += 1;
            }elseif(isset($comidas_agencia_count)){
                echo"
                    <i class=\"fas fa-utensils cabecera_" . $cabecera_count . "\"></i>
                ";
                $cabecera_count += 1;
            };
            
            

            if (isset($aniversarios_personal_count)) {
                
                    echo"
                        <img src=\"../../objetos/pastel.svg\" alt=\"\" class=\"pastel_icon cabecera_" . $cabecera_count . "\">
                    ";
                    $cabecera_count += 1;
                
            }elseif(isset($aniversarios_agencia_count)){
                
                    echo"
                        <img src=\"../../objetos/pastel.svg\" alt=\"\" class=\"pastel_icon cabecera_" . $cabecera_count . "\">
                    ";
                    $cabecera_count += 1;
            
            }elseif (isset($aniversarios_recurrentes_agencia_count)) {
                
                    echo"
                        <img src=\"../../objetos/pastel.svg\" alt=\"\" class=\"pastel_icon cabecera_" . $cabecera_count . "\">
                    ";
                    $cabecera_count += 1;
                
            }elseif (isset($aniversarios_recurrentes_personal_count)) {
                
                    echo"
                        <img src=\"../../objetos/pastel.svg\" alt=\"\" class=\"pastel_icon cabecera_" . $cabecera_count . "\">
                    ";
                    $cabecera_count += 1;
            };
            
            
            echo"</span>
            ";

            $anuncios_agencia_tot = 0;
            $eventos_tot = 0;
            $registros_tot = 0;
            $visitas_tot = 0;
            $citas_tot = 0;
            $salidas_tot = 0;

            if (!empty($array_anuncios_agencia)) {
                $anuncios_agencia_tot +=  count($array_anuncios_agencia);
            };
            if(!empty($array_eventos_personal)){
                $eventos_tot += count($array_eventos_personal);
            };
            if (!empty($array_eventos_agencia)) {
                $eventos_tot += count($array_eventos_agencia);
            };
            if (!empty($array_to_do)) {
                $eventos_tot += count($array_to_do);
            };
            if (!empty($array_count_visita)) {
                $visitas_tot += count($array_count_visita);
            };
            if (!empty($array_count_registro)) {
                $registros_tot += count($array_count_registro);
            };
            if (!empty($array_count_registro_fotografo)) {
                $registros_tot += count($array_count_registro_fotografo);
            };
            if (!empty($array_count_cita)) {
                $citas_tot += count($array_count_cita);
            };
            if (!empty($array_count_salida)) {
                $salidas_tot += count($array_count_salida);
            };


            // SE ARMA EL DAY AGENCIA SPACE
                if (($anuncios_agencia_tot > 0 && $eventos_tot > 0) && (($visitas_tot > 0 || $registros_tot > 0) || ($citas_tot > 0 || $salidas_tot > 0))) {
                    //ARRIBA LLENO y ALMENOS ALGO ABAJO
                    echo"<span class=\"day_agencia_space short\">";

                    echo"
                        <span class=\"day_agencia\">
                            <img src=\"../../objetos/icono_tutecho.svg\" alt=\"\" class=\"pastel_icon\">
                            <p class=\"spacer\">x</p>
                            <p class=\"text\">" . $anuncios_agencia_tot . "</p>
                            <p class=\"text_extra\">Anuncios</p>
                        </span>
                        <span class=\"spacer_vertical\"></span>
                        <span class=\"day_agencia\">
                            <i class=\"fas fa-exclamation-circle\"></i>
                            <p class=\"spacer\">x</p>
                            <p class=\"text\">" . $eventos_tot . "</p>
                            <p class=\"text_extra\">Eventos</p>
                        </span>
                    ";

                    echo"</span>";
                    
                }elseif ((($anuncios_agencia_tot > 0 || $eventos_tot > 0) && ($visitas_tot == 0 && $registros_tot == 0) && ($citas_tot == 0 && $salidas_tot == 0)) || ((($anuncios_agencia_tot > 0 && $eventos_tot == 0) || ($anuncios_agencia_tot == 0 && $eventos_tot > 0)) && ($visitas_tot > 0 || $registros_tot > 0 || $citas_tot > 0 || $salidas_tot > 0))) {
                    //ARRIBA ALGO Y ABAJO VACIO  -O BIEN- ARRIBA UN SOLO ELEMENTO Y ABAJO ALGO
                    echo"<span class=\"day_agencia_space large\">";

                    if ($anuncios_agencia_tot > 0) {
                        echo"
                            <span class=\"day_agencia\">
                                <img src=\"../../objetos/icono_tutecho.svg\" alt=\"\" class=\"pastel_icon\">
                                <p class=\"spacer\">x</p>
                                <p class=\"text\">" . $anuncios_agencia_tot . "</p>
                                <p class=\"text_extra\">Anuncios</p>
                            </span>
                        ";
                    };

                    echo"<span class=\"spacer_vertical\"></span>";

                    if ($eventos_tot > 0) {
                        echo"
                            <span class=\"day_agencia\">
                                <i class=\"fas fa-exclamation-circle\"></i>
                                <p class=\"spacer\">x</p>
                                <p class=\"text\">" . $eventos_tot . "</p>
                                <p class=\"text_extra\">Eventos</p>
                            </span>
                        ";
                    };

                    echo"</span>";

                };


            // SE ARMA EL DAY AGENTE SPACE 
            if ((($visitas_tot > 0 && $registros_tot > 0) || ($citas_tot > 0 && $salidas_tot > 0)) && ($anuncios_agencia_tot > 0 || $eventos_tot > 0)) {
                //ABAJO LLENO Y ARRIBA ALMENOS ALGO
                echo"<span class=\"day_agente_space short\">";

                if ($nivel_acceso == 3) {
                    echo"
                        <span class=\"day_agente\">
                            <i class=\"fas fa-user\"></i>
                            <p class=\"spacer\">x</p>
                            <p class=\"text\">" . $citas_tot . "</p>
                            <p class=\"text_extra\">Citas</p>
                        </span>
                        <span class=\"spacer_vertical\"></span>
                        <span class=\"day_agente\">
                            <i class=\"fas fa-university\"></i>
                            <p class=\"spacer\">x</p>
                            <p class=\"text\">" . $salidas_tot . "</p>
                            <p class=\"text_extra\">Salidas</p>
                        </span>
                    ";
                }else{
                    echo"
                        <span class=\"day_agente\">
                            <i class=\"fas fa-clipboard-list\"></i>
                            <p class=\"spacer\">x</p>
                            <p class=\"text\">" . $registros_tot . "</p>
                            <p class=\"text_extra\">Registros</p>
                        </span>
                        <span class=\"spacer_vertical\"></span>
                        <span class=\"day_agente\">
                            <i class=\"fas fa-user\"></i>
                            <p class=\"spacer\">x</p>
                            <p class=\"text\">" . $visitas_tot . "</p>
                            <p class=\"text_extra\">Visitas</p>
                        </span>
                    ";
                };
                

                echo"</span>";

            }elseif ((($visitas_tot > 0 || $registros_tot > 0 || $citas_tot > 0 || $salidas_tot > 0) && ($anuncios_agencia_tot == 0 && $eventos_tot == 0)) || ((($visitas_tot > 0 && $registros_tot == 0) || ($visitas_tot == 0 && $registros_tot > 0) || ($citas_tot == 0 && $salidas_tot > 0) || ($citas_tot > 0 && $salidas_tot == 0)) && ($anuncios_agencia_tot > 0 || $eventos_tot > 0))) {
            //ABAJO ALGO Y ARRIBA VACIO  -O BIEN- ABAJO UN SOLO ELEMENTO Y ARRIBA ALGO
                echo"<span class=\"day_agente_space large\">";

                if ($nivel_acceso == 3) {

                    if ($citas_tot > 0) {
                        echo"
                            <span class=\"day_agente\">
                                <i class=\"fas fa-user\"></i>
                                <p class=\"spacer\">x</p>
                                <p class=\"text\">" . $citas_tot . "</p>
                                <p class=\"text_extra\">Citas</p>
                            </span>
                        ";
                    };
        
                    echo"<span class=\"spacer_vertical\"></span>";
        
                    if ($salidas_tot > 0) {
                        echo"
                            <span class=\"day_agente\">
                                <i class=\"fas fa-university\"></i>
                                <p class=\"spacer\">x</p>
                                <p class=\"text\">" . $salidas_tot . "</p>
                                <p class=\"text_extra\">Salidas</p>
                            </span>
                        ";
                    };

                    
                }else{

                    if ($registros_tot > 0) {
                        echo"
                            <span class=\"day_agente\">
                                <i class=\"fas fa-clipboard-list\"></i>
                                <p class=\"spacer\">x</p>
                                <p class=\"text\">" . $registros_tot . "</p>
                                <p class=\"text_extra\">Registros</p>
                            </span>
                        ";
                    };
        
                    echo"<span class=\"spacer_vertical\"></span>";
        
                    if ($visitas_tot > 0) {
                        echo"
                            <span class=\"day_agente\">
                                <i class=\"fas fa-user\"></i>
                                <p class=\"spacer\">x</p>
                                <p class=\"text\">" . $visitas_tot . "</p>
                                <p class=\"text_extra\">Visitas</p>
                            </span>
                        ";
                    };
                    
                };



                echo"</span>";

            };
            
        // HANDLES DE POPUP DAY 
        }else{

            function check_acceso($expected_list){

                $return_value = '';

                foreach ($expected_list as $expected) {
                   if ($expected == $_SESSION['nivel_acceso']) {
                    $return_value = 'activo';
                   };
                };

                return $return_value;
            };

            function check_contenido($contenido){

                    if ($contenido !== '') {
                        return 'activo';
                    }else{
                        return '';
                    };
                
            };

            function check_fotografo_response($respuesta){

                if ($respuesta == '') {
                    return "<p>En espera</p>";
                }elseif($respuesta == 'true'){
                    return "<i class='fa fa-check-circle'></i>";
                }elseif ($respuesta == 'false') {
                    return "<i class='fa fa-times'></i>";
                };
            };

            function get_registro_table($referencia){
                $tabla= '';
                if (strpos($referencia, 'C') !== false) { $tabla = "casa";}
                else { if (strpos($referencia, 'D') !== false) { $tabla = "departamento";}
                  else { if (strpos($referencia, 'L') !== false) { $tabla = "local";}
                    else { if (strpos($referencia, 'T') !== false) { $tabla = "terreno";}
                          else {
                            $tabla = ''; 
                          };
                      };
                  };
              };

              return $tabla;
            };

            function check_limit_response_fotografo($fecha){

                $day_selected = new DateTime(date('d-m-Y',strtotime($fecha)));
                $today = new DateTime(date("d-m-Y", time()));

                if ($today <= $day_selected) {
                    return "activo";
                }else {
                    return "";
                };
            };

            function check_limit_response_agente($fecha){

                $day_selected = new DateTime(date('d-m-Y',strtotime($fecha)));
                $today = new DateTime(date("d-m-Y", time()));

                if ($today >= $day_selected) {
                    return "activo";
                }else {
                    return "";
                };
            };

            function check_response_edit($comparacion, $valor){

                if ($comparacion == $valor) {
                    return 'activo';
                }else {
                    return '';
                };
            };

            function get_agente_name($agente_id, $conexion){
                // SE CONSULTA EL NOMBRE DEL AGENTE SOLICITADO
                $consulta_agente_nombre =	$conexion->prepare("SELECT nombre, apellido FROM agentes WHERE id = :id");
                $consulta_agente_nombre->execute([":id" => $agente_id]);
                $agente_nombre = $consulta_agente_nombre->fetch(PDO::FETCH_ASSOC);

                $nombre = $agente_nombre['nombre'] . " " . $agente_nombre['apellido'];
                return $nombre;
            };

            $consulta_usuario_id = $conexion->prepare("SELECT id FROM agentes WHERE usuario =:usuario");
            $consulta_usuario_id->execute([":usuario" => $_SESSION['usuario']]);
            $usuario_id = $consulta_usuario_id->fetch(PDO::FETCH_ASSOC);


            echo"
        
                <span class=\"popup_contenido\">

                    <input type=\"hidden\" class=\"popup_date\" value=\"" . $date . "\">
                    <input type=\"hidden\" class=\"popup_agente_id\" value=\"" . $usuario_id['id'] . "\">
                    
                    <div class=\"popup_dia_cabecera\">

                        " . $extra . "

                        <span class=\"popup_dia_actions\">
                            <span class=\"popup_agregar_evento_btn\"><i class=\"fas fa-plus-circle\"></i></span>
                            <span class=\"cerrar_popup_dia\"><i class=\"fas fa-times\"></i></span>
                        </span>
                        
                    </div>

                        <div class=\"popup_dia_contenido\">";

                    if (!empty($array_anuncios_agencia)) {
                        echo"
                            <section>

                            <h2>Anuncios</h2>
                            <hr class=\"linea_naranja\">
                        ";

                        foreach ($array_anuncios_agencia as $key => $value) {
                            echo"
                                <span class=\"elemento_popup\" data=\"anuncios_agencia\" titulo=\"" . $value['titulo'] . "\" key=\"" . $key . "\">

                                    <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <i class=\"fa fa-circle\"></i>
                                        <p>" . $value['titulo'] . "&nbsp&nbsp" . $value['hora'] . "</p>
                                    </span>";

                                    if ($value['tipo'] == 'aniversario') {
                                       echo"
                                       <span class=\"btn_elemento_detalle\">
                                       ";
                                    }else {
                                        echo"
                                        <span class=\"btn_elemento_detalle " . check_contenido($value['descripcion']) . "\">
                                        ";
                                    };

                                    echo"
                                        <i class=\"fas fa-chevron-circle-down\"></i>
                                    </span>

                                    <span class=\"elemento_actions_wrap " . check_acceso([3, 12, 1, 11, 2]) . "\">
                                        
                                        <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                        <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                    </span>

                                    </span>";

                                    if ($value['tipo'] !== 'aniversario') {
                                        echo"<span class=\"elemento_detalle_wrap\"><p>" . $value['descripcion'] . "</p></span>";
                                    };
                                    echo"

                                </span>
                            ";
                        };
                        
                        echo "</section>";
                    };

                    if (!empty($array_eventos_agencia)) {
                        echo"
                            <section>
                                <h2>Eventos Agencia</h2>
                                <hr class=\"linea_naranja\">
                        ";

                        foreach ($array_eventos_agencia as $key => $value) {
                            echo"
                            <span class=\"elemento_popup\" data=\"eventos_agencia\" titulo=\"" . $value['titulo'] . "\"  key=\"" . $key . "\">

                                    <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <i class=\"fa fa-circle\"></i>
                                        <p>" . $value['titulo'] . "&nbsp&nbsp" . $value['hora'] . "</p>
                                    </span>";

                                    if ($value['tipo'] == 'aniversario') {
                                        echo"
                                        <span class=\"btn_elemento_detalle\">
                                        ";
                                     }else {
                                         echo"
                                         <span class=\"btn_elemento_detalle " . check_contenido($value['descripcion']) . "\">
                                         ";
                                     };
 
                                     echo"
                                         <i class=\"fas fa-chevron-circle-down\"></i>
                                     </span>

                                    <span class=\"elemento_actions_wrap " . check_acceso([3, 12, 1, 11, 2]) . "\">
                                        
                                        <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                        <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                    </span>

                                    </span>";

                                    if ($value['tipo'] !== 'aniversario') {
                                        echo"<span class=\"elemento_detalle_wrap\"><p>" . $value['descripcion'] . "</p></span>";
                                    };
                                    echo"


                                </span>
                            ";
                        };

                        echo"
                            </section>
                        ";
                    };

                    if (!empty($array_count_registro) && ($nivel_acceso == 4 || $nivel_acceso == 10)) {
                        echo"
                            <section>
                                <h2>Registros</h2>
                                <hr class=\"linea_azul\">
                        ";

                        foreach ($array_count_registro as $key => $value) {
                            echo"
                            <span class=\"elemento_popup\" data=\"registros_agente\" titulo=\"" . $value['referencia'] . "\"  key=\"" . $key . "\" hora=\"" . $value['hora'] . "\">

                                    <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <i class=\"fa fa-circle\"></i>
                                        <p>Referencia: " . $value['referencia'] . "&nbsp-&nbsp" . $value['hora'] . "</p>
                                    </span>

                                    <span class=\"btn_elemento_detalle activo\">
                                        <i class=\"fas fa-chevron-circle-down\"></i>
                                    </span>

                                    <span class=\"elemento_actions_wrap activo\">
                                        
                                        <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                        <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                    </span>

                                    </span>

                                    <span class=\"elemento_detalle_wrap\">
                                        <span class=\"respuesta_fotografo_wrap\">
                                            <p>Validación Fotógrafo: ... </p>" . check_fotografo_response($value['fotografo_check']) . "
                                        </span>

                                        <div id=\"" . $value['referencia'] . "\" class=\"boton_info_inmueble\" name=\"" . get_registro_table($value['referencia']) . "\">
                                            <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class=\"nombre\">Datos Inmueble</span></p>
                                        </div>

                                        <span class=\"validacion_tarea " . check_limit_response_agente($value['fecha']) . "\">
                                            <p>Exito Tarea:</p>
                                            <div class=\"btn_actions_agente\">
                                                <span class=\"agente_option btn_confirmar  " . check_response_edit('true', $value['exito_check']) . "\">Si</span>
                                                <span class=\"agente_option btn_rechazar  " . check_response_edit('false', $value['exito_check']) . "\">No</span>
                                            </div>
                                        </span>

                                        
                                    </span>

                                </span>
                            ";
                        };

                        echo"
                            </section>
                        ";
                    };

                    if (!empty($array_count_registro_fotografo) && ($nivel_acceso == 7)) {
                        echo"
                            <section>
                                <h2>Registros</h2>
                                <hr class=\"linea_azul\">
                        ";

                        foreach ($array_count_registro_fotografo as $key => $value) {
                            echo"
                            <span class=\"elemento_popup\" data=\"registros_agente_fotografo\" titulo=\"" . $value['referencia'] . "\"  key=\"" . $key . "\" hora=\"" . $value['hora'] . "\" agente=\"" . $value['agente_id'] . "\">

                                    <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <i class=\"fa fa-circle\"></i>
                                        <p>Referencia: " . $value['referencia'] . "&nbsp-&nbsp" . $value['hora'] . "</p>
                                    </span>

                                    <span class=\"btn_elemento_detalle activo\">
                                        <i class=\"fas fa-chevron-circle-down\"></i>
                                    </span>

                                    <span class=\"elemento_actions_wrap\">
                                        
                                        <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                        <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                    </span>

                                    </span>

                                    <span class=\"elemento_detalle_wrap\">
                                        <span class=\"info_agente\">Agente: " . get_agente_name($value['agente_id'], $conexion) . "</span>
                                        <span class=\"validacion_fotografo_wrap " . check_limit_response_fotografo($value['fecha']) . "\">
                                            <p>Validación:</p>
                                            <div class=\"btn_actions_fotografo\">
                                                <span class=\"fotografo_option btn_confirmar " . check_response_edit('true', $value['fotografo_check']) . "\">Confirmar</span>
                                                <span class=\"fotografo_option btn_rechazar " . check_response_edit('false', $value['fotografo_check']) . "\">Rechazar</span>
                                            </div>
                                        </span>
                                    </span>

                                </span>
                            ";
                        };

                        echo"
                            </section>
                        ";
                    };


                    if (!empty($array_count_cita) && $nivel_acceso == 3) {
                        echo"
                            <section>
                                <h2>Citas</h2>
                                <hr class=\"linea_azul\">
                        ";

                        foreach ($array_count_cita as $key => $value) {
                            echo"
                            <span class=\"elemento_popup\" data=\"citas_jefe\"  key=\"" . $key . "\" hora=\"" . $value['hora'] . "\">

                                    <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <i class=\"fa fa-circle\"></i>
                                        <p>Cita - " . $value['hora'] . "</p>
                                    </span>

                                    <span class=\"btn_elemento_detalle activo\">
                                        <i class=\"fas fa-chevron-circle-down\"></i>
                                    </span>

                                    <span class=\"elemento_actions_wrap activo\">
                                        
                                        <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                        <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                    </span>

                                    </span>

                                </span>
                            ";
                        };

                        echo"
                            </section>
                        ";
                    };
                    
                    if (!empty($array_count_visita) && ($nivel_acceso == 4 || $nivel_acceso == 10)) {
                       echo"
                        <section>
                        <h2>Visitas</h2>
                        <hr class=\"linea_azul\">
                       ";

                        foreach ($array_count_visita as $key => $value) {

                            if (strpos($value['referencia'], 'C') !== false) { $tabla = "casa";}
                                else { if (strpos($value['referencia'], 'D') !== false) { $tabla = "departamento";}
                                    else { if (strpos($value['referencia'], 'L') !== false) { $tabla = "local";}
                                        else { if (strpos($value['referencia'], 'T') !== false) { $tabla = "terreno";}
                                            else {$tabla = '';};
                                        };
                                    };
                                };

                            // SE CONSULTA EL ESTADO DEL BIEN
                            $consulta_estado =	$conexion->prepare("SELECT estado FROM $tabla WHERE referencia = :referencia");
                            $consulta_estado->execute([":referencia" => $value['referencia']]);
                            $estado = $consulta_estado->fetch(PDO::FETCH_ASSOC);

                            echo"
                            <span class=\"elemento_popup\" data=\"visitas_agente\" titulo=\"" . $value['referencia'] . "\"  key=\"" . $key . "\" hora=\"" . $value['hora'] . "\" tabla=\"" . $tabla . "\" estado=\"" . $estado['estado'] . "\">

                                    <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <i class=\"fa fa-circle\"></i>
                                        <p>Referencia: " . $value['referencia'] . "&nbsp-&nbsp" . $value['hora'] . "</p>
                                    </span>

                                    <span class=\"btn_elemento_detalle activo\">
                                        <i class=\"fas fa-chevron-circle-down\"></i>
                                    </span>

                                    <span class=\"elemento_actions_wrap activo\">
                                        
                                        <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                        <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                    </span>

                                    </span>

                                    <span class=\"elemento_detalle_wrap\">
                                        <span class=\"boton_ficha_inmueble\">
                                            <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class=\"nombre\">Ficha Inmueble</span></p>
                                        </span>

                                        <span class=\"validacion_tarea " . check_limit_response_agente($value['fecha']) . "\">
                                            <p>Exito Tarea:</p>
                                            <div class=\"btn_actions_agente\">
                                                <span class=\"agente_option btn_confirmar  " . check_response_edit('true', $value['exito_check']) . "\">Si</span>
                                                <span class=\"agente_option btn_rechazar  " . check_response_edit('false', $value['exito_check']) . "\">No</span>
                                            </div>
                                        </span>
                                    </span>


                                </span>
                            ";
                        };

                       echo"
                       </section>
                       ";
                    };

                    if (!empty($array_count_salida) && $nivel_acceso == 3) {
                        echo"
                         <section>
                            <h2>Salidas</h2>
                            <hr class=\"linea_azul\">
                        ";
 
                        foreach ($array_count_salida as $key => $value) {
                            echo"
                                <span class=\"elemento_popup\" data=\"salidas_jefe\"  key=\"" . $key . "\" hora=\"" . $value['hora'] . "\">

                                    <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <i class=\"fa fa-circle\"></i>
                                        <p>Salida - " . $value['hora'] . "</p>
                                    </span>

                                    <span class=\"btn_elemento_detalle activo\">
                                        <i class=\"fas fa-chevron-circle-down\"></i>
                                    </span>

                                    <span class=\"elemento_actions_wrap activo\">
                                        
                                        <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                        <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                    </span>

                                    </span>

                                    <span class=\"elemento_detalle_wrap\">
                                    
                                    </span>


                                </span>
                            ";
                        };
 
                        echo"
                        </section>
                        ";
                    };

                    if (!empty($array_eventos_personal) || !empty($array_to_do)) {
                        echo"
                            <section>
                            <h2>Eventos Personales</h2>
                            <hr class=\"linea_azul\">
                        ";

                        if (!empty($array_eventos_personal)) {
                            foreach ($array_eventos_personal as $key => $value) {
                                echo"
                                <span class=\"elemento_popup\" data=\"eventos_personal\" titulo=\"" . $value['titulo'] . "\"  key=\"" . $key . "\" tipo=\"" . $value['tipo'] . "\">
    
                                        <span class=\"elemento_header\">
    
                                        <span class=\"elemento_titulo\">
                                            <i class=\"fa fa-circle\"></i>
                                            <p>" . $value['tipo'] . " - " . $value['titulo'] . " " . $value['hora'] . "</p>
                                        </span>";
    
                                        if ($value['tipo'] == 'aniversario') {
                                            echo"
                                            <span class=\"btn_elemento_detalle\">
                                            ";
                                        }else {
                                            echo"
                                            <span class=\"btn_elemento_detalle " . check_contenido($value['descripcion']) . "\">
                                            ";
                                        };
    
                                        echo"
                                            <i class=\"fas fa-chevron-circle-down\"></i>
                                        </span>
    
                                        <span class=\"elemento_actions_wrap activo\">
                                            
                                            <i class=\"fas fa-trash-alt borrar_trash\"></i>
                                            <span class=\"confirmar_borrar\" style=\"display: none;\">BORRAR</span>
                                        </span>
    
                                        </span>";
    
                                        if ($value['tipo'] !== 'aniversario') {
                                            echo"<span class=\"elemento_detalle_wrap\"><p>" . $value['descripcion'] . "</p></span>";
                                        };
                                        echo"
    
    
                                    </span>
                                ";
                            };
                        };

                        

                        if (!empty($array_to_do)) {

                            foreach ($array_to_do as $key => $value) {
                                echo"
                                <span class=\"elemento_popup\" data=\"to_do_personal\" titulo=\"" . $value['titulo'] . "\"  key=\"" . $key . "\" hora=\"" . $value['hora'] . "\">

                                        <span class=\"elemento_header\">

                                        <span class=\"elemento_titulo\">
                                            <i class=\"fa fa-circle\"></i>
                                            <p>" . $value['titulo'] . " " . $value['hora'] . "</p>
                                        </span>

                                        <span class=\"btn_elemento_detalle activo\">
                                            <i class=\"fas fa-chevron-circle-down\"></i>
                                        </span>

                                        <span class=\"elemento_actions_wrap activo\">
                                            
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
                                                <span class=\"btn_editar_check_list\">
                                                    <i class=\"fa fa-edit\"></i>
                                                    <p>Editar</p>
                                                </span>
                                            </div>
                                            
                                         <div class=\"check_list_wrap edit\">
                                        ";
                                        
                                        foreach (json_decode($value['check_list'], true) as $check_element) {
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
                                       </div>
                                       </span>


                                    </span>
                                ";
                            };

                        };

                        echo"
                            </section>
                        ";
                    };


            echo"
                        </div>
                </span>
            ";


        };



    };

    function week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events){

        $count = 1;
        $interval = DateInterval::createFromDateString('1 day');
        $begin = DateTime::createFromFormat('d-m-Y', $start_date);
        $end = DateTime::createFromFormat('d-m-Y', $end_date);
        $period = new DatePeriod($begin, $interval, $end);

        function week_end_finder($count_day){
            if ($count_day == 6 || $count_day == 7) {
               return "week_end";
            }else{
               return "";
            }
        };

        function today_finder($test_day){
            if ($test_day == date("d-m-Y") ) {
               return "today";
            }else{
               return "";
            }
        };
        
        foreach ($period as $day) {
            
            if ($count == 1) {
                echo"
                    <div class=\"week_wrap\">
                ";
            };


            echo"
            <div id=\"" . $day->format("d-m-Y") . "\" class=\"day_wrap " . week_end_finder($count) . " " . today_finder($day->format("d-m-Y")) . " " . $lista_meses[($day->format("F"))] . "\" mes=\"" . $lista_meses[($day->format("F"))] . "\" dia=\"" . $lista_dias_nombres[$day->format("l")] . "\" year=\"" . $day->format("Y") . "\">
                <span class=\"day_num\">" . ($day->format("d")) . "</span>
                <span class=\"day_agregar_btn\"><i class=\"fas fa-plus-circle\"></i></span>";

            if ($past_events == '0') {

                $day_selected = new DateTime($day->format("d-m-Y"));
                $today = new DateTime(date("d-m-Y", time()));
                

                if ( $day_selected >= $today) {
                    day_fill_events(($day->format("d-m-Y")), $agencia_tag, $agente_id, 'tabla');
                };

            }elseif($past_events == '1'){

                day_fill_events(($day->format("d-m-Y")), $agencia_tag, $agente_id, 'tabla');

            };        
            
            echo"</div>";

           if ($count == 7) {
                echo"
                    </div>
                ";
                $count = 0;
            };

            $count += 1;

        };

    };




  if(isset($_POST["titles_requested"])){//CARGAR LOS TITULOS DE LOS DIAS DE LA SEMANA

    if ($pais_info['idioma'] == 'es') {
        $lista_dias = [1 =>'LUN', 2 => 'MAR', 3 => 'MIÉ', 4 => 'JUE', 5 => 'VIE', 6 => 'SÁB', 7 => 'DOM'];
    }elseif ($pais_info['idioma'] == 'en') {
        $lista_dias = [1 =>'MON', 2 => 'TUE', 3 => 'WED', 4 => 'THU', 5 => 'FRI', 6 => 'SAT', 7 => 'SUN'];
    };//sumar acá otros idiomas

    foreach ($lista_dias as $dia) {
        echo"
        <span class=\"titulo_dia\">" . $dia . "</span>
        ";
    };



  }elseif (isset($_POST["fecha_tag_sent"])) {//CARGAR EL CALENDARIO Y SU CONTENIDO

    $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php FROM paises WHERE pais = :pais");
    $consulta_pais_info->execute([":pais" => $_COOKIE['tutechopais']]);
    $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

    date_default_timezone_set($pais_info['time_zone_php']);

    $current_date = date("d-m-Y");

    $fecha_tag = $_POST["fecha_tag_sent"];

    if ($fecha_tag == 'hoy' && isset($_POST["agencia_tag_sent"]) && isset($_POST["agente_id_sent"]) && isset($_POST["past_events_sent"])) {

        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];
        $start_date = date('d-m-Y',strtotime('-63 day', strtotime('monday this week')));//9 semanas atras empezando en lunes
        $end_date = date('d-m-Y',strtotime('+84 day', strtotime('monday this week')));// 12 semanas adelante 
        
        week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);

    }elseif($fecha_tag == 'before' && isset($_POST["date_tag_sent"]) && isset($_POST["agencia_tag_sent"]) && isset($_POST["agente_id_sent"]) && isset($_POST["past_events_sent"])){

        $date_tag = $_POST["date_tag_sent"];
        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];
        
        $start_date = date('d-m-Y',strtotime('-35 day', strtotime($date_tag)));//5 semanas atras
        $end_date = date('d-m-Y',strtotime($date_tag));// date_tag 
        
        week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);

    }elseif ($fecha_tag == 'after' && isset($_POST["date_tag_sent"]) && isset($_POST["agencia_tag_sent"]) && isset($_POST["agente_id_sent"]) && isset($_POST["past_events_sent"])) {
        
        $date_tag = $_POST["date_tag_sent"];
        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];

        $start_date = date('d-m-Y',strtotime('+1 day', strtotime($date_tag)));//date_tag 
        $end_date = date('d-m-Y',strtotime('+35 day', strtotime($start_date)));// 3 semanas adelante

        week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);

    }elseif ($fecha_tag == 'past_month' && isset($_POST["date_tag_sent"]) && isset($_POST["agencia_tag_sent"]) && isset($_POST["agente_id_sent"]) && isset($_POST["past_events_sent"])) {

        $date_less_month = date('d-m-Y', strtotime('-1 months', strtotime($_POST["date_tag_sent"])));
        $week = date('w',strtotime($date_less_month));
        $delta = '-'.($week-1).' day';
        $monday_week = date('d-m-Y', strtotime($delta, strtotime($date_less_month)));
        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];


        $start_date = date('d-m-Y',strtotime('-63 day', strtotime($monday_week)));//9 semanas atras empezando en lunes
        $end_date = date('d-m-Y',strtotime('+84 day', strtotime($monday_week)));// 12 semanas adelante 
        
        week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);

        echo"<input type=\"hidden\" value=\"" . $date_less_month . "\" class=\"flag_day\">";

    }elseif ($fecha_tag == 'next_month' && isset($_POST["date_tag_sent"]) && isset($_POST["agencia_tag_sent"]) && isset($_POST["agente_id_sent"]) && isset($_POST["past_events_sent"])) {

        $date_more_month = date('d-m-Y', strtotime('+1 months', strtotime($_POST["date_tag_sent"])));
        $week = date('w',strtotime($date_more_month));
        $delta = '+'.(8-$week).' day';
        $monday_week = date('d-m-Y', strtotime($delta, strtotime($date_more_month)));
        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];


        $start_date = date('d-m-Y',strtotime('-63 day', strtotime($monday_week)));//9 semanas atras empezando en lunes
        $end_date = date('d-m-Y',strtotime('+84 day', strtotime($monday_week)));// 12 semanas adelante 
        
        week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);

        echo"<input type=\"hidden\" value=\"" . $date_more_month . "\" class=\"flag_day\">";



    }elseif ($fecha_tag == 'refresh_agente' && isset($_POST["date_tag_sent"]) && isset($_POST["agencia_tag_sent"]) && isset($_POST["agente_id_sent"]) && isset($_POST["past_events_sent"])) {

        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];

        if (isset($_POST['tipo_tarea_sent']) && isset($_POST['hora_tarea_sent']) && isset($_POST['referencia_sent'])) {

            $referencia = $_POST['referencia_sent'];
            $tipo_tarea = $_POST['tipo_tarea_sent'];
            $hora_tarea = $_POST['hora_tarea_sent'];

            $fecha_limite = new DateTime(date('d-m-Y',strtotime('-3 day', time())));
            $date_tag_sent = new DateTime(date('d-m-Y',strtotime($_POST['date_tag_sent'])));

            if ($date_tag_sent < $fecha_limite) {
                echo"error_fecha_limite";
            } else {

                $tabla = '';

                if ($tipo_tarea == 'registro') {
                    if (strpos($referencia, 'C') !== false) { $tabla = "borradores_casa";}
                    else { if (strpos($referencia, 'D') !== false) { $tabla = "borradores_departamento";}
                      else { if (strpos($referencia, 'L') !== false) { $tabla = "borradores_local";}
                        else { if (strpos($referencia, 'T') !== false) { $tabla = "borradores_terreno";}
                              else {$tabla = '';};
                          };
                      };
                  };
                }elseif($tipo_tarea == 'visita'){
                    if (strpos($referencia, 'C') !== false) { $tabla = "casa";}
                    else { if (strpos($referencia, 'D') !== false) { $tabla = "departamento";}
                      else { if (strpos($referencia, 'L') !== false) { $tabla = "local";}
                        else { if (strpos($referencia, 'T') !== false) { $tabla = "terreno";}
                              else {$tabla = '';};
                          };
                      };
                  };
                };
    
                if ($tabla !== '') {
                    
                    $consulta_referencia_exist = $conexion->prepare("SELECT referencia FROM $tabla WHERE referencia = :referencia");
                    $consulta_referencia_exist->execute([":referencia" => $referencia]);
                    $referencia_exist = $consulta_referencia_exist->fetch(PDO::FETCH_ASSOC);
    
                    if (!empty($referencia_exist)) {
    
                        if ($tipo_tarea == 'registro') {
        
                            if ($_SESSION['nivel_acceso'] == 4) {//agente normal
                                $new_element = [//se estructura los datos del nuevo contacto en forma de array
                                    "fecha" => $_POST['date_tag_sent'],
                                    "referencia" => $referencia,
                                    "hora" => $hora_tarea,
                                    "fotografo_check" => '',//confirmacion del fotografo, true o false
                                    "exito_check" => ''// exito en el registro true o false
                                ];
                            }elseif ($_SESSION['nivel_acceso'] == 10) {//agente express
                                $new_element = [//se estructura los datos del nuevo contacto en forma de array
                                    "fecha" => $_POST['date_tag_sent'],
                                    "referencia" => $referencia,
                                    "hora" => $hora_tarea,
                                    "fotografo_check" => true,//confirmacion del fotografo, true o false
                                    "exito_check" => ''// exito en el registro true o false
                                ];
                            };
        
                        }elseif ($tipo_tarea == 'visita') {
        
                            $new_element = [//se estructura los datos del nuevo contacto en forma de array
                                "fecha" => $_POST['date_tag_sent'],
                                "referencia" => $referencia,
                                "hora" => $hora_tarea,
                                "exito_check" => ''// exito en el registro true o false
                            ];
                        
                        };
     
                        $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
        
                        $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
                        $data_agentes_tareas = json_decode($json_agentes_tareas, true);
        
                        if (!array_key_exists($agente_id, $data_agentes_tareas)) {
                            $data_agentes_tareas[$agente_id] = array();
                            $data_agentes_tareas[$agente_id]['registro'] = array();
                            $data_agentes_tareas[$agente_id]['visita'] = array();
                        };
        
                        array_push($data_agentes_tareas[$agente_id][$tipo_tarea], $new_element);
        
                        $data_json = json_encode($data_agentes_tareas);// transformar el array en codigo json
        
                        file_put_contents($json_path_agentes_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
    
                        $week = date('w',strtotime($_POST["date_tag_sent"]));
                        if ($week == 0) {
                            $delta = '-6 day';
                        }else {
                            $delta = '-'.($week-1).' day';
                        };
                        $monday_week = date('d-m-Y', strtotime($delta, strtotime($_POST["date_tag_sent"])));
        
                        $start_date = date('d-m-Y',strtotime('-63 day', strtotime($monday_week)));//9 semanas atras empezando en lunes
                        $end_date = date('d-m-Y',strtotime('+84 day', strtotime($monday_week)));// 12 semanas adelante 
                        
                        week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);
        
                    }else {
                        echo"error";
                    };
    
                }else {
                    echo"error";
                };
    
            };

            
           
        }elseif (isset($_POST['tipo_evento_sent']) && isset($_POST['hora_evento_sent']) && isset($_POST['titulo_evento_sent']) && isset($_POST['complemento_sent'])) {

            $tipo_evento = $_POST['tipo_evento_sent'];
            $hora_evento = $_POST['hora_evento_sent'];
            $titulo_evento = $_POST['titulo_evento_sent'];
            $complemento = $_POST['complemento_sent'];

            $error = '';
            
            if ($tipo_evento == 'check_list') {

                if ($complemento == '[]') {
                    $error = 'error';
                }else{
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "fecha" => $_POST['date_tag_sent'],
                        "titulo" => $titulo_evento,
                        "hora" => $hora_evento,
                        "check_list" => $complemento
                    ];
    
                    $json_path_agente_eventos = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
                };

            }elseif ($tipo_evento == 'recordatorio' || $tipo_evento == 'comida' || $tipo_evento == 'viaje' || $tipo_evento == 'aniversario') {
                
                if ($tipo_evento == 'recordatorio' || $tipo_evento == 'comida' || $tipo_evento == 'viaje') {
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "tipo" => $tipo_evento,
                        "fecha" => $_POST['date_tag_sent'],
                        "titulo" => $titulo_evento,
                        "hora" => $hora_evento,
                        "descripcion" => $complemento
                    ];
                } else if ($tipo_evento == 'aniversario'){
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "tipo" => $tipo_evento,
                        "fecha" => $_POST['date_tag_sent'],
                        "titulo" => $titulo_evento,
                        "hora" => $hora_evento,
                        "recurrente" => $complemento
                    ];
                };

                $json_path_agente_eventos = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/eventos_personal.json';
                
            };

            if ($error == 'error') {
                echo"error";
            }else{
                 // We open the Json and decode its contents into an array
                $json_agente_eventos = file_get_contents($json_path_agente_eventos);
                $data_agente_eventos = json_decode($json_agente_eventos, true);

                // We push the new element and save the modified json
                array_push($data_agente_eventos, $new_element);
                $data_json = json_encode($data_agente_eventos);// transformar el array en codigo json
                file_put_contents($json_path_agente_eventos, $data_json); // FINALMENTE se guarda el data en un Json file


                $week = date('w',strtotime($_POST["date_tag_sent"]));
                if ($week == 0) {
                    $delta = '-6 day';
                }else {
                    $delta = '-'.($week-1).' day';
                };
                $monday_week = date('d-m-Y', strtotime($delta, strtotime($_POST["date_tag_sent"])));

                $start_date = date('d-m-Y',strtotime('-63 day', strtotime($monday_week)));//9 semanas atras empezando en lunes
                $end_date = date('d-m-Y',strtotime('+84 day', strtotime($monday_week)));// 12 semanas adelante 
                
                week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);
            };

           


        };   
        
    }elseif($fecha_tag == 'refresh_jefe_local' && isset($_POST["date_tag_sent"]) && isset($_POST["agencia_tag_sent"]) && isset($_POST["agente_id_sent"]) && isset($_POST["past_events_sent"])){

        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];

        if (isset($_POST['tipo_tarea_sent']) && isset($_POST['hora_tarea_sent']) && isset($_POST['descripcion_tarea_sent'])) {

            $descripcion = $_POST['descripcion_tarea_sent'];
            $tipo_tarea = $_POST['tipo_tarea_sent'];
            $hora_tarea = $_POST['hora_tarea_sent'];

            $fecha_limite = new DateTime(date('d-m-Y',strtotime('-3 day', time())));
            $date_tag_sent = new DateTime(date('d-m-Y',strtotime($_POST['date_tag_sent'])));

            if ($date_tag_sent < $fecha_limite) {
                echo"error_fecha_limite";
            } else {

                if ($tipo_tarea == 'cita') {
                
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "fecha" => $_POST['date_tag_sent'],
                        "descripcion" => $descripcion,
                        "hora" => $hora_tarea,
                        "exito_check" => ''// exito en el registro true o false
                    ];               
    
                }elseif ($tipo_tarea == 'salida') {
    
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "fecha" => $_POST['date_tag_sent'],
                        "descripcion" => $descripcion,
                        "hora" => $hora_tarea,
                        "exito_check" => ''// exito en el registro true o false
                    ];
                
                };
    
                $json_path_jefe_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/jefe_tareas.json';
        
                $json_jefe_tareas = file_get_contents($json_path_jefe_tareas);
                $data_jefe_tareas = json_decode($json_jefe_tareas, true);
    
                array_push($data_jefe_tareas[$tipo_tarea], $new_element);
    
                $data_json = json_encode($data_jefe_tareas);// transformar el array en codigo json
    
                file_put_contents($json_path_jefe_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
    
                $week = date('w',strtotime($_POST["date_tag_sent"]));
                if ($week == 0) {
                    $delta = '-6 day';
                }else {
                    $delta = '-'.($week-1).' day';
                };
                $monday_week = date('d-m-Y', strtotime($delta, strtotime($_POST["date_tag_sent"])));
    
                $start_date = date('d-m-Y',strtotime('-63 day', strtotime($monday_week)));//9 semanas atras empezando en lunes
                $end_date = date('d-m-Y',strtotime('+84 day', strtotime($monday_week)));// 12 semanas adelante 
                
                week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);
            };

            


        }elseif (isset($_POST['modo_sent']) && isset($_POST['tipo_evento_sent']) && isset($_POST['hora_evento_sent']) && isset($_POST['titulo_evento_sent']) && isset($_POST['complemento_sent'])) {

            $modo_evento = $_POST['modo_sent'];
            $tipo_evento = $_POST['tipo_evento_sent'];
            $hora_evento = $_POST['hora_evento_sent'];
            $titulo_evento = $_POST['titulo_evento_sent'];
            $complemento = $_POST['complemento_sent'];

            $error = '';
            
            if ($tipo_evento == 'check_list') {

                if ($complemento == '[]') {
                    $error = 'error';
                }else{
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "fecha" => $_POST['date_tag_sent'],
                        "titulo" => $titulo_evento,
                        "hora" => $hora_evento,
                        "check_list" => $complemento
                    ];
    
                    $json_path_eventos = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
                };

            }elseif ($tipo_evento == 'anuncio' || $tipo_evento == 'recordatorio' || $tipo_evento == 'comida' || $tipo_evento == 'viaje' || $tipo_evento == 'aniversario') {
                
                if ($tipo_evento == 'anuncio' || $tipo_evento == 'recordatorio' || $tipo_evento == 'comida' || $tipo_evento == 'viaje') {
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "tipo" => $tipo_evento,
                        "fecha" => $_POST['date_tag_sent'],
                        "titulo" => $titulo_evento,
                        "hora" => $hora_evento,
                        "descripcion" => $complemento
                    ];
                } else if ($tipo_evento == 'aniversario'){
                    $new_element = [//se estructura los datos del nuevo contacto en forma de array
                        "tipo" => $tipo_evento,
                        "fecha" => $_POST['date_tag_sent'],
                        "titulo" => $titulo_evento,
                        "hora" => $hora_evento,
                        "recurrente" => $complemento
                    ];
                };

                if ($modo_evento == 'agencia') {
                    $json_path_eventos = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/eventos.json';
                }elseif ($modo_evento == 'personal') {
                    $json_path_eventos = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/eventos_personal.json';
                };
                
            };

            if ($error == 'error') {
                echo"error";
            }else{
                 // We open the Json and decode its contents into an array
                $json_eventos = file_get_contents($json_path_eventos);
                $data_eventos = json_decode($json_eventos, true);

                // We push the new element and save the modified json
                if ($modo_evento == 'agencia') {
                    if ($tipo_evento == 'anuncio') {
                        array_push($data_eventos['anuncio'], $new_element);
                    }else{
                        array_push($data_eventos['evento'], $new_element);
                    };
                }elseif ($modo_evento == 'personal') {
                    array_push($data_eventos, $new_element);
                };

                $data_json = json_encode($data_eventos);// transformar el array en codigo json
                file_put_contents($json_path_eventos, $data_json); // FINALMENTE se guarda el data en un Json file

                $week = date('w',strtotime($_POST["date_tag_sent"]));
                if ($week == 0) {
                    $delta = '-6 day';
                }else {
                    $delta = '-'.($week-1).' day';
                };
                $monday_week = date('d-m-Y', strtotime($delta, strtotime($_POST["date_tag_sent"])));

                $start_date = date('d-m-Y',strtotime('-63 day', strtotime($monday_week)));//9 semanas atras empezando en lunes
                $end_date = date('d-m-Y',strtotime('+84 day', strtotime($monday_week)));// 12 semanas adelante 
                
                week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);
            };



        };



    }elseif ($fecha_tag == 'get_agentes' && isset($_POST['agencia_id_sent'])) {

        $agencia_id = $_POST['agencia_id_sent'];
        
        $consulta_agentes = $conexion->prepare("SELECT id, nombre, apellido FROM agentes WHERE agencia_id = :agencia_id AND activo = 1 AND nivel_acceso != 8 AND nivel_acceso != 9");
        $consulta_agentes->execute([":agencia_id" => $agencia_id]);
        $agentes = $consulta_agentes->fetchAll(PDO::FETCH_ASSOC);

        $consulta_admin_id = $conexion->prepare("SELECT id FROM agentes WHERE usuario =:usuario");
        $consulta_admin_id->execute([":usuario" => $_SESSION['usuario']]);
        $admin_id = $consulta_admin_id->fetch(PDO::FETCH_ASSOC);

        if ($_SESSION['nivel_acceso'] == 1 || $_SESSION['nivel_acceso'] == 11) {
            echo"
                <option value=\"\"></option>
                <option value=\"" . $admin_id['id'] . "\">ADMIN</option>
            ";
        } else if ($_SESSION['nivel_acceso'] == 12){
            echo"
                <option value=\"\"></option>
                <option value=\"" . $admin_id['id'] . "\">Jefe Central</option>
            ";
        } else {
            echo"
                <option value=\"\"></option>
            ";
        };
        

        foreach ($agentes as $agente) {
            echo"
                <option value=\"" . $agente['id'] . "\">" . $agente['nombre'] . " " . $agente['apellido'] . "</option>
            ";
        };


    }elseif ($fecha_tag == 'popup_dia' && isset($_POST['agencia_tag_sent']) && isset($_POST['agente_id_sent']) && isset($_POST['fecha_selected_sent']) && isset($_POST['titulo_h1_sent']) && isset($_POST['titulo_h4_sent'])) {

        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];

        $fecha_selected = $_POST['fecha_selected_sent'];
        $titulo_h1 = $_POST['titulo_h1_sent'];
        $titulo_h4 = $_POST['titulo_h4_sent'];

        $cabecera_popup = "<span class=\"titulo_day\"><h1>" . $titulo_h1 . "</h1><h4>" . $titulo_h4 . "</h4></span>"; 

        day_fill_events((date('d-m-Y',strtotime($fecha_selected))), $agencia_tag, $agente_id, $cabecera_popup);


    }elseif ($fecha_tag == 'borrar_elemento' && isset($_POST['agencia_tag_sent']) && isset($_POST['agente_id_sent']) && isset($_POST['past_events_sent']) && isset($_POST['extra_sent'])) {
        
        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];

        $extra = $_POST['extra_sent'];
        $tipo_elemento = $extra['elemento'];
        $key_elemento = $extra['key'];
        $fecha_actual = $extra['fecha_actual'];

        // SE LISTA LOS PATHS DE LOS DIFERENTES JSON QUE HAY QUE CONSULTAR
        $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
        $json_path_eventos_personales = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/eventos_personal.json';

        $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
        $json_path_eventos_agencia = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/eventos.json';

        $json_path_jefe_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/jefe_tareas.json';

        $exito = '';

        if ($tipo_elemento == 'anuncios_agencia') {
            $titulo = $extra['titulo'];

            $json = file_get_contents($json_path_eventos_agencia);
            $data = json_decode($json, true);
        
            $anuncio = $data['anuncio'][$key_elemento];
            if($anuncio['fecha'] == $fecha_actual && $anuncio['titulo'] == $titulo) {
                unset($data['anuncio'][$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_eventos_agencia, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };
        
        }else if($tipo_elemento == 'eventos_agencia'){
            $titulo = $extra['titulo'];

            $json = file_get_contents($json_path_eventos_agencia);
            $data = json_decode($json, true);
        
            $evento = $data['evento'][$key_elemento];
            if($evento['fecha'] == $fecha_actual && $evento['titulo'] == $titulo) {
                unset($data['evento'][$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_eventos_agencia, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };

        }else if($tipo_elemento == 'registros_agente'){
            $referencia = $extra['referencia'];//dentro de 'titulo'
            $hora =  $extra['hora'];

            $json = file_get_contents($json_path_agentes_tareas);
            $data = json_decode($json, true);
        
            $registro = $data[$agente_id]['registro'][$key_elemento];
            if($registro['fecha'] == $fecha_actual && $registro['referencia'] == $referencia && $registro['hora'] == $hora) {
                unset($data[$agente_id]['registro'][$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_agentes_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };

        }else if($tipo_elemento == 'citas_jefe'){
            $hora =  $extra['hora'];

            $json = file_get_contents($json_path_jefe_tareas);
            $data = json_decode($json, true);
        
            $cita = $data['cita'][$key_elemento];
            if($cita['fecha'] == $fecha_actual && $cita['hora'] == $hora) {
                unset($data['cita'][$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_jefe_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };

        }else if($tipo_elemento == 'visitas_agente'){
            $referencia = $extra['referencia'];//dentro de 'titulo'
            $hora =  $extra['hora'];

            $json = file_get_contents($json_path_agentes_tareas);
            $data = json_decode($json, true);
        
            $visita = $data[$agente_id]['visita'][$key_elemento];
            if($visita['fecha'] == $fecha_actual && $visita['referencia'] == $referencia && $visita['hora'] == $hora) {
                unset($data[$agente_id]['visita'][$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_agentes_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };

        }else if($tipo_elemento == 'salidas_jefe'){
            $hora =  $extra['hora'];

            $json = file_get_contents($json_path_jefe_tareas);
            $data = json_decode($json, true);

            $salida = $data['salida'][$key_elemento];
            if($salida['fecha'] == $fecha_actual && $salida['hora'] == $hora) {
                unset($data['salida'][$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_jefe_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };

        }else if($tipo_elemento == 'eventos_personal'){
            $titulo = $extra['titulo'];
            $tipo = $extra['tipo'];

            $json = file_get_contents($json_path_eventos_personales);
            $data = json_decode($json, true);
        
            $evento = $data[$key_elemento];
            if($evento['fecha'] == $fecha_actual && $evento['titulo'] == $titulo && $evento['tipo'] == $tipo) {
                unset($data[$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_eventos_personales, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };

        }else if($tipo_elemento == 'to_do_personal'){
            $titulo = $extra['titulo'];
            $hora =  $extra['hora'];

            $json = file_get_contents($json_path_to_do_lists);
            $data = json_decode($json, true);
        
            $to_do = $data[$key_elemento];
            if($to_do['fecha'] == $fecha_actual && $to_do['titulo'] == $titulo && $to_do['hora'] == $hora) {
                unset($data[$key_elemento]);

                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_to_do_lists, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };
        };

        echo $exito;

        if ($exito !== "error") {
            $week = date('w',strtotime($fecha_actual));
            if ($week == 0) {
                $delta = '-6 day';
            }else {
                $delta = '-'.($week-1).' day';
            };
            $monday_week = date('d-m-Y', strtotime($delta, strtotime($fecha_actual)));
    
            $start_date = date('d-m-Y',strtotime('-63 day', strtotime($monday_week)));//9 semanas atras empezando en lunes
            $end_date = date('d-m-Y',strtotime('+84 day', strtotime($monday_week)));// 12 semanas adelante 
            
            week_constructor($start_date, $end_date, $lista_meses, $lista_dias_nombres, $agencia_tag, $agente_id, $past_events);
        };

        



    }elseif ($fecha_tag == 'respuesta_fotografo' && isset($_POST['agencia_tag_sent']) && isset($_POST['agente_id_sent']) && isset($_POST['past_events_sent']) && isset($_POST['respuesta_sent']) && isset($_POST['registrador_sent']) && isset($_POST['fecha_sent']) && isset($_POST['hora_sent']) && isset($_POST['referencia_sent'])) {
        
        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];

        $respuesta_fotografo = $_POST['respuesta_sent'];
        $registrador = $_POST['registrador_sent'];
        $fecha_actual = $_POST['fecha_sent'];
        $hora = $_POST['hora_sent'];
        $referencia = $_POST['referencia_sent'];
       

        $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';


        $json = file_get_contents($json_path_agentes_tareas);
        $data = json_decode($json, true);

        
        $registros = $data[$registrador]['registro'];

        foreach ($registros as $key => $registro) {

            if($registro['fecha'] == $fecha_actual && $registro['referencia'] == $referencia && $registro['hora'] == $hora) {
                $data[$registrador]['registro'][$key]['fotografo_check'] = $respuesta_fotografo;
    
                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_agentes_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };
            
        };

        

    }elseif ($fecha_tag == 'exito_tarea' && isset($_POST['agencia_tag_sent']) && isset($_POST['agente_id_sent']) && isset($_POST['past_events_sent']) && isset($_POST['respuesta_sent']) && isset($_POST['tipo_sent']) && isset($_POST['fecha_sent']) && isset($_POST['hora_sent']) && isset($_POST['referencia_sent'])) {
        
        $agencia_tag = $_POST["agencia_tag_sent"];
        $agente_id = $_POST["agente_id_sent"];
        $past_events = $_POST["past_events_sent"];

        $respuesta_agente = $_POST['respuesta_sent'];
        $tipo = $_POST['tipo_sent'];
        $fecha_actual = $_POST['fecha_sent'];
        $hora = $_POST['hora_sent'];
        $referencia = $_POST['referencia_sent'];

        if ($tipo == 'visitas_agente') {
            $categoria = 'visita';
        }elseif($tipo == 'registros_agente') {
            $categoria = 'registro';
        };
       

        $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';


        $json = file_get_contents($json_path_agentes_tareas);
        $data = json_decode($json, true);

        
        $tareas = $data[$agente_id][$categoria];

        foreach ($tareas as $key => $tarea) {

            if($tarea['fecha'] == $fecha_actual && $tarea['referencia'] == $referencia && $tarea['hora'] == $hora) {
                $data[$agente_id][$categoria][$key]['exito_check'] = $respuesta_agente;
    
                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_agentes_tareas, $data_json); // FINALMENTE se guarda el data en un Json file
            }else {
                $exito = "error";
            };
            
        };

        

    } elseif ($fecha_tag == 'check_element' && isset($_POST['agente_id_sent']) && isset($_POST['action_sent']) && isset($_POST['key_check_sent']) && isset($_POST['fecha_sent']) && isset($_POST['key_to_do_sent']) && isset($_POST['titulo_sent'])) {
        
        $agente_id = $_POST["agente_id_sent"];
        $action = $_POST['action_sent'];
        $to_do_key = $_POST['key_to_do_sent'];
        $key_elemento = $_POST['key_check_sent'];
        $fecha = $_POST['fecha_sent'];
        $titulo = $_POST['titulo_sent'];

        $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
        $json = file_get_contents($json_path_to_do_lists);
        $data = json_decode($json, true);
        
        $to_do = $data[$to_do_key];
        if($to_do['fecha'] == $fecha && $to_do['titulo'] == $titulo) {
            $array_edit = json_decode($data[$to_do_key]['check_list'], true);
            $array_edit[$key_elemento]['checked'] = $action;
            
            $array_encoded = json_encode($array_edit);
            $data[$to_do_key]['check_list'] = $array_encoded;
            
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

    }elseif ($fecha_tag == 'edit_to_do' && isset($_POST['agente_id_sent']) && isset($_POST['to_do_json_sent']) && isset($_POST['fecha_sent']) && isset($_POST['key_to_do_sent']) && isset($_POST['titulo_sent'])) {
        
        $agente_id = $_POST["agente_id_sent"];
        $to_do_key = $_POST['key_to_do_sent'];
        $fecha = $_POST['fecha_sent'];
        $titulo = $_POST['titulo_sent'];
        $json_to_do = $_POST['to_do_json_sent'];

        $json_path_to_do_lists = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/to_do_list.json';
        $json = file_get_contents($json_path_to_do_lists);
        $data = json_decode($json, true);
        
        $to_do = $data[$to_do_key];
        if ($to_do['fecha'] == $fecha && $to_do['titulo'] == $titulo) {
            
            $data[$to_do_key]['check_list'] = $json_to_do;

            $data_json = json_encode($data);// transformar el array en codigo json
            file_put_contents($json_path_to_do_lists, $data_json); // FINALMENTE se guarda el data en un Json file
            echo"exito";
        } else {
            echo"error";
        };


    };
  };
  

};

?>
