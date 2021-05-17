<?php
if(isset($php_view_entry_control)){} else{header('Location: index.php');}; //para evitar que alguien entre directamente al .view.php y porque en los view no se abre ninguna session
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
      <title>Inmuebles en Venta</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="Description" content="">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/estilos_inmuebles.css">
      <link rel="stylesheet" href="../../css/popup_favoritos_log_or_register.css">
      <link rel="stylesheet" href="../../css/cuadro_elemento.css">
      <link rel="stylesheet" href="../../css/popup_ficha_bien.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider_bien_individual.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>

      <script type="text/javascript">
        const moneda = "<?= $pais_moneda['moneda']; ?>";
        const cambio = <?= $pais_moneda['cambio_dolar']; ?>;

        var estado = 'En Alquiler';

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

      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/webgl_tester.js"></script>
      <script src="../../js/three.js"></script>
      <script src="../../js/TweenLite.min.js"></script>
      <script src="../../js/configuracion_busqueda.js"></script>
      <script src="../../js/busqueda_avanzada_resultados.js"></script>
      <script src="../../js/paginacion_refresh.js"></script>
      <script type="text/javascript" src="../../js/jquery.ui.touch-punch.js"></script>
      <script type="text/javascript" src="../../js/dragable_feature_overflow.js"></script>
      <script src="../../js/tabs.js"></script>
      <script src="../../js/popup_favoritos_log_or_register.js"></script>
      <script src="../../js/browser_back_button_inmuebles.js"></script>
      <script src="../../js/popup_ficha_bien.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
      <!-- HAY SCRIPTS AL FINAL DEL BODY, Y SCRIPTS QUE SE CARGAN CON LOS PROCESS REQUESTS -->

 </head>
 <body>

 <div id="fondo"></div>
 <div class="ir-arriba">
   <span class="ir-arriba-flecha fa fa-angle-up"></span>
   <span class="ir-arriba-texto">Arriba</span>
 </div>

      <div id="contenedor_total">

<!-- POPUP ENTER FAVORITOS -->
          <div class="overlay_popup_enter_favoritos"> <!-- PopUp de concetarse o registrarse para acceder a la lista de favoritos -->

            <div class="popup_favoritos">

              <span class="popup_favoritos_cerrar_btn"><i class="fa fa-times "></i></span>

            	<form action="" class="signup">
            		<h2 class="form-title" id="signup"><span>o</span>Regístrate</h2>
            		<div class="form-holder">
            			<input type="text" class="input" placeholder="Nombre" />
            			<input type="email" class="input" placeholder="Email" />
            			<input type="password" class="input" placeholder="Contraseña" />
            		</div>
            		<button class="submit-btn">Registrar</button>
                <span class="spacer1_popup_button">o</span>
                <button class="log_with_facebook_bnt">Continuar con Facebook</button>
            	</form>

            	<form action="" class="login slide-up">
            		<div class="center">
            			<h2 class="form-title" id="login"><span>o</span>Inicia Sesión</h2>
            			<div class="form-holder">
            				<input type="email" class="input" placeholder="Email" />
            				<input type="password" class="input" placeholder="Contraseña" />
            			</div>
            			<button class="submit-btn">Acceder</button>
                  <span class="spacer2_popup_button">o</span>
                  <button class="log_with_facebook_bnt">Continuar con Facebook</button>
            		</div>
            	</form>

            </div>

          </div>

<!-- ########   POPUP FICHA BIEN ########## -->

<div class="overlay_popup_ficha_bien">

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


<!-- ########   END - POPUP FICHA BIEN ########## -->

<!-- BARRA DE NAVEGACION -->
      	  <header>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="../../index.php"><img src="../../objetos/logotipo2.svg" alt="Tu Techo.com" class="menu_logo"></a></li>
                  <li class="hover_menu"><span class="menu_boton"><img src="../../objetos/menu_boton_icon.svg" alt="Menu" class="menu_icons_style"><p>Menu</p></span></li>
                </ul>
            </div>
      	    <nav class="scroll">
      	     <div id="menu">
                <ul class="ulmenu">
                  <li class="logo_element limenu"><a href="../../index.php" class="a_menu"><img src="../../objetos/logotipo2.svg" alt="Tu Techo.com" class="logo_img"></a></li>
                  <li class="hover_menu limenu"><a href="../m1/anunciar.html" class="a_menu"><img src="../../objetos/anuncio_icon.svg" alt="Anunciar" class="menu_icons_style"><p>Anunciar un bien</p></a></li>
      	          <li class="hover_menu limenu"><a href="venta_inmueble.php" class="a_menu"><img src="../../objetos/buy_icon.svg" alt="Comprar" class="menu_icons_style"><p>Comprar</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m2/renta_inmueble.php" class="a_menu"><img src="../../objetos/alquilar_icon.svg" alt="Alquilar" class="menu_icons_style"><p>Alquilar</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m4/soporte.html" class="a_menu"><img src="../../objetos/soporte_legal_icon.svg" alt="Soporte Legal" class="menu_icons_style"><p>Soporte legal</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m5/agencias.php" class="a_menu"><img src="../../objetos/agencias.svg" alt="Contacto" class="menu_icons_style"><p>Agencias</p></a></li>
                </ul>
             </div>
           </nav>
           <span class="tag_current_tab"></span>
          </header>

