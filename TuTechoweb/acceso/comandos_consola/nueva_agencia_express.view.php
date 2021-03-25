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
      <title><?= (isset($_SESSION['agencia_edit']) ? 'Editar Agencia Express' : 'Nueva Agencia Express') ?></title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/nueva_agencia.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script type="text/javascript" src="../../js/jquery.uploadPreview.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src="../../js/nueva_agencia_express.js"></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
      <script> //en el modo EDIT esto define la dinamica del thumbnail de cada foto ESPECIFICO DE ESTE FORMULARIO

        function thumb_click_operator(oObject){
          var thumbfoto = oObject.parentNode.querySelector("img.thumb_foto_normal");
          var fotocampo = oObject.parentNode.querySelector("input[type=file]");
          var thumbfoto_p = oObject.parentNode.querySelector("div.thumb_foto_normal_p_container");
          var foto_return_button = oObject.parentNode.querySelector("i.return_change_foto");

          $(thumbfoto).hide();
          $(thumbfoto_p).hide();
          $(foto_return_button).css('visibility', 'visible');
          $(fotocampo).prop('disabled', false);
        }
      </script>

      <script> //en el modo EDIT esto define la dinamica del boton returnthumbnail ESPECIFICO DE ESTE FORMULARIO
        function return_foto_click_operator(oObject){
          var thumbfoto = oObject.parentNode.querySelector("img.thumb_foto_normal");
          var fotocampo = oObject.parentNode.querySelector("input[type=file]");
          var thumbfoto_p = oObject.parentNode.querySelector("div.thumb_foto_normal_p_container");
          var foto_return_button = oObject.parentNode.querySelector("i.return_change_foto");

          $(thumbfoto).show();
          $(thumbfoto_p).show();
          $(foto_return_button).css('visibility', 'hidden');
          $(fotocampo).prop('disabled', true).val("").trigger("change");

        }
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
  <div class="regreso_boton_div_contenedor">
  <a href="<?= (isset($_SESSION['agencia_edit']) ? 'consola_editar_agencia.php' : 'agencias_consola.php') ?>">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>
  <h1 class="titulo">Consola - <?= (isset($_SESSION['agencia_edit']) ? 'Editar Agencia Express' : 'Nueva Agencia Express') ?></h1>

  <div class="popup_success">
    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text"></p>
  </div>

  <form id="nueva_agencia_form" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="contenedor_editor_sponsor_consola">

    <span class="titulo_azul">- <?= (isset($_SESSION['agencia_edit']) ? ('Agencia Express de ' . $agencia_info['location_tag']) : 'Complete el formulario para crear una nueva agencia') ?> -</span>

    <div class="contenedor_consola">
      <div class="select_contenedor">

          <div class="elemento_formulario">
            <label for="departamento" class="departamento_label"> Departamento: </label>
            <select name="departamento" id="departamento" class="departamento">
              <option></option>
              <?php if (isset($_SESSION['agencia_edit'])): ?>
                <option selected><?php fill_edit_info('departamento', $agencia_info); ?></option>
              <?php endif; ?>
              <?php foreach ($regiones as $value): ?>
                <option><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="elemento_formulario">
              <label for="ciudad">Ciudad: </label>
              <select name="ciudad" id="ciudad" class="ciudad" onfocus='this.size=7;'
              onblur='this.size=0;'
              onchange='this.size=0; this.blur();' <?= (isset($_SESSION['agencia_edit']) ? '' : 'disabled') ?>>
              <option></option>
              <?php if (isset($_SESSION['agencia_edit'])): ?>
                <option selected><?php fill_edit_info('ciudad', $agencia_info); ?></option>
              <?php endif; ?>
              </select>
          </div>


      </div>

      <div class="mapa_contenedor">
        <p style="width: 100%; margin: auto; text-align: center; cursor: default"><b>Seleccione la localisación en el Mapa</b> (Click Derecho o Touch prolongado)</p>
        <div class="map_wrap">
          <div id="mapid_config" style="height:100%; width:100%; border: 1px solid rgb(57, 57, 57);"></div>
        </div>
        <div class="mapa_coordenadas_container">
          <input type="hidden" name="mapa_coordenada_lat" id="mapa_coordenada_lat" class="input_mapa_obligatorio panel_MAPA" value="<?= (isset($_SESSION['agencia_edit']) ? fill_edit_info('mapa_coordenada_lat', $agencia_info) : '') ?>">
          <input type="hidden" name="mapa_coordenada_lng" id="mapa_coordenada_lng" class="input_mapa_obligatorio panel_MAPA" value="<?= (isset($_SESSION['agencia_edit']) ? fill_edit_info('mapa_coordenada_lng', $agencia_info) : '') ?>">
          <input type="hidden" name="mapa_zoom" id="mapa_zoom" class="input_mapa_obligatorio panel_MAPA" value="<?= (isset($_SESSION['agencia_edit']) ? fill_edit_info('mapa_zoom', $agencia_info) : '') ?>">
        </div>
      </div>

      <div class="inputs_contenedor">
        <div class="inputs_pak">
          <span class="input_wrap">
            <label for="direccion">Dirección:</label>
            <input id="direccion" type="text" name="direccion" value="<?= (isset($_SESSION['agencia_edit']) ? fill_edit_info('direccion', $agencia_info) : '') ?>" placeholder="-OPCIONAL-">
          </span>
          <span class="input_wrap">
            <label for="direccion_complemento">Complemento: </label>
            <input id="direccion_complemento" type="text" name="direccion_complemento" value="<?= (isset($_SESSION['agencia_edit']) ? fill_edit_info('direccion_complemento', $agencia_info) : '') ?>" placeholder="-OPCIONAL-">
          </span>
        </div>
        <div class="inputs_pak">
          <span class="input_wrap">
            <label for="telefono">Telefono: </label>
            <input id="telefono" type="text" name="telefono" value="<?= (isset($_SESSION['agencia_edit']) ? fill_edit_info('telefono', $agencia_info) : '') ?>" placeholder="-OPCIONAL-">
          </span>
          <span class="input_wrap">
            <label for="nit">NIT: </label>
            <input id="nit" type="text" name="nit" value="<?= (isset($_SESSION['agencia_edit']) ? fill_edit_info('NIT', $agencia_info) : '') ?>">
          </span>
        </div>
      </div>


      <div class="fotos_wrap">

        
      <?php if ($modo_edicion !== ''): ?>

        <div id="contenedor_foto">
          <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Sube una foto de la Agencia (Generica)</p>
          <div id="campo_foto" class="campo_foto">
            <img src="<?= '../../agencias/' . $_SESSION['cookie_pais'] . '/' . $agencia_info['departamento'] . "_" . $agencia_info['location_tag'] . "/foto_agencia.jpg" . "?=" . Date('U')?>" alt="Foto Agencia" class="thumb_foto_normal">
            <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this)">
              <p class="thumb_foto_normal_p">Cambiar Fotografía</p>
            </div>
            <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this)"></i>
            <label for="foto" id="foto_label"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
            <input type="file" id="foto" name="foto" class="" disabled>
          </div>
        </div>

        <div id="contenedor_foto2">
          <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Sube una foto del Equipo</p>
          <div id="campo_foto2" class="campo_foto">
            <img src="<?= '../../agencias/' . $_SESSION['cookie_pais'] . '/' . $agencia_info['departamento'] . "_" . $agencia_info['location_tag'] . "/foto_agencia_frontis.jpg" . "?=" . Date('U')?>" alt="Foto Agencia" class="thumb_foto_normal">
            <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this)">
              <p class="thumb_foto_normal_p">Cambiar Fotografía</p>
            </div>
            <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this)"></i>
            <label for="foto2" id="foto_label2"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
            <input type="file" id="foto2" name="foto2" class="" disabled>
          </div>
        </div>
        
      <?php else: ?>

        <div id="contenedor_foto">
          <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Sube una foto de la Agencia (Generica)</p>
          <div id="campo_foto" class="campo_foto">
            <label for="foto" id="foto_label"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
            <input type="file" id="foto" name="foto" class="">
          </div>
        </div>

        <div id="contenedor_foto2">
          <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Sube una foto del Equipo</p>
          <div id="campo_foto2" class="campo_foto">
            <label for="foto2" id="foto_label2"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
            <input type="file" id="foto2" name="foto2" class="">
          </div>
        </div>

      <?php endif; ?>

      </div>

      <input type="hidden" name="modo" value="<?= $modo_edicion ?>">

      <span class="boton_crear_agencia"><?= (isset($_SESSION['agencia_edit']) ? 'Editar Agencia' : 'Crear Agencia') ?></span>

    </div>

    <input type="hidden" name="modo" value="<?= (isset($_SESSION['agencia_edit']) ? 'edicion' : 'first_entry') ?>">
  </form>

</main>
    </div>

 </body>
</html>
