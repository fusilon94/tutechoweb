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
      <title>Calendario</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/ficha_bien_detalle_inmueble.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider_bien_individual.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="../../css/calendario.css">
      <link rel="stylesheet" href="../../css/select2.min.css">
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
      <script src="../../js/jquery-clock-timepicker.min.js"></script>
      <script src="../../js/select2.min.js"></script>
      <script src="../../js/calendario_general.js"></script>
      <script src="../../js/calendario_<?= $js_file ?>.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
     
 </head>
 <body>

 <div id="fondo"></div>

 <div class="pais_list_overlay">
   <div class="pais_list">
     <span class="cerrar_pais_list"><i class="fa fa-times"></i></span>
     <h2>Escoge un País</h2>
     <hr>
     <div class="lista_btn_paises">
     <?php 
      foreach ($paises as $pais) {
        echo"
        <span class='pais_opcion' id='" . $pais . "'>
          <img src='../../objetos/flag_" . $pais . ".svg' alt='" . $pais . "'>
          <p>" . ucfirst($pais) . "</p>
        </span>
        ";
      };
     ?>
     </div>
   </div>
 </div>

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
            <a href="consola.php">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
            </a>
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
           <?php
            if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
              echo"
              <span class=\"pais_selector_calendario\">
                <p class=\"pais_selector_text\">" . ucfirst($_SESSION['cookie_pais']) . "</p>
                <img src=\"../../objetos/flag_" . $_SESSION['cookie_pais'] . ".svg\" alt=\"" . $_SESSION['cookie_pais'] . "\" class=\"pais_selector_flag\">
              </span>
              ";
            };
            ?>
          </header>

        <!-- POPUP -->

        <div class="popup_overlay">
            <span class="popup">
                
            </span>
        </div>

        <div class="overlay_datos_inmuebles"></div>


        <div class="filtros_overlay">

          <div class="filtros_wrap">
            <span class="filtros_cerrar_btn"><i class="fas fa-times-circle"></i></span>
            <h2>Filtros</h2>
            <hr>

            <span class="past_event_btn">Ver Eventos Pasados</span>
            <input type="hidden" id="past_event_check" value="0">

            <?php

            if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12 || $nivel_acceso == 2) {


              echo"
                <div class=\"select_agencia_wrap\">
                  <label for=\"agencia_select\">Agencia:</label>
                  <select name=\"agencia_select\" id=\"agencia_select\">
                  <option value=\"\" selected></option>
                  ";

                  foreach ($agencias as $agencia) {
                   echo"
                    <option value=\"" . $agencia['departamento'] . '_' .$agencia['location_tag'] . "\" data=\"" . $agencia['id'] . "\">" . $agencia['location_tag'] . " (" . $agencia['id'] .  ")</option>
                   ";
                  };
                    
              echo"
                  </select>
                </div>
              ";


            };

            if ($nivel_acceso == 1 || $nivel_acceso == 11) {

              echo"
                <div class=\"select_agente_wrap\">
                  <label for=\"agente_select\">Agente:</label>
                  <select name=\"agente_select\" id=\"agente_select\">
                    <option value=\"\"></option>
                    <option value=\"" . $agente_id['id'] . "\">ADMIN</option>
                  </select>
                </div>
            ";

            };

            ?>
            
          </div>
          
        </div>

        <!-- CONTENIDO -->

        <div class="contenido_wrap">

            <div class="cabecera">

                <span class="hoy_bnt">Hoy</span>

                <div class="cabecera_titulo_wrap">

                  <span class="mes_back_btn"><i class="fas fa-chevron-circle-left"></i></span>

                  <span class="cabecera_titulo" data="">Febrero 2021</span>

                  <span class="mes_foward_btn"><i class="fas fa-chevron-circle-right"></i></span>

                </div>

                <span class="filtros_btn">
                  <p class="filtro_label">Filtros</p>
                </span>
            
            </div>

            <div class="titulos_semana">

            </div>

            <div class="calendario_contendor">

            </div>

            
        

        </div>

    


    </div>

 </body>
 <script src="../../js/panorama_base_code_ficha_individual.js"></script>
</html>