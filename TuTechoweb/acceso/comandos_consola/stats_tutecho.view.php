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
      <title>Stats Tutecho</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="../../css/consola_stats.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>

      <script>
        const agente_id_default = '<?= $agente_id['id'] ?>';
        const CONTENEDOR = document.getElementById('graph_container');
        const moneda_code = '<?= $pais_info['moneda_code'] ?>';
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/plotly-latest.min.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>
      <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
      <script src="../../js/stats_functions.js"></script>
      <script src="../../js/consola_stats_tutecho.js"></script>
     
 </head>
 <body>

 <div id="fondo"></div>

    <div id="contenedor_total">

        <!-- BARRA DE NAVEGACION -->

      	  <header>
            <div class="regreso_boton_div_contenedor">
                <a href="info_personal_consola.php">
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
            <p>TuTecho Stats.</p>
          </span>
        </div>

        <div class="menu_lateral">
          <h2>Categorias</h2>
          <hr>
          <div class="categorias_wrap">

          </div>
        </div>


        <span class="menu_lateral_btn">
          <i class="fas fa-bars"></i>
        </span>

        <span class="print_btn">
          <img src="../../objetos/imprimir_btn.svg" alt="Imprimir" title="Imprimir">
        </span>

        <div class="graph_consola">

            <div class="tabs_wrap">
              <span class="tab tab_lista activo">
                <i class="fas fa-list-ul"></i>
                <p>Lista</p>
              </span>
              <span class="tab tab_graph">
                <i class="fas fa-chart-pie"></i>
                <p>Graph</p>
              </span>
            </div>

            <div class="tab_content_lista activo">

              <div class="list_container">

                <div class="titles_wrap">
                  <span class="title_element"><p>Fecha</p><span class="filtro_btn activo"><i class="fas fa-sort"></i></span></span>
                  <span class="title_element"><p>Referencia</p><span class="filtro_btn"><i class="fas fa-sort"></i></span></span>
                  <span class="title_element"><p>Tipo Inmueble</p><span class="filtro_btn activo"><i class="fas fa-sort"></i></span></span>
                  <span class="title_element"><p>Exito</p><span class="filtro_btn"><i class="fas fa-sort"></i></span></span>
                </div>

                <hr class="barra_titulos">

                <div class="lista_wrap">
                  <span class="lista_row">
                    <p class="lista_col">03/12/2020</p>
                    <p class="lista_col">#1C010118</p>
                    <p class="lista_col">Casa</p>
                    <p class="lista_col"><i class="fas fa-times"></i></p>
                  </span>

                  <span class="lista_row">
                    <p class="lista_col">10/02/2021</p>
                    <p class="lista_col">#1L065118</p>
                    <p class="lista_col">Local</p>
                    <p class="lista_col"><i class="fas fa-check"></i></p>
                  </span>

                  <span class="lista_row">
                    <p class="lista_col">27/02/2021</p>
                    <p class="lista_col">#5T079418</p>
                    <p class="lista_col">Terreno</p>
                    <p class="lista_col"><i class="fas fa-times"></i></p>
                  </span>
                </div>

              </div>


            </div>

            <div class="tab_content_graph">

                <div class="graph_container" id="graph_container">

                </div>

                <div class="actions_graph_wrap">

                </div>

                <div class="sub_categorias_wrap">
                  
                </div>


            </div>

            

        </div>



        </div>

    </div>

 </body>
</html>