<!-- CONTENIDO PRINCIPAL -->
          <main>

            <a href="tutecho_map.php#renta" class="tag_mapa"><span class="fa fa-map tag_icon_map"></span>&nbsp&nbspMapa</a>
            <span class="tag_favoritos"><span class="fa fa-star tag_favoritos_star"></span> Favoritos</span>

            <div id="lista_tabs_contenedor">
              <ul class="lista_tabs">
                <li class="tab">
                  <a href="#tab1" id="busqueda_casa_type" name="casa"><span class="fa fa-home icon_tab_inmuebles"></span><span class="tab_text">Casas</span></a>
                </li>
                <li class="tab">
                  <a href="#tab2" id="busqueda_departamento_type" name="departamento"><span class="fa fa-building icon_tab_inmuebles"></span><span class="tab_text">Departamentos</span></a>
                </li>
                <li class="tab">
                  <a href="#tab3" id="busqueda_local_type" name="local"><span class="fa fa-shopping-bag icon_tab_inmuebles"></span><span class="tab_text">Locales</span></a>
                </li>
                <li class="tab">
                  <a href="#tab4" id="busqueda_terreno_type" name="terreno"><span class="fa fa-tree icon_tab_inmuebles"></span><span class="tab_text">Terrenos</span></a>
                </li>
                <li class="tab_gear">
                  <a href="#tab5"><span class="fa fa-search icon_tab_inmuebles"></span><span class="tab_gear_text">Búsqueda avanzada</span> <span class="fa fa-angle-down arrow_js"></span></a>
                </li>
              </ul>
            </div>

