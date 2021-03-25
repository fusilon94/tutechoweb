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


    if (isset($_POST["nombre_sponsor_sent"])) {
      $nombre_sponsor = $_POST["nombre_sponsor_sent"];
      $nivel_acceso = $_SESSION['nivel_acceso'];
      $array_acceso = [1,11];


      $consulta_info_sponsor =	$conexion->prepare("SELECT * FROM sponsors WHERE nombre=:nombre ");
      $consulta_info_sponsor->execute([':nombre' => $nombre_sponsor]);//SE PASA EL NOMBRE DEL SPONSOR
      $info_sponsor = $consulta_info_sponsor->fetch(PDO::FETCH_ASSOC);

      $categorias_borrador = array(1 => 'Restaurantes', 2 => 'Bares & Cafés', 3 => 'Bienestar', 4 => 'Salud');

      echo "
      <div class=\"previsualizacion_container\">
        <div class=\"popup_sponsor\" style=\"background-color: " . $info_sponsor['borde'] . ";\">
            <span class=\"popup_sponsor_cerrar fa fa-times\"></span>
            <span class=\"popup_sponsor_illustration\" style=\"background-image: url(" . $info_sponsor['ilustracion'] . ");\"></span>
            <div class=\"popup_sponsor_info_container\">
              <div class=\"popup_sponsor_mapa\">
                  <div id=\"mapid_sponsor\" style=\"height:13em; width:100%;\"></div>
                  <input type=\"hidden\" name=\"mapa_sponsor_coordenada_lat\" id=\"mapa_sponsor_coordenada_lat\" class=\"panel_MAPA\" value=\"" . $info_sponsor['latitud'] . "\">
                  <input type=\"hidden\" name=\"mapa_sponsor_coordenada_lng\" id=\"mapa_sponsor_coordenada_lng\" class=\"panel_MAPA\" value=\"" . $info_sponsor['longitud'] . "\">
                  <input type=\"hidden\" name=\"mapa_sponsor_zoom\" id=\"mapa_sponsor_zoom\" class=\"panel_MAPA\" value=\"" . $info_sponsor['zoom'] . "\">
              </div>
              <div class=\"popup_sponsor_info\">
                <div class=\"popup_sponsor_titulo\">
                  <img class=\"popup_sponsor_img\" src=\"" . $info_sponsor['logo'] . "\" alt=\"" . $info_sponsor['label'] . " Logo\">
                  <label class=\"popup_sponsor_label\">" . $info_sponsor['label']  . "</label>
                </div>
                <span class=\"popup_sponsor_descripcion\">- " . $info_sponsor['subtitulo']  . " -</span>
                <span class=\"popup_sponsor_direccion fa fa-map-marker\">" . $info_sponsor['direccion'] . "</span>
                <span class=\"popup_sponsor_contacto fa fa-phone\">" . $info_sponsor['contacto'] . "</span>
                <span class=\"popup_sponsor_web fa fa-envelope\">" . $info_sponsor['web'] . "</span>
              </div>
            </div>
          </div>

          <div class=\"info_adicional_container\">
            <h2>Información Adicional:</h2>
            <div class=\"elemento_info_acicional_barrio\"><span class=\"info_label\">Barrio:</span><span class=\"info_text\">" . $info_sponsor['barrio'] . "</span></div>
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Categoría:</span><span class=\"info_text\">" . $categorias_borrador[$info_sponsor['categoria']] . "</span></div>
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Fecha de Vencimiento:</span><span class=\"info_text\">" . $info_sponsor['fecha_vencimiento'] . "</span></div>
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Registrador ID:</span><span class=\"info_text\">" . $info_sponsor['agente_id'] . "</span></div>
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Fecha de Regístro:</span><span class=\"info_text\">" . $info_sponsor['fecha_de_registro'] . "</span></div>
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Responsable:</span><span class=\"info_text\">" . $info_sponsor['responsable'] . "</span></div>
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Contacto:</span><span class=\"info_text\">" . $info_sponsor['responsable_contacto'] . "</span></div>
            ";

            if ($info_sponsor['cupon_agregado'] == 1) {

              $consulta_info_cupon =	$conexion->prepare("SELECT fecha_vencimiento FROM cupones_sponsor WHERE sponsor=:sponsor ");
              $consulta_info_cupon->execute([':sponsor' => $nombre_sponsor]);//SE PASA EL NOMBRE DEL SPONSOR
              $info_cupon = $consulta_info_cupon->fetch(PDO::FETCH_ASSOC);

              echo "
                  <div class=\"elemento_info_acicional\"><span class=\"info_label\">Cupón promoción:</span><span class=\"info_text\">SI</span></div>
                  <div class=\"elemento_info_acicional\"><span class=\"info_label\">Validez cupón:</span><span class=\"info_text\">" . $info_cupon['fecha_vencimiento'] . "</span></div>
              ";
              if ($info_sponsor['codigo_sponsor'] !== '' && in_array($nivel_acceso, $array_acceso) !== false) {
                echo "
                    <div class=\"elemento_info_acicional\"><span class=\"info_label\">Código Sponsor:</span><span class=\"info_text\">" . $info_sponsor['codigo_sponsor'] . "</span></div>
                    <div class=\"elemento_info_acicional\"><span class=\"info_label\">Código Respuesta:</span><span class=\"info_text\">" . $info_sponsor['codigo_respuesta'] . "</span></div>
                ";
              };
            }else {
              echo "
                  <div class=\"elemento_info_acicional\"><span class=\"info_label\">Cupón promoción:</span><span class=\"info_text\">NO</span></div>
                  <div class=\"elemento_info_acicional\"><span class=\"info_label\">Validez cupón:</span><span class=\"info_text\"></span></div>
              ";
            };

      echo "
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Editor ID:</span><span class=\"info_text\">" . $info_sponsor['id_ultima_edicion'] . "</span></div>
            <div class=\"elemento_info_acicional\"><span class=\"info_label\">Última edición:</span><span class=\"info_text\">" . $info_sponsor['fecha_ultima_edicion'] . "</span></div>

          </div>
      </div>
    
        <script src=\"../../js/sponsor_popup_mapa_OSM.js\"></script>
        
        ";


    };


};

?>
