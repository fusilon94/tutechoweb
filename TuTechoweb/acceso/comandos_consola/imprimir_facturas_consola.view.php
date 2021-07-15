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
      <title>Consola - Imprimir Facturas</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_modificar_file.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/imprimir_facturas_consola.js"></script>

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
  <a href="consola_facturas.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <div class="popup_success">
    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text"></p>
  </div>

  <h1 class="titulo">Consola - Imprimir Facturas</h1>
  <hr class="barra">

  <div class="contenedor_borradores">


    <?php
      foreach ($facturas_pendientes as $factura) {
        echo "
        <div class=\"boton_file\" id=\"" . $factura['id'] . "\" tipo=\"" . $factura['tipo'] . "\" fecha=\"" . $factura['fecha'] . "\" referencia=\"" . $factura['referencia_inmueble'] . "\">
          <div id=\"" . $factura['id'] . "\" class=\"boton_borrador_formulario\">
            <i class=\"far fa-edit\" aria-hidden=\"true\"></i>
            <p>
              <span class='nombre'>" . $factura['fecha'] . " - " . $factura['referencia_inmueble'] . "</span>
            </p>
          </div>
        </div>

        ";
      };
    ?>

  </div>

  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="formulario" name="open_file" id="open_file" autocomplete="off">
    <input type="hidden" name="id_factura" id="id_factura" value="">
    <input type="hidden" name="tipo_factura" id="tipo_facrura" value="">
    <input type="hidden" name="referencia_inmueble" id="referencia_inmueble" value="">
    <input type="hidden" name="fecha_factura" id="fecha_factura" value="">
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
