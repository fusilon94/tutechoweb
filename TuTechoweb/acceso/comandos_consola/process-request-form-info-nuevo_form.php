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


    if (isset($_POST["referencia_sent"]) && isset($_POST["tabla_sent"])) {
      $referencia = $_POST["referencia_sent"];
      $tabla = "borradores_" . $_POST["tabla_sent"];

      $consulta_info_form =	$conexion->prepare("SELECT estado, tipo_bien, exclusivo, anticretico, pre_venta, pais, ciudad, departamento, barrio, direccion, direccion_complemento, propietario_nombre, propietario_apellido, propietario_telefono FROM $tabla WHERE referencia=:referencia ");
      $consulta_info_form->execute([':referencia' => $referencia]);//SE PASA EL NOMBRE DEL SPONSOR
      $info_form = $consulta_info_form->fetch(PDO::FETCH_ASSOC);

      $exclusivo = 'ESTANDAR';
      $anticretico = 'NO';
      $pre_venta = 'NO';

      if ($info_form['exclusivo'] == 1) {
        $exclusivo = "EXCLUSIVO";
      };

      if ($info_form['anticretico'] == 1) {
        $anticretico = 'SI';
      };

      if ($info_form['pre_venta'] == 1) {
        $pre_venta = 'SI';
      };



      echo "
      <div class=\"previsualizacion_container\">
            <div class=\"info_adicional_container\">
              <h2>Información General</h2>
              <div class=\"elemento_info_acicional_barrio\"><span class=\"info_label\">Estado:</span><span class=\"info_text\">" . $info_form['estado'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Tipo Bien:</span><span class=\"info_text\">" . $info_form['tipo_bien'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Referencia:</span><span class=\"info_text\">" . $referencia . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">País:</span><span class=\"info_text\">" . $info_form['pais'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Departamento:</span><span class=\"info_text\">" . $info_form['departamento'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Ciudad:</span><span class=\"info_text\">" . $info_form['ciudad'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Barrio:</span><span class=\"info_text\">" . $info_form['barrio'] . "</span></div>
              <div class=\"elemento_info_acicional_barrio\"><span class=\"info_label\">Dirección:</span><span class=\"info_text\">" . $info_form['direccion'] . "</span></div>
              <div class=\"elemento_info_acicional_barrio\"><span class=\"info_label\">Complemento:</span><span class=\"info_text\">" . $info_form['direccion_complemento'] . "</span></div>
              <div class=\"elemento_info_acicional_barrio\"><span class=\"info_label\">Contrato:</span><span class=\"info_text\">" . $exclusivo . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Anticretico:</span><span class=\"info_text\">" . $anticretico . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Pre-Venta:</span><span class=\"info_text\">" . $pre_venta . "</span></div>
              <div class=\"elemento_info_acicional_barrio\"><span class=\"info_label\">Propietario:</span><span class=\"info_text\">" . $info_form['propietario_nombre'] . ' ' . $info_form['propietario_apellido'] . "</span></div>
              <div class=\"elemento_info_acicional\"><span class=\"info_label\">Contacto:</span><span class=\"info_text\">" . $info_form['propietario_telefono'] . "</span></div>

            </div>
        </div>

        ";


    };


};

?>
