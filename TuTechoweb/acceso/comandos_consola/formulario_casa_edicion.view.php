<?php
if(isset($_SESSION['usuario'])){} else{header('Location: ../acceso.php');}; //para evitar que alguien entre directamente al .view.php
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
      <title>Formulario casa</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/formulario_bien_consola.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>


      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script type="text/javascript" src="../../js/jquery.uploadPreview.min.js"></script>
      <script src="../../js/configuracion_formulario_casa.js"></script>
      <script src="../../js/configuracion_formulario_comun.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
   integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>
      <script src="../../js/formulario_bien_mapa_OSM_edit.js"></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
      
 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
      	  <header>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="../../index.php"><img src="../../objetos/logotipo2.svg" alt="Tu Techo.com" class="menu_logo"></a></li>
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

<!-- CONTENIDO PRINCIPAL -->
          <main>
            <div class="regreso_boton_div_contenedor">
            <a href="bien_inmueble_consola.php">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
            </a>
            </div>
            <h1 class="titulo_formulario">Formulario de registro para casas</h1>
            <h1 class="titulo_formulario">Referencia: <?php echo$referencia; ?></h1>

            <?php if(!empty($errores)): ?>
                  <div class="error">
                      <ul>
                         <?php echo $errores; ?>
                      </ul>
                 </div>
           <?php endif; ?>

            <p style="color:white; margin-left: 1em"><span class="fas fa-star"></span> Campos obligatorios</p>
            <form id="formulario_registro_bien" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

              <div id="accordion">
  <!-- PANEL 3 -->
              <h3 id="panel_3">Localización <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_3">

                <div class="elemento_formulario_bien">
                 <label for="pais">País</label>
                 <input id="pais" type="text" autocomplete="off" readonly="readonly" name="pais" value="<?php echo ucfirst($_COOKIE['tutechopais']); ?>">
               </div>

            <div class="elemento_formulario_bien">
              <label for="departamento" class="departamento_label"></label>
              <select name="departamento" id="departamento" style="width:199px;" class="campo_req panel_3 departamento" disabled>
                  <option selected="selected"><?php fill_info_bien('departamento', $info_bien_all); ?></option>
              </select>
            </div>

            <div class="elemento_formulario_bien" style="height:66px;">
              <div style="position:absolute; z-index:2;">
                <label for="ciudad">Ciudad: </label>
                <select name="ciudad" id="ciudad" style="width:199px;" class="campo_req panel_3 ciudad"
                onfocus='this.size=7;'
                onblur='this.size=1;'
                onchange='this.size=1; this.blur();' disabled>
                  <option selected="selected"><?php fill_info_bien('ciudad', $info_bien_all); ?></option>
                </select>
              </div>
            </div>

            <div class="elemento_formulario_bien" style="height:66px;">
              <div style="position:absolute; z-index:1;">
              <label for="barrio">Barrio: </label>
              <select name="barrio" id="barrio" style="width:199px;" onfocus='this.size=7;'
              onblur='this.size=1;'
              onchange='this.size=1; this.blur();' disabled>
              <?php if ($info_bien_all['barrio'] != ''): ?>
                <option selected="selected"><?php fill_info_bien('barrio', $info_bien_all); ?></option>
              <?php endif; ?>
              </select>
              </div>
            </div>

            <div class="elemento_formulario_bien">
                <label for="direccion_bien">Dirección: </label>
                <input id="direccion_bien" type="text" autocomplete="off" name="direccion" class="campo_req panel_3" value="<?php fill_info_bien('direccion', $info_bien_all); ?>" readonly>
              </div>

              <div class="elemento_formulario_bien">
                <label for="direccion_bien_complemento">Complemento: </label>
                <input id="direccion_bien_complemento" type="text" autocomplete="off" placeholder="Edificio, #piso, #departamento" name="direccion_complemento" value="<?php fill_info_bien('direccion_complemento', $info_bien_all); ?>" readonly>
              </div>

              <div class="elemento_formulario_bien">
                <label for="tipo_via"><span class="fas fa-star"></span> Tipo de vía: </label>
                <select name="tipo_via" id="tipo_via" class="campo_req panel_3">
                  <?php if ($info_bien_all['tipo_via'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['tipo_via']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Sendero de tierra</option>
                  <option>Calle empedrada</option>
                  <option>Calle adoquinada</option>
                  <option>Calle asfaltada</option>
                  <option>Avenida empedrada</option>
                  <option>Avenida adoquinada</option>
                  <option>Avenida asfaltada</option>
                  <option>Autopista empedrada</option>
                  <option>Autopista adoquinada</option>
                  <option>Autopista asfaltada</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="aceras"><span class="fas fa-star"></span> Aceras: </label>
                <select name="aceras" id="aceras" class="campo_req panel_3">
                  <?php if ($info_bien_all['aceras'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['aceras']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistentes</option>
                  <option>1 metro (estrecho)</option>
                  <option>1.5 metros (estrecho)</option>
                  <option>2 metros (estandar)</option>
                  <option>2.5 metros (estandar)</option>
                  <option>3 metros (amplio)</option>
                  <option>Más de 3 metros (muy amplio)</option>
                </select>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="residencia" value="0">
                <input type="checkbox" id="residencia_check" name="residencia" value="1" <?php if ($info_bien_all['residencia'] == 1) {echo "checked";} ?>>
                <label for="residencia_check">Residencia privada</label>
              </div>

              </div>
   <!-- PANEL MAPA -->
              <h3 id="panel_MAPA"><span class="fa fa-star"></span> Mapa <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_MAPA">
                  <p style="width:100%; font-size: 1em;">Apunte en el mapa la localisación del bien inmueble (CLICK DERECHO o LONG TOUCH)</p>
                  <div id="mapid" style="height:300px; width:100%; z-index: 1;"></div>
                  <input type="hidden" name="mapa_coordenada_lat" id="mapa_coordenada_lat" class="campo_req panel_MAPA" value="<?php fill_info_bien('mapa_coordenada_lat', $info_bien_all); ?>">
                  <input type="hidden" name="mapa_coordenada_lng" id="mapa_coordenada_lng" class="campo_req panel_MAPA" value="<?php fill_info_bien('mapa_coordenada_lng', $info_bien_all); ?>">
                  <input type="hidden" name="mapa_zoom" id="mapa_zoom" class="campo_req panel_MAPA" value="<?php fill_info_bien('mapa_zoom', $info_bien_all); ?>">
              </div>

   <!-- PANEL 4 -->
              <h3 id="panel_4">Datos Generales <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_4">

              <div class="elemento_formulario_bien">
                <label for="superficie_inmueble">Superficie Inmueble: </label>
                <input id="superficie_inmueble" type="text" autocomplete="off" name="superficie_inmueble" class="campo_req panel_4" value="<?php echo$info_bien_all['superficie_inmueble']; ?>" readonly>
                <select disabled>
                  <option selected>m&sup2</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="superficie_terreno">Superficie Terreno: </label>
                <input id="superficie_terreno" type="text" autocomplete="off" name="superficie_terreno" class="campo_req panel_4" value="<?php echo$info_bien_all['superficie_terreno']; ?>" readonly>
                <select name="superficie_terreno_medida" disabled>
                  <?php if ($info_bien_all['superficie_terreno_medida'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['superficie_terreno_medida']; ?></option>
                  <?php endif; ?>
                </select>
              </div>

              <div class="elemento_formulario_bien" name="div">
                <label for="dormitorios"><span class="fas fa-star"></span> Dormitorios: </label>
                <input id="dormitorios" name="dormitorios" readonly="readonly" class="campo_req panel_4" value="<?php echo$info_bien_all['dormitorios']; ?>">
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="handicap" value="0">
                <input type="checkbox" name="handicap" id="handicap" value="1" <?php if ($info_bien_all['handicap'] == 1) {echo "checked";} ?>>
                <label for="handicap">Handicap </label>
                <div id="info_ventana_handicap" class="info_ventana" title="Información Útil">
                  <p>Handicap: ¿Está el bien inmueble adaptado a personas en sillas de ruedas u otra discapacidad motríz?</p>
                </div>
                <button id="opener_handicap" class="info_ventana_boton"> ? </button>
              </div>

              <div class="elemento_formulario_bien">
                <label for="parqueos"><span class="fas fa-star"></span> Parqueos: </label>
                <input id="parqueos" name="parqueos" readonly="readonly" class="campo_req panel_4" value="<?php echo$info_bien_all['parqueos']; ?>">
              </div>

              <div class="elemento_formulario_bien">
                <label for="precio">Precio: </label>
                <input id="precio" type="text" autocomplete="off" name="precio" class="campo_req panel_4" value="<?php echo$info_bien_all['precio']; ?>" readonly>
                <select id="moneda" name="precio_moneda" disabled>
                  <?php if ($moneda != ''): ?>
                    <option selected='selected'><?php echo $moneda; ?></option>
                  <?php endif; ?>
                </select>
              </div>
          </div>

    <!-- PANEL 5 -->

              <h3 id="panel_5">Interiores <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_5">

              <div class="elemento_de_linea" style="display:flex">
                <div>
                <label for="pisos"><span class="fas fa-star"></span> Número de pisos: </label>
                <input id="pisos" name="pisos" readonly="readonly" class="campo_req panel_5" value="<?php echo$info_bien_all['pisos']; ?>">
                </div>
                <div id="info_ventana_pisos" class="info_ventana" title="Información Útil">
                  <p>El numero de pisos NO debe incluir sotanos ni alcobas que sean demasiado bajas para que una persona esté de pie (que pueda contener una habitación)</p>
                </div>
                <button id="opener_pisos" class="info_ventana_boton" style="margin: 40px 0px 0px 10px"> ? </button>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="acensor" value="0">
                <input type="checkbox" name="acensor" id="acensor_check" value="1" <?php if ($info_bien_all['acensor'] == 1) {echo "checked";} ?>>
                <label for="acensor_check">Acensor </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="intercomunicador" value="0">
                <input type="checkbox" name="intercomunicador" id="intercomunicador" value="1" <?php if ($info_bien_all['intercomunicador'] == 1) {echo "checked";} ?>>
                <label for="intercomunicador">Intercomunicador </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="hab_planta_baja" value="0">
                <input type="checkbox" name="hab_planta_baja" id="hab_planta_baja" value="1" <?php if ($info_bien_all['hab_planta_baja'] == 1) {echo "checked";} ?>>
                <label for="hab_planta_baja" style="font-size: 0.9em;">*Habitacion(es) en plata baja </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="amoblado" value="0">
                <input type="checkbox" name="amoblado" id="amoblado" value="1" <?php if ($info_bien_all['amoblado'] == 1) {echo "checked";} ?>>
                <label for="amoblado">Amoblado </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="sotano" value="0">
                <input type="checkbox" name="sotano" id="sotano" value="1" <?php if ($info_bien_all['sotano'] == 1) {echo "checked";} ?>>
                <label for="sotano">Sotano </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="alcoba" value="0">
                <input type="checkbox" name="alcoba" id="alcoba" value="1" <?php if ($info_bien_all['alcoba'] == 1) {echo "checked";} ?>>
                <label for="alcoba">Alcoba </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="lavanderia" value="0">
                <input type="checkbox" name="lavanderia" id="lavanderia" value="1" <?php if ($info_bien_all['lavanderia'] == 1) {echo "checked";} ?>>
                <label for="lavanderia">Lavanderia </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="balcon" value="0">
                <input type="checkbox" name="balcon" id="balcones" value="1" <?php if ($info_bien_all['balcon'] == 1) {echo "checked";} ?>>
                <label for="balcones">Balcón(es) </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="baulera" value="0">
                <input type="checkbox" name="baulera" id="baulera" value="1" <?php if ($info_bien_all['baulera'] == 1) {echo "checked";} ?>>
                <label for="baulera">Baulera </label>
              </div>

              <div class="elemento_relleno">
                <!-- elemento de relleno para maquetacion -->
              </div>

              <hr class="barra_separadora"></br>

              <h2 class="titulo_h2">Cocina</h2>

              <div class="elemento_formulario_bien">
                <label for="cocina_tipo"><span class="fas fa-star"></span> Cocina: </label>
                <select name="cocina" id="cocina" class="campo_req panel_5">
                  <?php if ($info_bien_all['cocina'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['cocina']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistente</option>
                  <option>Cerrada</option>
                  <option>Kitchinette</option>
                  <option>Americana</option>
                  <option>Comunal, compartida</option>
                </select>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="equipada" value="0">
                <input type="checkbox" name="equipada" id="cocina_equipada" value="1" <?php if ($info_bien_all['equipada'] == 1) {echo "checked";} ?>>
                <label for="cocina_equipada">Equipada </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="alacena" value="0">
                <input type="checkbox" name="alacena" id="alacena" value="1" <?php if ($info_bien_all['alacena'] == 1) {echo "checked";} ?>>
                <label for="alacena">Alacena/Despensa </label>
              </div>

              <hr class="barra_separadora"></br>

              <h2 class="titulo_h2">Baños</h2>

              <div class="elemento_formulario_bien">
                <label for="wc"><span class="fas fa-star"></span> Número de baños: </label>
                <input id="wc" name="wc" readonly="readonly" class="campo_req panel_5" value="<?php echo$info_bien_all['wc']; ?>">
              </div>

              <div class="elemento_formulario_bien">
                <label for="ducha_tipo"><span class="fas fa-star"></span> Tipo de ducha: </label>
                <select name="tipo_ducha" id="tipo_ducha" class="campo_req panel_5">
                  <?php if ($info_bien_all['tipo_ducha'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['tipo_ducha']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistente</option>
                  <option>Electrica</option>
                  <option>Calefón electrico</option>
                  <option>Calefón a gaz</option>
                  <option>Vertiente termal</option>
                  <option>Vertiente fria</option>
                  <option>Reserva de agua</option>
                  <option>Calentador comunal</option>
                  <option>Panel solar</option>
                </select>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="wc_separado" value="0">
                <input type="checkbox" name="wc_separado" id="wc_separado" value="1" <?php if ($info_bien_all['wc_separado'] == 1) {echo "checked";} ?>>
                <label for="wc_separado">Ducha/WC seprados </label>
              </div>

              <hr class="barra_separadora"></br>

              <h2 class="titulo_h2">Más datos</h2>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="gaz_domiciliario" value="0">
                <input type="checkbox" name="gaz_domiciliario" id="gaz_domiciliario" value="1" <?php if ($info_bien_all['gaz_domiciliario'] == 1) {echo "checked";} ?>>
                <label for="gaz_domiciliario">Gaz domiciliario </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="aire_acondicionado" value="0">
                <input type="checkbox" name="aire_acondicionado" id="aire_acondicionado" value="1" <?php if ($info_bien_all['aire_acondicionado'] == 1) {echo "checked";} ?>>
                <label for="aire_acondicionado">Aire acondicionado </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="chimenea" value="0">
                <input type="checkbox" name="chimenea" id="chimenea" value="1" <?php if ($info_bien_all['chimenea'] == 1) {echo "checked";} ?>>
                <label for="chimenea">Chimenea </label>
              </div>

              <div class="elemento_formulario_bien">
                <label for="ventanas"><span class="fas fa-star"></span> Ventanas: </label>
                <select name="ventanas" id="ventanas" class="campo_req panel_5">
                  <?php if ($info_bien_all['ventanas'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['ventanas']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistentes</option>
                  <option>Vidrio normal</option>
                  <option>Vidrios laminados</option>
                  <option>Doble vitraje</option>
                  <option>Vidrios blindados</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="calefaccion">Calefacción: </label>
                <select name="calefaccion" id="calefaccion">
                  <?php if ($info_bien_all['calefaccion'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['calefaccion']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistente</option>
                  <option>Electrica</option>
                  <option>A gaz</option>
                  <option>Solar</option>
                  <option>Comunal</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="conexion_electrica"><span class="fas fa-star"></span> Conexion Electrica: </label>
                <select name="conexion_electrica" id="conexion_electrica" class="campo_req panel_5">
                  <?php if ($info_bien_all['conexion_electrica'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['conexion_electrica']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>220V</option>
                  <option>110V</option>
                  <option>220V/110V</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="cobertura"><span class="fas fa-star"></span> Cobertura móvil: </label>
                <select name="cobertura" id="cobertura" class="campo_req panel_5">
                  <?php if ($info_bien_all['cobertura'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['cobertura']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistente</option>
                  <option>Baja</option>
                  <option>Media</option>
                  <option>Alta</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="internet"><span class="fas fa-star"></span> Conección internet: </label>
                <select name="internet" id="internet" class="campo_req panel_5">
                  <?php if ($info_bien_all['internet'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['internet']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistente</option>
                  <option>ADSL</option>
                  <option>Fibra Óptica</option>
                  <option>Satélital</option>
                  <option>Cobertura red inalambrica(modem)</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="tv_cable"><span class="fas fa-star"></span> Conección TV: </label>
                <select name="tv_cable" id="tv_cable" class="campo_req panel_5">
                  <?php if ($info_bien_all['tv_cable'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['tv_cable']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Inexistente</option>
                  <option>Antena Externa</option>
                  <option>TV cable</option>
                  <option>Satélital</option>
                  <option>TV Digital Terrestre(TDT)</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="ruido_interno">Ruido interno: </label>
                <input id="ruido_interno" type="text" autocomplete="off" onChange="validarSiNumero(this.value);" name="ruido_interno" value="<?php fill_info_bien('ruido_interno', $info_bien_all); ?>">
                <select>
                  <option>Decibelios(Db)</option>
                </select>
              </div>

              <hr class="barra_separadora"></br>
              <h2 class="titulo_h2">Resumen</h2>

              <div class="elemento_formulario_bien">
                <label for="interior_estado"><span class="fas fa-star"></span> Estado general: </label>
                <select name="interior_estado" id="interior_estado" class="campo_req panel_5">
                  <?php if ($info_bien_all['interior_estado'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['interior_estado']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>A renovar</option>
                  <option>Trabajos necesarios</option>
                  <option>Buen estado</option>
                  <option>Excelente estado</option>
                  <option>A estrenar</option>
                </select>
              </div>
              </div>


    <!-- PANEL 6 -->
              <h3 id="panel_6">Exteriores <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_6">

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="jardin" value="0">
                <input type="checkbox" name="jardin" id="jardin" value="1" <?php if ($info_bien_all['jardin'] == 1) {echo "checked";} ?>>
                <label for="jardin">Jardín/Patio </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="cesped" value="0">
                <input type="checkbox" name="cesped" id="cesped" value="1" <?php if ($info_bien_all['cesped'] == 1) {echo "checked";} ?>>
                <label for="cesped">Cesped </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="jardin_terraza" value="0">
                <input type="checkbox" name="jardin_terraza" id="jardin_terraza" value="1" <?php if ($info_bien_all['jardin_terraza'] == 1) {echo "checked";} ?>>
                <label for="jardin_terraza">Terraza de jardín </label>
              </div>

              <div class="elemento_formulario_bien">
                <label for="jardin_superficie">Superficie jardín/patio: </label>
                <input id="jardin_superficie" type="text" autocomplete="off" onChange="validarSiNumero(this.value);" name="jardin_superficie" value="<?php fill_info_bien('jardin_superficie', $info_bien_all); ?>">
                <select name="jardin_superficie_medida">
                  <?php if ($info_bien_all['jardin_superficie_medida'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['jardin_superficie_medida']; ?></option>
                  <?php endif; ?>
                  <option>m&sup2</option>
                  <option>Km&sup2</option>
                  <option>Hect</option>
                </select>
              </div>

              <hr class="barra_separadora"></br>
              <h2 class="titulo_h2">Parqueo</h2>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="parqueo_techado" value="0">
                <input type="checkbox" name="parqueo_techado" id="parqueo_techado" value="1" <?php if ($info_bien_all['parqueo_techado'] == 1) {echo "checked";} ?>>
                <label for="parqueo_techado">Parqueo techado </label>
              </div>

              <div class="elemento_formulario_bien">
                <label for="porton">Porton: </label>
                <select name="porton" id="porton">
                  <?php if ($info_bien_all['porton'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['porton']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Manual</option>
                  <option>Semi-automático</option>
                  <option>Automático</option>
                </select>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="parqueo_recarga" value="0">
                <input type="checkbox" name="parqueo_recarga" id="parqueo_recarga" value="1" <?php if ($info_bien_all['parqueo_recarga'] == 1) {echo "checked";} ?>>
                <label for="parqueo_recarga">Estación de recarga </label>
              </div>

              <hr class="barra_separadora"></br>
              <h2 class="titulo_h2">Vista</h2>

              <div class="elemento_formulario_bien">
                <label for="vista">Vista: </label>
                <select name="vista" id="vista">
                  <?php if ($info_bien_all['vista'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['vista']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Despejada</option>
                  <option>Despajada, urbana</option>
                  <option>Vecinos</option>
                  <option>Montañas</option>
                  <option>Calle</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="exposicion"><span class="fas fa-star"></span> Exposición: </label>
                <select name="exposicion" id="exposicion" class="campo_req panel_6">
                  <?php if ($info_bien_all['exposicion'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['exposicion']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Sin exposición al Sol</option>
                  <option>Sur</option>
                  <option>Norte</option>
                  <option>Este</option>
                  <option>Oeste</option>
                  <option>Nordeste</option>
                  <option>Nordoeste</option>
                  <option>Sudeste</option>
                  <option>Sudoeste</option>
                  <option>Norte-Sur</option>
                  <option>Este-Oeste</option>
                  <option>Global, 360°</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="ruido_externo">Ruido externo: </label>
                <input id="ruido_externo" type="text" autocomplete="off" onChange="validarSiNumero(this.value);" name="ruido_externo" value="<?php fill_info_bien('ruido_externo', $info_bien_all); ?>">
                <select>
                  <option>Decibelios(Db)</option>
                </select>
              </div>

              <hr class="barra_separadora"></br>
              <h2 class="titulo_h2">Más datos</h2>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="picina" value="0">
                <input type="checkbox" name="picina" id="picina" value="1" <?php if ($info_bien_all['picina'] == 1) {echo "checked";} ?>>
                <label for="picina">Picina </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="parrillero" value="0">
                <input type="checkbox" name="parrillero" id="parrillero" value="1" <?php if ($info_bien_all['parrillero'] == 1) {echo "checked";} ?>>
                <label for="parrillero">Parrillero </label>
              </div>

              <div class="elemento_relleno">
                <!-- eleme de relleno para maquetacion -->
              </div>

              <hr class="barra_separadora"></br>
              <h2 class="titulo_h2">Resumen</h2>

              <div class="elemento_formulario_bien">
                <label for="jardin_estado"><span class="fas fa-star"></span> Estado general: </label>
                <select name="jardin_estado" id="jardin_estado" class="campo_req panel_6">
                  <?php if ($info_bien_all['jardin_estado'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['jardin_estado']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>A renovar</option>
                  <option>Trabajos necesarios</option>
                  <option>Buen estado</option>
                  <option>Excelente estado</option>
                  <option>Sin exteriores</option>
                </select>
              </div>


   <!-- PANEL 7 -->
              </div>
              <h3 id="panel_7">Otros <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_7">

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="sauna" value="0">
                <input type="checkbox" name="sauna" id="sauna" value="1" <?php if ($info_bien_all['sauna'] == 1) {echo "checked";} ?>>
                <label for="sauna">Sauna </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="jacuzzi" value="0">
                <input type="checkbox" name="jacuzzi" id="jacuzzi" value="1" <?php if ($info_bien_all['jacuzzi'] == 1) {echo "checked";} ?>>
                <label for="jacuzzi">Jacuzzi </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="gimnasio" value="0">
                <input type="checkbox" name="gimnasio" id="gimnasio" value="1" <?php if ($info_bien_all['gimnasio'] == 1) {echo "checked";} ?>>
                <label for="gimnasio">Gimnasio </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="alcantarillado" value="0">
                <input type="checkbox" name="alcantarillado" id="alcantarillado" value="1" <?php if ($info_bien_all['alcantarillado'] == 1) {echo "checked";} ?>>
                <label for="alcantarillado">Alcantarillado </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="desague" value="0">
                <input type="checkbox" name="desague" id="desague" value="1" <?php if ($info_bien_all['desague'] == 1) {echo "checked";} ?>>
                <label for="desague">Desagüe </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="animales_domesticos" value="0">
                <input type="checkbox" name="animales_domesticos" id="animales_domesticos" value="1" <?php if ($info_bien_all['animales_domesticos'] == 1) {echo "checked";} ?>>
                <label for="animales_domesticos">Animales Domesticos </label>
              </div>

              <div class="elemento_formulario_bien">
                <label for="reserva_agua">Reserva/Tanque de agua: </label>
                <input id="reserva_agua" name="reserva_agua" onChange="validarSiNumero(this.value);" value="<?php fill_info_bien('reserva_agua', $info_bien_all); ?>">
                  <select name="reserva_agua_medida">
                    <?php if ($info_bien_all['reserva_agua_medida'] != ''): ?>
                      <option selected='selected'><?php echo $info_bien_all['reserva_agua_medida']; ?></option>
                    <?php endif; ?>
                    <option>Litros</option>
                    <option>Galones</option>
                  </select>
                </div>

              <div class="elemento_formulario_bien checkbox_container">
                  <input type="hidden" name="reserva_compartida" value="0">
                  <input type="checkbox" name="reserva_compartida" id="reserva_compartida" value="1" <?php if ($info_bien_all['reserva_compartida'] == 1) {echo "checked";} ?>>
                <label for="reserva_compartida">Reserva compartida </label>
              </div>

              </div>

  <!-- PANEL 8 -->
              <h3 id="panel_8"><span class="fa fa-star"></span> Descripción <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_8">

                <textarea id="descripcion_bien" name="descripcion_bien" rows="10" cols="100" placeholder="Descripción detallada del bien inmobiliario (Máximo 1000 carácteres)" class="campo_req panel_8"><?php fill_info_bien('descripcion_bien', $info_bien_all); ?></textarea>

              </div>

  <!-- PANEL 9 -->
              <h3 id="panel_9">Información De La Zona</h3>
              <div class="ui-widget section_container" name="panel_9">

                <h3 class="titulo_h2">Distancias en minutos</h3>
                <h4 class="titulo_h2">*Distancias de 0 min, indican que el servicio no está disponible</h4>

              <div class="elemento_formulario_bien">
                <label for="parada_bus">Parada bús: </label>
                <input id="parada_bus" type="text" autocomplete="off" readonly="readonly" name="parada_bus" value="<?php echo fill_info_bien('parada_bus', $info_bien_all); ?>">
              </div>

              <div class="elemento_formulario_bien">
                <label for="teleferico">Teleférico: </label>
                <input id="teleferico" type="text" autocomplete="off" readonly="readonly" name="teleferico" value="<?php fill_info_bien('teleferico', $info_bien_all); ?>">
              </div>

              <div class="elemento_formulario_bien">
                <label for="supermercado">Supermercado: </label>
                <input id="supermercado" type="text" autocomplete="off" readonly="readonly" name="supermercado" value="<?php fill_info_bien('supermercado', $info_bien_all); ?>">
              </div>

              <div class="elemento_formulario_bien">
                <label for="farmacia">Farmacia: </label>
                <input id="farmacia" type="text" autocomplete="off" readonly="readonly" name="farmacia" value="<?php fill_info_bien('farmacia', $info_bien_all); ?>">
              </div>

              <div class="elemento_formulario_bien">
                <label for="guarderia">Guardería: </label>
                <input id="guarderia" type="text" autocomplete="off" readonly="readonly" name="guarderia" value="<?php fill_info_bien('guarderia', $info_bien_all); ?>">
              </div>

              <div class="elemento_formulario_bien">
                <label for="escuela">Escuela: </label>
                <input id="escuela" type="text" autocomplete="off" readonly="readonly" name="escuela" value="<?php fill_info_bien('escuela', $info_bien_all); ?>">
              </div>
              <div class="elemento_formulario_bien">
                <label for="policia">Retén Policial: </label>
                <input id="policia" type="text" autocomplete="off" readonly="readonly" name="policia" value="<?php fill_info_bien('policia', $info_bien_all); ?>">
              </div>
              <div class="elemento_formulario_bien">
                <label for="hospital">Hospital/Clinica: </label>
                <input id="hospital" type="text" autocomplete="off" readonly="readonly" name="hospital" value="<?php fill_info_bien('hospital', $info_bien_all); ?>">
              </div>
              <div class="elemento_formulario_bien">
                <label for="area_verde">Area Verde: </label>
                <input id="area_verde" type="text" autocomplete="off" readonly="readonly" name="area_verde" value="<?php fill_info_bien('area_verde', $info_bien_all); ?>">
              </div>

              </div>

    <!-- PANEL 12 -->
              <h3 id="panel_12">Comentarios</h3>
              <div class="ui-widget section_container" name="panel_12">
                <textarea name="comentarios_bien" rows="10" cols="100" placeholder="Comentarios acerca de este bien (Máximo 1500 carácteres). Estos comentarios no aparecerán en el sitio web, son pura información útil para el agente"><?php fill_info_bien('comentarios_bien', $info_bien_all) ?></textarea>
              </div>

            </div>  <!-- Fin del accordion -->

            <div id="boton_fin_formulario_contenedor">
              <?php if ($modo == 'first_entry'): ?>
                <button type="submit" name="guardar_borrador" value="guardar_borrador" class="boton_fin_formulario">
                  <i class="far fa-save" aria-hidden="true"></i><p>Guardar borrador</p>
                </button>
              <?php endif; ?>
              <button type="button" id="boton_validar_form" name="validar_datos" class="boton_fin_formulario">
                <i class="fas fa-clipboard-check"></i><p> Validar formulario</p>
              </button>
              <button  id="boton_submit_form" type="button" name="registar_datos" value="registar_datos" class="boton_fin_formulario">
                <i class="fa fa-download" aria-hidden="true"></i><p>Registrar datos</p>
              </button>
            </div>

            <input type="hidden" id="referencia" name="referencia" value="<?php echo $referencia; ?>">
            <input type="hidden" id="modo" name="modo" value="<?php echo $modo; ?>">
            <input type="hidden" id="tabla" name="tabla" value="<?php echo $tabla_bien; ?>">
          </form>

          <div id="dialog" title="Formulario no valido"> <!-- alert message linked to validar datos button -->
            <p><span class="fas fa-star"></span> Quedan campos obligatorios por llenar.</p>
          </div>

          <?php if(!empty($errores)): ?>
                <div class="error">
                    <ul>
                       <?php echo $errores; ?>
                    </ul>
               </div>
         <?php endif; ?>

        </main>
    </div>

 </body>
</html>
