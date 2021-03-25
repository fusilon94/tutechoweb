<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    if (isset($_POST["departamentoChoice"])) {

        $departamento = $_POST["departamentoChoice"];

        $consulta_agencias_departamento =	$conexion->prepare("SELECT id, departamento, ciudad, location_tag, direccion, telefono, express FROM agencias WHERE departamento = :departamento");
        $consulta_agencias_departamento->execute([":departamento" => $departamento]);//SE PASA EL NOMBRE DEL DEPARTAMENTO
        $agencias_departamento = $consulta_agencias_departamento->fetchAll(PDO::FETCH_ASSOC);

        foreach ($agencias_departamento as $agencia) {

          if ($agencia['ciudad'] == $agencia['location_tag']) {
            $titulo = "TuTecho " . $agencia['ciudad'];
          }else {
            $titulo = "Tutecho " . $agencia['ciudad'] . " - " . $agencia['location_tag'];
          };

          echo "<div class=\"result_container\" id=\"" . $agencia['id'] . "\">
            <span class=\"agencia_titulo\">
              <h3>" . $titulo . "</h3>
              <hr class=\"barra_agencia\">
            </span>
            <div class=\"agencia_contenido\">
              <span class=\"foto_agencia\">
                <img src=\"../../agencias/" . $_COOKIE['tutechopais'] . "/" . $agencia['departamento'] . "_" . $agencia['location_tag'] . "/foto_agencia.jpg?=" . Date('U') . "\" alt=\"Sin Foto\">
              </span>
              <div class=\"agencia_info\">";
              if ($agencia['express'] == 1 && $agencia['direccion'] == '' && $agencia['telefono'] == '') {
                echo "
                  <span class=\"agencia_direccion\">
                    <span class=\"fa-stack icon_stacks_marker\">
                      <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                      <i class=\"fa fa-circle\"></i>
                    </span>
                    <p>-  AGENCIA EXPRESS  -</p>
                  </span>
                ";
              }else {
                if ($agencia['direccion'] !== '') {
                  echo "
                    <span class=\"agencia_direccion\">
                      <span class=\"fa-stack icon_stacks_marker\">
                        <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                        <i class=\"fa fa-circle\"></i>
                      </span>
                      <p>" . $agencia['direccion'] . "</p>
                    </span>
                  ";
                };
                if ($agencia['telefono'] !== '') {
                  echo "
                    <span class=\"agencia_contacto\">
                      <span class=\"contacto_icons\">
                        <span class=\"fa-stack icon_stacks_mobile\">
                          <i class=\"fa fa-mobile fa-stack-2x\"></i>
                          <i class=\"fa fa-bookmark fa-stack-1x\"></i>
                          <i class=\"fa fa-square\"></i>
                        </span>
                        <span class=\"fa-stack icon_stacks_whatsapp\">
                          <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                          <i class=\"fa fa-circle\"></i>
                        </span>
                      </span><p>" . $agencia['telefono'] . "</p>
                    </span>
                  ";
                };              
              };
                
                echo "<span class=\"agencia_mas_info\"><i class=\"fa fa-plus\"></i><p>Detalles</p></span>
              </div>
            </div>
          </div>";
        };

      };


    if (isset($_POST["ciudadChoice"])) {

        $ciudad = $_POST["ciudadChoice"];

        $consulta_agencias_ciudad =	$conexion->prepare("SELECT id, departamento, ciudad, location_tag, direccion, telefono, express FROM agencias WHERE ciudad = :ciudad");
        $consulta_agencias_ciudad->execute([":ciudad" => $ciudad]);//SE PASA EL NOMBRE DE LA CIUDAD O POBLADO
        $agencias_ciudad = $consulta_agencias_ciudad->fetchAll(PDO::FETCH_ASSOC);

        foreach ($agencias_ciudad as $agencia) {

          if ($agencia['ciudad'] == $agencia['location_tag']) {
            $titulo = "TuTecho " . $agencia['ciudad'];
          }else {
            $titulo = "Tutecho " . $agencia['ciudad'] . " - " . $agencia['location_tag'];
          };

          echo "<div class=\"result_container\" id=\"" . $agencia['id'] . "\">
            <span class=\"agencia_titulo\">
              <h3>" . $titulo . "</h3>
              <hr class=\"barra_agencia\">
            </span>
            <div class=\"agencia_contenido\">
              <span class=\"foto_agencia\">
                <img src=\"../../agencias/" . $_COOKIE['tutechopais'] . "/" . $agencia['departamento'] . "_" . $agencia['location_tag'] . "/foto_agencia.jpg?=" . Date('U') . "\" alt=\"Sin Foto\">
              </span>
              <div class=\"agencia_info\">";
              if ($agencia['express'] == 1 && $agencia['direccion'] == '' && $agencia['telefono'] == '') {
                echo "
                  <span class=\"agencia_direccion\">
                    <span class=\"fa-stack icon_stacks_marker\">
                      <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                      <i class=\"fa fa-circle\"></i>
                    </span>
                    <p>-  AGENCIA EXPRESS  -</p>
                  </span>
                ";
              }else {
                if ($agencia['direccion'] !== '') {
                  echo "
                    <span class=\"agencia_direccion\">
                      <span class=\"fa-stack icon_stacks_marker\">
                        <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                        <i class=\"fa fa-circle\"></i>
                      </span>
                      <p>" . $agencia['direccion'] . "</p>
                    </span>
                  ";
                };
                if ($agencia['telefono'] !== '') {
                  echo "
                    <span class=\"agencia_contacto\">
                      <span class=\"contacto_icons\">
                        <span class=\"fa-stack icon_stacks_mobile\">
                          <i class=\"fa fa-mobile fa-stack-2x\"></i>
                          <i class=\"fa fa-bookmark fa-stack-1x\"></i>
                          <i class=\"fa fa-square\"></i>
                        </span>
                        <span class=\"fa-stack icon_stacks_whatsapp\">
                          <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                          <i class=\"fa fa-circle\"></i>
                        </span>
                      </span><p>" . $agencia['telefono'] . "</p>
                    </span>
                  ";
                }; 
              };
                
                echo "<span class=\"agencia_mas_info\"><i class=\"fa fa-plus\"></i><p>Detalles</p></span>
              </div>
            </div>
          </div>";
        };

    };

    if (isset($_POST["agenciaChoice"])) {

      $agencia = $_POST["agenciaChoice"];

      $consulta_agencia_info =	$conexion->prepare("SELECT id, departamento, ciudad, location_tag, direccion, telefono, express FROM agencias WHERE id = :id");
      $consulta_agencia_info->execute([":id" => $agencia]);//SE PASA EL ID DE LA AGENCIA
      $agencia_info = $consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

        if ($agencia_info['ciudad'] == $agencia_info['location_tag']) {
          $titulo = "TuTecho " . $agencia_info['ciudad'];
        }else {
          $titulo = "Tutecho " . $agencia_info['ciudad'] . " - " . $agencia_info['location_tag'];
        };

        echo "<div class=\"result_container\" id=\"" . $agencia_info['id'] . "\">
          <span class=\"agencia_titulo\">
            <h3>" . $titulo . "</h3>
            <hr class=\"barra_agencia\">
          </span>
          <div class=\"agencia_contenido\">
            <span class=\"foto_agencia\">
              <img src=\"../../agencias/" . $_COOKIE['tutechopais'] . "/" . $agencia_info['departamento'] . "_" . $agencia_info['location_tag'] . "/foto_agencia.jpg?=" . Date('U') . "\" alt=\"Sin Foto\">
            </span>
            <div class=\"agencia_info\">";
            if ($agencia_info['express'] == 1 && $agencia_info['direccion'] == '' && $agencia_info['telefono'] == '') {
              echo "
                <span class=\"agencia_direccion\">
                  <span class=\"fa-stack icon_stacks_marker\">
                    <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                    <i class=\"fa fa-circle\"></i>
                  </span>
                  <p>-  AGENCIA EXPRESS  -</p>
                </span>
              ";
            }else {
              if ($agencia_info['direccion'] !== '') {
                echo "
                  <span class=\"agencia_direccion\">
                    <span class=\"fa-stack icon_stacks_marker\">
                      <i class=\"fa fa-map-marker fa-stack-1x\"></i>
                      <i class=\"fa fa-circle\"></i>
                    </span>
                    <p>" . $agencia_info['direccion'] . "</p>
                  </span>
                ";
              };
              if ($agencia_info['telefono'] !== '') {
                echo "
                  <span class=\"agencia_contacto\">
                    <span class=\"contacto_icons\">
                      <span class=\"fa-stack icon_stacks_mobile\">
                        <i class=\"fa fa-mobile fa-stack-2x\"></i>
                        <i class=\"fa fa-bookmark fa-stack-1x\"></i>
                        <i class=\"fa fa-square\"></i>
                      </span>
                      <span class=\"fa-stack icon_stacks_whatsapp\">
                        <i class=\"fa fa-whatsapp fa-stack-2x\"></i>
                        <i class=\"fa fa-circle\"></i>
                      </span>
                    </span><p>" . $agencia_info['telefono'] . "</p>
                  </span>
                ";
              }; 
            };
              
              echo "<span class=\"agencia_mas_info\"><i class=\"fa fa-plus\"></i><p>Detalles</p></span>
            </div>
          </div>
        </div>";

    };

};

?>
