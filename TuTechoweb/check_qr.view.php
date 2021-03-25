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
      <title>Validacion Cupón QR-code</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="css/estilos_validacion_qr_cupon.css">
      <link rel="stylesheet" type="text/css" href="css/flexslider.css">
      <link rel="stylesheet" href="css/font_awesome.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="js/js.cookie.js"></script>
<script src="js/menu.js"></script>
      <script src="js/consola_agente_jquery-ui.min.js"></script>
      <script src="js/validacion_qr_cupon.js"></script>

 </head>
 <body>

      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
      	  <header>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="index.php"><img src="objetos/logotipo2.svg" alt="TuTecho.com" class="menu_logo"></a></li>
                </ul>
            </div>
          </header>

<!-- CONTENIDO PRINCIPAL -->
<main>

  <div class="overlay_popup_aviso_advertencia">
      <div class="popup_aviso_advertencia">
        <p class="popup_aviso_texto">Ingrese su Código Sponsor para validar el cupón</p>
        <input type="codigo_sponsor" id="codigo_sponsor" name="codigo_sponsor" value="">
        <div class="popup_aviso_botones_container">
          <span class="btn_cancelar"><i class="fa fa-times"></i>Cancelar</span>
          <span class="btn_aceptar"><i class="fa fa-check"></i><span>Aceptar</span></span>
        </div>
      </div>
  </div>

  <span class="validez_cupon_btn">Validar Cupón</span>
  <div class="promocion_contenedor">

  </div>

</main>
    </div>

 </body>
</html>
