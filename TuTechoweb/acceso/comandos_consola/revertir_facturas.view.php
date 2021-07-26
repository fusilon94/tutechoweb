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
      <title>Revertir Facturas</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="../../css/consola_facturas.css">
      <link rel="stylesheet" href="../../css/facturas_consola_preview.css">


      <script>
        const modo = 'revertir';
        const moneda_string = "<?= $moneda['moneda_string'];?>";
        const moneda_code = "<?= $moneda['moneda_code'];?>";
        const moneda_symbol = "<?= $moneda['moneda'];?>";
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/qart.min.js"></script>
      <script src="../../js/numero_a_letras.js"></script>
      <script src="../../js/consola_facturas_<?= $_COOKIE['tutechopais'];?>.js"></script>
     
 </head>
 <body>

 <div id="fondo"></div>

 <div class="popup_overlay">
      <div class="popup">
          <span class="popup_cerrar"><i class="fa fa-times"></i></span>
          <div class="popup_content">


          </div>
      </div>    
  </div>

    <div id="contenedor_total">

        <!-- BARRA DE NAVEGACION -->

      	  <header>
            <div class="regreso_boton_div_contenedor">
                <a href="consola_facturas.php">
                <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
                <span class="atras_texto"><p>VOLVER ATRÁS</p></span>
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

        <!-- CONTENIDO -->

        <div class="contenido_wrap">

        <div class="cabecera">
          <span class="cabecera_nombre">
            <img src="../../objetos/agencias_consola.svg" alt="foto">
          </span>
          <select class="agencia_select">
            <?php
                if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {
                    echo"<option value=\"\"></option>";
                    foreach ($agencias as $agencia) {
                        echo"<option value=\"" . $agencia['id'] . "\">" . $agencia['location_tag'] . "</option>";
                    };
                }else{
                  echo"<option value=\"" . $agencias[0]['id'] . "\" selected>" . $agencias[0]['location_tag'] . "</option>";
                };
            ?>
            
          </select>
        </div>


        <div class="graph_consola">

            <div class="tabs_wrap">
              <i class="fas fa-search"></i>
              <input class="input_factura_id" placeholder="Factura ID">
            </div>

            <div class="tab_content_lista">

              <div class="list_container">


              </div>

            </div>

        </div>



        </div>

    </div>

 </body>
</html>