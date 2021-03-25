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
      <title>Consola - Inventario</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/inventario.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>
      <link rel="stylesheet" href="../../css/jquery-ui.css">

      <script>
        <?php
        echo" const agente_id = '" . $agente['id'] . "';";
        if ($agente["agencia_id"] == "0" || $agente["agencia_id"] == "1") {
          echo "const agencia_first = '" . $agencias_list[0]['id'] . "';";
          echo "const first_mode = 'edit';";
        } else {
          if ($nivel_acceso == 3){
            echo "const agencia_first = '" . $agente['agencia_id'] . "';";
            echo "const first_mode = 'edit';";
          } elseif ($nivel_acceso == 4){
            echo "const agencia_first = '" . $agente['agencia_id'] . "';";
            echo "const first_mode = 'read';";
          };
        };
        ?>
      </script>
      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/inventario.js"></script>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>

 </head>
 <body>

 <div class="overlay_popup">
        <div class="popup">
          <span class="popup_cerrar"><i class="fa fa-times"></i></span>
          <div class="popup_content">
          </div>
        </div>
        
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
  <a href="consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <span class="retornar_btn">Retornar Item</span>

  <div class="select_container">
    <label for="agencia">Agencia</label>
    <select id="agencia" class="select_menu" name="agencia">
      <?php
        if ($agente["agencia_id"] == "0" || $agente["agencia_id"] == "1") {
          echo "<option value=\"" . $agencias_list[0]['id'] . "\" acceso=\"1\" selected>" . $agencias_list[0]['location_tag'] . "</option>";
          foreach (array_slice($agencias_list,1) as $agencia_alternative) {
            echo "<option value=\"" . $agencia_alternative['id'] . "\" acceso=\"1\">" . $agencia_alternative['location_tag'] . "</option>";
          };
        }else {
          echo "<option value=\"" . $agente["agencia_id"] . "\" acceso=\"1\" selected>" . $agencia['location_tag'] . "</option>";
          foreach ($agencias_list as $agencia_alternative) {
            if ($agencia_alternative['id'] !== $agente["agencia_id"]) {
              echo "<option value=\"" . $agencia_alternative['id'] . "\" acceso=\"0\">" . $agencia_alternative['location_tag'] . "</option>";
            };
          };
        };

      ?>
    </select>
  </div>


  <div class="contenedor_consola">

      <span class="tab_choice active">INVENTARIO</span>
      <span class="tab_choice">ITEMS EN USO</span>

      <div class="resultados_inventario visible">
        <ul class="item_list">
          
        </ul>

        <?php if (in_array($nivel_acceso, $array_acceso) !== false): ?>
          <span class="agregar_nuevo_item_btn_wrap">
            <span class="agregar_nuevo_item_btn"><i class="fas fa-plus"></i><p>NUEVO ITEM</p></span>
          </span>
        <?php endif; ?>

      </div>

      <div class="resultados_items_usados">
        <ul class="item_usados_list">

        </ul>
      </div>

    </div>


</main>
    </div>

 </body>
</html>
