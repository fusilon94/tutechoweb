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
      <title>Consola - Borrar Inmueble</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/borrar_inmueble.css">
      <link rel="stylesheet" href="../../css/ficha_bien_borrar_inmueble.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
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
      <script src="../../js/webgl_tester.js"></script>
      <script src="../../js/three.js"></script>
      <script src="../../js/TweenLite.min.js"></script>
      <script type="text/javascript" src="../../js/jquery.ui.touch-punch.js"></script>
      <script type="text/javascript" src="../../js/dragable_feature_overflow.js"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/borrar_inmueble.js"></script>
      <script src="../../js/popup_ficha_bien_borrar_inmueble.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
      <script>
        function confirmacion(e){
          var parent = $(e).parent().find("div.boton_formulario_borrar_confirmar");
          var trashicon = $(e).find("i.fas");

          $(trashicon).toggleClass("fa-trash-alt fa-times");

          if ($(parent).is(":hidden")) {
            $(parent).show("slide", { direction: "left" }, 800);
          } else {
            $(parent).hide("slide", { direction: "left" }, 800);
          };
        }
      </script>

 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
<div class="overlay_sponsor_previsualizacion">

</div>
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
  <a href="bien_inmueble_consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>


  <h1 class="titulo">Consola - Borrar Inmueble</h1>
  <hr class="barra">

    <div class="contenedor_consola">

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

      <span class="label_select_contenedor">- Busca Bien-Inmueble según Referencia o Poblado/Barrio -</span>
      <div class="select_contenedor">

          <div class="elemento_formulario input_referencia_container">
            <label for="input_referencia"> Referencia: </label>
            <input type="text" id="input_referencia" name="input_referencia" value="">
            <span class="input_referencia_btn">Buscar</span>
          </div>

          <div class="elemento_formulario">
            <hr><!-- barra separadora -->
          </div>

          <div class="elemento_formulario">
            <label for="departamento" class="departamento_label"></label>
            <select name="departamento" id="departamento" class="departamento">
              <option></option>
              <?php foreach ($regiones as $value): ?>
                <option><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="elemento_formulario">
              <label for="ciudad">Ciudad: </label>
              <select name="ciudad" id="ciudad" class="ciudad" onfocus='this.size=7;'
              onblur='this.size=0;'
              onchange='this.size=0; this.blur();' disabled>
              <option></option>
              </select>
          </div>

          <div class="elemento_formulario">
              <label for="barrio">Barrio: </label>
              <select name="barrio" id="barrio" onfocus='this.size=7;'
              onblur='this.size=0;'
              onchange='this.size=0; this.blur();' class="barrio" disabled>
              </select>
          </div>


      </div>


      <div class="resultados_sponsors">
        <div class="switch_container">
          <div class="switch_sponsors">
            <span class="switch_activos switch active">Activos</span>
            <span class="switch_inactivos switch">Inactivos</span>
          </div>
        </div>

        <div class="resultados_container">
        </div>

      </div>

      <input type="hidden" id="switch_value" name="switch_value" value="">
      <input type="hidden" id="agencia_id" name="agencia_id" value="<?php echo $agencia_id;?>">


    </div>

</main>
    </div>

    <form id="formulario_exito" autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <input type="hidden" id="mensaje_exito" name="mensaje_exito" value="">
    </form>

 </body>
 <script src="../../js/panorama_base_code_ficha_individual.js"></script>
</html>
