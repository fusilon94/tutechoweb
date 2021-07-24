<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
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

if (isset($_POST['agencia_id']) && isset($_POST['modo'])) {

  $agencia_id = $_POST['agencia_id'];
  $modo = $_POST['modo'];

  $consulta_pais_info =	$conexion_internacional->prepare("SELECT moneda, moneda_code FROM paises WHERE pais = :pais");
  $consulta_pais_info->execute([":pais" => $_COOKIE['tutechopais']]);
  $pais_info =	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

  if ($modo == 'revertir') {

      $consulta_facturas=	$conexion->prepare("SELECT id, fecha_impresion, tipo, referencia_inmueble, monto, numero_factura, codigo_control, numero_autorizacion FROM facturas WHERE impreso = 1 AND anulado = 1 AND agencia_id = :agencia_id");
      $consulta_facturas->execute([':agencia_id' => $agencia_id]);
      $facturas =	$consulta_facturas->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($modo == 'anular' || $modo == 'archivo') {

      $consulta_facturas=	$conexion->prepare("SELECT id, fecha_impresion, tipo, referencia_inmueble, monto, numero_factura, codigo_control, numero_autorizacion FROM facturas WHERE impreso = 1 AND anulado = 0 AND agencia_id = :agencia_id");
      $consulta_facturas->execute([':agencia_id' => $agencia_id]);
      $facturas =	$consulta_facturas->fetchAll(PDO::FETCH_ASSOC);
        
  };

  echo"
    <div class=\"cabecera_lista\">
      <div class=\"titles_wrap\">
          <span class=\"title_element col1\" style=\"min-width:9em\"><p>Fecha</p></span>
          <span class=\"title_element col2\" style=\"min-width:13em\"><p>Factura ID</p></span>
          <span class=\"title_element col3\" style=\"min-width:15em\"><p>Numero Factura</p></span>
          <span class=\"title_element col4\" style=\"min-width:9em\"><p>Monto</p></span>
          <span class=\"title_element col5\" style=\"min-width:13em\"><p>Codigo Control</p></span>
          <span class=\"title_element col6\" style=\"min-width:13em\"><p>Autorización</p></span>
          <span class=\"title_element col7\" style=\"min-width:11em\"><p>Tipo</p></span>
          <span class=\"title_element col8\" style=\"min-width:15em\"><p>Referencia Inmueble</p></span>
      </div>

      <hr class=\"barra_titulos\">
    </div>

    <div class=\"lista_wrap\">
  ";


  foreach ($facturas as $factura) {
    echo"
      <span class=\"lista_row\" id=\"" . $factura['id'] . "\">
        <p class=\"lista_col col1\" style=\"min-width:9em\">" . $factura['fecha_impresion'] . "</p>
        <p class=\"lista_col col2\" style=\"min-width:13em\">" . $factura['id'] . "</p>
        <p class=\"lista_col col3\" style=\"min-width:15em\">" . $factura['numero_factura'] . "</p>
        <p class=\"lista_col col4\" style=\"min-width:9em\">" . $factura['monto'] . $pais_info['moneda_code'] . "</p>
        <p class=\"lista_col col5\" style=\"min-width:13em\">" . $factura['codigo_control'] . "</p>
        <p class=\"lista_col col6\" style=\"min-width:13em\">" . $factura['numero_autorizacion'] . "</p>
        <p class=\"lista_col col7\" style=\"min-width:11em\">" . $factura['tipo'] . "</p>
        <p class=\"lista_col col8\" style=\"min-width:15em\">" . $factura['referencia_inmueble'] . "</p>
      </span>
    ";

  };

  echo"</div>";
};


?>
