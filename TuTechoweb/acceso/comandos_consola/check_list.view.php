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
      <title>Check Lists</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="../../css/check_list.css">

      <script>
          const agente_id_default = '<?= $agente_id['id'] ?>';
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/jquery-clock-timepicker.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/check_list.js"></script>
     
 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">

<!-- BARRA DE NAVEGACION -->

      	  <header>
            <div class="regreso_boton_div_contenedor">
            <a href="consola.php">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
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

    <div class="popup_overlay">
        <span class="popup">
            <span class="cerrar_popup"><i class="fas fa-times-circle"></i></span>
            <span class="popup_contenido">
            

            </span>
        </span>
    </div>

    <div class="menu_more" data="">
        <span class="menu_more_opcion opcion_compartir">
            <p>Compartir</p>
        </span>
        <hr>
        <span class="menu_more_opcion opcion_visita">
        <i class="fas fa-plus-circle"></i>
        <p>Visita</p>
        </span>
        <hr>
    </div>

    <div class="global_warp">
    
        <div class="sub_global_wrap">
            
            <div class="tabs_wrap">

                <span class="tab_mis_listas tab activo">
                    <i class="fas fa-list-ul"></i>
                    <p>Tus Listas</p>
                </span>
                <?php
                    if ($nivel_acceso == 1 || $nivel_acceso == 11) {
                        echo"
                            <span class=\"tab_agente tab\"><i class=\"fas fa-user\"></i></span>
                        ";
                    };

                ?>
                
                <span class="agregar_btn fa-stack activo" title="Agregar">
                    <i class="fas fa-plus-circle"></i>
                </span>

            </div>

            <div class="contenedor">

                <div class="barra_selects_wrap">

                    <?php
                    
                        if ($nivel_acceso == 1 || $nivel_acceso == 11) {
                            echo"
                            <span class=\"selects_wrap\">
                                <label for=\"pais_select_agente\">País:</label>
                                <select name=\"pais_select_agente\" id=\"pais_select_agente\">
                                <option value=\"\"></option>
                            ";

                            foreach ($paises as $pais) {
                                    if ($pais == $_COOKIE['tutechopais']) {
                                        echo"
                                        <option value=\"" . $pais . "\" selected>" . ucfirst($pais) . "</option>
                                        ";
                                    }else {
                                        echo"
                                        <option value=\"" . $pais . "\">" . ucfirst($pais) . "</option>
                                        ";
                                    };
                            };

                            echo"
                                </select>
                            </span>
                            ";

                        };

                    ?>

                    <span class="selects_wrap">

                    <label for="agente_id">Agente ID:</label>
                    <input type="text" id="agente_id" name="agente_id">

                    </span>

                    <span class="buscar_agente_btn">Buscar</span>

                </div>

                <div class="resultados_wrap">
                    

                </div>

            </div>

        </div>

    </div>


    </div>

 </body>
</html>