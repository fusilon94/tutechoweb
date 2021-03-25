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
      <title>Creador de File</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/file_maker.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <!-- <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css"> -->

      <script>
        const moneda = "<?= $moneda ?>";
        const pais_selected = "<?= $pais; ?>";
        const id_file = "<?= $id_file; ?>";
        const tipo_file_recieved = "<?= $tipo_file_received; ?>";
        const tipo_doc = "<?= $documento; ?>";
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script type="text/javascript" src="../../js/jquery.uploadPreview.min.js"></script>
      <script src="../../js/file_maker.js"></script>
      <script src="../../js/file_maker_<?= $documento_mode ?>.js"></script>

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
  <a href="consola_registro_documentos.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <div class="popup_success">
    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text"></p>
  </div>

  <form id="nueva_file_form" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

    <span class="titulo_azul">- Registro File: <?= ucfirst(str_replace("_"," ",$documento)) ?><?php if($tipo_file_received == 'venta' || $tipo_file_received == 'alquiler'){echo " en " . ucfirst($tipo_file_received);}; ?> en <?= ucfirst($pais) ?> -<?php if($modo_edicion !== ''){echo "</br>MODO EDICIÓN</br>ID:&nbsp" . $id_file;} ?></span>

    <div class="contenedor_consola">
      <div class="carga_dinamica_container">

          <div class="inputs_contenedor">

          </div>
            
          <div class="drags_contenedor">

          </div>
          
        </div>    

      <span class="boton_crear_file"><?= ($modo_edicion !== '' ? 'Validar Cambios' : 'Crear File') ?></span>

      

    </div>

    <input type="hidden" name="doc_selected" value="<?= $documento ?>">
    <input type="hidden" name="modo" value="<?= ($modo_edicion !== '' ? 'edicion' : 'first_entry') ?>">
    <input type="hidden" name="id_edicion" value="<?= $id_file ?>">
    <input type="hidden" name="tipo_file" value="<?= $tipo_file_received ?>">
    <input type="hidden" name="tipo_inmueble" id="tipo_inmueble" value="">
  </form>

</main>
    </div>

 </body>
 <?php
  // unset($_SESSION['tipo_doc_selected']);// SIEMPRE DESTRUIR ESTA VARIABLE DE SESSION PARA PODER ACCEDER AL REGISTRO SPONSOR NORMAL SIN PROBLEMAS
  // unset($_SESSION['pais_selected']);
  // unset($_SESSION['tipo_file_selected']);
  // if (isset($_SESSION['id_file'])) {
  //   unset($_SESSION['id_file']);
  // };
 ?>
</html>