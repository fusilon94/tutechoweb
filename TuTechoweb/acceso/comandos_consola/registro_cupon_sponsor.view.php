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
      <title>Registro Cupón</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">


      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/registro_cupon_sponsor.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">


      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script type="text/javascript" src="../../js/jquery.uploadPreview.min.js"></script>
     <script src="../../js/registro_cupon_sponsor.js"></script>

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
            <a href="sponsors_cupones_consola.php">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
            </a>
            </div>

            <div class="popup_errores">
              <span class="popup_errores_cerrar"><i class="fa fa-times"></i></span>
              <i class="fa fa-exclamation-circle"></i>Datos incompletos o incorrectos
            </div>

            <h1 class="titulo_formulario">Registro - Cupón Promoción para Sponsor</h1>
            <h2 class="titulo_formulario">* Solo en PC y Tablet *</h2>

            <form id="formulario_registro_cupon_sponsor" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

              <div class="contenedor_plataforma_sponsor_edicion">

                <div class="pop_up_visualizer">
                  <div class="relative_container">
                    <div class="sticky_container">
                      <div class="switch_container">
                      <h2>Vista Preliminar del Cupón:</h2>
                      <span class="boton_ver_vista_preliminar"><i class="fa fa-eye-slash"></i></span>
                      </div>

                      <div class="popups_container">

                            <div class="popup_sponsor popup_visible" style="background-color:<?php check_sponsor_editar_info('borde', $info_sponsor, 'rgba(0, 0, 0, 0.88)') ?>">
                              <div class="validez_cupon">Oferta NO acumulable // Cupón Válido hasta: <span><?php if (isset($info_sponsor['fecha_vencimiento'])){echo $info_sponsor['fecha_vencimiento'];} ?><span></div>
                              <div class="popup_sponsor_info_container">
                                <div class="ilustracion_fondo_container">
                                  <span class="ilustracion_fondo" style="background-image: url(<?php if (isset($info_sponsor['ilustracion'])){echo $info_sponsor['ilustracion'];} ?>)"></span>
                                  <span class="ilustracion_filtro"></span>
                                </div>
                                <div class="popup_promo_zona">
                                  <div class="info_promo_1_container">
                                    <div class="promo_cuadro1">
                                      <span class="promo_cuadro1_texto1"></span>
                                      <span class="promo_tipo_2_x"></span>
                                      <span class="promo_cuadro1_texto2"></span>
                                    </div>
                                    <div class="promo_cuadro2">
                                      <span class="promo_cuadro2_texto1"></span>
                                    </div>
                                  </div>
                                  <div class="info_promo_2_container">
                                    <span class="promo_info_texto1"></span>
                                    <span class="promo_info_texto2"></span>
                                  </div>
                                </div>
                                <div class="popup_sponsor_info">
                                  <div class="popup_sponsor_titulo">
                                    <span id="logo_preview1" class="logo_preview <?php if (isset($info_sponsor['logo'])) {if ($info_sponsor['logo'] !== '') {echo "filled";};} ?>"
                                    style="<?php if (isset($info_sponsor['logo'])) {if ($info_sponsor['logo'] !== '') {echo "background-image: url(" . $info_sponsor['logo'] . "); background-size: contain";};} ?>">
                                    <?php if (isset($info_sponsor['logo'])){if($info_sponsor['logo'] == ''){echo "LOGO";};}else{echo "LOGO";} ?>
                                    </span>
                                    <label><?php check_sponsor_editar_info('label', $info_sponsor, 'Negocio') ?></label>
                                  </div>
                                  <span class="popup_sponsor_direccion fa fa-map-marker"><?php check_sponsor_editar_info('direccion', $info_sponsor, 'Dirección') ?></span>
                                  <span class="popup_sponsor_contacto fa fa-phone"><?php check_sponsor_editar_info('contacto', $info_sponsor, 'Contacto') ?></span>
                                  <span class="popup_sponsor_web fa fa-envelope"><?php check_sponsor_editar_info('web', $info_sponsor, 'Email o Web') ?></span>
                                </div>
                              </div>
                            </div>

                      </div>



                      </div>
                  </div>

                </div>

                <div class="configuracion_sponsor">
                  <h2>Datos del Cupón</h2>

                  <div class="configuracion_sponsor_paquete1">

                        <div class="elemento_formulario_sponsor">
                          <label for="promo_info_sponsor_posicion">Posición Info-Sponsor:</label>
                          <div class="promo_info_btn_mini_container">
                            <span class="promo_info_sponsor_btn_posicion"><i class="fas fa-arrow-alt-circle-left"></i></span>
                            <span class="promo_info_sponsor_btn_posicion"><i class="fas fa-arrow-alt-circle-right"></i></span>
                          </div>
                          <input id="promo_info_sponsor_posicion" type="hidden" name="promo_info_sponsor_posicion" value="1.5em">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="tipo_promocion"> Tipo de Promoción: </label>
                          <select name="tipo_promocion" id="tipo_promocion" class="tipo_promocion input_obligatorio">
                            <option></option>
                            <option value="1">Tipo 1 (ej: 20% OFF)</option>
                            <option value="2">Tipo 2 (ej: 2X1)</option>
                            <option value="3">Tipo 3 (ej: GRATIS)</option>
                          </select>
                        </div>

                  </div>

                  <div class="configuracion_sponsor_paquete2">



                  </div>

                  <div class="configuracion_sponsor_paquete3">

                        <div class="elemento_formulario_sponsor">
                          <label for="promo_info1">Promoción limitada a:</label>
                          <input id="promo_info1" type="text" autocomplete="off" name="promo_info1" class="input_obligatorio chars_check" value="" placeholder="Ejemplo: En toda la tienda / en cualquier combo">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="promo_info2">Promoción valida durante:</label>
                          <input id="promo_info2" type="text" autocomplete="off" name="promo_info2" class="chars_check" value="" placeholder="Ejemplo: Hasta agotar stock / todos los martes">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="promo_info_font_size_input">Tamaño de Texto 'Info':</label>
                          <div id="promo_info_font_size">

                          </div>
                          <input id="promo_info_font_size_input" type="hidden" name="promo_info_font_size_input" value="1em">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="promo_info_posicion">Posición de Texto 'Info':</label>
                          <div class="promo_info_btn_mini_container">
                            <span class="promo_info_btn_posicion"><i class="fas fa-arrow-alt-circle-left"></i></span>
                            <span class="promo_info_btn_posicion"><i class="fas fa-arrow-alt-circle-right"></i></span>
                          </div>
                          <input id="promo_info_posicion" type="hidden" name="promo_info_posicion" value="">
                        </div>

                  </div>

                  <div class="configuracion_sponsor_paquete4">

                      <div class="galeria_ilustraciones_contenedor">
                        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default">Elije una Ilustracion de Fondo</p>
                        <div class="galeria_ilustraciones" style="<?php if (isset($info_sponsor['ilustracion'])) {if ($info_sponsor['ilustracion'] !== '') {echo "height: unset";};} ?>">
                          <?php
                          if (isset($info_sponsor['ilustracion'])) {
                            if ($info_sponsor['ilustracion'] !== '') {
                              echo "<span style=\"border: 3px solid rgb(153, 153, 152)\" class=\"ilustracion\"><img src=\"" . $info_sponsor['ilustracion'] . "\" alt=\"\"></span>";
                            };
                          };

                           ?>

                           <?php
                            foreach ($ilustraciones as $ilustracion) {
                              echo "<span class=\"ilustracion\"><img src=\"" . $ilustracion[0] . "\" alt=\"\"></span>";
                            };

                           ?>
                        </div>
                        <input type="hidden" name="galeria_ilustraciones_input" id="galeria_ilustraciones_input" class="input_ilustracion_obligatorio" value="<?php check_sponsor_editar_info('ilustracion', $info_sponsor, '') ?>">
                      </div>

                      <div class="galeria_colores_contenedor">
                        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default">Elije el Borde Ideal</p>
                        <div class="galeria_colores">
                          <?php
                          if (isset($info_sponsor['borde'])) {
                            if ($info_sponsor['borde'] !== '') {
                              echo "<span class=\"color_borde\" style=\"background-color: " . $info_sponsor['borde'] . "; border: 3px solid rgb(153, 153, 152)\"></span>";
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
                        <input type="hidden" name="galeria_colores_input" id="galeria_colores_input" value="<?php check_sponsor_editar_info('borde', $info_sponsor, 'rgba(0, 0, 0, 0.88)') ?>">
                      </div>

                  </div>

                  <div class="configuracion_sponsor_paquete5">

                        <div class="elemento_formulario_sponsor">
                          <label for="fecha_vencimiento">Vencimiento del Cupón: </label>
                          <input type="text" id="fecha_vencimiento" readonly="readonly" name="fecha_vencimiento" class="input_obligatorio_fecha" value="<?php check_sponsor_editar_info('fecha_vencimiento', $info_sponsor, '') ?>">
                        </div>

                        <div class="elemento_formulario_sponsor">
                          <label for="seguridad_extra">Extra Seguridad? </label>
                          <div class="seguridad_extra_btn">
                            Códigos de Válidación
                          </div>
                          <input type="hidden" id="seguridad_extra" name="seguridad_extra" value="">
                        </div>

                  </div>

                </div>

              </div>

            <div id="boton_fin_formulario_contenedor">
              <button type="button" id="boton_validar_form" name="validar_datos" class="boton_fin_formulario" value="validar_datos">
                <i class="fas fa-clipboard-check"></i><p> Validar Datos</p>
              </button>
              <button  id="boton_submit_form" type="button" name="registar_datos" value="registar_datos" class="boton_fin_formulario">
                <i class="fa fa-download" aria-hidden="true"></i><p>Registrar Cupón</p>
              </button>
            </div>

            <input type="hidden" name="fecha_vencimiento_contrato_sponsor" id="fecha_vencimiento_contrato_sponsor" value="<?php check_sponsor_editar_info('fecha_vencimiento', $info_sponsor, '') ?>">
            <input type="hidden" name="sponsor" id="sponsor" value="<?php check_sponsor_editar_info('nombre', $info_sponsor, '') ?>">
          </form>

        </main>
    </div>

 </body>
</html>
