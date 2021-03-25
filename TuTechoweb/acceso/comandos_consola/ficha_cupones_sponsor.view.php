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
      <title>Consola - Ficha Cupónes</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/ficha_cupones.css" media="screen">
      <link rel="stylesheet" href="../../css/ficha_cupones_print.css" media="print">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/qart.min.js"></script>
      <script src="../../js/QR_code_generator.js"></script>
      <script>

      $(document).ready(function(){
       jQuery(function($){

         $("#btn_imprimir").on('click', function(){
           $('.overlay_popup_aviso_advertencia').css('visibility',  'visible');

         });

         $('.popup_success_cerrar i.fa-times').on("click", function(){
           $('.popup_success').css('visibility',  'hidden');
         });

         $('.btn_cancelar').on("click", function(){
           $('.overlay_popup_aviso_advertencia').css('visibility',  'hidden');
         });

         $('.btn_aceptar').on("click", function(){
           $('.overlay_popup_aviso_advertencia').css('visibility',  'hidden');
           window.print();
         });

       });
      });
      </script>



 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">

<!-- BARRA DE NAVEGACION -->
      	  <header>
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

<!-- CONTENIDO PRINCIPAL -->
<main>

  <div class="overlay_popup_aviso_advertencia">
      <div class="popup_aviso_advertencia">
        <p class="popup_aviso_texto">¡Asegurese de siempre usar Hoja(s) tamaño CARTA, imprimir SIN MARGENES y en AMBAS CARAS del papel!</p>
        <p class="popup_aviso_pregunta">¿Desea continuar?</p>
        <div class="popup_aviso_botones_container">
          <span class="btn_cancelar"><i class="fa fa-times"></i>Cancelar</span>
          <span class="btn_aceptar"><i class="fa fa-check"></i><span>Aceptar</span></span>
        </div>
      </div>
  </div>

  <div class="regreso_boton_div_contenedor">
  <a href="sponsors_cupones_consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  <div id="btn_imprimir" class="btn_imprimir">
      <span>IMPRIMIR</span>
      <img src="../../objetos/imprimir_btn.svg" alt="IMPRIMIR">
  </div>
  </div>

  <h1 class="titulo">Ficha Cupónes</br>NO recargar la pagina - imprimir o guardar PDF <p style="text-decoration: underline; text-decoration-color: #fff; color: rgb(203, 31, 31); font-size: 1.2em; font-weight: bold">De Inmediato</p></h1>
  <hr class="barra">

  <div id="ficha_cupones">

    <?php

    $i = 1;
    $k = 1;
    $sponsors_list = array();
    $cupon_vencimientos = array();
    $QR_codes_list = array();
    $last_page_QR = count($lista_cupones) % 8;

    foreach ($lista_cupones as $cupon) {

    $font_style = '';
    if ($cupon['promo_tipo_texto'] == 'italic') {
      $font_style = "font-style: italic";
    }else {
      $font_style = "font-weight: " . $cupon['promo_tipo_texto']. "";
    };

    $promo_tipo2_x = '';
    if ($cupon['tipo_promocion'] == '2') {
      $promo_tipo2_x = 'x';
    }

      echo "
      <div class=\"cupon_fixed_container\">
      <div class=\"popup_sponsor\" style=\"background-color:" . $cupon['borde'] . ";\">
        <div class=\"validez_cupon\">Oferta NO acumulable // Cupón Válido hasta: <span>" . $cupon['fecha_vencimiento'] . "<span></div>
        <div class=\"popup_sponsor_info_container\">
          <div class=\"ilustracion_fondo_container\">
            <span class=\"ilustracion_fondo\" style=\"background-image: url('" . $cupon['ilustracion_fondo'] . "');\"></span>
            <span class=\"ilustracion_filtro\"></span>
          </div>
          <div class=\"popup_promo_zona\">
            <div class=\"info_promo_1_container\" style=\"top: " . $cupon['promo_top'] . "; left: " . $cupon['promo_left'] . "; transform: rotate(" . $cupon['promo_inclinacion'] . "deg); flex-direction: " . $cupon['promo_var4'] . ";
            color: " . $cupon['promo_color'] . ";\">
              <div class=\"promo_cuadro1\" style=\"" . $font_style . "\">
                <span class=\"promo_cuadro1_texto1\" style=\"font-size: " . $cupon['promo_font_size1'] . ";\">" . $cupon['promo_var1'] . "</span>
                <span class=\"promo_tipo_2_x\" style=\"font-size: " . $cupon['promo_font_size1'] . ";\">" . $promo_tipo2_x . "</span>
                <span class=\"promo_cuadro1_texto2\" style=\"font-size: " . $cupon['promo_font_size1'] . ";\">" . $cupon['promo_var2'] . "</span>
              </div>
              <div class=\"promo_cuadro2\" style=\"" . $font_style . "\">
                <span class=\"promo_cuadro2_texto1\" style=\"font-size: " . $cupon['promo_font_size2'] . ";\">" . $cupon['promo_var3'] . "</span>
              </div>
            </div>
            <div class=\"info_promo_2_container\" style=\"padding-left: " . $cupon['promo_info_posicion'] . "; font-size: " . $cupon['promo_info_font_size'] . ";\">
              <span class=\"promo_info_texto1\">" . $cupon['promo_info1'] . "</span>
              <span class=\"promo_info_texto2\">" . $cupon['promo_info2'] . "</span>
            </div>
          </div>
          <div class=\"popup_sponsor_info\" style=\"right: " . $cupon['info_sponsor_right'] . "\">
            <div class=\"popup_sponsor_titulo\">
              <span id=\"logo_preview1\" class=\"logo_preview filled\" style=\"background-image: url('" . $cupon[2] . "'); background-size: contain\">
              </span>
              <label>" . $cupon[1] . "</label>
            </div>
            <span class=\"popup_sponsor_direccion fa fa-map-marker\">" . $cupon[3] . "</span>
            <span class=\"popup_sponsor_contacto fa fa-phone\">" . $cupon[4] . "</span>
            <span class=\"popup_sponsor_web fa fa-envelope\">" . $cupon[5] . "</span>
          </div>
        </div>
      </div>
      </div>";

      $sponsors_list[] = $cupon[0];
      $cupon_vencimientos[] = $cupon['fecha_vencimiento'];

      if ($i == 8) {
        echo "<div class=\"salto_pagina\"></div>";
        $i = 1;

        $j = 1;
        echo "<div class=\"QR_cupones_container\">";
          while ($j <= 8) {
            echo "
            <div class=\"cupon_fixed_container_back\">
              <div class=\"popup_sponsor_back\">
                <div class=\"popup_sponsor_info_container\">

                  <div class=\"popup_promo_qr\" id=\"" . 'QR_barcode_' . $k . "\"></div>

                  <div class=\"popup_sponsor_info\"><span class=\"cupon_tutecho_titulo\">Una promoción de:</span><span class=\"cupon_tutecho_logo\"><img src=\"../../objetos/logotipo.svg\" alt=\"TuTecho.com\"></span><span class=\"cupon_tutecho_instruccion\"><i class=\"fas fa-arrow-left\"></i>Scanear QRcode para invalidar cupón</span></div>

                </div>
              </div>
            </div>
            ";
            $j++;
            $k++;
          };
        echo "</div>";
        echo "<div class=\"salto_pagina\"></div>";

      }else {
        $i++;
      };


    };
    echo "<div class=\"salto_pagina\"></div>";
    $j = 1;
    echo "<div class=\"QR_cupones_container\">";
      while ($j <= $last_page_QR) {
        echo "
        <div class=\"cupon_fixed_container_back\">
          <div class=\"popup_sponsor_back\">
            <div class=\"popup_sponsor_info_container\">

              <div class=\"popup_promo_qr\" id=\"" . 'QR_barcode_' . $k . "\"></div>

              <div class=\"popup_sponsor_info\"><span class=\"cupon_tutecho_titulo\">Una promoción de:</span><span class=\"cupon_tutecho_logo\"><img src=\"../../objetos/logotipo.svg\" alt=\"TuTecho.com\"></span><span class=\"cupon_tutecho_instruccion\"><i class=\"fas fa-arrow-left\"></i>Scanear QRcode para invalidar cupón</span></div>

            </div>
          </div>
        </div>
        ";
        $j++;
        $k++;
      };
    echo "</div>";
     ?>

     <script type="text/javascript"><!-- almacena todos los nombres sponsor en un solo array JSON -->
      var sponsors_names = JSON.parse('<?php echo json_encode($sponsors_list,JSON_HEX_TAG|JSON_HEX_APOS); ?>');
      var cupones_vencimientos = JSON.parse('<?php echo json_encode($cupon_vencimientos,JSON_HEX_TAG|JSON_HEX_APOS); ?>');
     </script>

</div>


</main>
    </div>

 </body>
</html>
