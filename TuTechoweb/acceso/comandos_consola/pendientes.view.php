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
      <title>Pendientes</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
      
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/pendientes.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/pendientes.js"></script>

 </head>

 <!-- PARA AGREGAR MAS TIPOS DE PENDIENTES

  1-agregar al foreach del .view para la first charge (distinguir entre pendiente_agente et pendiente_grupal)
    1.1- definir si se podra borrar a voluntad o bien se hara automaticamente pasado un tiempo o bien cumpliendo con una fecha_creacion
    1.2- en caso de condicion, establecerla en el .js
  2-agregar el color de la etiqueta en el class
  3-agregar al foreach del process request para vistos y borrados (2 veces)

 -->

 <body>

   <div class="popup_pendiente_container">
     <div class="popup_pendiente">
       <span class="popup_cerrar"><i class="fas fa-times"></i></span>
       <div class="popup_contenido">

       </div>
     </div>
   </div>

   <div class="pop_up_info_container">
     <div class="popup_info">
       <span class="popup_info_cerrar"><i class="fas fa-times"></i></span>
       <div class="popup_info_contenido">

       </div>
     </div>
   </div>

 <div id="fondo"></div>
      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
          <header>
            <div class="regreso_boton_div_contenedor">
            <a href="consola.php">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
            </a>
            </div>
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

  <div class="contenedor_global">

    <span class="label_select_contenedor">- Lista de Pendientes -</span>
    <div class="switch_container">
      <div class="switch">
        <span class="switch_nuevos switch_btn active">Pendientes</span>
        <span class="switch_borrados switch_btn">Borrados</span>
      </div>
    </div>

    <div class="pendientes_contenedor">

      <?php foreach ($pendientes_agente as $pendiente_agente): ?>

        <div class="pendiente" id="<?= $pendiente_agente['codigo'] ?>">
          <div class="pendiente_wrapper <?= ($pendiente_agente['visto'] == 0 ? 'no_leido' : '') ?>">
            <span class="etiqueta
             <?= ($pendiente_agente['tipo'] == 'reclamo' ? 'reclamo' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'anuncio' ? 'anuncio' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'autorizacion' ? 'autorizacion' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'agente_validado' ? 'agente_validado' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'reclamo_file' ? 'reclamo_file' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'inmueble_validado' ? 'inmueble_validado' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'contacto_compartido' ? 'contacto_compartido' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'mensaje_interno' ? 'mensaje_interno' : '') ?>
             <?= ($pendiente_agente['tipo'] == 'check_list_compartido' ? 'check_list_compartido' : '') ?>
             ">
              <?= ucfirst($pendiente_agente['tipo']) ?>
            </span>
            <span class="tag_corner">
              <span class="pais_tag"><?= ucfirst($pendiente_agente['pais']);?></span>
              <span class="fecha_creacion"><?= "&nbsp/&nbsp" . $pendiente_agente['fecha_creacion'] ?></span>
            </span>
            
            <span class="pendiente_contenido">
              <?php if ($pendiente_agente['tipo'] == 'reclamo' || $pendiente_agente['tipo'] == 'autorizacion' || $pendiente_agente['tipo'] == 'inmueble_validado'): ?>
                Referencia: <?= $pendiente_agente['key_feature1']?>
              </br>
              <?php elseif($pendiente_agente['tipo'] == 'reclamo_file'): ?>
                Tipo: <?= $pendiente_agente['key_feature2']?>
                </br>
                File ID: <?= $pendiente_agente['key_feature1']?>
                </br>
              <?php elseif($pendiente_agente['tipo'] == 'agente_validado'): ?>
                File ID: <?= $pendiente_agente['key_feature1']?>
                </br>
              <?php elseif($pendiente_agente['tipo'] == 'contacto_compartido' || $pendiente_agente['tipo'] == 'mensaje_interno' || $pendiente_agente['tipo'] == 'check_list_compartido'): ?>
                Enviado por: <?= $pendiente_agente['key_feature1']?>
                </br></br>
              <?php endif; ?>
              <?= nl2br($pendiente_agente['mensaje']) ?>
            </span>
          </div>
          <div class="pendiente_btn">
            <?php if ($pendiente_agente['tipo'] == 'reclamo' || $pendiente_agente['tipo'] == 'reclamo_file'): ?>
              <i class="fas fa-question-circle"></i>
            <?php endif; ?>
            <?php if ($pendiente_agente['tipo'] == 'anuncio' || $pendiente_agente['tipo'] == 'autorizacion' || $pendiente_agente['tipo'] == 'agente_validado' || $pendiente_agente['tipo'] == 'inmueble_validado' || $pendiente_agente['tipo'] == 'contacto_compartido' || $pendiente_agente['tipo'] == 'mensaje_interno' || $pendiente_agente['tipo'] == 'check_list_compartido'): ?>
              <i class="fas fa-times-circle"></i>
            <?php endif; ?>
          </div>
        </div>

      <?php endforeach; ?>


      <?php foreach ($array_pendientes_grupo as $pendiente_grupo): ?>

        <div class="pendiente" id="<?= $pendiente_grupo['codigo'] ?>">
          <div class="pendiente_wrapper">
            <span class="etiqueta
             <?= ($pendiente_grupo['tipo'] == 'anuncio' ? 'anuncio' : '') ?>
             ">
              <?= ucfirst($pendiente_grupo['tipo']) ?>
            </span>
            <span class="tag_corner">
              <span class="pais_tag"><?= ucfirst($pendiente_grupo['pais']);?></span>
              <span class="fecha_creacion"><?= "&nbsp/&nbsp" . $pendiente_grupo['fecha_creacion'] ?></span>
            </span>
            <span class="pendiente_contenido">
              <?= nl2br($pendiente_grupo['mensaje']) ?>
            </span>
          </div>
          <div class="pendiente_btn">
              <i class="fas fa-question-circle"></i>
          </div>
        </div>

    <?php endforeach; ?>

    </div>

  </div>

  <input type="hidden" id="id_agente" name="id_agente" value="<?= $agente_id['id'] ?>">

</main>
    </div>

 </body>
</html>
