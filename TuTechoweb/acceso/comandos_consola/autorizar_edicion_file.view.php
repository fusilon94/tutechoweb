
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
      <title>Consola - Autorizar Edicion File</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_autorizar_edicon_files.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script>
      const agente = <?php echo $nivel_acceso; ?>; 
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/consola_autorizar_edicon_files.js"></script>

 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">

      <div class="popup_overlay">
        <span class="popup_message">
          <span class="popup_cerrar"><i class="fa fa-times"></i></span>
          <span class="popup_content"></span>
        </span>
      </div>
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
  <h1 class="titulo">Consola - Autorizar Edición File</h1>
  <hr class="barra">

  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="contenedor_comandos_consola" id="formulario_contratos_entry" autocomplete="off">

    <div class="opciones_wrapper">

        <div class="group_wrap">

            <div class="select_tipo_wrap">
                <label for="tipo_file_select" class="select_label">Tipo de File:</label>
                <select name="tipo_file_select" class="tipo_file_select" id="tipo_file_select">
                <option value=""></option>
                <option value="personal">Personal</option>
                <option value="inmueble">Inmuebles</option>
                </select>
            </div>

            <div class="select_pais_wrap">
            <label for="pais_select" class="select_label">País:</label>
            <select name="pais_select" class="pais_select" id="pais_select">
            <option value=""></option>
            <?php
                foreach ($paises as $pais) {
                    echo"
                    <option value=\"" . $pais['pais'] . "\">" . ucfirst($pais['pais']) . "</option>
                    ";
                };   
            ?>
            </select>
        </div>

      </div>

      <div class="group_wrap">

        <div class="select_tipo_wrap">
            <label for="tipo_doc_select" class="select_label">ID Agente Autorizado:</label>
            <input type="text" id="id_agente" class="id_agente" name="id_agente" value="">
        </div>

        <div class="select_tipo_wrap">
            <label for="tipo_doc_select" class="select_label">ID File:</label>
            <input type="text" id="id_file" class="id_file" name="id_file" value="">
            <span class="borrador_check_wrap">
                <input type="checkbox" id="borrador" name="borrador">
                <label for="borrador">Borrador</label><br>
            </span>
        </div>

      </div>

    </div>

    <span class="enviar_btn"><p>ENVIAR</p></span>

  </form>

</main>
    </div>
