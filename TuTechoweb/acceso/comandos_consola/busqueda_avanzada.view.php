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
      <title>Consola - Búsqueda Avanzada</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>
      <link rel="stylesheet" href="../../css/busqueda_avanzada.css">
      <link rel="stylesheet" href="../../css/ficha_bien_detalle_inmueble.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider_bien_individual.css">

      <script type="text/javascript">
        const moneda = "<?= $pais_moneda['moneda']; ?>";
        const cambio = <?= $pais_moneda['cambio_dolar']; ?>;

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
      <script src="../../js/busqueda_avanzada.js"></script><!-- contiene tambien el codigo para el popup_ficha bien...mirar tbn al final del body -->
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src="../../js/overlap_marker_spiderfy.min.js"></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>

 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">

<!-- BARRA DE NAVEGACION -->

      	  <header>
            <div class="regreso_boton_div_contenedor">
            <a href="bien_inmueble_consola.php">
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
          </header>


<!-- POPUP FICHA BIEN -->

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

<!-- CONTENIDO PRINCIPAL -->
<div class="busqueda_simple_contenedor">

  <div class="elemento_busqueda_simple elementos_base">
  <select id="tipo_bien" class="select_menu" name="tipo_bien">
    <option value="casa" selected>Casas</option>
    <option value="departamento">Departamentos</option>
    <option value="local">Locales</option>
    <option value="terreno">Terrenos</option>
  </select>
  <div class="input box_radio_choices">
    <fieldset style="border:none;">
             <label for="radio_estado-1.1" name="En Venta" class="box_active">Venta</label>
             <input type="radio" name="radio_estado-1" id="radio_estado-1.1" class="check_box_radio" value="En Venta" checked>
             <label for="radio_estado-1.2" name="En Alquiler" class="">Renta</label>
             <input type="radio" name="radio_estado-1" id="radio_estado-1.2" class="check_box_radio" value="En Alquiler">
    </fieldset>
  </div>
  </div>

  <div class="elemento_busqueda_simple elementos_variables">
    <div class="input precio slider_precio_1">
      <label for=""><h2>Precio Max: <span class="precio_val"></span> <span class="millon"><?= "&nbsp" . $pais_moneda['moneda']; ?></span></h2></label>
      <input type="text" class="range_price min-<?= (round(15000*$cambio))?> max-<?= (round(2300000*$cambio))?>" value="<?= (round(80000*$cambio))?>" id="precio_busqueda_venta">
    </div>
    <div class="input precio slider_precio_2 slider_hidden">
      <label for=""><h2>Precio Max: <span class="precio_val"></span><span class="millon"><?= "&nbsp" . $pais_moneda['moneda']; ?></span></h2></label>
      <input type="text" class="range_price_renta min-<?= (round(350*$cambio))?> max-<?= (round(2000*$cambio))?>" value="<?= (round(1050*$cambio))?>" id="precio_busqueda_renta">
    </div>
    <div class="input superficie slider_superficie_1">
      <label for=""><h2>Superficie Min: <span></span>m<sup>2</sup></h2></label>
      <input type="text" class="range min-20 max-1000" value="50" id="superficie_busqueda">
    </div>
    <div class="input superficie slider_superficie_2 slider_hidden" style="margin-top: -0.6em;">
      <label for="">
        <h2 style="display: flex; align-items: center;">Superficie Min:<span class="sup_terreno_val"></span>
          <span class="hect">m&sup2</span>
          <div id="boton_terreno_hect">
             <div id="opcion_terreno_m2" class="opcion_terreno_m2 opcion_terreno_active">m&sup2</div>
             <div id="opcion_terreno_hect" class="opcion_terreno_hect opcion_terreno_inactive">ha</div>
           </div>
        </h2>
     </label>
       <input type="text" class="range_sup_terreno min-20 max-5000" value="500" id="superficie_busqueda_terreno">
    </div>
  </div>

</div>

