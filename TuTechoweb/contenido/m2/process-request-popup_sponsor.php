<?php

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

      $consulta_info_sponsor =	$conexion->prepare("SELECT * FROM sponsors WHERE nombre=:nombre ");
      $consulta_info_sponsor->execute(['nombre' => $nombre_sponsor]);//SE PASA EL NOMBRE DEL SPONSOR
      $info_sponsor = $consulta_info_sponsor->fetch(PDO::FETCH_ASSOC);

      echo "<div class=\"popup_sponsor\" style=\"background-color: " . $info_sponsor['borde'] . ";\">
          <span class=\"popup_sponsor_cerrar fa fa-times\"></span>
          <span class=\"popup_sponsor_illustration\" style=\"background-image: url(" . $info_sponsor['ilustracion'] . ");\"></span>
          <div class=\"popup_sponsor_info_container\">
            <div class=\"popup_sponsor_mapa\">
                <div id=\"mapid_sponsor\" style=\"height:13em; width:100%;\"></div>
                <span class=\"attributions_mapa_popup_sponsor\">
                Map data &copy; <a href=\"https://www.openstreetmap.org/\">OpenStreetMap</a> contributors, <a href=\"https://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, Imagery © <a href=\"https://www.mapbox.com/\">Mapbox</a>
                </span>
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
        <script src=\"../../js/sponsor_popup_mapa_OSM.js\"></script>
        ";


    };


};

?>
