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
      <title>Consola - Validar Files</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_validar_file.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/validar_file_consola.js"></script>

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
  <a href="consola_registro_documentos.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <div class="popup_success">
    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text"></p>
  </div>

  <h1 class="titulo">Consola - Validar Files</h1>
  <hr class="barra">

  <div class="contenedor_borradores">


    <?php
    foreach ($files_agentes as $files_agente) {
      echo "
      <div class=\"boton_file\" id=\"" . $files_agente['id'] . "\" name=\"personal\" data=\"" . $files_agente['pais'] . "\">
        <div id=\"" . $files_agente['id'] . "\" class=\"boton_borrador_formulario\">
          <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class='nombre'>" . $files_agente['id'] . " - " . $files_agente['cargo'] . "<br>" . strtoupper($files_agente['pais']) . "</span></p>
        </div>
      </div>

      ";
    };

    foreach ($files_inmuebles as $files_inmueble) {
      echo "
      <div class=\"boton_file\" id=\"" . $files_inmueble['referencia'] . "\" name=\"inmueble\" data=\"" . $files_inmueble['pais'] . "\">
        <div id=\"" . $files_inmueble['referencia'] . "\" class=\"boton_borrador_formulario\">
          <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class='nombre'>" . $files_inmueble['referencia'] . " - " . ucfirst($files_inmueble['tipo_bien']) . "<br>" . strtoupper($files_inmueble['pais']) . " - " . ucfirst($files_inmueble['ciudad']) . "</span></p>
        </div>
      </div>

      ";
    };

    ?>

  </div>

  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="formulario" name="open_file" id="open_file" autocomplete="off">
    <input type="hidden" name="id_selected" id="id_selected" value="">
    <input type="hidden" name="tipo_file_selected" id="tipo_file_selected" value="">
    <input type="hidden" name="pais_selected" id="pais_selected" value="">
  </form>

</main>
    </div>

  <!-- zona para la ventana de dialogo emergente si hubo exito en alfun formulario anterior -->
<?php if(!empty($mesaje_file)): ?>
  <div id="dialog_exito" title="Informe:"> <!-- alert message linked to validar datos button -->
    <p><?php echo $mesaje_file; ?></p>
  </div>
<?php endif; ?>

 </body>
</html>
