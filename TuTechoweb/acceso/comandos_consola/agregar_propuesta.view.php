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
      <title>Agregar Propuesta</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/agregar_propuesta.css" media="screen">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/select2.min.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/select2.min.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/agregar_propuesta.js"></script>
      

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
  <main class="contenedor">
  
    <div class="popup_borrar_overlay">
        <div class="popup_borrar">
            <span class="popup_borrar_cerrar"><i class="fa fa-times"></i></span>
            <div class="popup_borrar_content">


            </div>
            <span class="btn_borrar_confirmar">BORRAR</span>
        </div>  
    </div>
    
    <div class="popup_overlay">
        <div class="popup">
            <span class="popup_cerrar"><i class="fa fa-times"></i></span>
            <div class="popup_content">


            </div>
            <span class="guardar_btn">GUARDAR</span>
        </div>    
    </div>
      

      <h1 class="titulo">Consola Agregar Propuesta</h1>
      <hr class="barra">
      <div class="regreso_boton_div_contenedor">
        <a href="bien_inmueble_consola.php">
          <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
          <span><p>VOLVER ATRÁS</p></span>
        </a>
      </div>

      <div class="formulario">

        <div class="form-group">
          <i class="icono izquierda fa fa-hashtag"></i><input type="text" name="referencia" class="referencia" placeholder="Referencia">
        </div>

        <span class="error_message"><i class="fas fa-exclamation-circle"></i><p></p></span>

        <span class="btn_cambiar_codigos">Ingresar</span>

      </div>

      <div class="propuestas_contenedor">
        <span class="agregar_btn">
            <i class="fas fa-plus-circle"></i>
            <p>AGREGAR</p>
        </span>
        <div class="titulos_wrap">
            <span class="titulo_tabla titulo_fecha">Fecha</span>
            <span class="titulo_tabla titulo_propuesta">Propuesta</span>
            <span class="titulo_tabla titulo_actions"></span>
        </div>

        <hr class="barra_titulos">
        
        <div class="lista_propuestas_wrap">

            

        </div>



      </div>

  </main>
  </div>

 </body>
</html>
