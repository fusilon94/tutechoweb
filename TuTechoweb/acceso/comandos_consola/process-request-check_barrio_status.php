<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


    if (isset($_POST["barrio_sent"]) && isset($_POST['tipo_sent'])) {
      $barrio = $_POST["barrio_sent"];
      $tabla = $_POST['tipo_sent'];
      $column = '';
      if ($tabla == 'ciudades') {
        $column = 'ciudad';
      }else {
        $column = 'barrio';
      };

      $consulta_qr_code =	$conexion->prepare("SELECT activacion_sponsors FROM $tabla WHERE $column=:columna ");
      $consulta_qr_code->execute([':columna' => $barrio]);
      $qr_code_respuesta = $consulta_qr_code->fetch(PDO::FETCH_ASSOC);

      $consulta_sponsors_barrio =	$conexion->prepare("SELECT categoria, cupon_visible FROM sponsors WHERE barrio=:barrio ");
      $consulta_sponsors_barrio->execute([':barrio' => $barrio]);
      $sponsors_barrio = $consulta_sponsors_barrio->fetchAll(PDO::FETCH_ASSOC);

      $estado_barrio = '';
      $estado_barrio_btn = '';
      if ($qr_code_respuesta['activacion_sponsors'] == 0) {
        $estado_barrio = 'Inactivo';
        $estado_barrio_btn = 'Activar';
      }else {
        $estado_barrio = 'Activo';
        $estado_barrio_btn = 'Inactivar';
      };

      $restaurantes = 0;
      $bares = 0;
      $bienestar = 0;
      $salud = 0;
      $cupones = 0;
      $total_sponsors = count($sponsors_barrio);

      foreach ($sponsors_barrio as $sponsors) {
        if ($sponsors['categoria'] == 1) {
          $restaurantes++;
        };
        if ($sponsors['categoria'] == 2) {
          $bares++;
        };
        if ($sponsors['categoria'] == 3) {
          $bienestar++;
        };
        if ($sponsors['categoria'] == 4) {
          $salud++;
        };
        if ($sponsors['cupon_visible'] == 1) {
          $cupones++;
        };
      };

      echo "
      <div class=\"resultados\">
        <div class=\"btn_activar_barrio " . $estado_barrio . "\">" . $estado_barrio_btn . "</div>
        <div class=\"info_barrio\">
          <span><b>Estado Actual:</b> " . $estado_barrio . "</span>
          <span><b>Total Sponsors Registrados:</b> " . $total_sponsors . "</span>
          <span>- <b>" . $restaurantes . "</b> en 'Restaurantes'</span>
          <span>- <b>" . $bares . "</b> en 'Bares & Cafés'</span>
          <span>- <b>" . $bienestar . "</b> en 'Bienestar'</span>
          <span>- <b>" . $salud  . "</b> en 'Salud'</span>
          <span><b>Promociones:</b> <b>" . $cupones . "</b> Cupones Validados</span>
        </div>
      </div>
      ";




    };






};

?>
