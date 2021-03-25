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
      <title>Formulario terreno</title>
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
      <script src="../../js/configuracion_formulario_terreno.js"></script>
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
            <h1 class="titulo_formulario">Formulario De Registro para terrenos</h1>
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
              <h3 id="panel_3">Localisación <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_3">

                <div class="elemento_formulario_bien">
                 <label for="pais">País</label>
                 <input id="pais" type="text" autocomplete="off" readonly="readonly" name="pais" value="<?php echo ucfirst($_COOKIE['tutechopais']); ?>">
               </div>

            <div class="elemento_formulario_bien">
              <label for="departamento"><span class="fas fa-star"></span> Departamento: </label>
              <select name="departamento" id="departamento" style="width:199px;" class="campo_req panel_3 departamento" disabled>
                <?php if ($info_bien_all['departamento'] != ''): ?>
                  <option selected="selected"><?php echo$info_bien_all['departamento']; ?></option>
                <?php endif; ?>
              </select>
            </div>

            <div class="elemento_formulario_bien" style="height:66px;">
              <div style="position:absolute; z-index:2;">
                <label for="ciudad"><span class="fas fa-star"></span>Ciudad: </label>
                <select name="ciudad" id="ciudad" style="width:199px;" class="campo_req panel_3 ciudad"
                onfocus='this.size=7;'
                onblur='this.size=1;'
                onchange='this.size=1; this.blur();' disabled>
                <?php if ($info_bien_all['ciudad'] != ''): ?>
                  <option selected="selected"><?php echo$info_bien_all['ciudad']; ?></option>
                <?php endif; ?>
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
                <label for="direccion_bien"><span class="fas fa-star"></span> Dirección: </label>
                <input id="direccion_bien" type="text" autocomplete="off" placeholder="#numero, calle/avenida" name="direccion" class="campo_req panel_3" value="<?php fill_info_bien('direccion', $info_bien_all); ?>" readonly>
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
             <h3 id="panel_MAPA">Mapa <i class="fas fa-circle"></i></h3>
             <div class="ui-widget section_container" name="panel_MAPA">
                 <p style="width:100%; font-size: 1em;">Apunte en el mapa la localisación del bien inmueble (DOBLE CLIC)</p>
                 <div id="mapid" style="height:300px; width:100%;"></div>
                 <input type="hidden" name="mapa_coordenada_lat" id="mapa_coordenada_lat" class="campo_req panel_MAPA" value="<?php fill_info_bien('mapa_coordenada_lat', $info_bien_all); ?>">
                 <input type="hidden" name="mapa_coordenada_lng" id="mapa_coordenada_lng" class="campo_req panel_MAPA" value="<?php fill_info_bien('mapa_coordenada_lng', $info_bien_all); ?>">
                 <input type="hidden" name="mapa_zoom" id="mapa_zoom" class="campo_req panel_MAPA" value="<?php fill_info_bien('mapa_zoom', $info_bien_all); ?>">
             </div>

   <!-- PANEL 4 -->
              <h3 id="panel_4">Datos Generales <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_4">

              <div class="elemento_formulario_bien">
                <label for="superficie_terreno"><span class="fas fa-star"></span> Superficie Terreno: </label>
                <input id="superficie_terreno" type="text" autocomplete="off" onChange="validarSiNumero(this.value);" name="superficie_terreno" class="campo_req panel_4" value="<?php fill_info_bien('superficie_terreno', $info_bien_all); ?>" readonly>
                <select name="superficie_terreno_medida" disabled>
                  <?php if ($info_bien_all['superficie_terreno_medida'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['superficie_terreno_medida']; ?></option>
                  <?php endif; ?>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="precio"><span class="fas fa-star"></span> Precio: </label>
                <input id="precio" type="text" autocomplete="off" onChange="validarSiNumero(this.value);" name="precio" class="campo_req panel_4" value="<?php fill_info_bien('precio', $info_bien_all); ?>" readonly>
                <select id="moneda" name="precio_moneda" disabled>
                  <?php if ($moneda != ''): ?>
                    <option selected='selected'><?php echo $moneda; ?></option>
                  <?php endif; ?>
                </select>
              </div>

              </div>

   <!-- PANEL 5 -->
              <h3 id="panel_5">Otros Datos <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_5">

              <div class="elemento_formulario_bien">
                <label for="tipo_zona"><span class="fas fa-star"></span> Tipo de zona</label>
                <select name="tipo_zona" id="tipo_zona" class="campo_req panel_5">
                  <?php if ($info_bien_all['tipo_zona'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['tipo_zona']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Urbana</option>
                  <option>Rural</option>
                </select>
              </div>

              <div class="elemento_formulario_bien">
                <label for="geografia">Geografía</label>
                <select name="geografia" id="geografia">
                  <?php if ($info_bien_all['geografia'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['geografia']; ?></option>
                  <?php endif; ?>
                  <option></option>
                  <option>Valle</option>
                  <option>Arido</option>
                  <option>Trópico</option>
                  <option>Selva</option>
                  <option>Montañas</option>
                  <option>Altiplano</option>
                  <option>Borde de río</option>
                  <option>Borde de lago</option>
                </select>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="residencia" value="0">
                <input type="checkbox" name="residencia" id="residencia" value="1" <?php if ($info_bien_all['residencia'] == 1) {echo "checked";} ?>>
                <label for="residencia">Residencia Privada</label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="muralla" value="0">
                <input type="checkbox" name="muralla" id="muralla" value="1" <?php if ($info_bien_all['muralla'] == 1) {echo "checked";} ?>>
                <label for="muralla">Muro/cerca perimetral</label>
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
                <input type="hidden" name="constructible" value="0">
                <input type="checkbox" name="constructible" id="constructible" value="1" <?php if ($info_bien_all['constructible'] == 1) {echo "checked";} ?>>
                <label for="constructible">Constructible</label>
              </div>

              <div class="elemento_formulario_bien">
                <label for="altura_max">Construcción: Altura-max</label>
                <input id="altura_max" type="text" autocomplete="off" onChange="validarSiNumero(this.value);" name="altura_max" value="<?php fill_info_bien('altura_max', $info_bien_all); ?>">
                <select name="altura_max_medida">
                  <?php if ($info_bien_all['altura_max_medida'] != ''): ?>
                    <option selected='selected'><?php echo $info_bien_all['altura_max_medida']; ?></option>
                  <?php endif; ?>
                  <option>metros</option>
                  <option>pisos</option>
                </select>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="agua" value="0">
                <input type="checkbox" name="agua" id="agua" value="1" <?php if ($info_bien_all['agua'] == 1) {echo "checked";} ?>>
                <label for="agua">Conexion Agua</label>
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
                <input type="hidden" name="electricidad" value="0">
                <input type="checkbox" name="electricidad" id="electricidad" value="1" <?php if ($info_bien_all['electricidad'] == 1) {echo "checked";} ?>>
                <label for="electricidad">Electricidad</label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="gaz_domiciliario" value="0">
                <input type="checkbox" name="gaz_domiciliario" id="gaz_domiciliario" value="1" <?php if ($info_bien_all['gaz_domiciliario'] == 1) {echo "checked";} ?>>
                <label for="gaz_domiciliario">Gaz domiciliario </label>
              </div>

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="zona_franca" value="0">
                <input type="checkbox" name="zona_franca" id="zona_franca" value="1" <?php if ($info_bien_all['zona_franca'] == 1) {echo "checked";} ?>>
                <label for="zona_franca">Zona Franca</label>
              </div>

              <div class="elemento_formulario_bien">
                <label for="ruido_externo">Ruido externo: </label>
                <input id="ruido_externo" type="text" autocomplete="off" onChange="validarSiNumero(this.value);" name="ruido_externo" value="<?php fill_info_bien('ruido_externo', $info_bien_all); ?>">
                <select>
                  <option>Decibelios(Db)</option>
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

              <div class="elemento_formulario_bien checkbox_container">
                <input type="hidden" name="animales_domesticos" value="0">
                <input type="checkbox" name="animales_domesticos" id="animales_domesticos" value="1" <?php if ($info_bien_all['animales_domesticos'] == 1) {echo "checked";} ?>>
                <label for="animales_domesticos">Animales Domesticos </label>
              </div>

              </div>

  <!-- PANEL 6 -->
              <h3 id="panel_6"><span class="fa fa-star"></span> Descripción <i class="fas fa-circle"></i></h3>
              <div class="ui-widget section_container" name="panel_6">

                <textarea id="descripcion_bien" name="descripcion_bien" rows="10" cols="100" placeholder="Descripción detallada del bien inmobiliario (Máximo 1000 carácteres)" class="campo_req panel_8"><?php fill_info_bien('descripcion_bien', $info_bien_all); ?></textarea>

              </div>

  <!-- PANEL7 -->
              <h3 id="panel_7">Información De La Zona</h3>
              <div class="ui-widget section_container" name="panel_7">

                <h3 class="titulo_h2">Distancias en minutos</h3>
                <h4 class="titulo_h2">*Distancias de 0 min, indican que el servicio no está disponible</h4>

                <div class="elemento_formulario_bien">
                  <label for="parada_bus">Parada bús: </label>
                  <input id="parada_bus" type="text" autocomplete="off" readonly="readonly" name="parada_bus" value="<?php echo$info_bien_all['parada_bus']; ?>">
                </div>

                <div class="elemento_formulario_bien">
                  <label for="teleferico">Teleférico: </label>
                  <input id="teleferico" type="text" autocomplete="off" readonly="readonly" name="teleferico" value="<?php echo$info_bien_all['teleferico']; ?>">
                </div>

                <div class="elemento_formulario_bien">
                  <label for="supermercado">Supermercado: </label>
                  <input id="supermercado" type="text" autocomplete="off" readonly="readonly" name="supermercado" value="<?php echo$info_bien_all['supermercado']; ?>">
                </div>

                <div class="elemento_formulario_bien">
                  <label for="farmacia">Farmacia: </label>
                  <input id="farmacia" type="text" autocomplete="off" readonly="readonly" name="farmacia" value="<?php echo$info_bien_all['farmacia']; ?>">
                </div>

                <div class="elemento_formulario_bien">
                  <label for="guarderia">Guardería: </label>
                  <input id="guarderia" type="text" autocomplete="off" readonly="readonly" name="guarderia" value="<?php echo$info_bien_all['guarderia']; ?>">
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

    <!-- PANEL 10 -->
              <h3 id="panel_10">Comentarios</h3>
              <div class="ui-widget section_container" name="panel_13">
                <textarea name="comentarios_bien" rows="10" cols="100" placeholder="Comentarios acerca de este bien (Máximo 1500 carácteres). Estos comentarios no aparecerán en el sitio web, son pura información administrativa"><?php fill_info_bien('comentarios_bien', $info_bien_all); ?></textarea>
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
