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


    if (isset($_POST["cupon_sent"])) {
      $nombre_sponsor = $_POST["cupon_sent"];

      $consulta_info_sponsor =	$conexion->prepare("SELECT label, logo, direccion, contacto, web, responsable, responsable_contacto, barrio, categoria, cupon_visible FROM sponsors WHERE nombre=:nombre ");
      $consulta_info_sponsor->execute(['nombre' => $nombre_sponsor]);//SE PASA EL NOMBRE DEL SPONSOR
      $info_sponsor = $consulta_info_sponsor->fetch(PDO::FETCH_ASSOC);

      $consulta_info_cupon =	$conexion->prepare("SELECT * FROM cupones_sponsor WHERE sponsor=:sponsor ");
      $consulta_info_cupon->execute(['sponsor' => $nombre_sponsor]);//SE PASA EL NOMBRE DEL SPONSOR
      $info_cupon = $consulta_info_cupon->fetch(PDO::FETCH_ASSOC);

      $font_style = '';
      if ($info_cupon['promo_tipo_texto'] == 'italic') {
        $font_style = "font-style: italic";
      }else {
        $font_style = "font-weight: " . $info_cupon['promo_tipo_texto']. "";
      };

      $promo_tipo2_x = '';
      if ($info_cupon['tipo_promocion'] == '2') {
        $promo_tipo2_x = 'x';
      }

      $promo_estado = '';
      if ($info_sponsor['cupon_visible'] == 0) {
        $promo_estado = "<b style='color: rgb(226, 65, 65)'>Activación Pendiente</b>";
      }else {
        $promo_estado = '<b>Activo</b>';
      };


      echo "
      <div class=\"previsualizacion_container\">

            <div class=\"popup_sponsor popup_visible\" style=\"background-color:" . $info_cupon['borde'] . ";\">
              <div class=\"validez_cupon\">Oferta NO acumulable // Cupón Válido hasta: <span>" . $info_cupon['fecha_vencimiento'] . "<span></div>
              <div class=\"popup_sponsor_info_container\">
                <div class=\"ilustracion_fondo_container\">
                  <span class=\"ilustracion_fondo\" style=\"background-image: url('" . $info_cupon['ilustracion_fondo'] . "');\"></span>
                  <span class=\"ilustracion_filtro\"></span>
                </div>
                <div class=\"popup_promo_zona\">
                  <div class=\"info_promo_1_container\" style=\"top: " . $info_cupon['promo_top'] . "; left: " . $info_cupon['promo_left'] . "; transform: rotate(" . $info_cupon['promo_inclinacion'] . "deg); flex-direction: " . $info_cupon['promo_var4'] . ";
                  color: " . $info_cupon['promo_color'] . ";\">
                    <div class=\"promo_cuadro1\" style=\"" . $font_style . "\">
                      <span class=\"promo_cuadro1_texto1\" style=\"font-size: " . $info_cupon['promo_font_size1'] . ";\">" . $info_cupon['promo_var1'] . "</span>
                      <span class=\"promo_tipo_2_x\">" . $promo_tipo2_x . "</span>
                      <span class=\"promo_cuadro1_texto2\" style=\"font-size: " . $info_cupon['promo_font_size1'] . ";\">" . $info_cupon['promo_var2'] . "</span>
                    </div>
                    <div class=\"promo_cuadro2\" style=\"" . $font_style . "\">
                      <span class=\"promo_cuadro2_texto1\" style=\"font-size: " . $info_cupon['promo_font_size2'] . ";\">" . $info_cupon['promo_var3'] . "</span>
                    </div>
                  </div>
                  <div class=\"info_promo_2_container\" style=\"padding-left: " . $info_cupon['promo_info_posicion'] . "; font-size: " . $info_cupon['promo_info_font_size'] . ";\">
                    <span class=\"promo_info_texto1\">" . $info_cupon['promo_info1'] . "</span>
                    <span class=\"promo_info_texto2\">" . $info_cupon['promo_info2'] . "</span>
                  </div>
                </div>
                <div class=\"popup_sponsor_info\" style=\"right: " . $info_cupon['info_sponsor_right'] . "\">
                  <div class=\"popup_sponsor_titulo\">
                    <span id=\"logo_preview1\" class=\"logo_preview filled\" style=\"background-image: url('" . $info_sponsor['logo'] . "'); background-size: contain\">
                    </span>
                    <label>" . $info_sponsor['label'] . "</label>
                  </div>
                  <span class=\"popup_sponsor_direccion fa fa-map-marker\">" . $info_sponsor['direccion'] . "</span>
                  <span class=\"popup_sponsor_contacto fa fa-phone\">" . $info_sponsor['contacto'] . "</span>
                  <span class=\"popup_sponsor_web fa fa-envelope\">" . $info_sponsor['web'] . "</span>
                </div>
              </div>
            </div>

            <div class=\"info_adicional_container\">
              <h2>Información Adicional:</h2>
              <div class=\"elemento_info_acicional_barrio\"><span class=\"info_label\">Barrio:</span><span class=\"info_text\">" . $info_sponsor['barrio'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Estado:</span><span class=\"info_text\">" . $promo_estado . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Fecha de Validez Cupón:</span><span class=\"info_text\">" . $info_cupon['fecha_vencimiento'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Registrador ID:</span><span class=\"info_text\">" . $info_cupon['agente_id'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Fecha de Regístro:</span><span class=\"info_text\">" . $info_cupon['fecha_registro'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Responsable:</span><span class=\"info_text\">" . $info_sponsor['responsable'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Contacto:</span><span class=\"info_text\">" . $info_sponsor['responsable_contacto'] . "</span></div>

            </div>
        </div>
        ";


    };


};

?>
