<?php
$php_view_entry_control = "algunvalor";

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

try {
	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
	echo "Error: " . $e->getMessage();
};

$consulta_regiones =	$conexion->prepare("SELECT departamentos FROM regiones");
$consulta_regiones->execute();
$regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_COLUMN, 0);

$tutechodb_internacional = "tutechodb_internacional";

try {
  $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};

$consulta_pais_moneda =	$conexion_internacional->prepare("SELECT moneda, cambio_dolar FROM paises WHERE pais=:pais ");
$consulta_pais_moneda->execute(['pais' => $_COOKIE['tutechopais']]);//SE PASA LA REFERENCIA
$pais_moneda = $consulta_pais_moneda->fetch(PDO::FETCH_ASSOC);

$cambio = $pais_moneda['cambio_dolar'];

require 'renta_inmueble.view.php';
?>
