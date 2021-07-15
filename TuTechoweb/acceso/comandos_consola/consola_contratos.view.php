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
      <title>Consola - Contratos</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>

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
  <div class="regreso_boton_div_contenedor">
  <a href="consola_legal.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER A LA CONSOLA</p></span>
  </a>
  </div>
  <?php
  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
    echo"
    <span class=\"pais_selector\">
      <p class=\"pais_selector_text\">" . ucfirst($_SESSION['cookie_pais']) . "</p>
      <img src=\"../../objetos/flag_" . $_SESSION['cookie_pais'] . ".svg\" alt=\"" . $_SESSION['cookie_pais'] . "\" class=\"pais_selector_flag\">
    </span>
    ";
  };
  ?>
  <h1 class="titulo">Consola - Contratos</h1>
  <hr class="barra">

  <div class="contenedor_comandos_consola">

    <?php

    foreach ($consola_herramientas as $herramienta) {
      echo "
      <div class=\"comando_consola\">
        <a href=\"" . $herramienta['destino'] . "\">
          <img src=\"" . $herramienta['logo'] . "\" alt=\"" . $herramienta['nombre'] . "\">
          <p>" . $herramienta['nombre'] . "</p>
        </a>
      </div>
      ";
    };

    ?>

  </div>

</main>
    </div>

 </body>
</html>
