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
      <title>Inmuebles en Venta</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="Description" content="">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/popup_favoritos_log_or_register.css">
      <link rel="stylesheet" href="../../css/ficha_bien_individual.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider_bien_individual.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>

      <script type="text/javascript">
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
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/webgl_tester.js"></script>
      <script src="../../js/three.js"></script>
      <script src="../../js/TweenLite.min.js"></script>
      <script type="text/javascript" src="../../js/jquery.ui.touch-punch.js"></script>
      <script type="text/javascript" src="../../js/dragable_feature_overflow.js"></script>
      <script src="../../js/popup_favoritos_log_or_register.js"></script>
      <script src="../../js/ficha_bien_individual.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <!-- HAY SCRIPTS AL FINAL DEL BODY, Y SCRIPTS QUE SE CARGAN CON LOS PROCESS REQUESTS -->
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
                  <li class="hover_menu"><span class="menu_boton"><img src="../../objetos/menu_boton_icon.svg" alt="Menu" class="menu_icons_style"><p>Menu</p></span></li>
                </ul>
            </div>
      	    <nav class="scroll">
      	     <div id="menu">
                <ul class="ulmenu">
                  <li class="logo_element limenu"><a href="../../index.php" class="a_menu"><img src="../../objetos/logotipo2.svg" alt="Tu Techo.com" class="logo_img"></a></li>
                  <li class="hover_menu limenu"><a href="../m1/anunciar.html" class="a_menu"><img src="../../objetos/anuncio_icon.svg" alt="Anunciar" class="menu_icons_style"><p>Anunciar un bien</p></a></li>
      	          <li class="hover_menu limenu"><a href="venta_inmueble.php" class="a_menu"><img src="../../objetos/buy_icon.svg" alt="Comprar" class="menu_icons_style"><p>Comprar</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m2/renta_inmueble.php" class="a_menu"><img src="../../objetos/alquilar_icon.svg" alt="Alquilar" class="menu_icons_style"><p>Alquilar</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m4/soporte.html" class="a_menu"><img src="../../objetos/soporte_legal_icon.svg" alt="Soporte Legal" class="menu_icons_style"><p>Soporte legal</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m5/agencias.php" class="a_menu"><img src="../../objetos/agencias.svg" alt="Contacto" class="menu_icons_style"><p>Agencias</p></a></li>
                </ul>
             </div>
           </nav>
          </header>

<!-- CONTENIDO PRINCIPAL -->
          <main>

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

          </main>
</div>
<!--PIE DE PAGINA -->
      	  <footer>
      	  	 <div id="footer_logo" class="footer_div">
      	  		 <a href="../../index.php">
      	  			 <img src="../../objetos/logotipo.svg" alt="TuTecho.com" class="footer_logo_img">
      	  		 </a>
      	  	 </div>
      	  	 <div id="footer_list" class="footer_div">
      	  		 <h2><p>Acerca de</p></h2>
      	  		  <div>
      	  		       <ul>
      	  			       <li><a href="../pie/quienes_somos.html">Quiénes somos</a></li>
      	  			       <li><a href="../m5/contacto.html">Contacto</a></li>
      	  			       <li><a href="../../acceso/acceso.php">Acceso</a></li>
      	  		       </ul>
      	  	 	       <ul>
      	  	 		       <li><a href="../pie/empleo.html">Empleo</a></li>
                       <li><a href="../pie/politica.html">Política de privacidad</a></li>
      	  			       <li class="cambiar_pais"><p><?php echo ucfirst($_COOKIE['tutechopais']); ?></p><img src="../../objetos/flag_<?php echo $_COOKIE['tutechopais']; ?>.svg" alt="<?php echo $_COOKIE['tutechopais']; ?>"></li>
      	  	 	       </ul>
      	  	 	  </div>
      	  	 </div>
      	  	 <div id="footer_socialmedia" class="footer_div">
      	  		 <h2><p>Síguenos en</p></h2>
      	  		 <ul>
      	  			 <li><a href="#"><img src="../../objetos/facebookicon.svg" alt="Facebook" class="socialmedia_style"><p>Facebook</p></a></li>
      	  			 <li><a href="#"><img src="../../objetos/twittericon.svg" alt="Twitter" class="socialmedia_style"><p>Twitter</p></a></li>
      	  			 <li><a href="#"><img src="../../objetos/youtubeicon.svg" alt="Youtube" class="socialmedia_style"><p>Youtube</p></a></li>
      	  		 </ul>
      	  	 </div>
      	  </footer>
 </body>
 <script src="../../js/panorama_base_code_ficha_individual.js"></script>
</html>
