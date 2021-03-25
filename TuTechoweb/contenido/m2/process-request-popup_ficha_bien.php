<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


    $tutechodb_internacional = "tutechodb_internacional";

    try {
      $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $consulta_pais_moneda =	$conexion_internacional->prepare("SELECT moneda, moneda_code, impuesto_transferencia_factor FROM paises WHERE pais=:pais ");
    $consulta_pais_moneda->execute(['pais' => $_COOKIE['tutechopais']]);//SE PASA LA REFERENCIA
    $pais_moneda = $consulta_pais_moneda->fetch(PDO::FETCH_ASSOC);

// REQUEST ALL INFO OF THE THUMBNAIL'S REFERENCE CLICKED AND CREATE THE CORRESPONDING POPUP_FICHA_BIEN TO BE SHOWN

    if (isset($_POST["ficha_bien_requested"]) && isset($_POST["ficha_bien_tipo_requested"]) && isset($_POST["estado"])) {
      $ficha_bien_requested = $_POST["ficha_bien_requested"];
      $tipo_bien_requested = $_POST["ficha_bien_tipo_requested"];
      $estado = $_POST["estado"];

      $consulta_info_ficha_bien =	$conexion->prepare("SELECT * FROM $tipo_bien_requested WHERE referencia=:referencia ");
      $consulta_info_ficha_bien->execute(['referencia' => $ficha_bien_requested]);//SE PASA LA REFERENCIA
      $info_ficha_bien = $consulta_info_ficha_bien->fetch(PDO::FETCH_ASSOC);

      $viewer_mode = 'view_360';

      $fotos_360_exist = false;
      $fotos_360_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $ficha_bien_requested . '\\' . 'fotos_360';
      if (is_dir($fotos_360_path)) {
        if (glob($fotos_360_path . "/*")) {
          $fotos_360_exist = true;
        };
      };

      $json_fotos_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $ficha_bien_requested . '\\' . 'fotos.json';
      $fotos_json = [];
      if (file_exists($json_fotos_path)) {
        $fotos_json = json_decode(file_get_contents($json_fotos_path), true);
      };

      $json_vr_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $ficha_bien_requested . '\\' . 'VR.json';
      $tour_vr_json = [];
      if (file_exists($json_vr_path) && $info_ficha_bien['tourvr_visibilidad'] == 1) {
        $tour_vr_json = json_decode(file_get_contents($json_vr_path), true);
        $viewer_mode = 'tour_VR';
      };

      $barrio_to_check = '';
      $barrio_table = '';
      $barrio_column = '';
      if ($info_ficha_bien['barrio'] !== '') {
        $barrio_to_check = $info_ficha_bien['barrio'];
        $barrio_table = 'barrios';
        $barrio_column = 'barrio';
      }else {
        $barrio_to_check = $info_ficha_bien['ciudad'];
        $barrio_table = 'ciudades';
        $barrio_column = 'ciudad';
      };

      $consulta_barrio_sponsors_check =	$conexion->prepare("SELECT activacion_sponsors FROM $barrio_table WHERE $barrio_column=:barrio");
      $consulta_barrio_sponsors_check->execute(['barrio' => $barrio_to_check]);//SE PASA EL BARRIO
      $barrio_sponsors_check = $consulta_barrio_sponsors_check->fetch(PDO::FETCH_ASSOC);

      $consulta_info_ficha_bien_sponsors_restaurantes =	$conexion->prepare("SELECT nombre,label,logo FROM sponsors WHERE barrio=:barrio AND categoria=1 AND visibilidad='visible' ORDER BY RAND()");
      $consulta_info_ficha_bien_sponsors_restaurantes->execute(['barrio' => $info_ficha_bien['barrio']]);//SE PASA EL BARRIO
      $sponsors_info_restaurantes = $consulta_info_ficha_bien_sponsors_restaurantes->fetchAll();

      $consulta_info_ficha_bien_sponsors_bares =	$conexion->prepare("SELECT nombre,label,logo FROM sponsors WHERE barrio=:barrio AND categoria=2 AND visibilidad='visible' ORDER BY RAND()");
      $consulta_info_ficha_bien_sponsors_bares->execute(['barrio' => $info_ficha_bien['barrio']]);//SE PASA EL BARRIO
      $sponsors_info_bares = $consulta_info_ficha_bien_sponsors_bares->fetchAll();

      $consulta_info_ficha_bien_sponsors_bienestar =	$conexion->prepare("SELECT nombre,label,logo FROM sponsors WHERE barrio=:barrio AND categoria=3 AND visibilidad='visible' ORDER BY RAND()");
      $consulta_info_ficha_bien_sponsors_bienestar->execute(['barrio' => $info_ficha_bien['barrio']]);//SE PASA EL BARRIO
      $sponsors_info_bienestar = $consulta_info_ficha_bien_sponsors_bienestar->fetchAll();

      $consulta_info_ficha_bien_sponsors_salud =	$conexion->prepare("SELECT nombre,label,logo FROM sponsors WHERE barrio=:barrio AND categoria=4 AND visibilidad='visible' ORDER BY RAND()");
      $consulta_info_ficha_bien_sponsors_salud->execute(['barrio' => $info_ficha_bien['barrio']]);//SE PASA EL BARRIO
      $sponsors_info_salud = $consulta_info_ficha_bien_sponsors_salud->fetchAll();
      // CONSTRUCCION DEL POPUP DE LA FICHA BIEN SELECTED

      echo "<div class=\"ficha_nav\">
            <div class=\"ficha_nav_info\">
              <div class=\"ficha_nav_info_titles\">
                <div class=\"title_tag_container\">
                  <div class=\"title_tag_group\">";
                  if ($tipo_bien_requested == 'casa') {
                    echo "<span class=\"fa fa-home\"></span>";
                  };
                  if ($tipo_bien_requested == 'departamento') {
                    echo "<span class=\"fa fa-building\"></span>";
                  };
                  if ($tipo_bien_requested == 'local') {
                    echo "<span class=\"fa fa-shopping-bag\"></span>";
                  };
                  if ($tipo_bien_requested == 'terreno') {
                    echo "<span class=\"fa fa-tree\"></span>";
                  };
                      if ($info_ficha_bien['estado'] == 'En Venta') {
                        if ($info_ficha_bien['pre_venta'] == 1) {
                          echo"<span class=\"tipo_bien_tag\">" . ucfirst($info_ficha_bien['tipo_bien']) . "</span><span class=\"estado_tag\">Pre-Venta</span>
                          </div>";
                        }else {
                          echo"<span class=\"tipo_bien_tag\">" . ucfirst($info_ficha_bien['tipo_bien']) . "</span><span class=\"estado_tag\">Venta</span>
                          </div>";
                        };
                      };
                      if ($info_ficha_bien['estado'] == 'En Alquiler') {
                        if ($info_ficha_bien['anticretico'] == 1) {
                          echo"<span class=\"tipo_bien_tag\">" . ucfirst($info_ficha_bien['tipo_bien']) . "</span><span class=\"estado_tag\">Anticretico</span>
                          </div>";
                        }else {
                          echo"<span class=\"tipo_bien_tag\">" . ucfirst($info_ficha_bien['tipo_bien']) . "</span><span class=\"estado_tag\">Alquiler</span>
                          </div>";
                        };
                      };
                  if ($info_ficha_bien['barrio'] !== '') {
                    echo "<span class=\"ficha_nav_info_direccion\">" . ucfirst($info_ficha_bien['barrio']) . " - " . ucfirst($info_ficha_bien['ciudad']) . "</span>";
                  }else {
                    echo "<span class=\"ficha_nav_info_direccion\">" . ucfirst($info_ficha_bien['ciudad']) . "</span>";
                  };
                echo"</div>
                <div class=\"actions_type1 actions_widescreendisplay\">
                      <div class=\"actions_type1_icon icon_contactar\">
                      <span class=\"fa fa-comments\"></span><span class=\"icon_text\">Contactar</span>
                      </div>

                      <div class=\"actions_type1_icon icon_favoritos\">
                      <span class=\"fa fa-star\"></span><span class=\"icon_text\">+ Favoritos</span>
                      </div>

                      <div class=\"actions_type1_icon icon_compartir\">
                      <span class=\"fa fa-share-alt\"></span><span class=\"icon_text\">Compartir</span>
                      </div>
                </div>
                <span class=\"ficha_nav_info_referencia\">Ref: " . $info_ficha_bien['referencia'] . "</span>
              </div>

            </div>
            <div class=\"actions_mobilescreendisplay\">";
            if ($info_ficha_bien['barrio'] !== '') {
              echo "<span class=\"ficha_nav_info_direccion\">" . ucfirst($info_ficha_bien['barrio']) . " - " . ucfirst($info_ficha_bien['ciudad']) . "</span>";
            }else {
              echo "<span class=\"ficha_nav_info_direccion\">" . ucfirst($info_ficha_bien['ciudad']) . "</span>";
            };
              echo"<span class=\"ficha_nav_info_referencia\">Ref: " . $info_ficha_bien['referencia'] . "</span>
            </div>
            <div class=\"actions_type2_container\">
              <div class=\"actions_type2\">
                  <a href=\"ficha_bien.php" . $info_ficha_bien['referencia'] . "\" class=\"fa fa-expand actions_type2_icon\" title=\"Abrir en nueva ventana\" name=\"" . $info_ficha_bien['referencia'] . "\"></a>
                  <span class=\"fa fa-times actions_type2_icon\" title=\"Cerrar\"></span>
              </div>
            </div>

          </div>


          <div class=\"ficha_contenido\">

          <div id=\"bien_individual_slider\" class=\"flexslider bien_individual_slider\" style=\"margin: 3em auto 0em auto\">
            <ul class=\"slides bien_individual_slides\">";

            foreach ($fotos_json as $titulo => $foto) {
              echo "
              <li data-thumb=\"../../bienes_inmuebles/" . $_COOKIE['tutechopais'] . "/" . urlencode($info_ficha_bien['referencia']) . "/fotos" . "/" . $foto . "\">
                <img src=\"../../bienes_inmuebles/" . $_COOKIE['tutechopais'] . "/" . urlencode($info_ficha_bien['referencia']) . "/fotos" . "/" . $foto . "?=" . Date('U') . "\" alt=\"" . $titulo . "\">
                 <section class=\"caption\">
                    <h2>" . str_replace("~", "", ucfirst($titulo)) . "</h2>
                 </section>
              </li>
              ";
            };

          echo "</ul>
          </div>";
          if ($fotos_360_exist) {
            if (file_exists($json_vr_path) && $info_ficha_bien['tourvr_visibilidad'] == 1) {
              echo "<div class=\"ficha_contenido_360_actions\">
                <div class=\"visita_virtual_btn open_viewer_btn\">
                <span class=\"overlay_open_viewer_bnt\">
                  <img src=\"../../objetos/vr_icon_para_banner.svg\" alt=\"VR\">
                  <span class=\"titulo_banner_360\">VISITA TU NUEVO HOGAR<br/> EN REALIDAD VIRTUAL</span>
                  <span class=\"btn_entrar\">¡QUIERO VER!</span>
                </span>
                </div>
              </div>";
            }else { //el contenido de algunos span se llenan desde el css
              echo "<div class=\"ficha_contenido_360_actions\">
                <div class=\"fotos_360_bnt open_viewer_btn\">
                <span class=\"overlay_open_viewer_bnt\">
                  <img src=\"../../objetos/360_icon_para_banner.svg\" alt=\"360\">
                  <span class=\"titulo_banner_360\"></span>
                  <span class=\"btn_entrar\">¡QUIERO VER!</span>
                </span>
                </div>
              </div>";
            };

          };
            echo"<div class=\"banner_experiencia_vr\">
            <span class=\"overlay_banner_hover\">
              <span class=\"banner_vr_tag\">VIVE LA EXPERIENCIA VR</span>
            </span>
            </div>";
            if ($tipo_bien_requested == 'terreno') {
              echo "<div class=\"ficha_contenido_resumen terreno\">";
            }else {
              echo "<div class=\"ficha_contenido_resumen\">";
            };
              echo "<div class=\"resumen_parte1\">
                <span class=\"resumen_precio_tag\">" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . "&nbsp" . $pais_moneda['moneda_code'] . "</span>
                <span class=\"resumen_pre_venta_tag\" style=\"" . ($info_ficha_bien['pre_venta'] == 1 ? 'display: block' : 'display: none') . "\">Pre-Venta</span>
                <span class=\"resumen_pre_venta_tag\" style=\"" . ($info_ficha_bien['anticretico'] == 1 ? 'display: block' : 'display: none') . "\">Anticretico</span>
                <span class=\"resumen_exclusivo_tag\" style=\"" . ($info_ficha_bien['exclusivo'] == 1 ? 'display: block' : 'display: none') . "\">Exclusivo</span>";
                if ($tipo_bien_requested == 'terreno') {
                  echo "<span class=\"resumen_superficie_tag_terreno\">" . ($info_ficha_bien['superficie_terreno_medida'] == 'mÂ²' ? number_format(ceil($info_ficha_bien['superficie_terreno']), 0, '.', ' ') : number_format(($info_ficha_bien['superficie_terreno']/10000), 1, '.', ' ')) . " " . ($info_ficha_bien['superficie_terreno_medida'] == 'mÂ²' ? ' m<sup>2</sup>' : ' Hect') . "</span>";
                };
              echo"</div>
              <div class=\"resumen_parte2\">";
              if ($tipo_bien_requested !== 'terreno') {
                echo "<span class=\"resumen_superficie_tag\">" . number_format(ceil($info_ficha_bien['superficie_inmueble']), 0, '.', ' ') . " m<sup>2</sup></span>";
              };
                if ($tipo_bien_requested == 'casa' || $tipo_bien_requested == 'departamento') {
                  echo "<span class=\"resumen_dormitorios_tag\"><img src=\"../../objetos/bed_icon.svg\" alt=\"Dormitorios: \">x" . $info_ficha_bien['dormitorios'] . "</span>";
                };
                if ($tipo_bien_requested == 'casa' || $tipo_bien_requested == 'departamento' || $tipo_bien_requested == 'local') {
                  echo "<span class=\"resumen_parqueos_tag\"><img src=\"../../objetos/car_icon.svg\" alt=\"Parqueos: \">x" . $info_ficha_bien['parqueos'] . "</span>";
                };

              echo"</div>
            </div>
            <div class=\"ficha_contenido_descripcion\">
              <h3>Descripción:</h3>
              <p>" . $info_ficha_bien['descripcion_bien'] . "</p>
            </div>";


        if ($tipo_bien_requested == 'casa') {

                  echo"<div class=\"ficha_contenido_caracteristicas\">
                  <h3>Características:</h3>

                  <div class=\"caracteristicas_columns\">
                    <div class=\"caracteristicas_column1\">
                    <h4><p>Espacios y medidas</p><span></span></h4>
                      <div class=\"caracteristicas_row\"><label>Superfície del inmueble</label><span>" . number_format(ceil($info_ficha_bien['superficie_inmueble']), 0, '.', ' ') . " m<sup>2</sup></span></div>
                      <div class=\"caracteristicas_row\"><label>Superfície del terreno</label><span>" . number_format(ceil($info_ficha_bien['superficie_terreno']), 0, '.', ' ') . ($info_ficha_bien['superficie_terreno_medida'] == 'mÂ²' ? ' m<sup>2</sup>' : ' Hect') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Superfície del jardín/patio</label><span>". number_format(ceil($info_ficha_bien['jardin_superficie']), 0, '.', ' ') . ($info_ficha_bien['jardin_superficie_medida'] == 'mÂ²' ? ' m<sup>2</sup>' : ' Hect') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Dormitorios</label><span>" . $info_ficha_bien['dormitorios'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Parqueos</label><span>" . $info_ficha_bien['parqueos'] . "</span></div>
                    <h4><p>Interiores</p><span></span></h4>
                      <div class=\"caracteristicas_row\"><label>Número de pisos</label><span>" . $info_ficha_bien['pisos'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Habitación en 1er Piso</label><span>" . ($info_ficha_bien['hab_planta_baja'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Amoblado</label><span>" . ($info_ficha_bien['amoblado'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Sótano</label><span>" . ($info_ficha_bien['sotano'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Alcoba</label><span>" . ($info_ficha_bien['alcoba'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Lavandería</label><span>" . ($info_ficha_bien['lavanderia'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Balcón</label><span>" . ($info_ficha_bien['balcon'] != 1 ? 'No' : 'Si') . "</span></div>";
                      if ($info_ficha_bien['baulera'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Baulera</label><span>Si</span></div>";
                      };
                      echo"<h5>Cocina</h5>
                      <div class=\"caracteristicas_row\"><label>Tipo de cocina</label><span>" . $info_ficha_bien['cocina'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Equipada</label><span>" . ($info_ficha_bien['equipada'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Alacena/Despensa</label><span>" . ($info_ficha_bien['alacena'] != 1 ? 'No' : 'Si') . "</span></div>
                      <h5>Baños</h5>
                      <div class=\"caracteristicas_row\"><label>Número de baños</label><span>" . $info_ficha_bien['wc'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Tipo de ducha</label><span>" . $info_ficha_bien['tipo_ducha'] . "</span></div>";
                      if ($info_ficha_bien['wc_separado'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Ducha/WC separados</label><span>Si</span></div>";
                      };
                      echo"<h5>Resumen de interiores</h5>";
                      if ($info_ficha_bien['ruido_interno'] <= 25) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Silencioso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                      };
                      if ($info_ficha_bien['ruido_interno'] > 25 && $info_ficha_bien['ruido_interno'] <= 55) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Normal(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                      };
                      if ($info_ficha_bien['ruido_interno'] > 55 && $info_ficha_bien['ruido_interno'] <= 90) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Ruidoso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                      };
                      if ($info_ficha_bien['ruido_interno'] > 90) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Muy Ruidoso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                      };

                      echo"<div class=\"caracteristicas_row\"><label>Estado general</label><span>" . $info_ficha_bien['interior_estado'] . "</span></div>
                    <h4><p>Exteriores</p><span></span></h4>";
                    if ($info_ficha_bien['cesped'] == 1) {
                      echo"<div class=\"caracteristicas_row\"><label>Cesped</label><span>Si</span></div>";
                    };
                    if ($info_ficha_bien['jardin_terraza'] == 1) {
                      echo"<div class=\"caracteristicas_row\"><label>Terraza en jardín</label><span>Si</span></div>";
                    };
                    if ($info_ficha_bien['parqueos'] > 0) {
                      echo"
                      <div class=\"caracteristicas_row\"><label>Parqueo techado</label><span>" . ($info_ficha_bien['parqueo_techado'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Tipo de portón</label><span>" . $info_ficha_bien['porton'] . "</span></div>";
                      if ($info_ficha_bien['parqueo_recarga'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Estación de carga</label><span>Si</span></div>";
                      };
                    };
                      echo"<h5>Entorno</h5>
                      <div class=\"caracteristicas_row\"><label>Vista</label><span>" . $info_ficha_bien['vista'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Exposición al Sol</label><span>" . $info_ficha_bien['exposicion'] . "</span></div>";
                      if ($info_ficha_bien['jardin_estado'] !== 'Sin exteriores') {
                        echo "<h5>Resumen de exteriores</h5>";
                      };
                      if ($info_ficha_bien['ruido_externo'] <= 25) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Silencioso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                      };
                      if ($info_ficha_bien['ruido_externo'] > 25 && $info_ficha_bien['ruido_externo'] <= 55) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Normal(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                      };
                      if ($info_ficha_bien['ruido_externo'] > 55 && $info_ficha_bien['ruido_externo'] <= 90) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                      };
                      if ($info_ficha_bien['ruido_externo'] > 90) {
                        echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Muy Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                      };
                      if ($info_ficha_bien['jardin_estado'] !== "Sin exteriores") {
                        echo"<div class=\"caracteristicas_row\"><label>Estado general</label><span>" . $info_ficha_bien['jardin_estado'] . "</span></div>";
                      };
                      echo"</div>

                    <div class=\"caracteristicas_column2\">";
                    if ($info_ficha_bien['picina'] == 1 || $info_ficha_bien['sauna'] == 1 || $info_ficha_bien['jacuzzi'] == 1 || $info_ficha_bien['parrillero'] == 1 || $info_ficha_bien['gimnasio'] == 1) {
                      echo "<h4><p>Hobby & Relajación</p><span></span></h4>";
                      if ($info_ficha_bien['picina'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Piscina</label><span>Si</span></div>";
                      };
                      if ($info_ficha_bien['sauna'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Sauna</label><span>Si</span></div>";
                      };
                      if ($info_ficha_bien['jacuzzi'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Jacuzzi</label><span>Si</span></div>";
                      };
                      if ($info_ficha_bien['parrillero'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Parrillero</label><span>Si</span></div>";
                      };
                      if ($info_ficha_bien['gimnasio'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Gimnasio</label><span>Si</span></div>";
                      };
                    };
                    echo"<h4><p>Otros</p><span></span></h4>
                      <div class=\"caracteristicas_row\"><label>Handicap <i style=\"color:rgb(60, 187, 241); font-weight: bold; font-size: 1.2em;\" class=\"fa fa-wheelchair\"></i></label><span>" . ($info_ficha_bien['handicap'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Tipo de Vía</label><span>" . $info_ficha_bien['tipo_via'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Aceras</label><span>" . $info_ficha_bien['aceras'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Residencia Privada</label><span>" . ($info_ficha_bien['residencia'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Intercomunicador</label><span>" . ($info_ficha_bien['intercomunicador'] != 1 ? 'No' : 'Si') . "</span></div>";
                      if ($info_ficha_bien['acensor'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Ascensor/Elevador</label><span>Si</span></div>";
                      };
                      echo"<div class=\"caracteristicas_row\"><label>Gaz domiciliario</label><span>" . ($info_ficha_bien['gaz_domiciliario'] != 1 ? 'No' : 'Si') . "</span></div>";
                      if ($info_ficha_bien['aire_acondicionado'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Aire Acondicionado</label><span>Si</span></div>";
                      };
                      if ($info_ficha_bien['chimenea'] == 1) {
                        echo"<div class=\"caracteristicas_row\"><label>Chimenea</label><span>Si</span></div>";
                      };
                      echo"<div class=\"caracteristicas_row\"><label>Tipo de ventanas</label><span>" . $info_ficha_bien['ventanas'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Calefacción</label><span>" . $info_ficha_bien['calefaccion'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Tensión eléctrica</label><span>" . $info_ficha_bien['conexion_electrica'] . "</span></div>";
                      if ($info_ficha_bien['cobertura'] == 'Inexistente') {
                        echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(212, 212, 212); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                      };
                      if ($info_ficha_bien['cobertura'] == 'Baja') {
                        echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(224, 36, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                      };
                      if ($info_ficha_bien['cobertura'] == 'Media') {
                        echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(249, 239, 1); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                      };
                      if ($info_ficha_bien['cobertura'] == 'Alta') {
                        echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(130, 224, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                      };
                      echo"<div class=\"caracteristicas_row\"><label>Internet</label><span>" . $info_ficha_bien['internet'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>TV cable</label><span>" . $info_ficha_bien['tv_cable'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Alcantarillado</label><span>" . ($info_ficha_bien['alcantarillado'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Desagüe</label><span>" . ($info_ficha_bien['desague'] != 1 ? 'No' : 'Si') . "</span></div>
                      <div class=\"caracteristicas_row\"><label>Reserva de agua</label><span>" . $info_ficha_bien['reserva_agua'] . " " . $info_ficha_bien['reserva_agua_medida'] . "</span></div>";
                      if ($info_ficha_bien['reserva_compartida'] == 1) {
                        echo "<div class=\"caracteristicas_row\"><label>Reserva compartida</label><span>Si</span></div>";
                      };
                      echo"<div class=\"caracteristicas_row\"><label>Animales domésticos <i style=\"color:rgb(55, 55, 55); font-size: 1.2em;\" class=\"fa fa-paw\"></i></label><span>" . ($info_ficha_bien['animales_domesticos'] != 1 ? 'Prohibidos' : 'Permitidos') . "</span></div>
                    <h4><p>Aspectos financieros</p><span></span></h4>";
                    if ($estado == 'En Venta') {
                      echo "<div class=\"caracteristicas_row\"><label>Precio de venta</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                    };
                    if ($estado == 'En Alquiler') {
                      echo "<div class=\"caracteristicas_row\"><label>Alquiler</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . " mensual</span></div>";
                    };
                      echo"<div class=\"caracteristicas_row\"><label>Mantenimiento (mensual)</label><span>" . number_format($info_ficha_bien['mantenimiento'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                    if ($estado == 'En Venta') {
                      echo "<div class=\"caracteristicas_row\"><label>Impuestos (anuales)</label><span>" . number_format($info_ficha_bien['impuestos'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>
                      <div class=\"caracteristicas_row\"><label>IT (estimado)</label><span>" . number_format(($info_ficha_bien['base_imponible']*$pais_moneda['impuesto_transferencia_factor']), 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                    };
                    echo"</div>
                  </div>

                  </div>";

        };
        if ($tipo_bien_requested == 'departamento') {

              echo"<div class=\"ficha_contenido_caracteristicas\">
              <h3>Características:</h3>

              <div class=\"caracteristicas_columns\">
                <div class=\"caracteristicas_column1\">
                <h4><p>Espacios y medidas</p><span></span></h4>
                  <div class=\"caracteristicas_row\"><label>Tipo de inmueble</label><span>" . $info_ficha_bien['tipo_departamento'] . "</span></div>";
                  if (($info_ficha_bien['planta_baja'] == '0' && $info_ficha_bien['penthouse'] == '0') || ($info_ficha_bien['planta_baja'] == 1 && $info_ficha_bien['penthouse'] == 1)) {
                    echo "<div class=\"caracteristicas_row\"><label># Piso/Planta</label><span>" . $info_ficha_bien['piso'] . "</span></div>";
                  };
                  if ($info_ficha_bien['planta_baja'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Planta Baja</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['penthouse'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Penthouse</label><span>Si</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Superfície del inmueble</label><span>" . number_format(ceil($info_ficha_bien['superficie_inmueble']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  if ($info_ficha_bien['patio'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Superfície de patio</label><span>" . number_format(ceil($info_ficha_bien['patio_superficie']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  };
                  if ($info_ficha_bien['terraza'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Superfície de terraza</label><span>" . number_format(ceil($info_ficha_bien['terraza_superficie']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  };
                  if ($info_ficha_bien['balcon'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Superfície de balcón</label><span>" . number_format(ceil($info_ficha_bien['balcon_superficie']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Dormitorios</label><span>" . $info_ficha_bien['dormitorios'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Parqueos</label><span>" . $info_ficha_bien['parqueos'] . "</span></div>
                <h4><p>Interiores</p><span></span></h4>
                  <div class=\"caracteristicas_row\"><label>Amoblado</label><span>" . ($info_ficha_bien['amoblado'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Lavandería</label><span>" . ($info_ficha_bien['lavanderia'] != 1 ? 'No' : 'Si') . "</span></div>";
                  if ($info_ficha_bien['baulera'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Baulera</label><span>Si</span></div>";
                  };
                  echo"<h5>Cocina</h5>
                  <div class=\"caracteristicas_row\"><label>Tipo de cocina</label><span>" . $info_ficha_bien['cocina'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Equipada</label><span>" . ($info_ficha_bien['equipada'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Alacena/Despensa</label><span>" . ($info_ficha_bien['alacena'] != 1 ? 'No' : 'Si') . "</span></div>
                  <h5>Baños</h5>
                  <div class=\"caracteristicas_row\"><label>Número de baños</label><span>" . $info_ficha_bien['wc'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tipo de ducha</label><span>" . $info_ficha_bien['tipo_ducha'] . "</span></div>";
                  if ($info_ficha_bien['wc_separado'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Ducha/WC separados</label><span>Si</span></div>";
                  };
                  echo"<h5>Resumen de interiores</h5>";
                  if ($info_ficha_bien['ruido_interno'] <= 25) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Silencioso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_interno'] > 25 && $info_ficha_bien['ruido_interno'] <= 55) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Normal(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_interno'] > 55 && $info_ficha_bien['ruido_interno'] <= 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Ruidoso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_interno'] > 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Muy Ruidoso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };

                  echo"<div class=\"caracteristicas_row\"><label>Estado general</label><span>" . $info_ficha_bien['interior_estado'] . "</span></div>
                <h4><p>Exteriores</p><span></span></h4>";
                if ($info_ficha_bien['cesped'] == 1) {
                  echo"<div class=\"caracteristicas_row\"><label>Cesped</label><span>Si</span></div>";
                };
                if ($info_ficha_bien['parqueos'] > 0) {
                  echo"
                  <div class=\"caracteristicas_row\"><label>Parqueo techado</label><span>" . ($info_ficha_bien['parqueo_techado'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tipo de portón</label><span>" . $info_ficha_bien['porton'] . "</span></div>";
                  if ($info_ficha_bien['parqueo_recarga'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Estación de carga</label><span>Si</span></div>";
                  };
                };
                  echo"<h5>Entorno</h5>
                  <div class=\"caracteristicas_row\"><label>Vista</label><span>" . $info_ficha_bien['vista'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Exposición al Sol</label><span>" . $info_ficha_bien['exposicion'] . "</span></div>";
                  if ($info_ficha_bien['jardin_estado'] !== 'Sin exteriores') {
                    echo "<h5>Resumen de exteriores</h5>";
                  };
                  if ($info_ficha_bien['ruido_externo'] <= 25) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Silencioso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 25 && $info_ficha_bien['ruido_externo'] <= 55) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Normal(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 55 && $info_ficha_bien['ruido_externo'] <= 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Muy Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['jardin_estado'] !== "Sin exteriores") {
                    echo"<div class=\"caracteristicas_row\"><label>Estado general</label><span>" . $info_ficha_bien['jardin_estado'] . "</span></div>";
                  };
                  echo"</div>

                <div class=\"caracteristicas_column2\">";
                if ($info_ficha_bien['picina'] == 1 || $info_ficha_bien['sauna'] == 1 || $info_ficha_bien['jacuzzi'] == 1 || $info_ficha_bien['parrillero'] == 1 || $info_ficha_bien['gimnasio'] == 1) {
                  echo "<h4><p>Hobby & Relajación</p><span></span></h4>";
                  if ($info_ficha_bien['picina'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Piscina</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['sauna'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Sauna</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['jacuzzi'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Jacuzzi</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['parrillero'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Parrillero</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['gimnasio'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Gimnasio</label><span>Si</span></div>";
                  };
                };

                echo"<h4><p>Datos del Edificio</p><span></span></h4>
                <div class=\"caracteristicas_row\"><label>Año de construcción</label><span>" . $info_ficha_bien['fecha_construccion'] . "</span></div>
                <div class=\"caracteristicas_row\"><label>Ascensor/Elevador</label><span>" . ($info_ficha_bien['acensor'] != 1 ? 'No' : 'Si') . "</span></div>
                <div class=\"caracteristicas_row\"><label>Portero</label><span>" . ($info_ficha_bien['portero'] != 1 ? 'No' : 'Si') . "</span></div>
                <div class=\"caracteristicas_row\"><label>Cámaras/Seguridad</label><span>" . ($info_ficha_bien['camaras'] != 1 ? 'No' : 'Si') . "</span></div>";
                if ($info_ficha_bien['extintores'] == 1) {
                  echo "<div class=\"caracteristicas_row\"><label>Extintores</label><span>Si</span></div>";
                };
                echo"<div class=\"caracteristicas_row\"><label>Desagüe</label><span>" . ($info_ficha_bien['desague'] != 1 ? 'No' : 'Si') . "</span></div>
                <div class=\"caracteristicas_row\"><label>Animales domésticos <i style=\"color:rgb(55, 55, 55); font-size: 1.2em;\" class=\"fa fa-paw\"></i></label><span>" . ($info_ficha_bien['animales_domesticos'] != 1 ? 'Prohibidos' : 'Permitidos') . "</span></div>

                <h4><p>Otros</p><span></span></h4>
                  <div class=\"caracteristicas_row\"><label>Handicap <i style=\"color:rgb(60, 187, 241); font-weight: bold; font-size: 1.2em;\" class=\"fa fa-wheelchair\"></i></label><span>" . ($info_ficha_bien['handicap'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tipo de Vía</label><span>" . $info_ficha_bien['tipo_via'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Aceras</label><span>" . $info_ficha_bien['aceras'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Residencia Privada</label><span>" . ($info_ficha_bien['residencia'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Intercomunicador</label><span>" . ($info_ficha_bien['intercomunicador'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Gaz domiciliario</label><span>" . ($info_ficha_bien['gaz_domiciliario'] != 1 ? 'No' : 'Si') . "</span></div>";
                  if ($info_ficha_bien['aire_acondicionado'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Aire Acondicionado</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['chimenea'] == 1) {
                    echo"<div class=\"caracteristicas_row\"><label>Chimenea</label><span>Si</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Tipo de ventanas</label><span>" . $info_ficha_bien['ventanas'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Calefacción</label><span>" . $info_ficha_bien['calefaccion'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tensión eléctrica</label><span>" . $info_ficha_bien['conexion_electrica'] . "</span></div>";
                  if ($info_ficha_bien['cobertura'] == 'Inexistente') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(212, 212, 212); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Baja') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(224, 36, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Media') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(249, 239, 1); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Alta') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(130, 224, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Internet</label><span>" . $info_ficha_bien['internet'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>TV cable</label><span>" . $info_ficha_bien['tv_cable'] . "</span></div>
                  <h4><p>Aspectos financieros</p><span></span></h4>";
                  if ($estado == 'En Venta') {
                    echo "<div class=\"caracteristicas_row\"><label>Precio de venta</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  };
                  if ($estado == 'En Alquiler') {
                    echo "<div class=\"caracteristicas_row\"><label>Alquiler</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . " mensual</span></div>";
                  };
                    echo"<div class=\"caracteristicas_row\"><label>Mantenimiento (mensual)</label><span>" . number_format($info_ficha_bien['mantenimiento'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  if ($estado == 'En Venta') {
                    echo "<div class=\"caracteristicas_row\"><label>Impuestos (anuales)</label><span>" . number_format($info_ficha_bien['impuestos'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>
                    <div class=\"caracteristicas_row\"><label>IT (estimado)</label><span>" . number_format(($info_ficha_bien['base_imponible']*$pais_moneda['impuesto_transferencia_factor']), 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  };
                  echo"</div>
              </div>

              </div>";

        };
        if ($tipo_bien_requested == 'local') {

              echo"<div class=\"ficha_contenido_caracteristicas\">
              <h3>Características:</h3>

              <div class=\"caracteristicas_columns\">
                <div class=\"caracteristicas_column1\">
                <h4><p>Espacios y medidas</p><span></span></h4>
                <div class=\"caracteristicas_row\"><label>Espacios</label><span>" . $info_ficha_bien['espacios'] . "</span></div>";
                  if ($info_ficha_bien['edificio'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label># Piso/Planta</label><span>" . $info_ficha_bien['piso'] . "</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Superfície del inmueble</label><span>" . number_format(ceil($info_ficha_bien['superficie_inmueble']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  if ($info_ficha_bien['patio'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Superfície de patio</label><span>" . number_format(ceil($info_ficha_bien['patio_superficie']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  };
                  if ($info_ficha_bien['terraza'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Superfície de terraza</label><span>" . number_format(ceil($info_ficha_bien['terraza_superficie']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  };
                  if ($info_ficha_bien['balcon'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Superfície de balcón</label><span>" . number_format(ceil($info_ficha_bien['balcon_superficie']), 0, '.', ' ') . " m<sup>2</sup></span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Parqueos</label><span>" . $info_ficha_bien['parqueos'] . "</span></div>";
                  if ($info_ficha_bien['baulera'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Baulera</label><span>Si</span></div>";
                  };
                echo"<h4><p>Interiores</p><span></span></h4>
                <div class=\"caracteristicas_row\"><label>Niveles/Pisos</label><span>" . $info_ficha_bien['niveles'] . "</span></div>";
                  if ($info_ficha_bien['salida_emergencia'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Salida de Emergencia</label><span>Si</span></div>";
                  };
                  echo "<div class=\"caracteristicas_row\"><label>Adaptación</label><span>" . $info_ficha_bien['adaptacion'] . "</span></div>";
                  if ($info_ficha_bien['cocina'] == 1) {
                    echo "<h5>Cocina</h5>
                    <div class=\"caracteristicas_row\"><label>Equipada</label><span>" . ($info_ficha_bien['equipada'] != 1 ? 'No' : 'Si') . "</span></div>
                    <div class=\"caracteristicas_row\"><label>Alacena/Despensa</label><span>" . ($info_ficha_bien['alacena'] != 1 ? 'No' : 'Si') . "</span></div>";
                  };
                  echo"<h5>Baños</h5>
                  <div class=\"caracteristicas_row\"><label>Número de baños</label><span>" . $info_ficha_bien['wc'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tipo de ducha</label><span>" . $info_ficha_bien['tipo_ducha'] . "</span></div>";
                  if ($info_ficha_bien['tipo_ducha'] !== 'Inexistente' && $info_ficha_bien['wc_separado'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Ducha/WC separados</label><span>Si</span></div>";
                  };
                  echo"<h5>Resumen de interiores</h5>";
                  if ($info_ficha_bien['ruido_interno'] <= 25) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Silencioso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_interno'] > 25 && $info_ficha_bien['ruido_interno'] <= 55) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Normal(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_interno'] > 55 && $info_ficha_bien['ruido_interno'] <= 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Ruidoso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_interno'] > 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido interno</label><span>Muy Ruidoso(" . $info_ficha_bien['ruido_interno'] . " dB)</span></div>";
                  };

                  echo"<div class=\"caracteristicas_row\"><label>Estado general</label><span>" . $info_ficha_bien['interior_estado'] . "</span></div>
                <h4><p>Exteriores</p><span></span></h4>";
                if ($info_ficha_bien['cesped'] == 1) {
                  echo"<div class=\"caracteristicas_row\"><label>Cesped</label><span>Si</span></div>";
                };
                if ($info_ficha_bien['parqueos'] > 0) {
                  echo"
                  <div class=\"caracteristicas_row\"><label>Parqueo techado</label><span>" . ($info_ficha_bien['parqueo_techado'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tipo de portón</label><span>" . $info_ficha_bien['porton'] . "</span></div>";
                  if ($info_ficha_bien['parqueo_recarga'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Estación de carga</label><span>Si</span></div>";
                  };
                };
                  echo"<h5>Entorno</h5>
                  <div class=\"caracteristicas_row\"><label>Vista</label><span>" . $info_ficha_bien['vista'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Exposición al Sol</label><span>" . $info_ficha_bien['exposicion'] . "</span></div>";
                  if ($info_ficha_bien['jardin_estado'] !== 'Sin exteriores') {
                    echo "<h5>Resumen de exteriores</h5>";
                  };
                  if ($info_ficha_bien['ruido_externo'] <= 25) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Silencioso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 25 && $info_ficha_bien['ruido_externo'] <= 55) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Normal(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 55 && $info_ficha_bien['ruido_externo'] <= 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Muy Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['jardin_estado'] !== "Sin exteriores") {
                    echo"<div class=\"caracteristicas_row\"><label>Estado general</label><span>" . $info_ficha_bien['jardin_estado'] . "</span></div>";
                  };
                  echo"</div>

                <div class=\"caracteristicas_column2\">";
                if ($info_ficha_bien['picina'] == 1 || $info_ficha_bien['sauna'] == 1 || $info_ficha_bien['jacuzzi'] == 1 || $info_ficha_bien['parrillero'] == 1 || $info_ficha_bien['gimnasio'] == 1) {
                  echo "<h4><p>Hobby & Relajación</p><span></span></h4>";
                  if ($info_ficha_bien['picina'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Piscina</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['sauna'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Sauna</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['jacuzzi'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Jacuzzi</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['parrillero'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Parrillero</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['gimnasio'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Gimnasio</label><span>Si</span></div>";
                  };
                };

                if ($info_ficha_bien['edificio'] == 1) {
                  echo"<h4><p>Datos del Edificio</p><span></span></h4>
                  <div class=\"caracteristicas_row\"><label>Año de construcción</label><span>" . $info_ficha_bien['fecha_construccion'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Ascensor/Elevador</label><span>" . ($info_ficha_bien['acensor'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Portero</label><span>" . ($info_ficha_bien['portero'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Cámaras/Seguridad</label><span>" . ($info_ficha_bien['camaras'] != 1 ? 'No' : 'Si') . "</span></div>";
                  if ($info_ficha_bien['extintores'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Extintores</label><span>Si</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Desagüe</label><span>" . ($info_ficha_bien['desague'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Animales domésticos <i style=\"color:rgb(55, 55, 55); font-size: 1.2em;\" class=\"fa fa-paw\"></i></label><span>" . ($info_ficha_bien['animales_domesticos'] != 1 ? 'Prohibidos' : 'Permitidos') . "</span></div>";
                };

                echo"<h4><p>Otros</p><span></span></h4>
                  <div class=\"caracteristicas_row\"><label>Handicap <i style=\"color:rgb(60, 187, 241); font-weight: bold; font-size: 1.2em;\" class=\"fa fa-wheelchair\"></i></label><span>" . ($info_ficha_bien['handicap'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tipo de Vía</label><span>" . $info_ficha_bien['tipo_via'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Aceras</label><span>" . $info_ficha_bien['aceras'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Residencia Privada</label><span>" . ($info_ficha_bien['residencia'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Intercomunicador</label><span>" . ($info_ficha_bien['intercomunicador'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Gaz domiciliario</label><span>" . ($info_ficha_bien['gaz_domiciliario'] != 1 ? 'No' : 'Si') . "</span></div>";
                  if ($info_ficha_bien['aire_acondicionado'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Aire Acondicionado</label><span>Si</span></div>";
                  };
                  if ($info_ficha_bien['chimenea'] == 1) {
                    echo"<div class=\"caracteristicas_row\"><label>Chimenea</label><span>Si</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Tipo de ventanas</label><span>" . $info_ficha_bien['ventanas'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Calefacción</label><span>" . $info_ficha_bien['calefaccion'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Tensión eléctrica</label><span>" . $info_ficha_bien['conexion_electrica'] . "</span></div>";
                  if ($info_ficha_bien['cobertura'] == 'Inexistente') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(212, 212, 212); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Baja') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(224, 36, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Media') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(249, 239, 1); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Alta') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(130, 224, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Internet</label><span>" . $info_ficha_bien['internet'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>TV cable</label><span>" . $info_ficha_bien['tv_cable'] . "</span></div>
                  <h4><p>Aspectos financieros</p><span></span></h4>";
                  if ($estado == 'En Venta') {
                    echo "<div class=\"caracteristicas_row\"><label>Precio de venta</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  };
                  if ($estado == 'En Alquiler') {
                    echo "<div class=\"caracteristicas_row\"><label>Alquiler</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . " mensual</span></div>";
                  };
                    echo"<div class=\"caracteristicas_row\"><label>Mantenimiento (mensual)</label><span>" . number_format($info_ficha_bien['mantenimiento'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  if ($estado == 'En Venta') {
                    echo "<div class=\"caracteristicas_row\"><label>Impuestos (anuales)</label><span>" . number_format($info_ficha_bien['impuestos'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>
                    <div class=\"caracteristicas_row\"><label>IT (estimado)</label><span>" . number_format(($info_ficha_bien['base_imponible']*$pais_moneda['impuesto_transferencia_factor']), 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  };
                  echo"</div>
              </div>

              </div>";

        };
        if ($tipo_bien_requested == 'terreno') {

              echo"<div class=\"ficha_contenido_caracteristicas\">
              <h3>Características:</h3>

              <div class=\"caracteristicas_columns\">
                <div class=\"caracteristicas_column1\">
                <h4><p>Espacios y medidas</p><span></span></h4>
                  <div class=\"caracteristicas_row\"><label>Superfície del terreno</label><span>" . ($info_ficha_bien['superficie_terreno_medida'] == 'mÂ²' ? number_format(ceil($info_ficha_bien['superficie_terreno']), 0, '.', ' ') : number_format(($info_ficha_bien['superficie_terreno']/10000), 1, '.', ' ')) . ($info_ficha_bien['superficie_terreno_medida'] == 'mÂ²' ? ' m<sup>2</sup>' : ' Hect') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Zona</label><span>" . $info_ficha_bien['tipo_zona'] . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Geografía</label><span>" . $info_ficha_bien['geografia'] . "</span></div>";
                  if ($info_ficha_bien['zona_franca'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Zona Franca</label><span>" . ($info_ficha_bien['zona_franca'] != 1 ? 'No' : 'Si') . "</span></div>";
                  };
                  if ($info_ficha_bien['constructible'] == 1) {
                    echo "<div class=\"caracteristicas_row\"><label>Edificable</label><span>Si</span></div>
                    <div class=\"caracteristicas_row\"><label>Altura máxima</label><span>" . $info_ficha_bien['altura_max'] . " " . $info_ficha_bien['altura_max_medida'] . "</span></div>";
                  }else {
                    echo "<div class=\"caracteristicas_row\"><label>Edificable</label><span>A Gestionar</span></div>";
                  };

                echo"<h4><p>Detalles</p><span></span></h4>
                <div class=\"caracteristicas_row\"><label>Muralla</label><span>" . ($info_ficha_bien['muralla'] != 1 ? 'No' : 'Si') . "</span></div>";
                if ($info_ficha_bien['muralla'] == 1 && $info_ficha_bien['porton'] !== '') {
                  echo "<div class=\"caracteristicas_row\"><label>Portón</label><span>" . $info_ficha_bien['porton'] . "</span></div>";
                };
                echo"<div class=\"caracteristicas_row\"><label>Aceras</label><span>" . $info_ficha_bien['aceras'] . "</span></div>
                <div class=\"caracteristicas_row\"><label>Tipo de Vía</label><span>" . $info_ficha_bien['tipo_via'] . "</span></div>
                <div class=\"caracteristicas_row\"><label>Residencia Privada</label><span>" . ($info_ficha_bien['residencia'] != 1 ? 'No' : 'Si') . "</span></div>
                <div class=\"caracteristicas_row\"><label>Animales domésticos <i style=\"color:rgb(55, 55, 55); font-size: 1.2em;\" class=\"fa fa-paw\"></i></label><span>" . ($info_ficha_bien['animales_domesticos'] != 1 ? 'Prohibidos' : 'Permitidos') . "</span></div>";
                  if ($info_ficha_bien['ruido_externo'] <= 25) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Silencioso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 25 && $info_ficha_bien['ruido_externo'] <= 55) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Normal(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 55 && $info_ficha_bien['ruido_externo'] <= 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
                  if ($info_ficha_bien['ruido_externo'] > 90) {
                    echo "<div class=\"caracteristicas_row\"><label>Ruido externo</label><span>Muy Ruidoso(" . $info_ficha_bien['ruido_externo'] . " dB)</span></div>";
                  };
          echo"</div>
          <div class=\"caracteristicas_column2\">
                  <h4><p>Servicios</p><span></span></h4>
                  <div class=\"caracteristicas_row\"><label>Agua</label><span>" . ($info_ficha_bien['agua'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Electricidad</label><span>" . ($info_ficha_bien['electricidad'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Gaz domiciliario</label><span>" . ($info_ficha_bien['gaz_domiciliario'] != 1 ? 'No' : 'Si') . "</span></div>";
                  if ($info_ficha_bien['cobertura'] == 'Inexistente') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(212, 212, 212); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Baja') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(224, 36, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Media') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(249, 239, 1); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  if ($info_ficha_bien['cobertura'] == 'Alta') {
                    echo "<div class=\"caracteristicas_row\"><label>Cobertura móvil <i style=\"color:rgb(130, 224, 36); font-size: 1.2em;\" class=\"fa fa-signal\"></i></label><span>" . $info_ficha_bien['cobertura'] . "</span></div>";
                  };
                  echo"<div class=\"caracteristicas_row\"><label>Alcantarillado</label><span>" . ($info_ficha_bien['alcantarillado'] != 1 ? 'No' : 'Si') . "</span></div>
                  <div class=\"caracteristicas_row\"><label>Desagüe</label><span>" . ($info_ficha_bien['desague'] != 1 ? 'No' : 'Si') . "</span></div>
                  <h4><p>Aspectos financieros</p><span></span></h4>";
                  if ($estado == 'En Venta') {
                    echo "<div class=\"caracteristicas_row\"><label>Precio de venta</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  };
                  if ($estado == 'En Alquiler') {
                    echo "<div class=\"caracteristicas_row\"><label>Alquiler</label><span>" . number_format($info_ficha_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . " mensual</span></div>";
                  };
                    echo"<div class=\"caracteristicas_row\"><label>Mantenimiento (mensual)</label><span>" . number_format($info_ficha_bien['mantenimiento'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  if ($estado == 'En Venta') {
                    echo "<div class=\"caracteristicas_row\"><label>Impuestos (anuales)</label><span>" . number_format($info_ficha_bien['impuestos'], 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>
                    <div class=\"caracteristicas_row\"><label>IT (estimado)</label><span>" . number_format(($info_ficha_bien['base_imponible']*$pais_moneda['impuesto_transferencia_factor']), 0, '.', ' ') . " " . $pais_moneda['moneda'] . '&nbsp' . $pais_moneda['moneda_code'] . "</span></div>";
                  };
                  echo"</div>
              </div>

              </div>";

        };

            echo"<div id=\"mapa_ficha_contenedor\" class=\"mapa_ficha_contenedor\">
            <div class=\"banner_ver_mapa\">
              <span class=\"overlay_banner_ver_mapa_hover\">
              <span class=\"banner_ver_mapa_tag\">Ir al Mapa</span>
              </span>
            </div>
            <input type=\"hidden\" name=\"mapa_coordenada_lat\" id=\"mapa_coordenada_lat\" class=\"panel_MAPA\" value=\"" . $info_ficha_bien['mapa_coordenada_lat'] . "\">
            <input type=\"hidden\" name=\"mapa_coordenada_lng\" id=\"mapa_coordenada_lng\" class=\"panel_MAPA\" value=\"" . $info_ficha_bien['mapa_coordenada_lng'] . "\">
            <input type=\"hidden\" name=\"mapa_zoom\" id=\"mapa_zoom\" class=\"panel_MAPA\" value=\"" . $info_ficha_bien['mapa_zoom'] . "\">
            <input type=\"hidden\" name=\"mapa_direccion\" id=\"mapa_direccion\" class=\"panel_MAPA\" value=\"" . $info_ficha_bien['direccion'] . "\">
            </div>

            <div class=\"ficha_contenido_info_zona\">
            <h3>Datos de la zona:</h3>
            <h4>*Distancias recorridas a pie</h4>
            <div class=\"elemento_vecindario_container\">
                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/bus_stop_icon.svg\" alt=\"Bus icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Parada de Bus:</label>";
                      if($info_ficha_bien['parada_bus'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['parada_bus'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/teleferico_icon.svg\" alt=\"teleferico icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Teleférico:</label>";
                      if($info_ficha_bien['teleferico'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['teleferico'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/farmacia_icon.svg\" alt=\"farmacia icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Farmacia:</label>";
                      if($info_ficha_bien['farmacia'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['farmacia'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/mercado_icon.svg\" alt=\"mercado icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Supermercado:</label>";
                      if($info_ficha_bien['supermercado'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['supermercado'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/guarderia_icon.svg\" alt=\"guarderia icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Guardería:</label>";
                      if($info_ficha_bien['guarderia'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['guarderia'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/colegio_icon.svg\" alt=\"colegio icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Escuela:</label>";
                      if($info_ficha_bien['escuela'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['escuela'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/policia_icon.svg\" alt=\"policia icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Retén policial:</label>";
                      if($info_ficha_bien['policia'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['policia'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/hospital_icon.svg\" alt=\"hospital icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Clínica/Hospital:</label>";
                      if($info_ficha_bien['hospital'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['hospital'] . " min</span>";};
                      echo "
                    </span>
                  </div>

                  <div class=\"elemento_vecindario\">
                    <img src=\"../../objetos/plaza_icon.svg\" alt=\"plaza icon\">
                    <span class=\"elemento_vecindario_info\">
                      <label>Area verde:</label>";
                      if($info_ficha_bien['area_verde'] == 0){echo "<span style='font-size:1.5em;'>NO HAY</span>";}else{echo "<span style='font-size:1.5em;'>" . $info_ficha_bien['area_verde'] . " min</span>";};
                      echo "
                    </span>
                  </div>

              </div>



            </div>";


          if ($barrio_sponsors_check['activacion_sponsors'] == 1) {
            echo"
            <div class=\"ficha_contenido_info_barrio\">
              <h3>" . ($info_ficha_bien['barrio'] != '' ? $info_ficha_bien['barrio'] : $info_ficha_bien['ciudad']) . "</h3>
            </div>

            <div class=\"ficha_sponsors\">

              <h3>¡Si vivieras acá, encontrarías todo esto y mucho más!</h3>
              <div class=\"caracteristicas_columns\">
              <div id=\"popup_sponsor_overlay\"></div>";

              if (count($sponsors_info_restaurantes) >= 1) {

                echo "<div class=\"sponsor_column caracteristicas_column1\">
                    <h4><p>Restaurantes</p><span></span></h4>";

                foreach ($sponsors_info_restaurantes as $restaurante) {
                  echo "
                    <div class=\"elemento_sponsor\">
                      <span class=\"img_container\">
                        <img src=\"" . $restaurante['logo'] . "\" alt=\"" . $restaurante['label'] . " Logo\">
                      </span>
                      <label>" . $restaurante['label'] . "</label>
                      <input type=\"hidden\" value=\"" . $restaurante['nombre'] . "\">
                    </div>
                  ";
                };

                echo "</div>";
              };





              if (count($sponsors_info_bares) >= 1) {

                echo"<div class=\"sponsor_column caracteristicas_column2\">
                    <h4><p>Bares & Cafés</p><span></span></h4>";

                foreach ($sponsors_info_bares as $bar) {
                  echo "
                    <div class=\"elemento_sponsor\">
                    <span class=\"img_container\">
                      <img src=\"" . $bar['logo'] . "\" alt=\"" . $bar['label'] . " Logo\">
                    </span>
                      <label>" . $bar['label'] . "</label>
                      <input type=\"hidden\" value=\"" . $bar['nombre'] . "\">
                    </div>
                  ";
                };

                echo "</div>";
              };





              if (count($sponsors_info_bienestar) >= 1) {

                echo" <div class=\"sponsor_column caracteristicas_column1\">
                  <h4><p>Bienestar</p><span></span></h4>";

                foreach ($sponsors_info_bienestar as $bienestar) {
                  echo "
                    <div class=\"elemento_sponsor\">
                      <span class=\"img_container\">
                        <img src=\"" . $bienestar['logo'] . "\" alt=\"" . $bienestar['label'] . " Logo\">
                      </span>
                      <label>" . $bienestar['label'] . "</label>
                      <input type=\"hidden\" value=\"" . $bienestar['nombre'] . "\">
                    </div>
                  ";
                };
                echo "</div>";
              };





              if (count($sponsors_info_salud) >= 1) {

                echo "<div class=\"sponsor_column caracteristicas_column2\">
                  <h4><p>Salud</p><span></span></h4>";

                foreach ($sponsors_info_salud as $salud) {
                  echo "
                    <div class=\"elemento_sponsor\">
                      <span class=\"img_container\">
                        <img src=\"" . $salud['logo'] . "\" alt=\"" . $salud['label'] . " Logo\">
                      </span>
                      <label>" . $salud['label'] . "</label>
                      <input type=\"hidden\" value=\"" . $salud['nombre'] . "\">
                    </div>
                  ";
                };
                echo "</div>";
              };


        echo" </div>
            </div>";
          };


    echo "<div class=\"ficha_banner_banco\">
          </div>
          <div class=\"ficha_banner_banco_texto\">
            <h3>¿Buscás un Crédito Vivienda?</h3>
          </div>

          </div>

          <div class=\"ficha_chat_agente_tag\">

          </div>

          <div class=\"ficha_overlay_fotoscreen\">

          </div>
          <div class=\"ficha_overlay_mapa\">

          </div>

          <script src=\"../../js/slider.js\"></script>
          <script type=\"text/javascript\" src=\"../../js/dragable_feature_overflow.js\"></script>
          <script type=\"text/javascript\">
           viewer_mode = '" . $viewer_mode . "';
           var ficha_bien_referencia = '" . urlencode($ficha_bien_requested) . "';
           var fotos_json = JSON.parse('";
            echo json_encode($fotos_json,JSON_HEX_TAG|JSON_HEX_APOS|JSON_FORCE_OBJECT);
            echo"');
           var tour_vr_json = JSON.parse('";
            echo json_encode($tour_vr_json,JSON_HEX_TAG|JSON_HEX_APOS|JSON_FORCE_OBJECT);
            echo"');
          </script>
          <script src=\"../../js/abrir_360_viewer.js\"></script>
      ";

    };


};




?>