<!-- Configuracion de busqueda -->

            <div id="configuracion_busqueda_Toggle">
    <!-- Buscador para TAB CASAS -->
               <div id="configuracion_busqueda_contenedor_interno" class="busqueda_contenedor_interno busqueda_casa_type">

                 <input type="hidden" name="busqueda_casa" value="busqueda_casa">

                  <div class="form_group form_localisacion">
                     <div class="input">
                       <label for="speed"><h2>Elija un departamento</h2></label>
                       <select name="speed" class="select_menu" id="departamento_busqueda_casa">
                         <option selected="selected">Toda Bolivia</option>
                         <?php foreach ($regiones as $value): ?>
                           <option><?php echo $value; ?></option>
                         <?php endforeach; ?>
                       </select>
                     </div>
                     <div class="input">
                       <label for="speed"><h2>Elija una ciudad</h2></label>
                       <select name="speed" class="select_menu" id="ciudad_busqueda_casa">
                         <option selected="selected">Todas las ciudades</option>
                       </select>
                     </div>
                  </div>

                  <div class="form_group form_slider">
                     <div class="input sl1">
                       <label for=""><h2>Superficie mínima: <span></span>m<sup>2</sup></h2></label>
                       <input type="text" class="range min-20 max-1000" value="300" id="superficie_busqueda_casa">
                     </div>
                     <div class="input">
                       <label for=""><h2>Precio máximo: <span class="precio_val"></span><span class="millon"><?= "&nbsp" . $pais_moneda['moneda']; ?></span></h2></label>
                       <input type="text" class="range_price_renta min-<?= (round(350*$cambio))?> max-<?= (round(2000*$cambio))?>" value="<?= (round(1050*$cambio))?>" id="precio_busqueda_casa">
                     </div>
                  </div>

                  <div class="form_group form_checkbox">
                     <div class="input">
                       <fieldset style="border:none;">
                          <div>
                            <label class="title_label"><h2>Dormitorios:</h2></label>
                                <label for="radio_casa-1.1">1</label>
                                <input type="radio" name="radio_casa-1" id="radio_casa-1.1"  class="check_box_radio" value="1" checked>
                                <label for="radio_casa-1.2" id="holabola">2</label>
                                <input type="radio" name="radio_casa-1" id="radio_casa-1.2" class="check_box_radio" value="2">
                                <label for="radio_casa-1.3">3</label>
                                <input type="radio" name="radio_casa-1" id="radio_casa-1.3" class="check_box_radio" value="3">
                                <label for="radio_casa-1.4">4+</label>
                                <input type="radio" name="radio_casa-1" id="radio_casa-1.4" class="check_box_radio" value="4">
                          </div>
                       </fieldset>
                     </div>
                     <div class="input">
                       <fieldset style="border:none;">
                          <div>
                            <label class="title_label"><h2>Espacios de parqueo:</h2></label>
                                <label for="radio_casa-2.1">0</label>
                                <input type="radio" name="radio_casa-2" id="radio_casa-2.1" class="check_box_radio" value="0" checked>
                                <label for="radio_casa-2.2">1</label>
                                <input type="radio" name="radio_casa-2" id="radio_casa-2.2" class="check_box_radio" value="1">
                                <label for="radio_casa-2.3">2</label>
                                <input type="radio" name="radio_casa-2" id="radio_casa-2.3" class="check_box_radio" value="2">
                                <label for="radio_casa-2.4">3+</label>
                                <input type="radio" name="radio_casa-2" id="radio_casa-2.4" class="check_box_radio" value="3">
                          </div>
                       </fieldset>
                     </div>
                  </div>
                  <div class="form_btn">
                    <button onclick="busqueda_casa_sent(this, 1, 1)" name="casa">Buscar</button>
                  </div>
              </div>
    <!-- Buscador para TAB DEPARTAMENTOS -->
               <div class="busqueda_contenedor_interno busqueda_departamento_type" style="display:none;">

                 <input type="hidden" name="busqueda_departamento" value="busqueda_departamento">

                  <div class="form_group form_localisacion">
                     <div class="input">
                       <label for="speed"><h2>Elija un departamento</h2></label>
                       <select name="speed" class="select_menu" id="departamento_busqueda_departamento">
                         <option selected="selected">Toda Bolivia</option>
                         <?php foreach ($regiones as $value): ?>
                           <option><?php echo $value; ?></option>
                         <?php endforeach; ?>
                       </select>
                     </div>
                     <div class="input">
                       <label for="speed"><h2>Elija una ciudad</h2></label>
                       <select name="speed" class="select_menu" id="ciudad_busqueda_departamento">
                         <option selected="selected">Todas las ciudades</option>
                       </select>
                     </div>
                  </div>

                  <div class="form_group form_slider">
                     <div class="input sl1">
                       <label for=""><h2>Superficie mínima: <span></span>m<sup>2</sup></h2></label>
                       <input type="text" class="range min-20 max-1000" value="300" id="superficie_busqueda_departamento">
                     </div>
                     <div class="input">
                       <label for=""><h2>Precio máximo: <span class="precio_val"></span><span class="millon"><?= "&nbsp" . $pais_moneda['moneda']; ?></span></h2></label>
                       <input type="text" class="range_price_renta min-<?= (round(350*$cambio))?> max-<?= (round(2000*$cambio))?>" value="<?= (round(1050*$cambio))?>" id="precio_busqueda_departamento">
                     </div>
                  </div>

                  <div class="form_group form_checkbox">
                     <div class="input">
                       <fieldset style="border:none;">
                          <div>
                            <label class="title_label"><h2>Dormitorios:</h2></label>
                                <label for="radio_departamento-1.1">1</label>
                                <input type="radio" name="radio_departamento-1" id="radio_departamento-1.1"  class="check_box_radio" value="1" checked>
                                <label for="radio_departamento-1.2">2</label>
                                <input type="radio" name="radio_departamento-1" id="radio_departamento-1.2" class="check_box_radio" value="2">
                                <label for="radio_departamento-1.3">3</label>
                                <input type="radio" name="radio_departamento-1" id="radio_departamento-1.3" class="check_box_radio" value="3">
                                <label for="radio_departamento-1.4">4+</label>
                                <input type="radio" name="radio_departamento-1" id="radio_departamento-1.4" class="check_box_radio" value="4">
                          </div>
                       </fieldset>
                     </div>
                     <div class="input">
                       <fieldset style="border:none;">
                          <div>
                            <label class="title_label"><h2>Espacios de parqueo:</h2></label>
                                <label for="radio_departamento-2.1">0</label>
                                <input type="radio" name="radio_departamento-2" id="radio_departamento-2.1"  class="check_box_radio" value="0" checked>
                                <label for="radio_departamento-2.2">1</label>
                                <input type="radio" name="radio_departamento-2" id="radio_departamento-2.2" class="check_box_radio" value="1">
                                <label for="radio_departamento-2.3">2</label>
                                <input type="radio" name="radio_departamento-2" id="radio_departamento-2.3" class="check_box_radio" value="2">
                                <label for="radio_departamento-2.4">3+</label>
                                <input type="radio" name="radio_departamento-2" id="radio_departamento-2.4" class="check_box_radio" value="3">
                          </div>
                       </fieldset>
                     </div>
                  </div>
                  <div class="form_btn">
                    <button onclick="busqueda_departamento_sent(this, 1, 1)" name="departamento">Buscar</button>
                  </div>
              </div>
    <!-- Buscador para TAB LOCALES -->
               <div class="busqueda_contenedor_interno busqueda_local_type" style="display:none;">

                 <input type="hidden" name="busqueda_local" value="busqueda_local">

                  <div class="form_group form_localisacion">
                     <div class="input">
                       <label for="speed"><h2>Elija un departamento</h2></label>
                       <select name="speed" class="select_menu" id="departamento_busqueda_local">
                         <option selected="selected">Toda Bolivia</option>
                         <?php foreach ($regiones as $value): ?>
                           <option><?php echo $value; ?></option>
                         <?php endforeach; ?>
                       </select>
                     </div>
                     <div class="input">
                       <label for="speed"><h2>Elija una ciudad</h2></label>
                       <select name="speed" class="select_menu" id="ciudad_busqueda_local">
                         <option selected="selected">Todas las ciudades</option>
                       </select>
                     </div>
                  </div>

                  <div class="form_group form_slider">
                     <div class="input sl1">
                       <label for=""><h2>Superficie mínima: <span></span>m<sup>2</sup></h2></label>
                       <input type="text" class="range min-20 max-3000" value="300" id="superficie_busqueda_local">
                     </div>
                     <div class="input">
                       <label for=""><h2>Precio máximo: <span class="precio_val"></span><span class="millon"><?= "&nbsp" . $pais_moneda['moneda']; ?></span></h2></label>
                       <input type="text" class="range_price_renta min-<?= (round(350*$cambio))?> max-<?= (round(2000*$cambio))?>" value="<?= (round(1050*$cambio))?>" id="precio_busqueda_local">
                     </div>
                  </div>

                  <div class="form_group form_checkbox">
                    <div class="input">
                      <fieldset style="border:none;">
                        <div>
                          <label class="title_label"><h2>Tipo de local:</h2></label>
                              <label for="radio_local-1.1">Comercial</label>
                              <input type="radio" name="radio_local-1" id="radio_local-1.1"  class="check_box_radio" value="Comercial">
                              <label for="radio_local-1.2">Oficina</label>
                              <input type="radio" name="radio_local-1" id="radio_local-1.2" class="check_box_radio" value="Oficina">
                              <label for="radio_local-1.3">Ambos</label>
                              <input type="radio" name="radio_local-1" id="radio_local-1.3" class="check_box_radio" value="Ambos" checked>
                        </div>
                      </fieldset>
                    </div>
                     <div class="input">
                       <fieldset style="border:none;">
                          <div>
                            <label class="title_label"><h2>Espacios de parqueo:</h2></label>
                                <label for="radio_local-2.1">0</label>
                                <input type="radio" name="radio_local-2" id="radio_local-2.1"  class="check_box_radio" value="0" checked>
                                <label for="radio_local-2.2">1</label>
                                <input type="radio" name="radio_local-2" id="radio_local-2.2" class="check_box_radio" value="1">
                                <label for="radio_local-2.3">2</label>
                                <input type="radio" name="radio_local-2" id="radio_local-2.3" class="check_box_radio" value="2">
                                <label for="radio_local-2.4">3+</label>
                                <input type="radio" name="radio_local-2" id="radio_local-2.4" class="check_box_radio" value="3">
                          </div>
                       </fieldset>
                     </div>

                  </div>
                  <div class="form_btn">
                    <button onclick="busqueda_local_sent(this, 1, 1)" name="local">Buscar</button>
                  </div>
              </div>
  <!-- Buscador para TAB TERRENOS -->
             <div class="busqueda_contenedor_interno busqueda_terreno_type" style="display:none;">

               <input type="hidden" name="busqueda_terreno" value="busqueda_terreno">

                <div class="form_group form_localisacion">
                   <div class="input">
                     <label for="speed"><h2>Elija un departamento</h2></label>
                     <select name="speed" class="select_menu" id="departamento_busqueda_terreno">
                       <option selected="selected">Toda Bolivia</option>
                       <?php foreach ($regiones as $value): ?>
                         <option><?php echo $value; ?></option>
                       <?php endforeach; ?>
                     </select>
                   </div>
                   <div class="input">
                     <label for="speed"><h2>Elija una ciudad</h2></label>
                     <select name="speed" class="select_menu" id="ciudad_busqueda_terreno">
                       <option selected="selected">Todas las ciudades</option>
                     </select>
                   </div>
                </div>

                <div class="form_group form_slider">
                   <div class="input sl1">
                     <label for=""><h2 style="display: flex; align-items: center; justify-content: center;">Superficie mínima: <span class="sup_terreno_val" style="margin-left: 4px;"></span><span class="hect" style="margin-left: 4px;"> m&sup2</span>
                    <div id="boton_terreno_hect">
                           <div id="opcion_terreno_m2" class="opcion_terreno_m2 opcion_terreno_active">m&sup2</div>
                           <div id="opcion_terreno_hect" class="opcion_terreno_hect opcion_terreno_inactive">ha</div>
                     </div></h2></label>
                     <input type="text" class="range_sup_terreno min-20 max-5000" value="500" id="superficie_busqueda_terreno">
                   </div>
                   <div class="input">
                       <label for=""><h2>Precio máximo: <span class="precio_val"></span><span class="millon"><?= "&nbsp" . $pais_moneda['moneda']; ?></span></h2></label>
                       <input type="text" class="range_price_renta min-<?= (round(350*$cambio))?> max-<?= (round(2000*$cambio))?>" value="<?= (round(1050*$cambio))?>" id="precio_busqueda_terreno">
                     </div>
                </div>

                <div class="form_btn">
                  <button name="terreno" onclick="busqueda_terreno_sent(this, 1, 1)">Buscar</button>
                </div>
            </div>
            </div>

