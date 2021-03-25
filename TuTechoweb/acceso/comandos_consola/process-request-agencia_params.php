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

    $tutecho_db_internacional = "tutechodb_internacional";
   
    try {
      $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutecho_db_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $consulta_anticretico_existe =	$conexion_internacional->prepare("SELECT anticretico_existe FROM paises WHERE pais = :pais");
    $consulta_anticretico_existe->execute([':pais' => $_COOKIE['tutechopais']]);
    $anticretico_existe	=	$consulta_anticretico_existe->fetch(PDO::FETCH_ASSOC);


    if (isset($_POST["agenciaChoice"])) {
      $agencia_id = $_POST["agenciaChoice"];

      $consulta_info_agencia =	$conexion->prepare("SELECT * FROM agencias WHERE id=:id ");
      $consulta_info_agencia->execute([':id' => $agencia_id]);//SE PASA EL NOMBRE DEL SPONSOR
      $info_agencia = $consulta_info_agencia->fetch(PDO::FETCH_ASSOC);

      echo"<div class=\"modos_container\">

        <h2>Elija el \"Modo\" de trabajo que mejor se adapte a su situacion actual</h2>";


        $count = 1;
        while ($count <= 5) {
          if ($info_agencia['modo_de_trabajo'] == $count) {
            echo "<span id=\"modo" . $count . "\" class=\"modo_btn activo\" name=\"" . $count . "\">Modo " . $count . "</span>";
          }else {
            echo "<span id=\"modo" . $count . "\" class=\"modo_btn\" name=\"" . $count . "\">Modo " . $count . "</span>";
          };

          $count++;
        };

      echo "<input id=\"modo_input\" type=\"hidden\" name=\"modo_input\" value=\"" . $info_agencia['modo_de_trabajo'] . "\">
      <div class=\"explicacion_modo\">
        <span class=\"modo_text text_modo1\"><b>MODO 1:</b> Cuando la DEMANDA es ALTA y la OFERTA tambien es ALTA</span>
        <span class=\"modo_text text_modo2\"><b>MODO 2:</b> Cuando la DEMANDA es ALTA pero la OFERTA es BAJA</br>(Si se tiene control del Mercado, usar Modo 1)</span>
        <span class=\"modo_text text_modo3\"><b>MODO 3:</b> Cuando la DEMANDA y la OFERTA son ambas REGULARES</span>
        <span class=\"modo_text text_modo4\"><b>MODO 4:</b> Cuando la DEMANDA es BAJA y la OFERTA es ALTA</span>
        <span class=\"modo_text text_modo5\"><b>MODO 5:</b> Cuando la DEMANDA es BAJA y la OFERTA tambien es BAJA</br>(Si se tiene control del Mercado, usar Modo 4)</span>
      </div>";

      echo"</div>

      <div class=\"parametros_container\">

        <div class=\"parametros_sub_container\">
          <h2>Capacidad de compra promedio de:</h2>

          <div class=\"parametro_individual\">
            <label for=\"compra_casa\"><i class=\"fa fa-home\"></i> Casas</label>
            <input id=\"compra_casa\" value=\"";
            if($info_agencia['cap_compra_casa'] > 0){echo $info_agencia['cap_compra_casa'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"compra_casa\" class=\"input_obligatorio_spinner\">
          </div>
          <div class=\"parametro_individual\">
            <label for=\"compra_departamento\"><i class=\"fa fa-building\"></i> Departamentos</label>
            <input id=\"compra_departamento\" value=\"";
            if($info_agencia['cap_compra_departamento'] > 0){echo $info_agencia['cap_compra_departamento'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"compra_departamento\" class=\"input_obligatorio_spinner\">
          </div>
          <div class=\"parametro_individual\">
            <label for=\"compra_local\"><i class=\"fa fa-shopping-bag\"></i> Locales</label>
            <input id=\"compra_local\" value=\"";
            if($info_agencia['cap_compra_local'] > 0){echo $info_agencia['cap_compra_local'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"compra_local\" class=\"input_obligatorio_spinner\">
          </div>
          <div class=\"parametro_individual\">
            <label for=\"compra_terreno\"><i class=\"fa fa-tree\"></i> Terrenos</label>
            <input id=\"compra_terreno\" value=\"";
            if($info_agencia['cap_compra_terreno'] > 0){echo $info_agencia['cap_compra_terreno'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"compra_terreno\" class=\"input_obligatorio_spinner\">
          </div>
        </div>
        <div class=\"parametros_sub_container\">
          <h2>Capacidad de alquiler promedio de:</h2>

          <div class=\"parametro_individual\">
            <label for=\"renta_casa\"><i class=\"fa fa-home\"></i> Casas</label>
            <input id=\"renta_casa\" value=\"";
            if($info_agencia['cap_renta_casa'] > 0){echo $info_agencia['cap_renta_casa'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"renta_casa\" class=\"input_obligatorio_spinner\">
          </div>
          <div class=\"parametro_individual\">
            <label for=\"renta_departamento\"><i class=\"fa fa-building\"></i> Departamentos</label>
            <input id=\"renta_departamento\" value=\"";
            if($info_agencia['cap_renta_departamento'] > 0){echo $info_agencia['cap_renta_departamento'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"renta_departamento\" class=\"input_obligatorio_spinner\">
          </div>
          <div class=\"parametro_individual\">
            <label for=\"renta_local\"><i class=\"fa fa-shopping-bag\"></i> Locales</label>
            <input id=\"renta_local\" value=\"";
            if($info_agencia['cap_renta_local'] > 0){echo $info_agencia['cap_renta_local'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"renta_local\" class=\"input_obligatorio_spinner\">
          </div>
          <div class=\"parametro_individual\">
            <label for=\"renta_terreno\"><i class=\"fa fa-tree\"></i> Terrenos</label>
            <input id=\"renta_terreno\" value=\"";
            if($info_agencia['cap_renta_terreno'] > 0){echo $info_agencia['cap_renta_terreno'];};
            echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"renta_terreno\" class=\"input_obligatorio_spinner\">
          </div>
        </div>";

        if ($anticretico_existe['anticretico_existe'] == 1) {
          echo"
            <div class=\"parametros_sub_container\">
              <h2>Anticretico promedio</h2>

              <div class=\"parametro_individual\">
                <label for=\"anticretico\">Porcentage del Precio de Venta (%)</label>
                <input id=\"anticretico\" value=\"";
                if($info_agencia['cap_anticretico'] > 0){echo $info_agencia['cap_anticretico'];};
                echo"\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"anticretico\" class=\"input_obligatorio_spinner\">
              </div>
            </div>
          ";
        };
        

      echo "</div>
      <div class=\"registrar_btn_container\">
        <span class=\"registrar_btn\">Registrar</span>
      </div>";

    };


};

?>
