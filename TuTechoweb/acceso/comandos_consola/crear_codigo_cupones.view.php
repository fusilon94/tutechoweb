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
      <title>Consola - Crear Código Cupones</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/crear_codigo_cupones.css">
      <link rel="stylesheet" href="../../css/borrar_cupon_consola.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/crear_codigo_cupones.js"></script>

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
  <a href="sponsors_cupones_consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <h1 class="titulo">Crear Código Cupones</h1>
  <hr class="barra">

    <div class="contenedor_inactivar_sponsor_consola">

      <span class="label_select_contenedor">- Busca Sponsor(s) con cupones según Poblado o Barrio -</span>
      <div class="select_contenedor">

          <div class="elemento_formulario_sponsor">
            <label for="departamento" class="departamento_label"></label>
            <select name="departamento" id="departamento" class="departamento">
              <option></option>
              <?php foreach ($regiones as $value): ?>
                <option><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="elemento_formulario_sponsor">
              <label for="ciudad">Ciudad: </label>
              <select name="ciudad" id="ciudad" class="ciudad" onfocus='this.size=7;'
              onblur='this.size=0;'
              onchange='this.size=0; this.blur();' disabled>
              <option></option>
              </select>
          </div>

          <div class="elemento_formulario_sponsor">
              <label for="barrio">Barrio: </label>
              <select name="barrio" id="barrio" onfocus='this.size=7;'
              onblur='this.size=0;'
              onchange='this.size=0; this.blur();' class="barrio" disabled>
              </select>
          </div>


      </div>



      <div class="resultados_sponsors">

      </div>

      <input type="hidden" id="barrio_selected_input" name="barrio_selected_input" value="">

    </div>


</main>
    </div>

 </body>
</html>