<!-- SECTION -->
            <div class="texto_resultado_busqueda" id="texto_resultado_busqueda">
            </div>

            <section id="paginacion_top_section" class="paginacion paginacion_top">
              <ul id="paginacion_container_top">
              </ul>
            </section>

            <section id="sections_tabs_contenedor">
<!--## TAB1 ##-->
                 <article id="tab1">

                 </article>

<!--## TAB2 ##-->
                 <article id="tab2">

                 </article>

<!--## TAB3 ##-->
                 <article id="tab3">

                 </article>

<!--### TAB4 ###-->
                 <article id="tab4">

                 </article>

            </section>

<!--############ PAGINACION ############# -->
            <section class="paginacion paginacion_bottom">
              <ul id="paginacion_container">
              </ul>
            </section>
          </main>
</div>
<!--PIE DE PAGINA -->
      	  <footer>
      	  	 <div id="footer_logo" class="footer_div">
      	  		 <a href="../../index.php">
      	  			 <img src="../../objetos/logotipo.svg" alt="TuTecho.com" class="footer_logo_img">
      	  		 </a>
      	  	 </div>
      	  	 <div id="footer_list" class="footer_div">
      	  		 <h2><p>Acerca de</p></h2>
      	  		  <div>
      	  		       <ul>
      	  			       <li><a href="../pie/quienes_somos.html">Quiénes somos</a></li>
      	  			       <li><a href="../pie/propietario_login.php">Propietario</a></li>
      	  			       <li><a href="../../acceso/acceso.php">Acceso</a></li>
      	  		       </ul>
      	  	 	       <ul>
      	  	 		       <li><a href="../pie/empleo.html">Empleo</a></li>
                       <li><a href="../pie/politica.html">Política de privacidad</a></li>
      	  			       <li class="cambiar_pais"><p><?php echo ucfirst($_COOKIE['tutechopais']); ?></p><img src="../../objetos/flag_<?php echo $_COOKIE['tutechopais']; ?>.svg" alt="<?php echo $_COOKIE['tutechopais']; ?>"></li>
      	  	 	       </ul>
      	  	 	  </div>
      	  	 </div>
      	  	 <div id="footer_socialmedia" class="footer_div">
      	  		 <h2><p>Síguenos en</p></h2>
      	  		 <ul>
      	  			 <li><a href="#"><img src="../../objetos/facebookicon.svg" alt="Facebook" class="socialmedia_style"><p>Facebook</p></a></li>
      	  			 <li><a href="#"><img src="../../objetos/twittericon.svg" alt="Twitter" class="socialmedia_style"><p>Twitter</p></a></li>
      	  			 <li><a href="#"><img src="../../objetos/youtubeicon.svg" alt="Youtube" class="socialmedia_style"><p>Youtube</p></a></li>
      	  		 </ul>
      	  	 </div>
      	  </footer>
 </body>
 <script src="../../js/panorama_base_code.js"></script>
</html>
