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
      <title>Completar Fotos</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/completar_fotos.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script type="text/javascript" src="../../js/jquery.uploadPreview.min.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/completar_fotos.js"></script>
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
  <a href="<?php if($modo_consola == 'first entry'){echo"consola_completar_fotos.php";}else{echo"consola_editar_fotos.php";}; ?>">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <div class="popup_success">
    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text">Todos los titulos deben ser distintos</p>
  </div>

  <h1 class="titulo">Completar Fotos</h1>
  <h1 class="titulo"> Referencia: <?php echo $referencia ?></h1>
  <hr class="barra">


    <form id="formulario_registro_fotos" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

      <div class="instrucciones">
        <p style="width:100%; font-size: 0.8em;">* Sólo fotografias 2D de 3:2 1600x1068</p>
        <p style="width:100%; font-size: 0.8em;">* Las miniaturas pueden salir distorcionadas (1:1)</p>
        <?php if(empty($VR_json)){echo "<p style=\"width:100%; font-size: 0.8em;\">* El mínimo de fotos es de 2</p>";}; ?>
        <p style="width:100%; font-size: 0.8em;">* Todos los campos NARANJAS visibles deben estar llenos para registrar</p>
        <?php if(!empty($VR_json)){echo "<b style=\"width:100%; font-size: 0.9em;\">* Tour VR associado, no se pueden eliminar los campos verdes</b>";}; ?>
      </div>

      <div class="btn_agregar_campos_container">
        <div class="sticky_container">
          <span class="limite_text">Max: <?php if($exclusivo == 1){echo '15';}else{echo '3';}; ?> fotos</span>
          <div class="btn_container">
            <span class="quitar_campo_btn"><i class="fas fa-minus-circle"></i></span>
            <span class="cuenta_de_campos"><?php if($modo_consola == "first entry"){if($exclusivo == 0){echo "MAX";}else{echo "3 Fotos";};}; if($modo_consola == "edicion"){if($fotos_edicion_count == 2){echo "MIN";}else{if(!empty($VR_json)){echo "MIN";}else{if(($exclusivo == 0 && $fotos_edicion_count == 3) || ($exclusivo == 1 && $fotos_edicion_count == 15)){echo "MAX";}else{echo $fotos_edicion_count . " Fotos";};};};} ?></span>
            <span class="agregar_campo_btn <?php if($exclusivo == 0){echo "limit_reached";}; ?>"><i class="fas fa-plus-circle"></i></span>
          </div>
        </div>
      </div>

      <div class="lista" id="lista">

    <?php if ($modo_consola == 'edicion'): ?>

      <div class="marcos_contenedor ignore-elements">
        <?php $contador_bordes = 1; ?>
        <?php foreach ($fotos_found as $foto): ?>
          <div class="borde_foto">
            <span class="tag_borde"><p><?= $contador_bordes ?></p></span>
          </div>
          <?php $contador_bordes++; ?>
        <?php endforeach; ?>

      </div>

        <?php $contador_fotos = 1; ?>
        <?php foreach ($fotos_found as $foto_key => $foto_value): ?>

          <div id="contenedor_foto<?= $contador_fotos ?>" class="borde_foto_trans <?php if(empty($VR_json)){echo "campo_borrable";};?>">

            <span class="drag_handler"><i class="fas fa-arrows-alt"></i></span>

          <div id="campo_foto<?= $contador_fotos ?>" class="campo_foto">
            <img src="<?= $directorio_bien . '/' . 'fotos' . '/' . $foto_value . "?=" . Date('U')?>" alt="thumb_foto<?= $contador_fotos ?>" class="thumb_foto_normal">
            <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this)">
              <p class="thumb_foto_normal_p">Cambiar Fotografía</p>
            </div>
            <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this)"></i>
            <label for="foto<?= $contador_fotos ?>" id="foto<?= $contador_fotos ?>_label"><p> Sube la Fotografía</br><span>Click or Drop</span></p></label>
            <input type="file" id="foto<?= $contador_fotos ?>" name="foto<?= $contador_fotos ?>" accept="image/jpeg" onchange="check_jpg(this)" disabled>
          </div>

          <div class="contenedor_foto_360_titulo">
              <div class="subtitulo_container">
                 <label for="titulo_foto<?= $contador_fotos ?>">Titulo de la foto</label>
                 <input type="text" id="titulo_foto<?= $contador_fotos ?>" name="titulo_foto<?= $contador_fotos ?>" class="titulo_foto" value="<?= str_replace("~", "", $foto_key); ?>">
                 <input type="hidden" id="titulo_foto<?= $contador_fotos ?>_original" name="titulo_foto<?= $contador_fotos ?>_original" value="<?= str_replace("~", "", $foto_key); ?>">
              </div>

              <div class="campo_foto_360" id="campo_foto<?= $contador_fotos ?>_360">
                <img src="<?= $directorio_bien . '/' . 'fotos_360' . '/' . $foto_value. "?=" . Date('U')?>" alt="thumb_foto<?= $contador_fotos ?>_360" class="thumb_foto_normal">
                <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this)">
                  <p class="thumb_foto_normal_p360">Cambiar</p>
                </div>
                <i class="fa fa-undo-alt return_change_foto return_foto360" onclick="return_foto_click_operator(this)"></i>
                <label for="foto<?= $contador_fotos ?>_360"><p>360°</p><i class="far fa-check-circle"></i></label>
                <input type="file" id="foto<?= $contador_fotos ?>_360" accept="image/jpeg" name="foto<?= $contador_fotos ?>_360" data-id="foto<?= $contador_fotos ?>_360" onchange = "check(this)" disabled>
              </div>

            </div>
          </div>
          <?php $contador_fotos++; ?>
        <?php endforeach; ?>


        <?php else: ?>

          <div class="marcos_contenedor ignore-elements">

            <div class="borde_foto">
              <span class="tag_borde"><p>1</p></span>
            </div>

            <div class="borde_foto">
              <span class="tag_borde"><p>2</p></span>
            </div>

            <div class="borde_foto">
              <span class="tag_borde"><p>3</p></span>
            </div>

          </div>

          <div id="contenedor_foto1" class="campo_nuevo borde_foto_trans campo_editable campo_borrable">

              <span class="drag_handler"><i class="fas fa-arrows-alt"></i></span>

              <div id="campo_foto1" class="campo_foto">
                <label for="foto1" id="foto1_label"><p> Sube la Fotografía</br><span>Click or Drop</span></p></label>
                <input type="file" id="foto1" name="foto1" accept="image/jpeg" onchange="check_jpg(this)">
              </div>

            <div class="contenedor_foto_360_titulo">
                <div class="subtitulo_container">
                   <label for="titulo_foto1">Titulo de la foto</label>
                   <input type="text" id="titulo_foto1" name="titulo_foto1" class="titulo_foto" value="">
                   <input type="hidden" id="titulo_foto1_original" name="titulo_foto1_original" value="old_empty">
                </div>

                <?php
                if ($activo_360 == 0 || ($activo_360 == 1 && $exclusivo_360 == 1 && $exclusivo == 0)) {
                  echo"<div class=\"campo_foto_360\" id=\"campo_foto1_360\" style=\"visibility: hidden\">
                  </div>";
                }else if(($activo_360 == 1 && $exclusivo_360 == 0) || ($activo_360 == 1 && $exclusivo_360 == 1 && $exclusivo == 1)){
                  echo"<div class=\"campo_foto_360\" id=\"campo_foto1_360\">
                    <label for=\"foto1_360\"><p>360°</p><i class=\"far fa-check-circle\"></i></label>
                    <input type=\"file\" id=\"foto1_360\" accept=\"image/jpeg\" name=\"foto1_360\" data-id=\"foto1_360\" onchange = \"check(this)\">
                  </div>";
                };
                 ?>

            </div>

          </div>

          <div id="contenedor_foto2" class="borde_foto_trans campo_editable campo_borrable campo_nuevo">

            <span class="drag_handler"><i class="fas fa-arrows-alt"></i></span>

              <div id="campo_foto2" class="campo_foto">
                <label for="foto2" id="foto2_label"><p> Sube la Fotografía</br><span>Click or Drop</span></p></label>
                <input type="file" id="foto2" name="foto2" accept="image/jpeg" onchange="check_jpg(this)">
              </div>

            <div class="contenedor_foto_360_titulo">
                <div class="subtitulo_container">
                   <label for="titulo_foto2">Titulo de la foto</label>
                   <input type="text" id="titulo_foto2" name="titulo_foto2" class="titulo_foto" value="">
                   <input type="hidden" id="titulo_foto2_original" name="titulo_foto2_original" value="old_empty">
                </div>

                <?php
                if ($activo_360 == 0 || ($activo_360 == 1 && $exclusivo_360 == 1 && $exclusivo == 0)) {
                  echo"<div class=\"campo_foto_360\" id=\"campo_foto2_360\" style=\"visibility: hidden\">
                  </div>";
                }else if(($activo_360 == 1 && $exclusivo_360 == 0) || ($activo_360 == 1 && $exclusivo_360 == 1 && $exclusivo == 1)){
                  echo"<div class=\"campo_foto_360\" id=\"campo_foto2_360\">
                    <label for=\"foto2_360\"><p>360°</p><i class=\"far fa-check-circle\"></i></label>
                    <input type=\"file\" id=\"foto2_360\" accept=\"image/jpeg\" name=\"foto2_360\" data-id=\"foto2_360\" onchange = \"check(this)\">
                  </div>";
                };
                 ?>

            </div>

          </div>

          <div id="contenedor_foto3" class="borde_foto_trans campo_editable campo_borrable campo_nuevo">

            <span class="drag_handler"><i class="fas fa-arrows-alt"></i></span>

              <div id="campo_foto3" class="campo_foto">
                <label for="foto3" id="foto3_label"><p> Sube la Fotografía</br><span>Click or Drop</span></p></label>
                <input type="file" id="foto3" name="foto3" accept="image/jpeg" onchange="check_jpg(this)">
              </div>

            <div class="contenedor_foto_360_titulo">
                <div class="subtitulo_container">
                   <label for="titulo_foto3">Titulo de la foto</label>
                   <input type="text" id="titulo_foto3" name="titulo_foto3" class="titulo_foto" value="">
                   <input type="hidden" id="titulo_foto3_original" name="titulo_foto3_original" value="old_empty">
                </div>
                <?php
                if ($activo_360 == 0 || ($activo_360 == 1 && $exclusivo_360 == 1 && $exclusivo == 0)) {
                  echo"<div class=\"campo_foto_360\" id=\"campo_foto3_360\" style=\"visibility: hidden\">
                  </div>";
                }else if(($activo_360 == 1 && $exclusivo_360 == 0) || ($activo_360 == 1 && $exclusivo_360 == 1 && $exclusivo == 1)){
                  echo"<div class=\"campo_foto_360\" id=\"campo_foto3_360\">
                    <label for=\"foto3_360\"><p>360°</p><i class=\"far fa-check-circle\"></i></label>
                    <input type=\"file\" id=\"foto3_360\" name=\"foto3_360\" accept=\"image/jpeg\" data-id=\"foto3_360\" onchange = \"check(this)\">
                  </div>";
                };
                 ?>

            </div>

          </div>

        <?php endif; ?>



      </div>

      <input type="hidden" id="referencia" name="referencia" value="<?php echo $referencia ?>">
      <input type="hidden" id="tabla_bien" name="tabla_bien" value="<?php echo $tabla_bien ?>">
      <input type="hidden" id="modo_consola" name="modo_consola" value="<?php echo $modo_consola ?>">
    </form>

    <div class="btn_fin_container">
      <div class="btn_validar">
        VALIDAR
      </div>
      <div class="btn_registrar">
        REGISTRAR
      </div>
    </div>




  <input type="hidden" id="exclusivo_360" name="exclusivo_360" value="<?= $exclusivo_360 ?>">
  <input type="hidden" id="activo_360" name="activo_360" value="<?= $activo_360 ?>">
  <input type="hidden" id="contador_max" name="contador_max" value="<?php if($exclusivo == 1){echo 15;}else{echo 3;}; ?>">
  <input type="hidden" id="contador_min" name="contador_min" value="<?php if($modo_consola == 'first entry'){echo 2;}else{if(empty($VR_json)){echo 2;}else{echo $fotos_edicion_count;};}; ?>">
  <input type="hidden" id="contador_edit" name="contador_edit" value="<?php if($fotos_edicion_count !== ''){echo $fotos_edicion_count;} ?>">
  <input type="hidden" id="vr_exist" name="vr_exist" value="<?php if(!empty($VR_json)){echo 'SI';} ?>">

  <?php
  unset($_SESSION['referencia_bien']);//ASI NO TENEMOS PROBLEMAS DE SESSION SI SE ABANDONA LA PAGINA SUBITAMENTE
  unset($_SESSION['tabla_bien']);//ASI NO TENEMOS PROBLEMAS DE SESSION SI SE ABANDONA LA PAGINA SUBITAMENTE
   ?>

</main>
    </div>

 </body>
</html>
