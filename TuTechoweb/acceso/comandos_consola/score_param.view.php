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
      <title>Parametros Agencia</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/score_param.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/score_param.js"></script>

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
  <a href="agencias_consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>
  <h1 class="titulo">Consola - Parametros Agencia</h1>

  <form id="agencia_params_form" autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="contenedor_editor_sponsor_consola">

    <span class="label_select_contenedor"><?php if(isset($agencias)){echo "- Busca Agencia según Pais -";}else{echo "- Tu Agencia -";}; ?></span>

    <?php if (isset($agencias)): ?>

      <div class="agencia_select_container">
        <label for="agencia">Agencia: </label>
        <select name="agencia" id="agencia" class="agencia">
          <option></option>
          <?php foreach ($agencias as $agencia): ?>
            <option value="<?= $agencia['id']; ?>"><?= ucfirst($agencia['location_tag']) . " (id: #" . $agencia['id'] . ")"; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="all_params_container">
      </div>

    <?php else: ?>

      <div class="agencia_select_container">
        <label for="agencia">Agencia: </label>
        <select name="agencia" id="agencia" class="agencia" disabled>
          <option value="<?= $agencia_especifica['id']; ?>" selected><?= ucfirst($agencia_especifica['location_tag']) . " (id: #" . $agencia_especifica['id'] . ")"; ?></option>
        </select>
      </div>

      <div class="modos_container">

        <h2>Elija el "Modo" de trabajo que mejor se adapte a su situacion actual</h2>

        <?php
        $count = 1;
        while ($count <= 5) {
          if ($agencia_especifica['modo_de_trabajo'] == $count) {
            echo "<span id=\"modo" . $count . "\" class=\"modo_btn activo\" name=\"" . $count . "\">Modo " . $count . "</span>";
          }else {
            echo "<span id=\"modo" . $count . "\" class=\"modo_btn\" name=\"" . $count . "\">Modo " . $count . "</span>";
          };

          $count++;
        };
         ?>

         <input id="modo_input" type="hidden" name="modo_input" value="<?= $agencia_especifica['modo_de_trabajo']; ?>">
         <div class="explicacion_modo">

           <span class="modo_text text_modo1"><b>MODO 1:</b> Cuando la DEMANDA es ALTA y la OFERTA tambien es ALTA</span>
           <span class="modo_text text_modo2"><b>MODO 2: </b> Cuando la DEMANDA es ALTA pero la OFERTA es BAJA</br>(Si se tiene control del Mercado, usar Modo 1)</span>
           <span class="modo_text text_modo3"><b>MODO 3: </b> Cuando la DEMANDA y la OFERTA son ambas REGULARES</span>
           <span class="modo_text text_modo4"><b>MODO 4: </b> Cuando la DEMANDA es BAJA y la OFERTA es ALTA</span>
           <span class="modo_text text_modo5"><b>MODO 5: </b> Cuando la DEMANDA es BAJA y la OFERTA tambien es BAJA</br>(Si se tiene control del Mercado, usar Modo 4)</span>

         </div>

      </div>

      <div class="parametros_container">

        <div class="parametros_sub_container">
          <h2>Capacidad de compra promedio de:</h2>

          <div class="parametro_individual">
            <label for="compra_casa"><i class="fa fa-home"></i> Casas</label>
            <input id="compra_casa" value="<?php if($agencia_especifica['cap_compra_casa'] > 0){echo $agencia_especifica['cap_compra_casa'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="compra_casa" class="input_obligatorio_spinner">
          </div>
          <div class="parametro_individual">
            <label for="compra_departamento"><i class="fa fa-building"></i> Departamentos</label>
            <input id="compra_departamento" value="<?php if($agencia_especifica['cap_compra_departamento'] > 0){echo $agencia_especifica['cap_compra_departamento'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="compra_departamento" class="input_obligatorio_spinner">
          </div>
          <div class="parametro_individual">
            <label for="compra_local"><i class="fa fa-shopping-bag"></i> Locales</label>
            <input id="compra_local" value="<?php if($agencia_especifica['cap_compra_local'] > 0){echo $agencia_especifica['cap_compra_local'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="compra_local" class="input_obligatorio_spinner">
          </div>
          <div class="parametro_individual">
            <label for="compra_terreno"><i class="fa fa-tree"></i> Terrenos</label>
            <input id="compra_terreno" value="<?php if($agencia_especifica['cap_compra_terreno'] > 0){echo $agencia_especifica['cap_compra_terreno'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="compra_terreno" class="input_obligatorio_spinner">
          </div>
        </div>
        <div class="parametros_sub_container">
          <h2>Capacidad de alquiler promedio de:</h2>

          <div class="parametro_individual">
            <label for="renta_casa"><i class="fa fa-home"></i> Casas</label>
            <input id="renta_casa" value="<?php if($agencia_especifica['cap_renta_casa'] > 0){echo $agencia_especifica['cap_renta_casa'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="renta_casa" class="input_obligatorio_spinner">
          </div>
          <div class="parametro_individual">
            <label for="renta_departamento"><i class="fa fa-building"></i> Departamentos</label>
            <input id="renta_departamento" value="<?php if($agencia_especifica['cap_renta_departamento'] > 0){echo $agencia_especifica['cap_renta_departamento'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="renta_departamento" class="input_obligatorio_spinner">
          </div>
          <div class="parametro_individual">
            <label for="renta_local"><i class="fa fa-shopping-bag"></i> Locales</label>
            <input id="renta_local" value="<?php if($agencia_especifica['cap_renta_local'] > 0){echo $agencia_especifica['cap_renta_local'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="renta_local" class="input_obligatorio_spinner">
          </div>
          <div class="parametro_individual">
            <label for="renta_terreno"><i class="fa fa-tree"></i> Terrenos</label>
            <input id="renta_terreno" value="<?php if($agencia_especifica['cap_renta_terreno'] > 0){echo $agencia_especifica['cap_renta_terreno'];}; ?>" type="text" autocomplete="off" readonly="readonly" name="renta_terreno" class="input_obligatorio_spinner">
          </div>
        </div>

        <?php
          if ($anticretico_existe['anticretico_existe'] == 1) {
            echo"
              <div class=\"parametros_sub_container\">
                <h2>Anticretico promedio</h2>
      
                <div class=\"parametro_individual\">
                  <label for=\"anticretico\">Porcentage del Precio de Venta (%)</label>
                  <input id=\"anticretico\" value=\"";

              if($agencia_especifica['cap_anticretico'] > 0){echo $agencia_especifica['cap_anticretico'];};

              echo "\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"anticretico\" class=\"input_obligatorio_spinner\">
                </div>
              </div>
            ";
          };
        ?>
        

        <div class="registrar_btn_container">
          <span class="registrar_btn">Registrar</span>
        </div>


      </div>

    <?php endif; ?>


  </form>

</main>
    </div>

 </body>
</html>