<div class="mapa_contenedor">
<span class="cerrar_filtros_btn" title="Cerrar"><i class="fa fa-times"></i></span>
<span class="borrar_filtros_btn" title="Borrar Búsqueda"><i class="fa fa-trash-alt"></i></span>
<!-- ############################## FILTROS CASA ########################################### -->
  <div class="filtros_container_deslizante filtros_casa">

    <h3>General</h3>
    <hr>

    <div class="elemento_spinner_container">
      <span name="dormitorios_casa" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="dormitorios"> Dormitorios (MIN):</label>
        <input id="dormitorios_casa" name="dormitorios" readonly="readonly" class="elemento_spinner spinner_casa" value="0">
      </div>
    </div>

    <div class="elemento_spinner_container">
      <span name="wc_casa" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="wc"> Baños (MIN):</label>
        <input id="wc_casa" name="wc" readonly="readonly" class="elemento_spinner spinner_casa" value="0">
      </div>
    </div>

    <div class="elemento_spinner_container">
      <span name="parqueos_casa" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="parqueos"> Parqueos (MIN):</label>
        <input id="parqueos_casa" name="parqueos" readonly="readonly" class="elemento_spinner spinner_casa" value="0">
      </div>
    </div>

    <h3>Interiores</h3>
    <hr>

    <div class="elemento_spinner_container">
      <span name="pisos_casa" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="pisos"> Pisos/Niveles (MIN):</label>
        <input id="pisos_casa" name="pisos" readonly="readonly" class="elemento_spinner spinner_casa" value="0">
      </div>
    </div>
    <div class="elemento_select_container">
      <span name="calefaccion_casa" class="check_activate"></span>
      <div class="select_wrap">
        <label for="calefaccion">Tipo Calefacción</label>
        <select id="calefaccion_casa" name="calefaccion" class="elemento_select select_casa_calefaccion" >
          <option class="option_default" value="Todos" selected>Todos</option>
          <option value="Inexistente">Inexistente</option>
          <option value="Electrica">Electrica</option>
          <option value="A gaz">A gaz</option>
          <option value="Solar">Solar</option>
          <option value="Comunal">Comunal</option>
        </select>
      </div>
    </div>
    <div class="elemento_checkbox_container">
      <span name="residencia_casa" class="check_activate"></span>
      <span id="residencia_casa" name="residencia" class="elemento_checkbox checkbox_casa" value="0">Residencia Privada</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="handicap_casa" class="check_activate"></span>
      <span id="handicap_casa" name="handicap" class="elemento_checkbox checkbox_casa" value="0">Handicap</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="hab_planta_baja_casa" class="check_activate"></span>
      <span id="hab_planta_baja_casa" name="hab_planta_baja" class="elemento_checkbox checkbox_casa" value="0">Hab. planta Baja</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="amoblado_casa" class="check_activate"></span>
      <span id="amoblado_casa" name="amoblado" class="elemento_checkbox checkbox_casa" value="0">Amoblado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="balcon_casa" class="check_activate"></span>
      <span id="balcon_casa" name="balcon" class="elemento_checkbox checkbox_casa" value="0">Balcón</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="equipada_casa" class="check_activate"></span>
      <span id="equipada_casa" name="equipada" class="elemento_checkbox checkbox_casa" value="0">Cocina Equipada</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="gaz_domiciliario_casa" class="check_activate"></span>
      <span id="gaz_domiciliario_casa" name="gaz_domiciliario" class="elemento_checkbox checkbox_casa" value="0">Gaz Domiciliario</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="aire_acondicionado_casa" class="check_activate"></span>
      <span id="aire_acondicionado_casa" name="aire_acondicionado" class="elemento_checkbox checkbox_casa" value="0">Aire Acondicionado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="internet_casa" class="check_activate"></span>
      <span id="internet_casa" name="internet" class="elemento_checkbox checkbox_casa_internet" value="0">Internet</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="animales_domesticos_casa" class="check_activate"></span>
      <span id="animales_domesticos_casa" name="animales_domesticos" class="elemento_checkbox checkbox_casa" value="0">Animales Domesticos</span>
    </div>

    <h3>Exteriores</h3>
    <hr>

    <div class="elemento_checkbox_container">
      <span name="jardin_casa" class="check_activate"></span>
      <span id="jardin_casa" name="jardin" class="elemento_checkbox checkbox_casa" value="0">Jardín / Patio</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="parqueo_techado_casa" class="check_activate"></span>
      <span id="parqueo_techado_casa" name="parqueo_techado" class="elemento_checkbox checkbox_casa" value="0">Parqueo Techado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="picina_casa" class="check_activate"></span>
      <span id="picina_casa" name="picina" class="elemento_checkbox checkbox_casa" value="0">Piscina</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="parrillero_casa" class="check_activate"></span>
      <span id="parrillero_casa" name="parrillero" class="elemento_checkbox checkbox_casa" value="0">Parrillero</span>
    </div>

    <span class="button_busqueda_deslizante button_casa">Buscar</span>

  </div>

