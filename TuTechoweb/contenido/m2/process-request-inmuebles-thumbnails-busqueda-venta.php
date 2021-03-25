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

    $consulta_pais_moneda =	$conexion_internacional->prepare("SELECT moneda, cambio_dolar, moneda_code, impuesto_transferencia_factor FROM paises WHERE pais=:pais ");
    $consulta_pais_moneda->execute(['pais' => $_COOKIE['tutechopais']]);//SE PASA LA REFERENCIA
    $pais_moneda = $consulta_pais_moneda->fetch(PDO::FETCH_ASSOC);

    $cambio = $pais_moneda['cambio_dolar'];

  if(isset($_POST["tipo_bien_selected"]) && isset($_POST["estado"])){
    $tipo_bien_selected = $_POST["tipo_bien_selected"];
    $estado = $_POST["estado"];

    $departamento_busqueda = $_POST["departamento_busqueda"];
    $ciudad_busqueda = $_POST["ciudad_busqueda"];
    $superficie_busqueda = $_POST["superficie_busqueda"];
    if ($_POST["precio_busqueda"] > (2000000*$cambio)) {
      $precio_busqueda = 100000000000;
    } else {
      $precio_busqueda = $_POST["precio_busqueda"];
    };

    if (isset($_POST["dormitorios_busqueda"])) {
      $dormitorios_busqueda = $_POST["dormitorios_busqueda"];
    };

    if (isset($_POST["tipo_local_busqueda"])) {
      $tipo_local_busqueda = $_POST["tipo_local_busqueda"];
    };

    if (isset($_POST["parqueos_busqueda"])) {
      $parqueos_busqueda = $_POST["parqueos_busqueda"];
    };

    $articulos_por_pagina = 15;
    $page_requested = $_POST["page_requested"];
    $inicio = ($page_requested > 1) ? ($page_requested * $articulos_por_pagina - $articulos_por_pagina) : 0;

    if ($_POST["price_sorting"] == 1) {
      $price_sorting = "tutecho_score DESC";
    }else {
      if ($_POST["price_sorting"] == 2) {
        $price_sorting = "precio ASC";
      }else {
        $price_sorting = "precio DESC";
      };
    };

//BUSQUEDA EN CASA
    if ($tipo_bien_selected == 'casa') {
            // Recuperar informacion de casas en venta segun la busqueda personalizada

            if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
              $consulta_info_bienes_casa =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM casa WHERE visibilidad=:visibilidad AND estado=:estado AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
              $consulta_info_bienes_casa->execute(array(
              ':visibilidad' => 'visible',
              ':estado' => $estado,
              ':estado' => $estado,
              ':superficie_inmueble' => $superficie_busqueda,
              ':precio' => $precio_busqueda,
              ':dormitorios' => $dormitorios_busqueda,
              ':parqueos' => $parqueos_busqueda
              ));
              $info_bienes_casa	=	$consulta_info_bienes_casa->fetchAll();
              if ($price_sorting == "tutecho_score DESC") {
                shuffle($info_bienes_casa);
              };

              $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
              $numero_articulos = $numero_articulos->fetch()['total'];
            };

            if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
              $consulta_info_bienes_casa =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM casa WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
              $consulta_info_bienes_casa->execute(array(
              ':visibilidad' => 'visible',
              ':estado' => $estado,
              ':departamento' => $departamento_busqueda,
              ':superficie_inmueble' => $superficie_busqueda,
              ':precio' => $precio_busqueda,
              ':dormitorios' => $dormitorios_busqueda,
              ':parqueos' => $parqueos_busqueda
              ));
              $info_bienes_casa	=	$consulta_info_bienes_casa->fetchAll();
              if ($price_sorting == "tutecho_score DESC") {
                shuffle($info_bienes_casa);
              };

              $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
              $numero_articulos = $numero_articulos->fetch()['total'];
            };

            if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
              $consulta_info_bienes_casa =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM casa WHERE visibilidad=:visibilidad AND estado=:estado AND ciudad=:ciudad AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
              $consulta_info_bienes_casa->execute(array(
              ':visibilidad' => 'visible',
              ':estado' => $estado,
              ':ciudad' => $ciudad_busqueda,
              ':superficie_inmueble' => $superficie_busqueda,
              ':precio' => $precio_busqueda,
              ':dormitorios' => $dormitorios_busqueda,
              ':parqueos' => $parqueos_busqueda
              ));
              $info_bienes_casa	=	$consulta_info_bienes_casa->fetchAll();
              if ($price_sorting == "tutecho_score DESC") {
                shuffle($info_bienes_casa);
              };

              $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
              $numero_articulos = $numero_articulos->fetch()['total'];
            };

            if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
              $consulta_info_bienes_casa =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM casa WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND ciudad=:ciudad AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
              $consulta_info_bienes_casa->execute(array(
              ':visibilidad' => 'visible',
              ':estado' => $estado,
              ':departamento' => $departamento_busqueda,
              ':ciudad' => $ciudad_busqueda,
              ':superficie_inmueble' => $superficie_busqueda,
              ':precio' => $precio_busqueda,
              ':dormitorios' => $dormitorios_busqueda,
              ':parqueos' => $parqueos_busqueda
              ));
              $info_bienes_casa	=	$consulta_info_bienes_casa->fetchAll();
              if ($price_sorting == "tutecho_score DESC") {
                shuffle($info_bienes_casa);
              };

              $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
              $numero_articulos = $numero_articulos->fetch()['total'];
            };

            // Estructuracion de los thumbnails que seran devueltos como DATA

            foreach($info_bienes_casa as $info_bien){

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

//BUSQUEDA EN DEPARTAMENTO
    if ($tipo_bien_selected == 'departamento') {

      // Recuperar informacion de casas en venta segun la busqueda personalizada

      if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
        $consulta_info_bienes_departamento =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM departamento WHERE visibilidad=:visibilidad AND estado=:estado AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_departamento->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':dormitorios' => $dormitorios_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_departamento	=	$consulta_info_bienes_departamento->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_departamento);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
        $consulta_info_bienes_departamento =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM departamento WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_departamento->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':departamento' => $departamento_busqueda,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':dormitorios' => $dormitorios_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_departamento	=	$consulta_info_bienes_departamento->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_departamento);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
        $consulta_info_bienes_departamento =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM departamento WHERE visibilidad=:visibilidad AND estado=:estado AND ciudad=:ciudad AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_departamento->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':ciudad' => $ciudad_busqueda,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':dormitorios' => $dormitorios_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_departamento	=	$consulta_info_bienes_departamento->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_departamento);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
        $consulta_info_bienes_departamento =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM departamento WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND ciudad=:ciudad AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND dormitorios>=:dormitorios AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_departamento->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':departamento' => $departamento_busqueda,
        ':ciudad' => $ciudad_busqueda,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':dormitorios' => $dormitorios_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_departamento	=	$consulta_info_bienes_departamento->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_departamento);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      // Estructuracion de los thumbnails que seran devueltos como DATA

      foreach($info_bienes_departamento as $info_bien){

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

//BUSQUEDA EN LOCAL
    if ($tipo_bien_selected == 'local') {

      if ($tipo_local_busqueda == 'Comercial') {
        $tipo_local_sql_request = "tipo_local='Comercial'";
      } else {
        if ($tipo_local_busqueda == 'Oficina') {
          $tipo_local_sql_request = "tipo_local='Oficina'";
        } else {
          $tipo_local_sql_request = "(tipo_local='Comercial' OR tipo_local='Oficina' OR tipo_local='Ambos')";
        };
      };


      // Recuperar informacion de casas en venta segun la busqueda personalizada

      if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
        $consulta_info_bienes_local =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM local WHERE visibilidad=:visibilidad AND estado=:estado AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND $tipo_local_sql_request AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_local->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_local	=	$consulta_info_bienes_local->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_local);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
        $consulta_info_bienes_local =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM local WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND $tipo_local_sql_request AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_local->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':departamento' => $departamento_busqueda,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_local	=	$consulta_info_bienes_local->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_local);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
        $consulta_info_bienes_local =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM local WHERE visibilidad=:visibilidad AND estado=:estado AND ciudad=:ciudad AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND $tipo_local_sql_request AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_local->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':ciudad' => $ciudad_busqueda,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_local	=	$consulta_info_bienes_local->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_local);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
        $consulta_info_bienes_local =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM local WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND ciudad=:ciudad AND superficie_inmueble>=:superficie_inmueble AND precio<=:precio AND $tipo_local_sql_request AND parqueos>=:parqueos AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_local->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':departamento' => $departamento_busqueda,
        ':ciudad' => $ciudad_busqueda,
        ':superficie_inmueble' => $superficie_busqueda,
        ':precio' => $precio_busqueda,
        ':parqueos' => $parqueos_busqueda
        ));
        $info_bienes_local	=	$consulta_info_bienes_local->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_local);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      // Estructuracion de los thumbnails que seran devueltos como DATA

      foreach($info_bienes_local as $info_bien){

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

//BUSQUEDA EN TERRENO
    if ($tipo_bien_selected == 'terreno') {

      // Recuperar informacion de casas en venta segun la busqueda personalizada

      if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
        $consulta_info_bienes_terreno =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM terreno WHERE visibilidad=:visibilidad AND estado=:estado AND superficie_terreno>=:superficie_terreno AND precio<=:precio AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_terreno->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':superficie_terreno' => $superficie_busqueda,
        ':precio' => $precio_busqueda
        ));
        $info_bienes_terreno	=	$consulta_info_bienes_terreno->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_terreno);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda == "Todas las ciudades") {
        $consulta_info_bienes_terreno =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM terreno WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND superficie_terreno>=:superficie_terreno AND precio<=:precio AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_terreno->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':departamento' => $departamento_busqueda,
        ':superficie_terreno' => $superficie_busqueda,
        ':precio' => $precio_busqueda
        ));
        $info_bienes_terreno	=	$consulta_info_bienes_terreno->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_terreno);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda == "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
        $consulta_info_bienes_terreno =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM terreno WHERE visibilidad=:visibilidad AND estado=:estado AND ciudad=:ciudad AND superficie_terreno>=:superficie_terreno AND precio<=:precio AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_terreno->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':ciudad' => $ciudad_busqueda,
        ':superficie_terreno' => $superficie_busqueda,
        ':precio' => $precio_busqueda
        ));
        $info_bienes_terreno	=	$consulta_info_bienes_terreno->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_terreno);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      if ($departamento_busqueda !== "Toda Bolivia" && $ciudad_busqueda !== "Todas las ciudades") {
        $consulta_info_bienes_terreno =	$conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM terreno WHERE visibilidad=:visibilidad AND estado=:estado AND departamento=:departamento AND ciudad=:ciudad AND superficie_terreno>=:superficie_terreno AND precio<=:precio AND inactivo = 0 ORDER BY $price_sorting LIMIT $inicio, $articulos_por_pagina");
        $consulta_info_bienes_terreno->execute(array(
        ':visibilidad' => 'visible',
        ':estado' => $estado,
        ':departamento' => $departamento_busqueda,
        ':ciudad' => $ciudad_busqueda,
        ':superficie_terreno' => $superficie_busqueda,
        ':precio' => $precio_busqueda
        ));
        $info_bienes_terreno	=	$consulta_info_bienes_terreno->fetchAll();
        if ($price_sorting == "tutecho_score DESC") {
          shuffle($info_bienes_terreno);
        };

        $numero_articulos = $conexion->query('SELECT FOUND_ROWS() as total');
        $numero_articulos = $numero_articulos->fetch()['total'];
      };

      // Estructuracion de los thumbnails que seran devueltos como DATA

      foreach($info_bienes_terreno as $info_bien){

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
          if (strlen($info_bien['precio']) > 7) {
            $tag_precio_size = 'tag_precio_small';
          };
        }else if($info_bien['anticretico'] == 1){
          if (strlen($info_bien['precio']) > 7) {
            $tag_precio_size = 'tag_precio_small';
          };
        }else{
          if (strlen($info_bien['precio']) > 4) {
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
                <p>" . number_format($info_bien['precio'], 0, '.', ' ') . "&nbsp" . $pais_moneda['moneda'] . "</p>
              </li>
            </ul>
          </li>
          <li class=\"" . $etiqueta_class . "\" style=\"display:" . $etiqueta_display . $extra_tag . "\">
            <p>" . $etiqueta_text . "</p>
          </li>
        </ul>";

    };
  };

// ENVIAR DATOS PAGE_REQUESTED, NUMERO_ARTICULOS Y ARTICULOS_POR_PAGINA A PAGINACION_REFRESH.JS

    echo "<script type='text/javascript'>paginacion_refresh('$page_requested', '$numero_articulos', '$articulos_por_pagina');</script>";

// CODIGO PARA MOSTRAR MENSAJE DE "NINGUN BIEN CORRESPONDE A SU BUSQUEDA", QUE SERA DEVUELTO COMO DATA POR AJAX

    if ($numero_articulos == 0) {
    echo "
    <div id=\"mensaje_sin_resultados_busqueda\">
    <span class=\"fa fa-search\"></span>
    <h4>NO SE ENCONTRARON RESULADOS</h4>
    <p>Intente modificar los parametros de búsqueda</p>
    </div>
    ";
    };

};
}

?>
