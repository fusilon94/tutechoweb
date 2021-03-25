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
      <title>Consola - Crear Tour Virtual</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" type="text/css" href="../../css/crear_tourvr.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/webgl_tester.js"></script>
      <script src="../../js/three.js"></script>
      <script src="../../js/TweenLite.min.js"></script>
      <script src="../../js/crear_tourvr.js"></script>

 </head>

 <body>

  <div id="contenedor_total">

    <div class="viewer_tooltip"></div>
    <div class="viewer_imagenes_opcionales">
      <span class="btn_cerrar_imagenes_opcionales"><i class="fas fa-times-circle"></i></span>
      <div class="contenedor_imagenes_opcionales">
        <?php foreach ($materiales as $material): ?>
          <span class="foto_opcional" id="<?= $material[0] ?>" name="<?= $material[1] ?>">
            <img src="<?= $material[1] ?>" alt="<?= $material[0] ?>">
          </span>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="viewer_tooltip_content">
      <span class="tooltip_cerrar"><i class="fas fa-times-circle"></i></span>
      <span class="imagen_opcional_container"></span>
      <span class="tooltip_text"></span>
    </div>
    <div id="foto360_container" class="foto360_container">

    </div>

    <div id="control_left_container" class="control_left_container">
      <span class="btn_abrir_left"><i class="fas fa-cog"></i></span>
      <div class="control_left">
        <span class="click_message">Haz Click Derecho para agregar un botón interactivo sobre la imagen actual</span>
        <div class="tools_consola">
          <div class="btn_choice_container">
            <span class="btn_link_choice">Link</span>
            <span class="btn_tooltip_choice">Tooltip</span>
          </div>
          <div class="choice_parameters_container">
            <div class="choice_titulo_container">
              <label for="choice_titulo">Titulo (hover):</label>
              <input id="choice_titulo" class="choice_titulo regex_checked" type="text" name="choice_titulo" value="">
            </div>
            <div class="choice_contenido">
              <div class="choice_select_foto">
                <label for="select_foto_link">Lista de Fotos:</label>
                <select id="select_foto_link" class="select_foto_link" name="select_foto_link">

                </select>
              </div>
              <div class="choice_description">
                <div class="mini_foto_config_container">
                  <span class="mini_foto_tag_opcional">(opcional)</span>
                  <span class="btn_opciones_minifotos">Imagen</span>
                  <span class="mini_foto_result"></span>
                </div>
                <textarea id="tooltip_description" class="tooltip_description regex_checked" name="tooltip_description" rows="8" cols="50" placeholder="Description del Tooltip (Max: 250 caracteres)" maxlength="250"></textarea>
                <input type="hidden" id="tooltip_description_minifoto" name="tooltip_description_minifoto" value="">
              </div>
            </div>

          </div>
          <div class="btn_add_container">
            <span class="btn_add">Agregar</span>
          </div>
        </div>
        <div class="listado_botones">
          <div class="lista_links">

            <span class="titulo_lista_links">Links Agregados <i class="fas fa-circle"></i></span>

            <div class="elementos_container">

            </div>



          </div>

          <div class="lista_tooltips">

            <span class="titulo_lista_tooltips">Tooltips Agregados <i class="fas fa-circle"></i></span>

            <div class="elementos_container">

            </div>

          </div>

        </div>

      </div>

    </div>

    <div id="control_right_container" class="control_right_container">
      <span class="btn_abrir_right"><i class="far fa-images"></i></span>
      <div class="control_right">
        <span class="encabezado">Galeria Fotos (<?= count($fotos_json) ?>)</span>
        <div class="fotos_gran_container">

          <?php foreach ($fotos_json as $titulo => $foto): ?>

            <div class="foto_mini_container">
              <div class="foto_prev_container" id="<?= $foto ?>" name='<?= $titulo ?>'>
                <img src="<?= '..\..\bienes_inmuebles' . '\\' . $_COOKIE['tutechopais'] . '\\' . urlencode($referencia) . '\\' . 'fotos' . '\\' . $foto; ?>" alt="IMAGEN PREV">
              </div>
              <div class="info_foto_prev_container">
                <span class="check_foto_entry"></span>
                <span class="foto_prev_titulo"><?= str_replace("~", "", $titulo); ?></span>
                <span class="info_foto_prev info_links"><p><?php if($modo == 'edicion'){}else{echo '0';}; ?> Links</p> <i class="fas fa-info-circle"><span class="links_info_list"></span></i></span>
                <span class="info_foto_prev info_tooltips"><?php if($modo == 'edicion'){}else{echo '0';}; ?> Tooltips</span>
              </div>
            </div>

          <?php endforeach; ?>

        </div>
        <div class="btn_crear_tour_container">
          <span class="btn_crear_tour"><?php if($modo == 'edicion'){echo 'Editar Tour VR';}else{echo 'Crear Tour VR';}; ?></span>
        </div>

      </div>

    </div>






  </div>

  <script type="text/javascript"><!-- pasamos nuestro array fotos al js -->
   var fotos_keys = JSON.parse('<?php echo json_encode($fotos_keys,JSON_HEX_TAG|JSON_HEX_APOS); ?>');
   var vr_json_edit = JSON.parse('<?php echo json_encode($VR_json,JSON_HEX_TAG|JSON_HEX_APOS|JSON_FORCE_OBJECT); ?>');
  </script>

  <form id="vr_tour_form" autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <input id="vr_tour_string" type="hidden" name="vr_tour_string" value="">
    <input type="hidden" id="referencia" name="referencia" value="<?= urlencode($referencia); ?>">
    <input type="hidden" id="tabla_bien" name="tabla_bien" value="<?= $tabla_bien ?>">
    <input type="hidden" id="modo" name="modo" value="<?= $modo; ?>">
  </form>

 </body>
</html>
