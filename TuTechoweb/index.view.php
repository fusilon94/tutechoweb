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
      <title>TuTecho</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="TuTecho.com immobiliaria lider en Bolivia">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="estilos.css">
      <link rel="stylesheet" type="text/css" href="css/flexslider.css">
      <link rel="stylesheet" href="css/font_awesome.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="js/js.cookie.js"></script>
      <script src="js/menu.js"></script>
      <script src="js/slider.js"></script>
      <script src="js/jquery.flexslider.js"></script>
 </head>
 <body>
 <div id="fondo"></div>
      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
      	  <header>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="index.php"><img src="objetos/logotipo2.svg" alt="Tu Techo.com" class="menu_logo"></a></li>
                  <li class="hover_menu menu_boton"><span><img src="objetos/menu_boton_icon.svg" alt="Menu" class="menu_icons_style"><p>Menu</p></span></li>
                </ul>
            </div>
      	    <nav class="scroll">
      	     <div id="menu">
                <ul class="ulmenu">
                  <li class="logo_element limenu"><a href="index.php" class="a_menu"><img src="objetos/logotipo2.svg" alt="Tu Techo.com" class="logo_img"></a></li>
                  <li class="hover_menu limenu"><a href="contenido/m1/anunciar.html" class="a_menu"><img src="objetos/anuncio_icon.svg" alt="Anunciar" class="menu_icons_style"><p>Anunciar un bien</p></a></li>
      	          <li class="hover_menu limenu"><a href="contenido/m2/venta_inmueble.php" class="a_menu"><img src="objetos/buy_icon.svg" alt="Comprar" class="menu_icons_style"><p>Comprar</p></a></li>
      	          <li class="hover_menu limenu"><a href="contenido/m2/renta_inmueble.php" class="a_menu"><img src="objetos/alquilar_icon.svg" alt="Alquilar" class="menu_icons_style"><p>Alquilar</p></a></li>
      	          <li class="hover_menu limenu"><a href="contenido/m4/soporte.html" class="a_menu"><img src="objetos/soporte_legal_icon.svg" alt="Soporte Legal" class="menu_icons_style"><p>Soporte legal</p></a></li>
      	          <li class="hover_menu limenu"><a href="contenido/m5/agencias.php" class="a_menu"><img src="objetos/agencias.svg" alt="Contacto" class="menu_icons_style"><p>Agencias</p></a></li>
                </ul>
             </div>
           </nav>
          </header>
<!-- ZONA DE NOVEDADES CON SCROLL -->
   	     <div id="index_slider" class="flexslider index_slider">
      	    <ul class="slides index_slides">
      	      <li>
      	        <img src="objetos/img/slide1.jpg" alt="imagen1">
                <section class="caption">
                   <h2>foto</h2>
                </section>
      	      </li>
              <li>
                <img src="objetos/img/slide2.jpg" alt="imagen2">
                  <section class="caption">
                     <h2>texto2</h2>
                  </section>
              </li>
              <li>
                <img src="objetos/img/slide3.jpg" alt="imagen3">
                  <section class="caption">
                     <h2>texto3</h2>
                  </section>
              </li>
      	    </ul>
      	  </div>

<!-- CONTENIDO PRINCIPAL -->
          <main>
               <section>
          	     <h1>Descripción</h1>
          	     <article>
          	          <div class="visitas_vistuales article_div_index">
          		         <a heref="#">visitas virtuales</a>
          	          </div>

          	           <div class="mapa_interactivo article_div_index">
          		          mapa interactivo
          	           </div>
                 </article>
                 <article>
          	          <div class="fotografos_expertos article_div_index">
          		          Fotografos expertos
          	          </div>

          	          <div class="soporte_article article_div_index">
          		          Soporte legal
          	          </div>
          	     </article>
               </section>
          </main>
    </div>
<!--PIE DE PAGINA -->
      	  <footer>
      	  	 <div id="footer_logo" class="footer_div">
      	  		 <a href="index.php">
      	  			 <img src="objetos/logotipo.svg" alt="TuTecho.com" class="footer_logo_img">
      	  		 </a>
      	  	 </div>
      	  	 <div id="footer_list" class="footer_div">
      	  		 <h2><p>Acerca de</p></h2>
      	  		  <div>
      	  		       <ul>
      	  			       <li><a href="contenido/pie/quienes_somos.html">Quiénes somos</a></li>
      	  			       <li><a href="contenido/pie/propietario_login.php">Propietario</a></li>
      	  			       <li><a href="acceso/acceso.php">Acceso</a></li>
      	  		       </ul>
      	  	 	       <ul>
      	  	 		       <li><a href="contenido/pie/empleo.html">Empleo</a></li>
                       <li><a href="contenido/pie/politica.html">Política de privacidad</a></li>
                       <li class="cambiar_pais"><p><?php echo ucfirst($_COOKIE['tutechopais']); ?></p><img src="objetos/flag_<?php echo $_COOKIE['tutechopais']; ?>.svg" alt="<?php echo $_COOKIE['tutechopais']; ?>"></li>
      	  	 	       </ul>
      	  	 	  </div>
      	  	 </div>
      	  	 <div id="footer_socialmedia" class="footer_div">
      	  		 <h2><p>Síguenos en</p></h2>
      	  		 <ul>
      	  			 <li><a href="#"><img src="objetos/facebookicon.svg" alt="Facebook" class="socialmedia_style"><p>Facebook</p></a></li>
      	  			 <li><a href="#"><img src="objetos/twittericon.svg" alt="Twitter" class="socialmedia_style"><p>Twitter</p></a></li>
      	  			 <li><a href="#"><img src="objetos/youtubeicon.svg" alt="Youtube" class="socialmedia_style"><p>Youtube</p></a></li>
      	  		 </ul>
      	  	 </div>
      	  </footer>
 </body>
</html>
