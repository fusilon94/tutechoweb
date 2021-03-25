<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["pais_sent"]) && isset($_POST["agencia_sent"])){

    $pais = $_POST["pais_sent"];
    $agencia = $_POST["agencia_sent"];

    $tutechodb = "tutechodb_" . $pais;
    $tutechodb_internacional = "tutechodb_internacional";

    try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

    try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
    };


    $consulta_pais_info =	$conexion_internacional->prepare("SELECT moneda, moneda_code, anticretico_existe FROM paises WHERE pais = :pais");
    $consulta_pais_info->execute([':pais' => $pais]);
    $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

    $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag, express FROM agencias WHERE id = :id");
    $consulta_agencia_info->execute([':id' => $agencia]);
    $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);


    $moneda = $pais_info['moneda'] . $pais_info['moneda_code'];
    $anticretico = $pais_info['anticretico_existe'];
    $express = $agencia_info['express'];
    $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
    

    $json_path = '../../agencias/' . $pais . '/' . $agencia_tag . '/tabla_precios.json';
    
    $json = file_get_contents($json_path);
    $data = json_decode($json, true);

    $titulo_pdf = '';
    if ($express == 1) {
        $titulo_pdf = 'TUTECHO INMOBILIARIA-EXPRESS ' .  strtoupper($agencia_info['location_tag']);
    }else {
        $titulo_pdf = 'TUTECHO INMOBILIARIA-GESTORIA ' .  strtoupper($agencia_info['location_tag']);
    };

    function format($valor){
        return number_format($valor, 0, '.', ' ');
    };

    function tabla_valor_exclusivo($monto, $tipo, $moneda){
        if ($tipo == 'fijo') {
            return (number_format($monto, 0, '.', ' ') . ' ' . $moneda);
        }elseif ($tipo == 'porcentage') {
            return $monto . '% PVF*';
        };
    };

    function tabla_valor_no_exclusivo($monto, $tipo, $moneda){
        if ($tipo == 'no_disponible') {
            return 'NO DISPONIBLE';
        }elseif ($tipo == 'fijo') {
            return (number_format($monto, 0, '.', ' ') . ' ' . $moneda . ' + cargo b&aacute;sico');
        }elseif ($tipo == 'porcentage') {
            return $monto . '% PVF* + cargo b&aacute;sico';
        };
    };

    function tabla_valor_exclusivo_alquiler($monto, $tipo, $moneda){
        if ($tipo == 'fijo') {
            return (number_format($monto, 0, '.', ' ') . ' ' . $moneda);
        }elseif ($tipo == 'porcentage') {
            if ($monto == 100) {
                return '1 Alquiler';
            }else{
                return $monto . '% del Alquiler';
            };
            
        };
    };

    function tabla_valor_no_exclusivo_alquiler($monto, $tipo, $moneda){
        if ($tipo == 'no_disponible') {
            return 'NO DISPONIBLE';
        }elseif ($tipo == 'fijo') {
            return (number_format($monto, 0, '.', ' ') . ' ' . $moneda . ' + cargo b&aacute;sico');
        }elseif ($tipo == 'porcentage') {
            if ($monto == 100) {
                return '1 Alquiler + cargo b&aacute;sico';
            }else {
                return $monto . '% del Alquiler + cargo b&aacute;sico'; 
            };
        };
    };

    echo '
        <div class="pdf_header">
            <div class="pdf_logo_wrap">
            <img src="../../objetos/logotipo2.svg" alt="TuTecho Logo" class="pdf_logo">
            </div>
            <div class="pdf_titulo">
                <span>TABLA DE PRECIOS</span>
                <span>' . $titulo_pdf . '</span>
            </div>
        </div>

        <p class="fecha_edicion">Actualizado el: ' . $data['fecha'] . '</p>

        
        <ul>
        <li class="pdf_label_tabla"><strong>Venta ' . ($anticretico == 1 ? 'o Anticr&eacute;tico ' : '') . 'de Bienes Inmuebles </strong>(Casas, Departamentos, Locales y Terrenos)</li>
        </ul>
        <table width="0" class="pdf_tabla">
        <tbody class="tabla_venta_gris">
          <tr>
            <td width="208" class="pdf_rango">
            <p><strong>&nbsp;</strong></p>
            </td>
            <td width="271" class="pdf_exclusivo">
            <p><strong>EXCLUSIVO</strong></p>
            </td>
            <td width="239" class="pdf_no_exclusivo">
            <p><strong>NO-EXCLUSIVO</strong></p>
            </td>
          </tr>
          <tr>
            <td width="208" class="pdf_rango">
            <p>Hasta ' . format($data['venta']['first']['rango']['max']) . ' ' . $moneda . '</p>
            </td>
            <td width="271" class="pdf_exclusivo">
            <p>' . tabla_valor_exclusivo($data['venta']['first']['exclusivo']['monto'], $data['venta']['first']['exclusivo']['tipo'], $moneda) . '</p>
            </td>
            <td width="239" class="pdf_no_exclusivo">
            <p>' . tabla_valor_no_exclusivo($data['venta']['first']['no_exclusivo']['monto'], $data['venta']['first']['no_exclusivo']['tipo'], $moneda) . '</p>
            </td>
          </tr>';

          foreach ($data['venta']['intermediate'] as $linea) {
              echo'
              <tr>
                  <td width="208" class="pdf_rango">
                  <p>' . format($linea['rango']['min']) . ' &ndash; ' . format($linea['rango']['max']) . ' ' . $moneda . '</p>
                  </td>
                  <td width="271" class="pdf_exclusivo">
                  <p>' . tabla_valor_exclusivo($linea['exclusivo']['monto'], $linea['exclusivo']['tipo'], $moneda) . '</p>
                  </td>
                  <td width="239" class="pdf_no_exclusivo">
                  <p>' . tabla_valor_no_exclusivo($linea['no_exclusivo']['monto'], $linea['no_exclusivo']['tipo'], $moneda) . '</p>
                  </td>                 
              </tr>
              ';
          };
            


          echo '<tr>
            <td width="208" class="pdf_rango">
            <p>Superior a ' . format($data['venta']['last']['rango']['min']) . ' ' . $moneda .'</p>
            </td>
            <td width="271" class="pdf_exclusivo">
            <p>'. tabla_valor_exclusivo($data['venta']['last']['exclusivo']['monto'], $data['venta']['last']['exclusivo']['tipo'], $moneda) .'</p>
            </td>
            <td width="239" class="pdf_no_exclusivo">
            <p>' . tabla_valor_no_exclusivo($data['venta']['last']['no_exclusivo']['monto'], $data['venta']['last']['no_exclusivo']['tipo'], $moneda) . '</p>
            </td>
          </tr>
          <tr>
            <td width="208" class="pdf_rango">
            <p>Por lotes** de inmuebles</p>
            </td>
            <td width="271" class="pdf_exclusivo">
            <p>'. tabla_valor_exclusivo($data['venta']['lotes']['exclusivo']['monto'], $data['venta']['lotes']['exclusivo']['tipo'], $moneda) .'</p>
            </td>
            <td width="239" class="pdf_no_exclusivo">
            <p>'. tabla_valor_no_exclusivo($data['venta']['lotes']['no_exclusivo']['monto'], $data['venta']['lotes']['no_exclusivo']['tipo'], $moneda) .' (max ' . $data['venta']['lotes']['max_lotes'] . ')***</p>
            </td>
          </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p class="low_tag">*PVF = Precio de venta final **Aplica a partir de 3 lotes&nbsp;&nbsp; ***El cargo b&aacute;sico adicional no aumenta pasado el lote número ' . $data['venta']['lotes']['max_lotes'] . '</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>

        
        <ul>
        <li class="pdf_label_tabla"><strong>Alquiler de Bienes Inmuebles </strong>(Casas, Departamentos, Locales y Terrenos)</li>
        </ul>
        <p><strong>&nbsp;</strong></p>
        <table width="0" class="pdf_tabla">
        <tbody class="tabla_alquiler_gris">
        <tr>
          <td width="208" class="pdf_rango">
          <p><strong>&nbsp;</strong></p>
          </td>
          <td width="271" class="pdf_exclusivo">
          <p><strong>EXCLUSIVO</strong></p>
          </td>
          <td width="239" class="pdf_no_exclusivo">
          <p><strong>NO-EXCLUSIVO</strong></p>
          </td>
        </tr>
        <tr>
          <td width="208" class="pdf_rango">
          <p>Hasta ' . format($data['alquiler']['first']['rango']['max']) . ' ' . $moneda . '</p>
          </td>
          <td width="271" class="pdf_exclusivo">
          <p>' . tabla_valor_exclusivo_alquiler($data['alquiler']['first']['exclusivo']['monto'], $data['alquiler']['first']['exclusivo']['tipo'], $moneda) . '</p>
          </td>
          <td width="239" class="pdf_no_exclusivo">
          <p>' . tabla_valor_no_exclusivo_alquiler($data['alquiler']['first']['no_exclusivo']['monto'], $data['alquiler']['first']['no_exclusivo']['tipo'], $moneda) . '</p>
          </td>
        </tr>';

        foreach ($data['alquiler']['intermediate'] as $linea) {
          echo'
          <tr>
              <td width="208" class="pdf_rango">
              <p>' . format($linea['rango']['min']) . ' &ndash; ' . format($linea['rango']['max']) . ' ' . $moneda . '</p>
              </td>
              <td width="271" class="pdf_exclusivo">
              <p>' . tabla_valor_exclusivo_alquiler($linea['exclusivo']['monto'], $linea['exclusivo']['tipo'], $moneda) . '</p>
              </td>
              <td width="239" class="pdf_no_exclusivo">
              <p>' . tabla_valor_no_exclusivo_alquiler($linea['no_exclusivo']['monto'], $linea['no_exclusivo']['tipo'], $moneda) . '</p>
              </td>                 
          </tr>
          ';
        };
        
        echo'<tr>
          <td width="208" class="pdf_rango">
          <p>Superior a ' . format($data['alquiler']['last']['rango']['min']) . ' ' . $moneda .'</p>
          </td>
          <td width="271" class="pdf_exclusivo">
          <p>'. tabla_valor_exclusivo_alquiler($data['alquiler']['last']['exclusivo']['monto'], $data['alquiler']['last']['exclusivo']['tipo'], $moneda) .'</p>
          </td>
          <td width="239" class="pdf_no_exclusivo">
          <p>' . tabla_valor_no_exclusivo_alquiler($data['alquiler']['last']['no_exclusivo']['monto'], $data['alquiler']['last']['no_exclusivo']['tipo'], $moneda) . '</p>
          </td>
        </tr>
        <tr>
          <td width="208" class="pdf_rango">
          <p>Por lotes* de inmuebles</p>
          </td>
          <td width="271" class="pdf_exclusivo">
          <p>'. tabla_valor_exclusivo_alquiler($data['alquiler']['lotes']['exclusivo']['monto'], $data['alquiler']['lotes']['exclusivo']['tipo'], $moneda) .'</p>
          </td>
          <td width="239" class="pdf_no_exclusivo">
          <p>'. tabla_valor_no_exclusivo_alquiler($data['alquiler']['lotes']['no_exclusivo']['monto'], $data['alquiler']['lotes']['no_exclusivo']['tipo'], $moneda) .' (max ' . $data['alquiler']['lotes']['max_lotes'] . ')**</p>
          </td>
        </tr>
        </tbody>
        </table>

        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p class="low_tag">*Aplica a partir de 3 lotes&nbsp; &nbsp;&nbsp; **El cargo b&aacute;sico adicional no aumenta pasado el lote número ' . $data['alquiler']['lotes']['max_lotes'] . '</p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <ul>';

        if ($express == 0) {
            echo'
                <li class="pdf_label_tabla"><strong>Gesti&oacute;n de Bienes Inmuebles </strong>(Casas, Departamentos, Locales y Terrenos)</li>
                </ul>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <table width="0" class="pdf_tabla">
                <tbody>
                <tr>
                <td width="161" class="pdf_otros_titulos">
                <p><strong>Administraci&oacute;n</strong></p>
                </td>
                <td width="397"  class="pdf_otros_detalle">
                <p>Cobro mensual del alquiler, contacto de referencia con los inquilinos, gesti&oacute;n de trabajos y reparaciones.</p>
                </td>
                <td width="170"  class="pdf_otros_monto">
                <p><strong>' . $data['otros']['administracion']['monto'] . '</strong></p>
                </td>
                </tr>
                <tr>
                <td width="161"  class="pdf_otros_titulos">
                <p><strong>Estado e Inventario</strong></p>
                </td>
                <td width="397"  class="pdf_otros_detalle">
                <p>Revisi&oacute;n y llenado de formularios de inventario al inicio o al cierre de un contrato de alquiler.</p>
                </td>
                <td width="170"  class="pdf_otros_monto">
                <p><strong>' . $data['otros']['check_estado']['monto'] . '*</sup></strong></p>
                </td>
                </tr>
                </tbody>
                </table>
                <p><strong>&nbsp;</strong></p>
                <p class="low_tag">*' . $data['otros']['check_estado']['min'] . '</p>
                <p>&nbsp;</p>
            ';
        }
        
};
?>
