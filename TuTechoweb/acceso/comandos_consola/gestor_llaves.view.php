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
      <title>Gestor de Llaves</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos2.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/ficha_bien_detalle_inmueble.css">
      <link rel="stylesheet" href="../../css/gestor_llaves.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider_bien_individual.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="../../css/select2.min.css">

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
        const agente_id = "<?= $agente['id'] ?>" ;
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
      <script src="../../js/gestor_llaves.js"></script>
      <script src="../../js/popup_ficha_bien_detalle_inmueble.js"></script>
      <script src="../../js/select2.min.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>

 </head>
 <body>

 <div class="overlay_popup">
        <div class="popup">
          <span class="popup_cerrar"><i class="fa fa-times"></i></span>
          <div class="popup_content">
            
          </div>
        </div>
        
</div>

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
  <div class="regreso_boton_div_contenedor">
  <a href="consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <span class="retornar_btn">Retornar Llaves</span>

  <div class="select_keys_container">

    <div class="select_container">
      <label for="agencia">Agencia</label>
      <select id="agencia" class="select_menu" name="agencia">
        <?php
          if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
            echo "<option value=\"" . $agencias_list[0]['id'] . "\" acceso=\"1\" selected>" . $agencias_list[0]['location_tag'] . "</option>";
            foreach (array_slice($agencias_list,1) as $agencia_alternative) {
              echo "<option value=\"" . $agencia_alternative['id'] . "\" acceso=\"1\">" . $agencia_alternative['location_tag'] . "</option>";
            };
          }else {
              echo "<option value=\"" . $agencias_list[0]['id'] . "\" acceso=\"1\" selected>" . $agencias_list[0]['location_tag'] . "</option>";
          };
  
        ?>
      </select>
    </div>
    <div class="show_all_keys">
          <img src="../../objetos/llavero.svg" alt="Llavero" title="Ver Llavero">
    </div>
  </div>


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

      <span class="label_select_contenedor">- Busca Bien-Inmueble según Referencia, Poblado/Barrio o Dirección -</span>
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
            <label for="departamento" class="departamento_label">Departamento: </label>
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

          <div class="elemento_formulario" style="margin-top: 1.5em">
            <hr><!-- barra separadora -->
          </div>

          <div class="elemento_formulario input_direccion_container">
            <label for="input_direccion"> Dirección: </label>
            <input type="text" id="input_direccion" name="input_direccion" value="" placeholder="Palabra clave">
          </div>


      </div>


      <div class="resultados_sponsors">
        <div class="resultados_container">
        </div>

      </div>

      <input type="hidden" id="switch_value" name="switch_value" value="">
      <input type="hidden" id="agente_id" name="agente_id" value="<?php echo $usuario;?>">

    </div>


</main>
    </div>

 </body>
 <script src="../../js/panorama_base_code_ficha_individual.js"></script>
</html>
