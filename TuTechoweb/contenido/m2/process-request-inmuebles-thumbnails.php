<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verificar que se envio la solicitud por AJAX
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

// REQUEST LISTA DE REFERENCIAS A SER REENVIADAS COMO DATA PARA SER MOSTRADAS PROGRESIVAMENTE EN EL CUADRO DEL SCROLL INFINITO

    if (isset($_POST["list_of_references_requested"]) && isset($_POST["estado"])) {
      $list_of_references_requested = $_POST["list_of_references_requested"];
      $estado = $_POST["estado"];
      $consulta_referencias_lista =	$conexion->prepare("SELECT referencia FROM $list_of_references_requested WHERE visibilidad=:visibilidad AND inactivo = 0 AND estado=:estado ORDER BY tutecho_score DESC");
      $consulta_referencias_lista->execute([':visibilidad' => 'visible', ':estado' => $estado]);
      $referencia_lista_obtenida	=	$consulta_referencias_lista->fetchAll(PDO::FETCH_COLUMN);
      echo json_encode($referencia_lista_obtenida);//SE PONE EN MODO JSON PORQUE JS NO RECONOCE ARREGLOS DE PHP, PERO SI TEXTO JSON
    };

  if(isset($_POST["tipo_bien_selected"]) && isset($_POST["estado"])){
    $tipo_bien_selected = $_POST["tipo_bien_selected"];
    $estado = $_POST["estado"];
            // Recuperar informacion de bienes en venta

            if (isset($_POST["next_thumbs_default"])) {//SI SE PIDO AGREGAR MAS CONTENIDO
              $array_next_thumbs_default = $_POST["next_thumbs_default"]; //SE GUARDA EL ARREGLO DE REFERENCIAS
              $in_value_insert = str_repeat('?,', count($array_next_thumbs_default) - 1) . '?';//PERMITE COLOCAR TANTOS PLACEHOLDER DE TIPO ORDENADO [?] EN LA CONSULTA COMO REFERENCIAS HAY EN EL PEDIDO

              if ($estado == 'En Venta') {
                $consulta_info_bienes =	$conexion->prepare("SELECT * FROM $tipo_bien_selected WHERE visibilidad='visible' AND inactivo = 0 AND estado='En Venta' AND referencia IN ($in_value_insert) ORDER BY RAND()");
                $consulta_info_bienes->execute($array_next_thumbs_default);//SE PASAN LAS REFERENCIAS
                $info_bienes	=	$consulta_info_bienes->fetchAll();
              };
              if ($estado == 'En Alquiler') {
                $consulta_info_bienes =	$conexion->prepare("SELECT * FROM $tipo_bien_selected WHERE visibilidad='visible' AND inactivo = 0 AND estado='En Alquiler' AND referencia IN ($in_value_insert) ORDER BY RAND()");
                $consulta_info_bienes->execute($array_next_thumbs_default);//SE PASAN LAS REFERENCIAS
                $info_bienes	=	$consulta_info_bienes->fetchAll();
              };

            } else {// SI SE PIDIO EL CONTENIDO INICIAL
              if (isset($_POST["first_thumbs_default"])) {
                $array_first_thumbs_default = $_POST["first_thumbs_default"];
                $in_value_insert = str_repeat('?,', count($array_first_thumbs_default) - 1) . '?';//PERMITE COLOCAR TANTOS PLACEHOLDER DE TIPO ORDENADO [?] EN LA CONSULTA COMO REFERENCIAS HAY EN EL PEDIDO

                if ($estado == 'En Venta') {
                  $consulta_info_bienes =	$conexion->prepare("SELECT * FROM $tipo_bien_selected WHERE visibilidad='visible' AND inactivo = 0 AND estado='En Venta' AND referencia IN ($in_value_insert) ORDER BY RAND()");
                  $consulta_info_bienes->execute($array_first_thumbs_default);
                  $info_bienes	=	$consulta_info_bienes->fetchAll();
                };
                if ($estado == 'En Alquiler') {
                  $consulta_info_bienes =	$conexion->prepare("SELECT * FROM $tipo_bien_selected WHERE visibilidad='visible' AND inactivo = 0 AND estado='En Alquiler' AND referencia IN ($in_value_insert) ORDER BY RAND()");
                  $consulta_info_bienes->execute($array_first_thumbs_default);
                  $info_bienes	=	$consulta_info_bienes->fetchAll();
                };

              };
            };

//CLICK EN CASA
    if ($tipo_bien_selected == 'casa') {
            // Estructuracion de los thumbnails que seran devueltos como DATA

            foreach($info_bienes as $info_bien){

                  $json_fotos_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos.json';
                  $fotos_json = [];
                  $foto_cantidad = 0;
                  if (file_exists($json_fotos_path)) {
                    $fotos_json = json_decode(file_get_contents($json_fotos_path), true);
                    $foto_cantidad = count($fotos_json);
                  };

                  $foto_360_exist = '_off';
                  $fotos_360_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos_360';
                  if (is_dir($fotos_360_path)) {
                    if (glob($fotos_360_path . "/*")) {
                      $foto_360_exist = '';
                    };
                  };

                  $json_vr_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'VR.json';
                  $VR_exist = '_off';
                  if (file_exists($json_vr_path) && $info_bien['tourvr_visibilidad'] == 1) {
                    $VR_exist = '';
                  };


                  $extra_tag = '';

                  if ($estado == 'En Venta') {
                      if ($info_bien['pre_venta'] == 1) {
                        $etiqueta_display = 'block';
                        $etiqueta_class = 'etiqueta_pre_venta';
                        $etiqueta_text = 'Pre-Venta';
                      } else {
                        if ($info_bien['exclusivo'] == 1) {
                          $etiqueta_display = 'block';
                          $etiqueta_class = 'etiqueta_exclusivo';
                          $etiqueta_text = 'Exclusivo';
                        } else {
                          $etiqueta_display = 'none';
                          $etiqueta_class = '';
                          $etiqueta_text = '';
                        };
                      };
                  };

                  if ($estado == 'En Alquiler') {
                      if ($info_bien['anticretico'] == 1) {
                        $etiqueta_display = 'block';
                        $etiqueta_class = 'etiqueta_pre_venta';
                        $etiqueta_text = 'Anticretico';
                      } else {
                        $extra_tag = '/mes';
                        if ($info_bien['exclusivo'] == 1) {
                          $etiqueta_display = 'block';
                          $etiqueta_class = 'etiqueta_exclusivo';
                          $etiqueta_text = 'Exclusivo';
                        } else {
                          $etiqueta_display = 'none';
                          $etiqueta_class = '';
                          $etiqueta_text = '';
                        };
                      };
                  };

                  $referencia_para_foto = str_replace("#", "%23", $info_bien['referencia']);
                  
                  $tag_precio_size = '';

                  if ($estado == 'En Venta') {
                    if (strlen(strval($info_bien['precio'])) > 7) {
                      $tag_precio_size = 'tag_precio_small';
                    };
                  }else if($info_bien['anticretico'] == 1){
                    if (strlen(strval($info_bien['precio'])) > 7) {
                      $tag_precio_size = 'tag_precio_small';
                    };
                  }else{
                    if (strlen(strval($info_bien['precio'])) > 4) {
                      $tag_precio_size = 'tag_precio_small';
                    };
                  };


                echo "<ul class=\"articulo\" name=\"" . $info_bien['referencia'] . "\">
                  <a href=\"ficha_bien.php" . $info_bien['referencia'] . "\" class=\"overlay_thumbnail_inmueble\"></a>
                  <li class=\"parte_imagen\">
                    <ul class=\"ul_parte_imagen\">
                    <span class=\"favoritos_star_icon fa fa-star\"></span>
                      <li class=\"imagen_bien\">
                        <img src=\"../../bienes_inmuebles/" . $_COOKIE['tutechopais'] . "/" . $referencia_para_foto . "/portada.jpg?t=" . time() . "\" alt=\"imagen\">
                      </li>
                      <li class=\"iconos_bien\">
                        <ul>
                          <li><img src=\"../../objetos/fotos_icon.svg\" alt=\"Fotos\"><p>x" . $foto_cantidad . "</p></li>
                          <li><img src=\"../../objetos/360_grados_icon" . $foto_360_exist . ".svg\" alt=\"360\"></li>
                          <li><img src=\"../../objetos/vr_icon" . $VR_exist . ".svg\" alt=\"VR\"></li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class=\"parte_info\">
                    <ul class=\"ul_parte_info\">
                      <li class=\"li_info_bien\">
                        <ul>
                            <li>
                              <div class=\"div_ciudad_zona\">" . $info_bien['ciudad'] . "<br>" . $info_bien['barrio'] . "</div>
                              <div class=\"div_info_superficie\">" . number_format(round($info_bien['superficie_inmueble'], 0, PHP_ROUND_HALF_UP), 0, '.', ' ') . " " . " m<sup>2</sup></div>
                            </li>
                            <li class=\"li_info_icon\">
                              <div><span class=\"fa fa-bed\"></span> x " . $info_bien['dormitorios'] . "</div>
                              <div><span class=\"fa fa-car\"></span> x " . $info_bien['parqueos'] . "</div>
                            </li>
                        </ul>
                      </li>
                      <li class=\"precio_bien " . $tag_precio_size . "\">
                        <p>" . number_format($info_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . $extra_tag . "</p>
                      </li>
                    </ul>
                  </li>
                  <li class=\"" . $etiqueta_class . "\" style=\"display:" . $etiqueta_display . "\">
                    <p>" . $etiqueta_text . "</p>
                  </li>
                </ul>";
    };
  };

//CLICK EN DEPARTAMENTO
    if ($tipo_bien_selected == 'departamento') {

      // Estructuracion de los thumbnails que seran devueltos como DATA

      foreach($info_bienes as $info_bien){

            $json_fotos_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos.json';
            $fotos_json = [];
            $foto_cantidad = 0;
            if (file_exists($json_fotos_path)) {
              $fotos_json = json_decode(file_get_contents($json_fotos_path), true);
              $foto_cantidad = count($fotos_json);
            };

            $foto_360_exist = '_off';
            $fotos_360_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos_360';
            if (is_dir($fotos_360_path)) {
              if (glob($fotos_360_path . "/*")) {
                $foto_360_exist = '';
              };
            };

            $json_vr_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'VR.json';
            $VR_exist = '_off';
            if (file_exists($json_vr_path) && $info_bien['tourvr_visibilidad'] == 1) {
              $VR_exist = '';
            };


            $extra_tag = '';

            if ($estado == 'En Venta') {
                if ($info_bien['pre_venta'] == 1) {
                  $etiqueta_display = 'block';
                  $etiqueta_class = 'etiqueta_pre_venta';
                  $etiqueta_text = 'Pre-Venta';
                } else {
                  if ($info_bien['exclusivo'] == 1) {
                    $etiqueta_display = 'block';
                    $etiqueta_class = 'etiqueta_exclusivo';
                    $etiqueta_text = 'Exclusivo';
                  } else {
                    $etiqueta_display = 'none';
                    $etiqueta_class = '';
                    $etiqueta_text = '';
                  };
                };
            };

            if ($estado == 'En Alquiler') {
                if ($info_bien['anticretico'] == 1) {
                  $etiqueta_display = 'block';
                  $etiqueta_class = 'etiqueta_pre_venta';
                  $etiqueta_text = 'Anticretico';
                } else {
                  $extra_tag = '/mes';
                  if ($info_bien['exclusivo'] == 1) {
                    $etiqueta_display = 'block';
                    $etiqueta_class = 'etiqueta_exclusivo';
                    $etiqueta_text = 'Exclusivo';
                  } else {
                    $etiqueta_display = 'none';
                    $etiqueta_class = '';
                    $etiqueta_text = '';
                  };
                };
            };

            $referencia_para_foto = str_replace("#", "%23", $info_bien['referencia']);

            $tag_precio_size = '';

            if ($estado == 'En Venta') {
              if (strlen(strval($info_bien['precio'])) > 7) {
                $tag_precio_size = 'tag_precio_small';
              };
            }else if($info_bien['anticretico'] == 1){
              if (strlen(strval($info_bien['precio'])) > 7) {
                $tag_precio_size = 'tag_precio_small';
              };
            }else{
              if (strlen(strval($info_bien['precio'])) > 4) {
                $tag_precio_size = 'tag_precio_small';
              };
            };

            echo "<ul class=\"articulo\" name=\"" . $info_bien['referencia'] . "\">
              <a href=\"ficha_bien.php" . $info_bien['referencia'] . "\" class=\"overlay_thumbnail_inmueble\"></a>
              <li class=\"parte_imagen\">
                <ul class=\"ul_parte_imagen\">
                <span class=\"favoritos_star_icon fa fa-star\"></span>
                  <li class=\"imagen_bien\">
                    <img src=\"../../bienes_inmuebles/" . $_COOKIE['tutechopais'] . "/" . $referencia_para_foto . "/portada.jpg?t=" . time() . "\" alt=\"imagen\">
                  </li>
                  <li class=\"iconos_bien\">
                    <ul>
                      <li><img src=\"../../objetos/fotos_icon.svg\" alt=\"Fotos\"><p>x" . $foto_cantidad . "</p></li>
                      <li><img src=\"../../objetos/360_grados_icon" . $foto_360_exist . ".svg\" alt=\"360\"></li>
                      <li><img src=\"../../objetos/vr_icon" . $VR_exist . ".svg\" alt=\"VR\"></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class=\"parte_info\">
                <ul class=\"ul_parte_info\">
                  <li class=\"li_info_bien\">
                    <ul>
                        <li>
                          <div class=\"div_ciudad_zona\">" . $info_bien['ciudad'] . "<br>" . $info_bien['barrio'] . "</div>
                          <div class=\"div_info_superficie\">" . number_format(round($info_bien['superficie_inmueble'], 0, PHP_ROUND_HALF_UP), 0, '.', ' ') . " " . " m<sup>2</sup></div>
                        </li>
                        <li class=\"li_info_icon\">
                          <div><span class=\"fa fa-bed\"></span> x " . $info_bien['dormitorios'] . "</div>
                          <div><span class=\"fa fa-car\"></span> x " . $info_bien['parqueos'] . "</div>
                        </li>
                    </ul>
                  </li>
                  <li class=\"precio_bien " . $tag_precio_size . "\">
                    <p>" . number_format($info_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . $extra_tag . "</p>
                  </li>
                </ul>
              </li>
              <li class=\"" . $etiqueta_class . "\" style=\"display:" . $etiqueta_display . "\">
                <p>" . $etiqueta_text . "</p>
              </li>
            </ul>";

    };

  };

//CLICK EN LOCAL
    if ($tipo_bien_selected == 'local') {

      // Estructuracion de los thumbnails que seran devueltos como DATA

      foreach($info_bienes as $info_bien){

            $json_fotos_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos.json';
            $fotos_json = [];
            $foto_cantidad = 0;
            if (file_exists($json_fotos_path)) {
              $fotos_json = json_decode(file_get_contents($json_fotos_path), true);
              $foto_cantidad = count($fotos_json);
            };

            $foto_360_exist = '_off';
            $fotos_360_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos_360';
            if (is_dir($fotos_360_path)) {
              if (glob($fotos_360_path . "/*")) {
                $foto_360_exist = '';
              };
            };

            $json_vr_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'VR.json';
            $VR_exist = '_off';
            if (file_exists($json_vr_path) && $info_bien['tourvr_visibilidad'] == 1) {
              $VR_exist = '';
            };


            $extra_tag = '';


            if ($estado == 'En Venta') {
                if ($info_bien['pre_venta'] == 1) {
                  $etiqueta_display = 'block';
                  $etiqueta_class = 'etiqueta_pre_venta';
                  $etiqueta_text = 'Pre-Venta';
                } else {
                  if ($info_bien['exclusivo'] == 1) {
                    $etiqueta_display = 'block';
                    $etiqueta_class = 'etiqueta_exclusivo';
                    $etiqueta_text = 'Exclusivo';
                  } else {
                    $etiqueta_display = 'none';
                    $etiqueta_class = '';
                    $etiqueta_text = '';
                  };
                };
            };

            if ($estado == 'En Alquiler') {
                if ($info_bien['anticretico'] == 1) {
                  $etiqueta_display = 'block';
                  $etiqueta_class = 'etiqueta_pre_venta';
                  $etiqueta_text = 'Anticretico';
                } else {
                  $extra_tag = '/mes';
                  if ($info_bien['exclusivo'] == 1) {
                    $etiqueta_display = 'block';
                    $etiqueta_class = 'etiqueta_exclusivo';
                    $etiqueta_text = 'Exclusivo';
                  } else {
                    $etiqueta_display = 'none';
                    $etiqueta_class = '';
                    $etiqueta_text = '';
                  };
                };
            };

            $referencia_para_foto = str_replace("#", "%23", $info_bien['referencia']);

            if ($info_bien['tipo_local'] == 'Ambos') {
              $tipo_local_info_thumb = "Todo Uso";
            } else {
              $tipo_local_info_thumb = $info_bien['tipo_local'];
            };

            $tag_precio_size = '';

            if ($estado == 'En Venta') {
              if (strlen(strval($info_bien['precio'])) > 7) {
                $tag_precio_size = 'tag_precio_small';
              };
            }else if($info_bien['anticretico'] == 1){
              if (strlen(strval($info_bien['precio'])) > 7) {
                $tag_precio_size = 'tag_precio_small';
              };
            }else{
              if (strlen(strval($info_bien['precio'])) > 4) {
                $tag_precio_size = 'tag_precio_small';
              };
            };

            echo "<ul class=\"articulo\" name=\"" . $info_bien['referencia'] . "\">
              <a href=\"ficha_bien.php" . $info_bien['referencia'] . "\" class=\"overlay_thumbnail_inmueble\"></a>
              <li class=\"parte_imagen\">
                <ul class=\"ul_parte_imagen\">
                <span class=\"favoritos_star_icon fa fa-star\"></span>
                  <li class=\"imagen_bien\">
                    <img src=\"../../bienes_inmuebles/" . $_COOKIE['tutechopais'] . "/" . $referencia_para_foto . "/portada.jpg?t=" . time() . "\" alt=\"imagen\">
                  </li>
                  <li class=\"iconos_bien\">
                    <ul>
                      <li><img src=\"../../objetos/fotos_icon.svg\" alt=\"Fotos\"><p>x" . $foto_cantidad . "</p></li>
                      <li><img src=\"../../objetos/360_grados_icon" . $foto_360_exist . ".svg\" alt=\"360\"></li>
                      <li><img src=\"../../objetos/vr_icon" . $VR_exist . ".svg\" alt=\"VR\"></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class=\"parte_info\">
                <ul class=\"ul_parte_info\">
                  <li class=\"li_info_bien\">
                    <ul>
                        <li>
                          <div class=\"div_ciudad_zona\">" . $info_bien['ciudad'] . "<br>" . $info_bien['barrio'] . "</div>
                          <div class=\"div_info_superficie\">" . number_format(round($info_bien['superficie_inmueble'], 0, PHP_ROUND_HALF_UP), 0, '.', ' ') . " " . " m<sup>2</sup></div>
                        </li>
                        <li class=\"li_info_icon\">
                          <div><span class=\"fa fa-car\"></span> x " . $info_bien['parqueos'] . "</div>
                          <div class=\"div_tipo_local\">" . $tipo_local_info_thumb . "</div>
                        </li>
                    </ul>
                  </li>
                  <li class=\"precio_bien " . $tag_precio_size . "\">
                    <p>" . number_format($info_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . $extra_tag . "</p>
                  </li>
                </ul>
              </li>
              <li class=\"" . $etiqueta_class . "\" style=\"display:" . $etiqueta_display . "\">
                <p>" . $etiqueta_text . "</p>
              </li>
            </ul>";

    };

  };

//CLICK EN TERRENO
    if ($tipo_bien_selected == 'terreno') {

      // Estructuracion de los thumbnails que seran devueltos como DATA

      foreach($info_bienes as $info_bien){

            $json_fotos_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos.json';
            $fotos_json = [];
            $foto_cantidad = 0;
            if (file_exists($json_fotos_path)) {
              $fotos_json = json_decode(file_get_contents($json_fotos_path), true);
              $foto_cantidad = count($fotos_json);
            };

            $foto_360_exist = '_off';
            $fotos_360_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'fotos_360';
            if (is_dir($fotos_360_path)) {
              if (glob($fotos_360_path . "/*")) {
                $foto_360_exist = '';
              };
            };

            $json_vr_path = '..\..\bienes_inmuebles' . "\\" . $_COOKIE['tutechopais'] . "\\" . $info_bien['referencia'] . '\\' . 'VR.json';
            $VR_exist = '_off';
            if (file_exists($json_vr_path) && $info_bien['tourvr_visibilidad'] == 1) {
              $VR_exist = '';
            };

            $extra_tag = '';

            if ($estado == 'En Venta') {
                if ($info_bien['pre_venta'] == 1) {
                  $etiqueta_display = 'block';
                  $etiqueta_class = 'etiqueta_pre_venta';
                  $etiqueta_text = 'Pre-Venta';
                } else {
                  if ($info_bien['exclusivo'] == 1) {
                    $etiqueta_display = 'block';
                    $etiqueta_class = 'etiqueta_exclusivo';
                    $etiqueta_text = 'Exclusivo';
                  } else {
                    $etiqueta_display = 'none';
                    $etiqueta_class = '';
                    $etiqueta_text = '';
                  };
                };
            };

            if ($estado == 'En Alquiler') {
                if ($info_bien['anticretico'] == 1) {
                  $etiqueta_display = 'block';
                  $etiqueta_class = 'etiqueta_pre_venta';
                  $etiqueta_text = 'Anticretico';
                } else {
                  $extra_tag = '/mes';
                  if ($info_bien['exclusivo'] == 1) {
                    $etiqueta_display = 'block';
                    $etiqueta_class = 'etiqueta_exclusivo';
                    $etiqueta_text = 'Exclusivo';
                  } else {
                    $etiqueta_display = 'none';
                    $etiqueta_class = '';
                    $etiqueta_text = '';
                  };
                };
            };

            $referencia_para_foto = str_replace("#", "%23", $info_bien['referencia']);

            //si terreno es mas de 10000 m2 pasa a hectareas, para hectareas no se redondea, para m2 se redondea el decimal al nuemro superior
            if ($info_bien['superficie_terreno'] >= 10000) {
              $superficie_terreno_true = number_format($info_bien['superficie_terreno']/10000, 1, '.', ' ');
              $superficie_terreno_medida_true = 'ha';
            } else {
              $superficie_terreno_true = number_format(round($info_bien['superficie_terreno'], 0, PHP_ROUND_HALF_UP), 0, '.', ' ');
              $superficie_terreno_medida_true = 'm&sup2';
            };

            $tag_precio_size = '';

            if ($estado == 'En Venta') {
              if (strlen(strval($info_bien['precio'])) > 7) {
                $tag_precio_size = 'tag_precio_small';
              };
            }else if($info_bien['anticretico'] == 1){
              if (strlen(strval($info_bien['precio'])) > 7) {
                $tag_precio_size = 'tag_precio_small';
              };
            }else{
              if (strlen(strval($info_bien['precio'])) > 4) {
                $tag_precio_size = 'tag_precio_small';
              };
            };

            echo "<ul class=\"articulo\" name=\"" . $info_bien['referencia'] . "\">
              <a href=\"ficha_bien.php" . $info_bien['referencia'] . "\" class=\"overlay_thumbnail_inmueble\"></a>
              <li class=\"parte_imagen\">
                <ul class=\"ul_parte_imagen\">
                <span class=\"favoritos_star_icon fa fa-star\"></span>
                  <li class=\"imagen_bien\">
                    <img src=\"../../bienes_inmuebles/" . $_COOKIE['tutechopais'] . "/" . $referencia_para_foto . "/portada.jpg?t=" . time() . "\" alt=\"imagen\">
                  </li>
                  <li class=\"iconos_bien\">
                    <ul>
                      <li><img src=\"../../objetos/fotos_icon.svg\" alt=\"Fotos\"><p>x" . $foto_cantidad . "</p></li>
                      <li><img src=\"../../objetos/360_grados_icon" . $foto_360_exist . ".svg\" alt=\"360\"></li>
                      <li><img src=\"../../objetos/vr_icon" . $VR_exist . ".svg\" alt=\"VR\"></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class=\"parte_info\">
                <ul class=\"ul_parte_info\">
                  <li class=\"li_info_bien\">
                    <ul>
                        <li>
                          <div class=\"div_ciudad_zona\">" . $info_bien['ciudad'] . "<br>" . $info_bien['barrio'] . "</div>
                          <div class=\"div_info_superficie\">" . $superficie_terreno_true . " " . $superficie_terreno_medida_true . "</div>
                        </li>
                    </ul>
                  </li>
                  <li class=\"precio_bien " . $tag_precio_size . "\">
                    <p>" . number_format($info_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] ."</p>
                  </li>
                </ul>
              </li>
              <li class=\"" . $etiqueta_class . "\" style=\"display:" . $etiqueta_display . $extra_tag . "\">
                <p>" . $etiqueta_text . "</p>
              </li>
            </ul>";

    };
  };

};
}

?>
