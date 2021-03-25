<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


    if (isset($_POST["qr_code_sent"])) {
      $qr_code = $_POST["qr_code_sent"];

      $consulta_qr_code_existe =	$conexion->prepare("SELECT sponsor_cupon FROM qr_cupones WHERE codigoQR=:codigoQR ");
      $consulta_qr_code_existe->execute([':codigoQR' => $qr_code]);
      $qr_code_existe = $consulta_qr_code_existe->fetch(PDO::FETCH_ASSOC);

      if (isset($qr_code_existe['sponsor_cupon'])) {
        $sponsor = $qr_code_existe['sponsor_cupon'];

        $consulta_cupon_promo =	$conexion->prepare("SELECT tipo_promocion, promo_var1, promo_var2, promo_var3, promo_color, promo_info1, promo_info2, fecha_vencimiento FROM cupones_sponsor WHERE sponsor=:sponsor ");
        $consulta_cupon_promo->execute([':sponsor' => $sponsor]);
        $cupon_promo = $consulta_cupon_promo->fetch(PDO::FETCH_ASSOC);

        $consulta_sponsor_info =	$conexion->prepare("SELECT barrio, label, logo, direccion FROM sponsors WHERE nombre=:nombre ");
        $consulta_sponsor_info->execute([':nombre' => $sponsor]);
        $sponsor_info = $consulta_sponsor_info->fetch(PDO::FETCH_ASSOC);

        $promo_tipo2 = '';

        if ($cupon_promo['tipo_promocion'] == '2') {
          $promo_tipo2 = 'x';
        };

        echo "
            <div class=\"promo\">
              <h2>Promoción del Cupón:</h2>
              <div class=\"promo_vars\" style=\"color: " . $cupon_promo['promo_color'] . "\">
                <span>" . $cupon_promo['promo_var1'] . " <span>" . $promo_tipo2 . "</span>" . $cupon_promo['promo_var2'] . "</span>
                <span>" . $cupon_promo['promo_var3'] . "</span>
              </div>
              <div class=\"promo_infos\">
                <span>" . $cupon_promo['promo_info1'] . "</span>
                <span>" . $cupon_promo['promo_info2'] . "</span>
              </div>
              <span class=\"fecha_vencimiento\">Cupón válido hasta: " . $cupon_promo['fecha_vencimiento'] . "</span>
            </div>
            <div class=\"sponsor_info\">
                <h2>Información del Sponsor:</h2>
                <div class=\"sponsors_name\">
                  <span class=\"logo\"><img src=\"" . substr($sponsor_info['logo'], 6) . "\" alt=\"LOGO\"></span>
                  <span class=\"label\">" . $sponsor_info['label'] . "</span>
                </div>

                <div class=\"sponsor_localizacion\">
                  <span class=\"sponsor_barrio\"><b>Sector:</b> " . $sponsor_info['barrio'] . "</span>
                  <span class=\"sponsor_direccion\"><b>Dirección:</b> " . $sponsor_info['direccion'] . "</span>
                </div>
            </div>
        ";

      }else {
        echo "<span class=\"codigo_no_valido\">Código NO válido</span>";
      };


    };


    if (isset($_POST["verificacion_requerida"])) {
      $qr_code = $_POST["verificacion_requerida"];

      $consulta_qr_code =	$conexion->prepare("SELECT * FROM qr_cupones WHERE codigoQR=:codigoQR ");
      $consulta_qr_code->execute([':codigoQR' => $qr_code]);
      $qr_code_respuesta = $consulta_qr_code->fetch(PDO::FETCH_ASSOC);

      $fecha_creacion = $qr_code_respuesta['fecha_creacion'];
      $fecha_validez = $qr_code_respuesta['fecha_validez'];
      $fecha_usado = date("Y/m/d");

      if (isset($qr_code_respuesta['sponsor_cupon'])) {
        $sponsor = $qr_code_respuesta['sponsor_cupon'];

        $consulta_codigo_sponsor =	$conexion->prepare("SELECT codigo_sponsor FROM sponsors WHERE nombre=:nombre ");
        $consulta_codigo_sponsor->execute([':nombre' => $sponsor]);
        $sponsor_codigo = $consulta_codigo_sponsor->fetch(PDO::FETCH_ASSOC);

        if ($sponsor_codigo['codigo_sponsor'] !== '') {
          echo "requiere_codigo";
        }else {

          $statement = $conexion->prepare(
           "INSERT INTO qr_cupones_usados (codigoQR, sponsor_cupon, fecha_creacion, fecha_validez, fecha_usado) VALUES (:codigoQR, :sponsor_cupon, :fecha_creacion, :fecha_validez, :fecha_usado)"
          );

          $statement->execute(array(
            ':codigoQR' => $qr_code,
            ':sponsor_cupon' => $sponsor,
            ':fecha_creacion' => $fecha_creacion,
            ':fecha_validez' => $fecha_validez,
            ':fecha_usado' => $fecha_usado
          ));

          $consulta_borrar_qr_cupon =	$conexion->prepare("DELETE FROM qr_cupones WHERE codigoQR = :codigoQR");
          $consulta_borrar_qr_cupon->execute([':codigoQR' => $qr_code]);
          echo "exito";
        };

      };

    };


    if (isset($_POST["codigo_sponsor_sent"]) && isset($_POST['qr_code_extra'])) {
      $sponsor_code = $_POST["codigo_sponsor_sent"];
      $qr_code = $_POST['qr_code_extra'];

      $consulta_qr_code =	$conexion->prepare("SELECT * FROM qr_cupones WHERE codigoQR=:codigoQR ");
      $consulta_qr_code->execute([':codigoQR' => $qr_code]);
      $qr_code_respuesta = $consulta_qr_code->fetch(PDO::FETCH_ASSOC);

      $fecha_creacion = $qr_code_respuesta['fecha_creacion'];
      $fecha_validez = $qr_code_respuesta['fecha_validez'];
      $fecha_usado = date("Y/m/d");

      if (isset($qr_code_respuesta['sponsor_cupon'])) {
        $sponsor = $qr_code_respuesta['sponsor_cupon'];

        $consulta_codigo_sponsor =	$conexion->prepare("SELECT codigo_sponsor, codigo_respuesta FROM sponsors WHERE nombre=:nombre ");
        $consulta_codigo_sponsor->execute([':nombre' => $sponsor]);
        $sponsor_codigo = $consulta_codigo_sponsor->fetch(PDO::FETCH_ASSOC);

        if ($sponsor_codigo['codigo_sponsor'] === $sponsor_code) {

          $statement = $conexion->prepare(
           "INSERT INTO qr_cupones_usados (codigoQR, sponsor_cupon, fecha_creacion, fecha_validez, fecha_usado) VALUES (:codigoQR, :sponsor_cupon, :fecha_creacion, :fecha_validez, :fecha_usado)"
          );

          $statement->execute(array(
            ':codigoQR' => $qr_code,
            ':sponsor_cupon' => $sponsor,
            ':fecha_creacion' => $fecha_creacion,
            ':fecha_validez' => $fecha_validez,
            ':fecha_usado' => $fecha_usado
          ));

          $consulta_borrar_qr_cupon =	$conexion->prepare("DELETE FROM qr_cupones WHERE codigoQR = :codigoQR");
          $consulta_borrar_qr_cupon->execute([':codigoQR' => $qr_code]);
          echo $sponsor_codigo['codigo_respuesta'];
        }else {
          echo "codigo_incorrecto";
        };

      };


    };



};

?>
