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
      <title>Agencias</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      
      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>
      <link rel="stylesheet" type="text/css" href="../../css/agencias.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">

      
  
      <script type="text/javascript">
        const departamentos = <?php echo json_encode($regiones) ?>;
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/agencias.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
 </head>
 <body>
 <div id="fondo"></div>

 <div class="popup_overlay">

 </div>

 <div class="popup_agente_overlay">
  <div class="popup_agente">
    <span class="cerrar_popup_agente"><i class="fa fa-times-circle" title="Cerrar"></i></span>
    <span class="popup_agente_contenido">
    
    </span>
  </div>
 </div>

 <div class="preview_overlay">
    <span class="preview_popup">
        <span class="cerrar_preview"><i class="fa fa-times-circle" title="Cerrar"></i></span>
        <a href="" target="_blank" class="tabla_print_btn_wrap"><img src="../../objetos/imprimir_btn.svg" alt="IMPRIMIR" class="tabla_print_btn" title="IMPRIMIR"></a>
        <span class="preview_contenido"></span>
    </span>
  </div>

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
      	          <li class="hover_menu limenu"><a href="../m2/venta_inmueble.php" class="a_menu"><img src="../../objetos/buy_icon.svg" alt="Comprar" class="menu_icons_style"><p>Comprar</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m2/renta_inmueble.php" class="a_menu"><img src="../../objetos/alquilar_icon.svg" alt="Alquilar" class="menu_icons_style"><p>Alquilar</p></a></li>
      	          <li class="hover_menu limenu"><a href="../m4/soporte.html" class="a_menu"><img src="../../objetos/soporte_legal_icon.svg" alt="Soporte Legal" class="menu_icons_style"><p>Soporte legal</p></a></li>
      	          <li class="hover_menu limenu"><a href="agencias.php" class="a_menu"><img src="../../objetos/agencias.svg" alt="Contacto" class="menu_icons_style"><p>Agencias</p></a></li>
                </ul>
             </div>
           </nav>
          </header>

<!-- CONTENIDO PRINCIPAL -->
          <main>
               <h1>NUESTRAS AGENCIAS</h1>
               <hr class="barra">
               <div class="global_container">

                 <div class="left_container">

                   <div class="mapa_contenedor">
                     <span class="contador_agencias_contenedor">
                       <img src="../../objetos/agencias.svg" alt="">
                       <span class="contador_agencia_total"><?= $agencias_total . (($agencias_total > 1) ? " Agencia" : " Agencias") ?></span>
                       <span class="contador_agencias"></span>
                     </span>
                     <div class="map_wrap">
                       <div id="mapid_config" style="height:100%; width:100%; border: 1px solid rgb(57, 57, 57);"></div>
                     </div>
                     <div class="mapa_coordenadas_container">
                       <input type="hidden" name="mapa_coordenada_lat" id="mapa_coordenada_lat" class="" value="">
                       <input type="hidden" name="mapa_coordenada_lng" id="mapa_coordenada_lng" class="" value="">
                       <input type="hidden" name="mapa_zoom" id="mapa_zoom" class="" value="">
                     </div>
                   </div>

                 </div>

                 <div class="right_container">

                   <div class="selects_container">

                     <div class="select_campo">
                       <label for="speed"><h2 class="departamento_label"></h2></label>
                       <select name="speed" class="select_menu" id="departamento_busqueda" style="display: none;">
                         <option>Todo el País</option>
                         <?php foreach ($regiones as $value): ?>
                           <option><?php echo $value['departamentos']; ?></option>
                         <?php endforeach; ?>
                      </select>
                     </div>

                     <div class="select_campo">
                       <label for="speed"><h2>Ciudad</h2></label>
                       <select name="speed" class="select_menu" id="ciudad_busqueda" style="display: none;" disabled>
                         <option>Todas las ciudades</option>
                       </select>
                     </div>

                   </div>

                   <div class="agencias_container">
                     <div class="blur_container">
                       <span class="arrow_up"><i class="fa fa-arrow-up"></i></span>
                       <span class="arrow_left"><i class="fa fa-arrow-left"></i></span>
                       <span class="blur_message"><p class="departamento_blur"></p></br>o</br>Búsque en el Mapa</span>
                       <img class="image_blur" src="../../objetos/agencias_blur.svg" alt="">
                     </div>

                     <div class="all_results_container">
                     </div>

                   </div>

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
      	  			       <li><a href="propietario_consola.php">Propietario</a></li>
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
 
</html>
