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
      <title><?php if($the_call !== ''){if($borrador_call !== ''){echo"Borrador Sponsor";};if($editor_call !== ''){echo"Edición Sponsors";}}else{echo"Registro Sponsor";} ?></title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/plataforma_sponsor_consola.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>

      <script>
      function check_image(element){
        const fileExtension = ['jpg', 'svg', 'png'];
        if ($(element).val() !== "") {
          if ($.inArray($(element).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Formato de imagen no admitido");
            
            $(element).val("").trigger("change");
          };
        }; 
      };
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script type="text/javascript" src="../../js/jquery.uploadPreview.min.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
   integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>
     <script src="../../js/sponsor_popup_mapa_OSM_registro.js"></script>
     <script src="../../js/registro_sponsor.js"></script>
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
            <a href="<?php if($borrador_call !== ''){echo "borradores_sponsors_consola.php";}else {if($editor_call !== ''){echo "editor_sponsors_consola.php";}else {echo "sponsors_consola.php";}} ?>">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
            </a>
            </div>

            <div class="popup_errores">
              <span class="popup_errores_cerrar"><i class="fa fa-times"></i></span>
              <i class="fa fa-exclamation-circle"></i>Datos incompletos o incorrectos
            </div>

            <div class="overlay_popup_verificacion_nueva_sucursal">
                <div class="popup_verificacion_nueva_sucursal">
                  <p class="popup_sucursal_texto"></p>
                  <div class="popup_sucursal_lista"></div>
                  <p class="popup_sucursal_pregunta"></p>
                  <div class="popup_nueva_sucursal_botones_container">
                    <span class="btn_cancelar"><i class="fa fa-times"></i>Cancelar</span>
                    <span class="btn_crear_nueva_sucursal"><i class="fa fa-check"></i><span></span></span>
                  </div>
                </div>
            </div>

            <h1 class="titulo_formulario"><?php if($the_call !== ''){if($borrador_call !== ''){echo"Plataforma de Borrador Sponsor";};if($editor_call !== ''){echo"Plataforma de Edición para Sponsors";}}else{echo"Plataforma de Registro para Sponsors";} ?></h1>

            <form id="formulario_registro_sponsor" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

              <div class="contenedor_plataforma_sponsor_edicion">

                <div class="pop_up_visualizer">
                  <div class="relative_container">
                    <div class="sticky_container">
                      <div class="switch_container">
                      <h2>Vista Preliminar del Anuncio:</h2>
                      <span class="boton_ver_vista_preliminar"><i class="fa fa-eye-slash"></i></span>
                      <div class="switch_vista_preliminar">
                        <span class="switch_desktop switch active"><i class="fa fa-desktop"></i></span>
                        <span class="switch_mobile switch"><i class="fa fa-mobile"></i></span>
                      </div>
                      </div>

                      <div class="popups_container">

                            <div class="popup_sponsor popup_visible" style="background-color:<?php check_borrador_info('borde', $info_borrador, 'rgba(0, 0, 0, 0.88)') ?>">
                              <span class="popup_sponsor_cerrar fa fa-times"></span>
                              <span class="popup_sponsor_illustration" style="<?php if (isset($info_borrador['ilustracion'])) {if ($info_borrador['ilustracion'] !== '') {echo "background-image: url(" . $info_borrador['ilustracion'] . ")";};} ?>"></span>
                              <div class="popup_sponsor_info_container">
                                <div id="popup_sponsor_mapa" class="popup_sponsor_mapa">
                                  <div id="mapid_sponsor"></div>
                                </div>
                                <div class="popup_sponsor_info">
                                  <div class="popup_sponsor_titulo">
                                    <span id="logo_preview1" class="logo_preview <?php if (isset($info_borrador['logo'])) {if ($info_borrador['logo'] !== '') {echo "filled";};} ?>"
                                    style="<?php if (isset($info_borrador['logo'])) {if ($info_borrador['logo'] !== '') {echo "background-image: url(" . $info_borrador['logo'] . "); background-size: contain";};} ?>">
                                    <?php if (isset($info_borrador['logo'])){if($info_borrador['logo'] == ''){echo "LOGO";};}else{echo "LOGO";} ?>
                                    </span>
                                    <label><?php check_borrador_info('label', $info_borrador, 'Negocio') ?></label>
                                  </div>
                                  <span class="popup_sponsor_descripcion"><?php check_borrador_info('subtitulo', $info_borrador, '- Subtitulo o Descripción -') ?></span>
                                  <span class="popup_sponsor_direccion fa fa-map-marker"><?php check_borrador_info('direccion', $info_borrador, 'Dirección del negocio') ?></span>
                                  <span class="popup_sponsor_contacto fa fa-phone"><?php check_borrador_info('contacto', $info_borrador, 'Número de contacto') ?></span>
                                  <span class="popup_sponsor_web fa fa-envelope"><?php check_borrador_info('web', $info_borrador, 'Email o sitio web del negocio') ?></span>
                                </div>
                              </div>
                            </div>

                            <div class="popup_sponsor2" style="background-color:<?php check_borrador_info('borde', $info_borrador, 'rgba(0, 0, 0, 0.88)') ?>">
                              <span class="popup_sponsor_cerrar2 fa fa-times"></span>
                              <span class="popup_sponsor_illustration2" style="<?php if (isset($info_borrador['ilustracion'])) {if ($info_borrador['ilustracion'] !== '') {echo "background-image: url(" . $info_borrador['ilustracion'] . ")";};} ?>"></span>
                              <div class="popup_sponsor_info_container2">
                                <div id="popup_sponsor_mapa2" class="popup_sponsor_mapa2">
                                  <div id="mapid_sponsor2" style="height:13em; width:100%;"></div>
                                </div>
                                <div class="popup_sponsor_info2">
                                  <div class="popup_sponsor_titulo2">
                                    <span class="logo_preview <?php if (isset($info_borrador['logo'])) {if ($info_borrador['logo'] !== '') {echo "filled";};} ?>"
                                      style="<?php if (isset($info_borrador['logo'])) {if ($info_borrador['logo'] !== '') {echo "background-image: url(" . $info_borrador['logo'] . "); background-size: contain";};} ?>">
                                      <?php if (isset($info_borrador['logo'])){if($info_borrador['logo'] == ''){echo "LOGO";};}else{echo "LOGO";} ?>
                                    </span>
                                    <label><?php check_borrador_info('label', $info_borrador, 'Negocio') ?></label>
                                  </div>
                                  <span class="popup_sponsor_descripcion2"><?php check_borrador_info('subtitulo', $info_borrador, '- Subtitulo o Descripción -') ?></span>
                                  <span class="popup_sponsor_direccion2 fa fa-map-marker"><?php check_borrador_info('direccion', $info_borrador, 'Dirección del negocio') ?></span>
                                  <span class="popup_sponsor_contacto2 fa fa-phone"><?php check_borrador_info('contacto', $info_borrador, 'Número de contacto') ?></span>
                                  <span class="popup_sponsor_web2 fa fa-envelope"><?php check_borrador_info('web', $info_borrador, 'Email o sitio web del negocio') ?></span>
                                </div>
                              </div>
                            </div>

                      </div>



                      </div>
                  </div>

                </div>

                <div class="configuracion_sponsor">
                  <h2>Datos del Sponsor</h2>

                  <div class="configuracion_sponsor_paquete1">
                        <div class="elemento_formulario_sponsor" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">
                          <label for="nombre">Nombre de la Empresa</label>
                          <input id="nombre" type="text" autocomplete="off" name="nombre" class="input_obligatorio chars_check" value="<?php check_borrador_info('label', $info_borrador, '') ?>">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="categoria"> Categoría: </label>
                          <select name="categoria" id="categoria" class="categoria input_obligatorio edit_check">
                            <?php
                            if (isset($info_borrador['categoria'])) {
                              if ($info_borrador['categoria'] !== '') {
                                echo "<option selected=\"selected\" value=\"" . $info_borrador['categoria'] . "\">" . $categorias_borrador[$info_borrador['categoria']] . "</option>";
                              };
                            };
                            ?>

                            <option></option>
                            <option value="1">Restaurantes</option>
                            <option value="2">Bares & Cafés</option>
                            <option value="3">Bienestar</option>
                            <option value="4">Salud</option>
                          </select>
                        </div>

                        <div class="elemento_formulario_sponsor" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">
                          <label for="departamento" class="departamento_label"></label>
                          <select name="departamento" id="departamento" class="departamento input_obligatorio">
                             <?php
                             if ($departamento_borrador !== '') {
                               echo "<option selected=\"selected\" value=\"" . $departamento_borrador . "\">" . $departamento_borrador . "</option>";
                             };
                             ?>
                            <option></option>
                            <?php foreach ($regiones as $value): ?>
                              <option><?php echo $value; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="elemento_formulario_sponsor" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">
                            <label for="ciudad">Ciudad: </label>
                            <select name="ciudad" id="ciudad" class="ciudad input_obligatorio" onfocus='this.size=7;'
                            onblur='this.size=0;'
                            onchange='this.size=0; this.blur();' <?php if($ciudad_borrador == ''){echo"disabled";} ?>>
                            <?php
                            if ($ciudad_borrador !== '') {
                              echo "<option selected=\"selected\" value=\"" . $ciudad_borrador . "\">" . $ciudad_borrador . "</option>";
                            };
                            ?>
                            <option></option>
                            </select>
                        </div>

                        <div class="elemento_formulario_sponsor" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">
                            <label for="barrio">Barrio: </label>
                            <select name="barrio" id="barrio" onfocus='this.size=7;'
                            onblur='this.size=0;'
                            onchange='this.size=0; this.blur();' class="barrio" <?php if($barrio_borrador == ''){echo"disabled";} ?>>
                            <?php
                            if ($barrio_borrador !== '') {
                              echo "<option selected=\"selected\" value=\"" . $barrio_borrador . "\">" . $barrio_borrador . "</option>";
                            };
                            ?>

                            </select>
                        </div>

                  </div>

                  <div class="configuracion_sponsor_paquete2">

                        <div class="elemento_formulario_sponsor">
                          <label for="subtitulo">Subtítulo</label>
                          <input id="subtitulo" type="text" autocomplete="off" name="subtitulo" class="input_obligatorio chars_check edit_check" value="<?php check_borrador_info('subtitulo', $info_borrador, '') ?>">
                        </div>

                        <div class="elemento_formulario_sponsor" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">
                          <label for="direccion">Dirección</label>
                          <input id="direccion" type="text" autocomplete="off" name="direccion" class="input_obligatorio chars_check" value="<?php check_borrador_info('direccion', $info_borrador, '') ?>" placeholder="Ejemplo: #número + Av./Calle/Pasaje">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="contacto">Numero de Telefono / Celular</label>
                          <input id="contacto" type="text" autocomplete="off" name="contacto" class="input_obligatorio chars_check edit_check" value="<?php check_borrador_info('contacto', $info_borrador, '') ?>" placeholder="Ejemplo: (+591 2) 2424002 / 77100142">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="email">Email o Sitio Web</label>
                          <input id="email" type="text" autocomplete="off" name="email" class="input_obligatorio chars_check edit_check" value="<?php check_borrador_info('web', $info_borrador, '') ?>" placeholder="Ejemplo: contacto@ejemplo.com">
                        </div>
                  </div>

                  <div class="popup_sponsor_mapa_config">
                      <p style="width: 100%; margin: auto; text-align: center; cursor: default"><b>Seleccione la localisación en el Mapa</b> <br> (Click Derecho o Touch prolongado)</p>
                      <div id="mapid_sponsor_config" style="height:100%; width:100%; border: 1px solid rgb(57, 57, 57);"></div>
                      <div class="mapa_coordenadas_container">
                        <input type="hidden" name="mapa_sponsor_coordenada_lat" id="mapa_sponsor_coordenada_lat" class="input_mapa_obligatorio panel_MAPA" value="<?php check_borrador_info('latitud', $info_borrador, '') ?>">
                        <input type="hidden" name="mapa_sponsor_coordenada_lng" id="mapa_sponsor_coordenada_lng" class="input_mapa_obligatorio panel_MAPA" value="<?php check_borrador_info('longitud', $info_borrador, '') ?>">
                        <input type="hidden" name="mapa_sponsor_zoom" id="mapa_sponsor_zoom" class="input_mapa_obligatorio panel_MAPA" value="<?php check_borrador_info('zoom', $info_borrador, '') ?>">
                      </div>
                  </div>

                  <div class="configuracion_sponsor_paquete3" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">

                        <div id="contenedor_logo">
                          <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Sube el Logo de la Empresa (dimensión 1:1)</p>
                          <div id="campo_logo" class="campo_logo">
                            <label for="logo" id="logo_label"><p> Sube el Logo</br><span>Click or Drop</span></p></label>
                            <input type="file" id="logo" name="logo" class="" onchange="check_image(this)" accept="image/jpeg,image/x-png,image/svg+xml">
                          </div>
                        </div>

                        <div class="galeria_logos_contenedor">
                          <p style="text-align: center; color: #333333; font-weight: bold; cursor: default"> ...o bien escoje uno</p>
                          <div class="galeria_logos" style="<?php if (isset($info_borrador['logo'])) {if ($info_borrador['logo'] !== '') {echo "height: unset";};} ?>">
                            <?php
                            if (isset($info_borrador['logo'])) {
                              if ($info_borrador['logo'] !== '') {
                                echo "<span style=\"border: 3px solid rgb(153, 153, 152)\" class=\"logo existente\"><img src=\"" . $info_borrador['logo'] . "\" alt=\"\"></span>";
                              };
                            };

                             ?>
                          </div>
                          <input type="hidden" name="galeria_logos_input" id="galeria_logos_input" class="" value="<?php check_borrador_info('logo', $info_borrador, '') ?>">
                        </div>

                  </div>

                  <div class="configuracion_sponsor_paquete4">

                      <div class="galeria_ilustraciones_contenedor" style="<?php if($editor_call !== ''){echo 'margin-top: 3em';} ?>">
                        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default">Elije una Ilustracion de Esquina</p>
                        <div class="galeria_ilustraciones" style="<?php if (isset($info_borrador['ilustracion'])) {if ($info_borrador['ilustracion'] !== '') {echo "height: unset";};} ?>">
                          <?php
                          if (isset($info_borrador['ilustracion'])) {
                            if ($info_borrador['ilustracion'] !== '') {
                              echo "<span style=\"border: 3px solid rgb(153, 153, 152)\" class=\"ilustracion\"><img src=\"" . $info_borrador['ilustracion'] . "\" alt=\"\"></span>";
                            };
                          };

                           ?>
                        </div>
                        <input type="hidden" name="galeria_ilustraciones_input" id="galeria_ilustraciones_input" class="input_ilustracion_obligatorio" value="<?php check_borrador_info('ilustracion', $info_borrador, '') ?>">
                      </div>

                      <div class="galeria_colores_contenedor" style="<?php if($editor_call !== ''){echo 'margin-top: 3em';} ?>">
                        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default">Elije el Borde Ideal</p>
                        <div class="galeria_colores">
                          <?php
                          if (isset($info_borrador['borde'])) {
                            if ($info_borrador['borde'] !== '') {
                              echo "<span class=\"color_borde\" style=\"background-color: " . $info_borrador['borde'] . "; border: 3px solid rgb(153, 153, 152)\"></span>";
                            };
                          };

                           ?>
                          <span class="color_borde" style="background-color: rgba(142, 36, 170, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(94, 53, 177, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(57, 73, 171, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(30, 136, 229, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(3, 155, 229, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(0, 172, 193, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(0, 137, 123, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(67, 160, 71, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(124, 179, 66, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(251, 192, 45, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(255, 143, 0, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(239, 108, 0, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(216, 67, 21, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(211, 47, 47, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(216, 27, 96, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(208, 0, 0, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(93, 64, 55, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(158, 158, 158, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(97, 97, 97, 0.88);"></span>
                          <span class="color_borde" style="background-color: rgba(0, 0, 0, 0.88);"></span>
                        </div>
                        <input type="hidden" name="galeria_colores_input" id="galeria_colores_input" value="<?php check_borrador_info('borde', $info_borrador, 'rgba(0, 0, 0, 0.88)') ?>">
                      </div>

                  </div>

                  <div class="configuracion_sponsor_paquete5" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">

                        <div class="elemento_formulario_sponsor" >
                          <label for="responsable">Responsable del Negocio</label>
                          <input id="responsable" type="text" autocomplete="off" name="responsable" class="input_obligatorio chars_check" value="<?php check_borrador_info('responsable', $info_borrador, '') ?>">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="responsable_contacto">Número Telefónico del Responsable</label>
                          <input id="responsable_contacto" type="text" autocomplete="off" name="responsable_contacto" class="input_obligatorio chars_check" value="<?php check_borrador_info('responsable_contacto', $info_borrador, '') ?>" placeholder="Ejemplo: (+591 2) 2424002 / 77100142">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="fecha_vencimiento">Vencimiento del Contrato: </label>
                          <input type="text" id="fecha_vencimiento" readonly="readonly" name="fecha_vencimiento" class="input_obligatorio" value="<?php check_borrador_info('fecha_vencimiento', $info_borrador, '') ?>">
                        </div>

                  </div>

                </div>

              </div>

            <div id="boton_fin_formulario_contenedor">
              <button type="button" name="guardar_borrador" value="guardar_borrador" class="boton_fin_formulario" style="<?php if($editor_call !== ''){echo 'display: none';} ?>">
                <i class="far fa-save" aria-hidden="true"></i><p>Guardar borrador</p>
              </button>
              <button type="button" id="boton_validar_form" name="validar_datos" class="boton_fin_formulario" value="validar_datos">
                <i class="fas fa-clipboard-check"></i><p> Validar Datos</p>
              </button>
              <button  id="boton_submit_form" type="button" name="registar_datos" value="registar_datos" class="boton_fin_formulario">
                <i class="fa fa-download" aria-hidden="true"></i><p>Registrar Sponsor</p>
              </button>
            </div>

            <input type="hidden" name="boton_form_input" id="boton_form_input" value="">
            <input type="hidden" name="modo_borrador_edicion" id="modo_borrador_edicion" value="<?php if($borrador_call !== ''){echo "modo_borrador";}; if($editor_call !== ''){echo "modo_editor";}  ?>">
            <input type="hidden" name="borrador_editor_old_name" id="borrador_editor_old_name" value="<?php if($the_call !== ''){echo $the_call;} ?>">
          </form>

        </main>
    </div>

 </body>
</html>
