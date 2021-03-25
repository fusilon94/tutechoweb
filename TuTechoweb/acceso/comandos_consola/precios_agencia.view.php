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
      <title>Precios Agencia</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/precio_agencia.css" media="screen">
      <link rel="stylesheet" href="../../css/precio_agencia_print.css" media="print">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script>const current_date = "<?= date('Y-m-d') ?>"</script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/precio_agencia.js"></script>

 </head>
 <body>

 <div class="popup_overlay">
    <span class="popup">
        <span class="cerrar_popup"><i class="fas fa-times-circle"></i></span>
        <span class="popup_contenido"></span>
    </span>
  </div>

  <div class="preview_overlay">
    <span class="preview">
        <span class="cerrar_preview"><i class="fas fa-times-circle"></i></span>
        <span class="preview_contenido"></span>
    </span>
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
  <a href="agencias_consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <h1 class="titulo">Consola - Precios Agencia</h1>

  <form id="agencia_params_form" autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="contenedor_editor_sponsor_consola">

    <span class="label_select_contenedor">- Busca Agencia según Pais -</span>

    <div class="selects_container">

        <?php if (isset($paises)): ?>

            <div class="pais_select_container">
            <label for="pais">Pais: </label>
            <select name="pais" id="pais" class="pais">
                <option></option>
                <?php foreach ($paises as $pais): ?>
                <option value="<?= $pais['pais']; ?>"><?= ucfirst($pais['pais']); ?></option>
                <?php endforeach; ?>
            </select>
            </div>

        <?php endif; ?>

        <div class="agencia_select_container">
            <label for="agencia">Agencia: </label>
            <select name="agencia" id="agencia" class="agencia" disabled>
            <option></option>
            </select>
        </div>

    </div>              

    <div class="all_params_container">

        

    </div>




  </form>

</main>
    </div>

 </body>
</html>