<!-- ############################## FILTROS DEPARTAMENTO ########################################### -->
  <div class="filtros_container_deslizante filtros_departamento">
    <h3>General</h3>
    <hr>

    <div class="elemento_spinner_container">
      <span name="dormitorios_departamento" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="dormitorios"> Dormitorios (MIN):</label>
        <input id="dormitorios_departamento" name="dormitorios" readonly="readonly" class="elemento_spinner spinner_departamento" value="0">
      </div>
    </div>

    <div class="elemento_spinner_container">
      <span name="wc_departamento" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="wc"> Baños (MIN):</label>
        <input id="wc_departamento" name="wc" readonly="readonly" class="elemento_spinner spinner_departamento" value="0">
      </div>
    </div>

    <div class="elemento_spinner_container">
      <span name="parqueos_departamento" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="parqueos"> Parqueos (MIN):</label>
        <input id="parqueos_departamento" name="parqueos" readonly="readonly" class="elemento_spinner spinner_departamento" value="0">
      </div>
    </div>

    <h3>Interiores</h3>
    <hr>

    <div class="elemento_spinner_container">
      <span name="piso_departamento" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="piso"> #Piso/Planta (MIN):</label>
        <input id="piso_departamento" name="piso" readonly="readonly" class="elemento_spinner spinner_departamento" value="0">
      </div>
    </div>
    <div class="elemento_select_container">
      <span name="calefaccion_departamento" class="check_activate"></span>
      <div class="select_wrap">
        <label for="calefaccion">Tipo Calefacción</label>
        <select id="calefaccion_departamento" name="calefaccion" class="elemento_select select_departamento_calefaccion" >
          <option class="option_default" value="Todos" selected>Todos</option>
          <option value="Inexistente">Inexistente</option>
          <option value="Electrica">Electrica</option>
          <option value="A gaz">A gaz</option>
          <option value="Solar">Solar</option>
          <option value="Comunal">Comunal</option>
        </select>
      </div>
    </div>
    <div class="elemento_checkbox_container">
      <span name="residencia_departamento" class="check_activate"></span>
      <span id="residencia_departamento" name="residencia" class="elemento_checkbox checkbox_departamento" value="0">Residencia Privada</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="handicap_departamento" class="check_activate"></span>
      <span id="handicap_departamento" name="handicap" class="elemento_checkbox checkbox_departamento" value="0">Handicap</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="acensor_departamento" class="check_activate"></span>
      <span id="acensor_departamento" name="acensor" class="elemento_checkbox checkbox_departamento" value="0">Ascensor</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="amoblado_departamento" class="check_activate"></span>
      <span id="amoblado_departamento" name="amoblado" class="elemento_checkbox checkbox_departamento" value="0">Amoblado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="balcon_departamento" class="check_activate"></span>
      <span id="balcon_departamento" name="balcon" class="elemento_checkbox checkbox_departamento_balcon" value="0">Balcón o Terraza</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="equipada_departamento" class="check_activate"></span>
      <span id="equipada_departamento" name="equipada" class="elemento_checkbox checkbox_departamento" value="0">Cocina Equipada</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="gaz_domiciliario_departamento" class="check_activate"></span>
      <span id="gaz_domiciliario_departamento" name="gaz_domiciliario" class="elemento_checkbox checkbox_departamento" value="0">Gaz Domiciliario</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="aire_acondicionado_departamento" class="check_activate"></span>
      <span id="aire_acondicionado_departamento" name="aire_acondicionado" class="elemento_checkbox checkbox_departamento" value="0">Aire Acondicionado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="internet_departamento" class="check_activate"></span>
      <span id="internet_departamento" name="internet" class="elemento_checkbox checkbox_departamento_internet" value="0">Internet</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="animales_domesticos_departamento" class="check_activate"></span>
      <span id="animales_domesticos_departamento" name="animales_domesticos" class="elemento_checkbox checkbox_departamento" value="0">Animales Domesticos</span>
    </div>



    <h3>Exteriores</h3>
    <hr>
    <div class="elemento_checkbox_container">
      <span name="parqueo_techado_departamento" class="check_activate"></span>
      <span id="parqueo_techado_departamento" name="parqueo_techado" class="elemento_checkbox checkbox_departamento" value="0">Parqueo Techado</span>
    </div>

    <h3>Inmueble/Edificio</h3>
    <hr>
    <div class="elemento_checkbox_container">
      <span name="planta_baja_departamento" class="check_activate"></span>
      <span id="planta_baja_departamento" name="planta_baja" class="elemento_checkbox checkbox_departamento" value="0">Planta Baja</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="penthouse_departamento" class="check_activate"></span>
      <span id="penthouse_departamento" name="penthouse" class="elemento_checkbox checkbox_departamento" value="0">Penthouse</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="baulera_departamento" class="check_activate"></span>
      <span id="baulera_departamento" name="baulera" class="elemento_checkbox checkbox_departamento" value="0">Baulera</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="gimnasio_departamento" class="check_activate"></span>
      <span id="gimnasio_departamento" name="gimnasio" class="elemento_checkbox checkbox_departamento" value="0">GYM</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="camaras_departamento" class="check_activate"></span>
      <span id="camaras_departamento" name="camaras" class="elemento_checkbox checkbox_departamento" value="0">Seguridad / Cámaras</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="portero_departamento" class="check_activate"></span>
      <span id="portero_departamento" name="portero" class="elemento_checkbox checkbox_departamento" value="0">Portero</span>
    </div>

    <span class="button_busqueda_deslizante button_departamento">Buscar</span>

  </div>

