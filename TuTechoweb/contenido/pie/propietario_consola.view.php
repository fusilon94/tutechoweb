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
      <title>Consola Propietarios</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/propietario_consola.css">
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/propietario_consola.js"></script>

 </head>
 <body>

 <div id="fondo"></div>
    <div id="contenedor_total">

    <div class="popup_overlay">
        <div class="popup">
            
        </div>
    </div>

<!-- BARRA DE NAVEGACION -->
      <header>
        <div class="regreso_boton_div_contenedor">
            <a href="../../index.php">
            <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
            <span><p>SALIR</p></span>
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


<!-- CONTENIDO -->


    <div class="contenido">

        

    </div>


    </div>
  </div>

 </body>
</html>
