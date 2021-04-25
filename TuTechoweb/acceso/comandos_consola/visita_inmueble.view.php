<?php
if(isset($_SESSION['usuario'])){} else{header('Location: ../acceso.php');} //para evitar que alguien entre directamente al .view.php y porque en los view no se abre ninguna session

?>
<!DOCTYPE html>
<html lang="es">
 <head>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#2d89ef">
<meta name="theme-color" content="#ffffff">
      <title>Visita Inmueble</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/ficha_bien_detalle_inmueble.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider_bien_individual.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="../../css/visita_inmueble.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>

      <script>
        var viewer_mode;
        var new_texture;
        var sphere;
        var current_foto = '';
        var func_abrir_viewer;
        var func_cerrar_viewer;
        var func_cerrar_tooltip;
        var func_abrir_menu_derecho;
        var func_entrar_tour_vr;
        var func_cargar_next_foto;

        const agencia_tag_default = '<?= $agencia_tag ?>';
        const agente_id_default = '<?= $agente_id['id'] ?>';
        const visita_key_default = '<?= $visita_key ?>';
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/webgl_tester.js"></script>
      <script src="../../js/three.js"></script>
      <script src="../../js/TweenLite.min.js"></script>
      <script type="text/javascript" src="../../js/jquery.ui.touch-punch.js"></script>
      <script type="text/javascript" src="../../js/dragable_feature_overflow.js"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/popup_ficha_bien_detalle_inmueble.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
      <script src="../../js/visita_inmueble.js"></script>
     
 </head>
 <body>

 <div id="fondo"></div>

    <div id="contenedor_total">

    <div class="ficha_bien_container">

        <div class="overlay_media_viewer">
          <div class="tutorial_vrviewer overlay_tutorial_vr">
            <div class="tutorial_vr_popup">
              <span class="tutorial_logo_container"><img src="../../objetos/logo_tutecho_white.svg" alt="TuTecho.com"></span>
              <h3>TU HOGAR EN VISITA VIRTUAL</h3>
              <h4>Antes de empezar:</h4>
              <span class="tutorial_dot_explain"><img src="../../objetos/dot_orange.svg" alt="DOT"><p><b>PUNTOS NARANJA:</b> Úsualos para moverte de un espacio a otro</p></span>
              <span class="tutorial_dot_explain"><img src="../../objetos/dot_blue.svg" alt="DOT"><p><b>PUNTOS AZULES:</b> Te informan acerca de tu entorno</p></span>
              <span class="btn_entrar_tour_vr">ENTRAR</span>
            </div>
          </div>
          <span class="media_viewer_cerrar" title="Cerrar"><i class="fa fa-times"></i></span>
          <span class="tutorial_360viewer tutorial_cerrar"><i class="fa fa-arrow-up"></i><p>SALIDA</p></span>
          <span class="tutorial_360viewer tutorial_galeria_pc"><p>GALERIA</p><i class="fa fa-arrow-right"></i></span>
          <span class="tutorial_360viewer tutorial_galeria_mobile"><p>GALERIA</p><i class="fa fa-arrow-down"></i></span>

          <div class="viewer_tooltip"></div>

          <div class="viewer_tooltip_content">
            <span class="tooltip_cerrar"><i class="fa fa-times-circle"></i></span>
            <span class="imagen_opcional_container"></span>
            <span class="tooltip_text"></span>
          </div>

          <div id="media_viewer_container" class="media_viewer_container"></div>

          <div id="control_right_container" class="control_right_container">
            <span class="btn_abrir_right" title="Galeria Fotos">
              <img src="../../objetos/fotos_icon.svg" alt="fotos">
            </span>
            <div class="control_right">
              <span class="encabezado">Galeria Fotos</span>
              <div class="fotos_gran_container">

              </div>

            </div>

          </div>

        </div>

        <div id="popup_ficha_bien" class="popup_ficha_bien">

        </div>
      </div>

        <!-- BARRA DE NAVEGACION -->

      	  <header>
            <div class="regreso_boton_div_contenedor">
                <a href="visita_inmueble_consola.php">
                <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
                <span class="atras_texto"><p>VOLVER ATRÁS</p></span>
                </a>
            </div>
            <div class="actions_visita_wrap">
                <span class="visita_fail_btn <?php if($datos_visita['exito_check'] == 0){echo"activo";}; ?>" title="Visita Fallida"><i class="fas fa-times"></i></span>
                <span class="visita_exito_btn <?php if($datos_visita['exito_check'] == 1){echo"activo";}; ?>" title="Visita Exitosa"><i class="far fa-check-circle"></i></span>
            </div>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="../../index.php"><img src="../../objetos/logotipo2.svg" alt="TuTecho.com" class="menu_logo"></a></li>
                </ul>
            </div>
      	    <nav class="scroll">
      	     <div id="menu">
                <ul class="ulmenu">
                  <li class="logo_element limenu"><a href="../../index.php" class="a_menu"><img src="../../objetos/logotipo2.svg" alt="Tu Techo.com" class="logo_img"></a></li>
                </ul>
             </div>
           </nav>
           
          </header>

        <!-- CONTENIDO -->

        <div class="contenido_wrap">

        <?php

            if ($tabla == 'terreno') {
                echo "<div class=\"cabecera_ficha terreno\">";
                }else {
                echo "<div class=\"cabecera_ficha\">";
                };
                echo "<div class=\"cabecera_parte1\">
                    <div class=\"cabecera_parte1_left\">
                ";

                if ($tabla == 'casa') {
                    echo"
                        <span class=\"fa fa-home\"></span>
                    ";
                }elseif ($tabla == "departamento") {
                    echo"
                        <span class=\"fa fa-building\"></span>
                    ";
                }elseif ($tabla == "local") {
                    echo"
                        <span class=\"fa fa-shopping-bag\"></span>
                    ";
                }elseif ($tabla = "terreno") {
                    echo"
                        <span class=\"fa fa-tree\"></span>
                    ";
                };

                    echo"<span class=\"precio_cabecera\">" . number_format($inmueble['precio'], 0, '.', ' ') . "&nbsp" . $pais_info['moneda'] . "&nbsp" . $pais_info['moneda_code'] . "</span>
                    <span class=\"pre_venta_cabecera\" style=\"" . ($inmueble['pre_venta'] == 1 ? 'display: block' : 'display: none') . "\">Pre-Venta</span>
                    <span class=\"pre_venta_cabecera\" style=\"" . ($inmueble['anticretico'] == 1 ? 'display: block' : 'display: none') . "\">Anticretico</span>
                    <span class=\"exclusivo_cabecera\" style=\"" . ($inmueble['exclusivo'] == 1 ? 'display: block' : 'display: none') . "\">Exclusivo</span>";
                    if ($tabla == 'terreno') {
                    echo "<span class=\"resumen_superficie_tag_terreno\">" . ($inmueble['superficie_terreno_medida'] == 'mÂ²' ? number_format(ceil($inmueble['superficie_terreno']), 0, '.', ' ') : number_format(($inmueble['superficie_terreno']/10000), 1, '.', ' ')) . " " . ($inmueble['superficie_terreno_medida'] == 'mÂ²' ? ' m<sup>2</sup>' : ' Hect') . "</span>";
                    };
                echo"
                    </div>
                    <div class=\"cabecera_parte1_right\">" . $datos_visita['fecha'] . "&nbsp&nbsp<i class=\"far fa-clock\"></i>&nbsp" . $datos_visita['hora'] . "</div>
                    
                </div>
                <div class=\"timer_visita_wrap\">
                    <span class=\"timer_controls\">
                        <i class=\"fas fa-sort-up timer_up\"></i>
                        <i class=\"fas fa-sort-down timer_down\"></i>
                    </span>
                    <span class=\"timer_visita ";
                        if($datos_visita['tiempo'] !== ''){
                            echo "terminado";
                        };
                    echo"\">
                        <i class=\"fas fa-stopwatch\"></i>
                        <p class=\"timer_visita_count\">";
                        if($datos_visita['tiempo'] !== ''){
                            echo $datos_visita['tiempo'];
                        }else{
                            echo "0";
                        };
                        echo"</p>
                        <p>Minutos</p>
                    </span>
                    <span class=\"reset_timer_buton\" ";
                    if($datos_visita['tiempo'] !== ''){
                        echo "style='visibility: hidden'";
                    };
                    echo">
                        <i class=\"fas fa-undo-alt\"></i>
                    </span>
                </div>
                <div class=\"resumen_parte2\">";
                if ($tabla !== 'terreno') {
                    echo "<span class=\"resumen_superficie_tag\">" . number_format(ceil($inmueble['superficie_inmueble']), 0, '.', ' ') . " m<sup>2</sup></span>";
                };
                    if ($tabla == 'casa' || $tabla == 'departamento') {
                    echo "<span class=\"resumen_dormitorios_tag\"><img src=\"../../objetos/bed_icon.svg\" alt=\"Dormitorios: \">x" . $inmueble['dormitorios'] . "</span>";
                    };
                    if ($tabla == 'casa' || $tabla == 'departamento' || $tabla == 'local') {
                    echo "<span class=\"resumen_parqueos_tag\"><img src=\"../../objetos/car_icon.svg\" alt=\"Parqueos: \">x" . $inmueble['parqueos'] . "</span>";
                    };

                echo"</div>
                </div>

                <div id=\"mapa_contenedor\" class=\"mapa_contenedor\">
                    <a href=\"https://www.google.com/maps/search/?api=1&query=" . $inmueble['mapa_coordenada_lat'] . "," . $inmueble['mapa_coordenada_lng'] . "\" target=\"_blank\" class=\"google_map_btn\" title=\"Ir a Google Maps\"><img src=\"../../objetos/google_map_icon.svg\" alt=\"Google Map\"></a>
                    <div class=\"wrap_mapa_popup_agencia\">
                        <div id=\"mapid_config\" style=\"min-height: 30em; position: relative; width: 100%; height: 100%\"></div>
                    </div>
                    <input type=\"hidden\" id=\"mapa_lat\" name=\"mapa_lat\" value=\"" . $inmueble['mapa_coordenada_lat'] . "\">
                    <input type=\"hidden\" id=\"mapa_lng\" name=\"mapa_lng\" value=\"" . $inmueble['mapa_coordenada_lng'] . "\">
                    <input type=\"hidden\" id=\"mapa_zoom\" name=\"mapa_zoom\" value=\"" . $inmueble['mapa_zoom'] . "\">
                </div>
                ";

            ?>

                
            <div class="direccion_ficha">
                <p><?php echo ucfirst($inmueble['ciudad']) . " - " . ucfirst($inmueble['direccion']) . "</p><p>" . ucfirst($inmueble['direccion_complemento']); ?></p>
            </div>

            <div class="btn_ficha_inmueble_wrap" id_agente="<?= $agente_id['id'] ?>" estado="<?= $inmueble['estado'] ?>" tabla="<?= $inmueble['tipo_bien'] ?>" referencia="<?= $inmueble['referencia'] ?>">
                <span class="btn_ficha_inmueble"><i class="fas fa-search" aria-hidden="true"></i><p>Ver Ficha Inmueble</p></span>
            </div>

            <div class="agentes_container">
                <?php
                
                        //CONTACTO VISITANTE
                        echo"
                        <div class=\"agente_wrap disponible\">
                            <p class=\"contacto_title\">Visitante</p>
                            <img src=\"../../objetos/hombre_icono_min_blue.svg?t=" . time() . "\" alt=\"Foto\" class=\"foto_agente\">
                            <span class=\"info_agente_wrap\">
                            <p class=\"nombre_agente\">" . $datos_visita['visitante_nombre'] . "</p>";
                            if ($datos_visita['visitante_telefono'] !== '') {
                                echo"
                                <span class=\"contacto_agente\">
                                    <span class=\"fa-stack icon_stacks_whatsapp\">
                                    <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                                    <i class=\"fa fa-circle\"></i>
                                    </span>
                                    <p class=\"agente_telefono\">" . $datos_visita['visitante_telefono'] . "</p>
                                </span>
                                <span class=\"call_btns_wrap\">
                                    <a class=\"contacto_call_btn\" href=\"tel:" . getNumberFormat($datos_visita['visitante_telefono']) . "\"><p>Llamar</p></a>
                                    <a class=\"contacto_whatsapp_btn\" href=\"https://api.whatsapp.com/send?phone=" . getNumberFormat($datos_visita['visitante_telefono']) . "\" target=\"_blank\">
                                        <span class=\"fa-stack icon_stacks_whatsapp\">
                                            <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                                            <i class=\"fa fa-circle\"></i>
                                        </span>
                                        <p>WhatsApp</p>
                                    </a>
                                </span>
                                ";
                            };
                            
                            echo"
                        </div>
                        ";



                        //CONTACTO PROPIETARIO
                        echo"
                        <div class=\"agente_wrap disponible\">
                            <p class=\"contacto_title\">Contacto</p>
                            <img src=\"../../objetos/hombre_icono_min_blue.svg?t=" . time() . "\" alt=\"Foto\" class=\"foto_agente\">
                            <span class=\"info_agente_wrap\">
                            <p class=\"nombre_agente\">" . $inmueble['propietario_nombre'] . " " . $inmueble['propietario_apellido'] . "</p>";
                            if ($inmueble['propietario_telefono'] !== '') {
                                echo"
                                <span class=\"contacto_agente\">
                                    <span class=\"fa-stack icon_stacks_whatsapp\">
                                    <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                                    <i class=\"fa fa-circle\"></i>
                                    </span>
                                    <p class=\"agente_telefono\">" . $inmueble['propietario_telefono'] . "</p>
                                </span>
                                <span class=\"call_btns_wrap\">
                                    <a class=\"contacto_call_btn\" href=\"tel:" . getNumberFormat($inmueble['propietario_telefono']) . "\"><p>Llamar</p></a>
                                    <a class=\"contacto_whatsapp_btn\" href=\"https://api.whatsapp.com/send?phone=" . getNumberFormat($inmueble['propietario_telefono']) . "\" target=\"_blank\">
                                        <span class=\"fa-stack icon_stacks_whatsapp\">
                                            <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                                            <i class=\"fa fa-circle\"></i>
                                        </span>
                                        <p>WhatsApp</p>
                                    </a>
                                </span>
                                ";
                            };
                            
                            echo"
                        </div>
                        ";

                        //CONTACTO REGISTRADOR
                        echo"
                        <div class=\"agente_wrap disponible\">
                            <p class=\"contacto_title\">Registrador</p>
                            <img src=\"../../agentes/" . $_COOKIE['tutechopais'] . "/" . $registrador['id'] . "/foto_blanco.jpg?t=" . time() . "\" alt=\"Foto\" class=\"foto_agente\">
                            <span class=\"info_agente_wrap\">
                            <p class=\"nombre_agente\">" . $registrador['nombre'] . " " . $registrador['apellido'] . "</p>";
                            if ($registrador['contacto'] !== '') {
                                echo"
                                <span class=\"contacto_agente\">
                                    <span class=\"fa-stack icon_stacks_whatsapp\">
                                    <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                                    <i class=\"fa fa-circle\"></i>
                                    </span>
                                    <p class=\"agente_telefono\">" . $registrador['contacto'] . "</p>
                                </span>
                                <span class=\"call_btns_wrap\">
                                    <a class=\"contacto_call_btn\" href=\"tel:" . getNumberFormat($registrador['contacto']) . "\"><p>Llamar</p></a>
                                    <a class=\"contacto_whatsapp_btn\" href=\"https://api.whatsapp.com/send?phone=" . getNumberFormat($registrador['contacto']) . "\" target=\"_blank\">
                                        <span class=\"fa-stack icon_stacks_whatsapp\">
                                            <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                                            <i class=\"fa fa-circle\"></i>
                                        </span>
                                        <p>WhatsApp</p>
                                    </a>
                                </span>

                                ";
                            };
                            
                            echo"
                        </div>
                        ";
                        

                        //CONTACTOS EXTRAS       
                        if (isset($contactos_extra)) {
                            foreach ($contactos_extra as $key => $extra) {
                                echo"
                                <div class=\"agente_wrap disponible\" key=\"" . $key . "\" telefono=\"" . $extra['telefono'] . "\">
                                    <span class=\"eliminar_contacto_extra\"><i class=\"fa fa-times-circle\"></i></span>
                                    <p class=\"contacto_title\">" . $extra['info'] . "</p>
                                    <img src=\"" . $extra['src'] . "?t=" . time() . "\" alt=\"Foto\" class=\"foto_agente\">
                                    <span class=\"info_agente_wrap\">
                                    <p class=\"nombre_agente\">" . $extra['nombre'] . "</p>";
                                    if ($extra['telefono'] !== '') {
                                        echo"
                                        <span class=\"contacto_agente\">
                                            <span class=\"fa-stack icon_stacks_whatsapp\">
                                            <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                                            <i class=\"fa fa-circle\"></i>
                                            </span>
                                            <p class=\"agente_telefono\">" . $extra['telefono'] . "</p>
                                        </span>
                                        <span class=\"call_btns_wrap\">
                                            <a class=\"contacto_call_btn\" href=\"tel:" . getNumberFormat($extra['telefono']) . "\"><p>Llamar</p></a>
                                            <a class=\"contacto_whatsapp_btn\" href=\"https://api.whatsapp.com/send?phone=" . getNumberFormat($extra['telefono']) . "\" target=\"_blank\">
                                                <span class=\"fa-stack icon_stacks_whatsapp\">
                                                    <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                                                    <i class=\"fa fa-circle\"></i>
                                                </span>
                                                <p>WhatsApp</p>
                                            </a>
                                        </span>
                                        ";
                                    };
                                    
                                echo"
                                </div>
                                ";
                            };
                        };
                        
                ?>
              </div>


            <label for="comentarios" class="titulo_ficha">Comentarios:</label>
            <hr class="linea_naranja">
            <div class="comentarios_wrap">
                <textarea name="comentarios" class="comentarios_textarea" readonly><?php echo $inmueble['comentarios_bien']; ?></textarea>
                <span class="comentarios_btn editar_comentarios_btn">Editar</span>
                <span class="comentarios_btn guardar_comentarios_btn">Guardar</span>
            </div>
            
             
            <?php
                if (isset($check_lists_extra)) {
                    echo"<label class=\"titulo_ficha titulo_check_list\">Check-Lists:</label>
                    <hr class=\"linea_naranja titulo_check_list\">
                    ";

                    foreach ($check_lists_extra as $key =>$value) {
                       
                        echo"
                            <span class=\"elemento_popup\" id=\"" . $key . "\" titulo=\"" . $value['titulo'] . "\"  key=\"" . $key . "\">
                                    <span class=\"eliminar_check_list_btn\"><i class=\"fa fa-times-circle\"></i></span>
                                    <span class=\"elemento_header\">

                                        <span class=\"elemento_titulo\">
                                            <span class=\"titulo_read\">
                                                <i class=\"fa fa-circle\"></i>
                                                <p>" . $value['titulo'] . "</p>
                                            </span>
                                        </span>

                                        <span class=\"btn_elemento_detalle activo\">
                                            <i class=\"fas fa-chevron-circle-down\"></i>
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
                                        </div>
                                </span>


                                </span>
                            ";


                    };
                };
            ?>
            
            
            <?php
            
                if (!empty($inventario)) {
                   echo"<label class=\"titulo_ficha\">Inventario:</label>
                   <hr class=\"linea_naranja\">
                   ";

                   foreach ($inventario as $key => $value) {
                    echo"
                        <span class=\"elemento_popup\" data=\"" . $value['id'] . "\">

                                <span class=\"elemento_header\">

                                    <span class=\"elemento_titulo\">
                                        <span class=\"titulo_read\">
                                            <i class=\"fa fa-circle\"></i>
                                            <p>" . $value['item'] . " - " . $value['dimensiones'] . " - " . $value['estado'] . "</p>
                                        </span>
                                    </span>

                                    <span class=\"btn_elemento_detalle activo\">
                                        <i class=\"fas fa-chevron-circle-down\"></i>
                                    </span>

                                </span>

                                <span class=\"elemento_detalle_wrap centrado\">";

                                echo"
                                    <div class=\"inventario_detalle_wrap\">
                                    Fecha Retiro: " . $value['fecha_retiro'] . "<br>
                                    Comentarios: " . $value['comentarios'] . "
                                    </div>
                            </span>


                            </span>
                        ";
                   };
                };

            ?>
            


        </div>

    </div>

 </body>
 <script src="../../js/panorama_base_code_ficha_individual.js"></script>
</html>