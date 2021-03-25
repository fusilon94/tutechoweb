<?php
if(isset($_SESSION['usuario'])){} else{header('Location: ../acceso.php');} //para evitar que alguien entre directamente al .view.php
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
      <title>Cambiar mi Contraseña</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/cambiar_codigos_individuales.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/cambiar_codigos_individuales.js"></script>
 </head>
 <body>
 <div id="fondo"></div>
      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
      	  <header>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="../index.php"><img src="../../objetos/logotipo2.svg" alt="Tu Techo.com" class="menu_logo"></a></li>
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
          <main class="contenedor">
                <div class="popup_success">
                    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
                    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text"></p>
                </div>
            <h1 class="titulo">Cambiar mis Codigos de Acceso</h1>
            <hr class="barra">
            <div class="regreso_boton_div_contenedor">
            <a href="info_personal_consola.php">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
            </a>
            </div>

            <span class="instruciones">
                <i class="fas fa-exclamation-circle"></i>
                <p>La nueva contraseña debe ser de almenos 6 caracteres y contener 1 mayúscula y 1 digito</p>
            </span>

            <div class="formulario">
              <div class="form-group">
                <i class="icono izquierda fas fa-key"></i><input type="password" name="codigo_actual" class="codigo_actual" placeholder="Contraseña Actual">
              </div>

              <div class="form-group">
                <i class="icono izquierda fa fa-lock"></i><input type="password" name="new_codigo" class="new_codigo" placeholder="Nueva Contraseña">
              </div>

              <div class="form-group">
                <i class="icono izquierda fa fa-lock"></i><input type="password" name="new_codigo2" class="new_codigo2" placeholder="Repetir Contraseña">
              </div>

              <span class="btn_cambiar_codigos">Guardar Cambios</span>

              <span class="sin_codigos"><p>No recuerdo mi contraseña</p></span>

             </div>

          </main>
    </div>

 </body>
</html>