<!-- ############################## FILTROS LOCAL ########################################### -->
  <div class="filtros_container_deslizante filtros_local">
    <h3>General</h3>
    <hr>

    <div class="elemento_spinner_container">
      <span name="espacios_local" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="espacios"> Espacios (MIN):</label>
        <input id="espacios_local" name="espacios" readonly="readonly" class="elemento_spinner spinner_local" value="0">
      </div>
    </div>

    <div class="elemento_spinner_container">
      <span name="wc_local" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="wc"> Baños (MIN):</label>
        <input id="wc_local" name="wc" readonly="readonly" class="elemento_spinner spinner_local" value="0">
      </div>
    </div>

    <div class="elemento_spinner_container">
      <span name="parqueos_local" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="parqueos"> Parqueos (MIN):</label>
        <input id="parqueos_local" name="parqueos" readonly="readonly" class="elemento_spinner spinner_local" value="0">
      </div>
    </div>
    <div class="elemento_select_container">
      <span name="tipo_local_local" class="check_activate"></span>
      <div class="select_wrap">
        <label for="tipo_local">Tipo Local</label>
        <select id="tipo_local_local" name="tipo_local" class="elemento_select select_local_tipo_local" >
          <option class="option_default" value="Ambos" selected>Ambos</option>
          <option value="Oficina">Oficina</option>
          <option value="Comercial">Comercial</option>
        </select>
      </div>
    </div>

    <h3>Interiores</h3>
    <hr>
    <div class="elemento_spinner_container">
      <span name="piso_local" class="check_activate"></span>
      <div class="spinner_wrap">
        <label for="piso"> #Piso/Planta (MIN):</label>
        <input id="piso_local" name="piso" readonly="readonly" class="elemento_spinner spinner_local" value="0">
      </div>
    </div>
    <div class="elemento_select_container">
      <span name="adaptacion_local" class="check_activate"></span>
      <div class="select_wrap">
        <label for="adaptacion">Adaptación Específica</label>
        <select id="adaptacion_local" name="adaptacion" class="elemento_select select_local_adaptacion" >
          <option class="option_default" class="option_default" value="Todo Uso" selected>Todo Uso</option>
          <option value="Restaurante">Restaurante</option>
          <option value="Bar/Café">Bar/Café</option>
          <option value="Discoteca">Discoteca</option>
          <option value="Tienda">Tienda</option>
          <option value="Consultorio">Consultorio</option>
          <option value="Gimnasio">Gimnasio</option>
          <option value="Salon de eventos">Salon de eventos</option>
          <option value="Museo/Sala de exposición">Museo/Sala de exposición</option>
          <option value="Picina/Sauna">Picina/Sauna</option>
          <option value="Oficinas">Oficinas</option>
        </select>
      </div>
    </div>
    <div class="elemento_select_container">
      <span name="calefaccion_local" class="check_activate"></span>
      <div class="select_wrap">
        <label for="calefaccion">Tipo Calefacción</label>
        <select id="calefaccion_local" name="calefaccion" class="elemento_select select_local_calefaccion" >
          <option class="option_default" value="Todos" selected>Todos</option>
          <option value="Inexistente">Inexistente</option>
          <option value="Electrica">Electrica</option>
          <option value="A gaz">A gaz</option>
          <option value="Solar">Solar</option>
          <option value="Comunal">Comunal</option>
        </select>
      </div>
    </div>
    <div class="elemento_checkbox_container">
      <span name="handicap_local" class="check_activate"></span>
      <span id="handicap_local" name="handicap" class="elemento_checkbox checkbox_local" value="0">Handicap</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="acensor_local" class="check_activate"></span>
      <span id="acensor_local" name="acensor" class="elemento_checkbox checkbox_local" value="0">Ascensor</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="cocina_local" class="check_activate"></span>
      <span id="cocina_local" name="cocina" class="elemento_checkbox checkbox_local" value="0">Cocina</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="equipada_local" class="check_activate"></span>
      <span id="equipada_local" name="equipada" class="elemento_checkbox checkbox_local" value="0">Cocina Equipada</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="gaz_domiciliario_local" class="check_activate"></span>
      <span id="gaz_domiciliario_local" name="gaz_domiciliario" class="elemento_checkbox checkbox_local" value="0">Gaz Domiciliario</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="aire_acondicionado_local" class="check_activate"></span>
      <span id="aire_acondicionado_local" name="aire_acondicionado" class="elemento_checkbox checkbox_local" value="0">Aire Acondicionado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="internet_local" class="check_activate"></span>
      <span id="internet_local" name="internet" class="elemento_checkbox checkbox_local_internet" value="0">Internet</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="animales_domesticos_local" class="check_activate"></span>
      <span id="animales_domesticos_local" name="animales_domesticos" class="elemento_checkbox checkbox_local" value="0">Animales Domesticos</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="balcon_local" class="check_activate"></span>
      <span id="balcon_local" name="balcon" class="elemento_checkbox checkbox_local_balcon" value="0">Balcón o Terraza</span>
    </div>


    <h3>Exteriores</h3>
    <hr>
    <div class="elemento_checkbox_container">
      <span name="patio_local" class="check_activate"></span>
      <span id="patio_local" name="patio" class="elemento_checkbox checkbox_local" value="0">Jardín / Patio</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="cesped_local" class="check_activate"></span>
      <span id="cesped_local" name="cesped" class="elemento_checkbox checkbox_local" value="0">Cesped</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="parqueo_techado_local" class="check_activate"></span>
      <span id="parqueo_techado_local" name="parqueo_techado" class="elemento_checkbox checkbox_local" value="0">Parqueo Techado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="picina_local" class="check_activate"></span>
      <span id="picina_local" name="picina" class="elemento_checkbox checkbox_local" value="0">Piscina</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="parrillero_local" class="check_activate"></span>
      <span id="parrillero_local" name="parrillero" class="elemento_checkbox checkbox_local" value="0">Parrillero</span>
    </div>


    <h3>Inmueble/Edificio</h3>
    <hr>
    <div class="elemento_checkbox_container">
      <span name="baulera_local" class="check_activate"></span>
      <span id="baulera_local" name="baulera" class="elemento_checkbox checkbox_local" value="0">Baulera</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="camaras_local" class="check_activate"></span>
      <span id="camaras_local" name="camaras" class="elemento_checkbox checkbox_local" value="0">Seguridad / Cámaras</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="portero_local" class="check_activate"></span>
      <span id="portero_local" name="portero" class="elemento_checkbox checkbox_local" value="0">Portero</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="salida_emergencia_local" class="check_activate"></span>
      <span id="salida_emergencia_local" name="salida_emergencia" class="elemento_checkbox checkbox_local" value="0">Salida Emergencia</span>
    </div>

    <span class="button_busqueda_deslizante button_local">Buscar</span>

  </div>

<!-- ############################## FILTROS TERRENO ########################################### -->
  <div class="filtros_container_deslizante filtros_terreno">

    <div class="elemento_select_container">
      <span name="tipo_zona_terreno" class="check_activate"></span>
      <div class="select_wrap">
        <label for="tipo_zona">Tipo Zona</label>
        <select id="tipo_zona_terreno" name="tipo_zona" class="elemento_select select_terreno_tipo_zona" >
          <option class="option_default" value="Todos" selected>Todos</option>
          <option value="Urbana">Urbana</option>
          <option value="Rural">Rural</option>
        </select>
      </div>
    </div>
    <div class="elemento_checkbox_container">
      <span name="residencia_terreno" class="check_activate"></span>
      <span id="residencia_terreno" name="residencia" class="elemento_checkbox checkbox_terreno" value="0">Residencia Privada</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="animales_domesticos_terreno" class="check_activate"></span>
      <span id="animales_domesticos_terreno" name="animales_domesticos" class="elemento_checkbox checkbox_terreno" value="0">Animales Domesticos</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="constructible_terreno" class="check_activate"></span>
      <span id="constructible_terreno" name="constructible" class="elemento_checkbox checkbox_terreno" value="0">Constructible</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="electricidad_terreno" class="check_activate"></span>
      <span id="electricidad_terreno" name="electricidad" class="elemento_checkbox checkbox_terreno" value="0">Electricidad</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="agua_terreno" class="check_activate"></span>
      <span id="agua_terreno" name="agua" class="elemento_checkbox checkbox_terreno" value="0">Agua</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="gaz_domiciliario_terreno" class="check_activate"></span>
      <span id="gaz_domiciliario_terreno" name="gaz_domiciliario" class="elemento_checkbox checkbox_terreno" value="0">Gaz Domiciliario</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="alcantarillado_terreno" class="check_activate"></span>
      <span id="alcantarillado_terreno" name="alcantarillado" class="elemento_checkbox checkbox_terreno" value="0">Alcantarillado</span>
    </div>
    <div class="elemento_checkbox_container">
      <span name="muralla_terreno" class="check_activate"></span>
      <span id="muralla_terreno" name="muralla" class="elemento_checkbox checkbox_terreno" value="0">Muralla</span>
    </div>

    <span class="button_busqueda_deslizante button_terreno">Buscar</span>
  </div>


  <div class="mapa_wrap">
    <div id="mapid_config">

    </div>
  </div>
  <div class="view_coordenadas">
    <input id="view_lat" type="hidden" name="view_lat" value="">
    <input id="view_lng" type="hidden" name="view_lng" value="">
    <input id="view_zoom" type="hidden" name="view_zoom" value="">
  </div>

  <span class="filtros_activos"><p>ACTIVOS</p></span>
  <span class="filtros_btn">
    <i class="fa fa-sliders-h"></i>
    <p>Filtros</p>
  </span>


</div>
    </div>

    <input type="hidden" id="agente_id" name="agente_id" value="<?php echo $usuario;?>">

 </body>
 <script src="../../js/panorama_base_code_ficha_individual.js"></script>
</html>
