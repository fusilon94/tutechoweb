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

$consulta_regiones =	$conexion->prepare("SELECT departamentos, agencias FROM regiones WHERE agencias > 0");
$consulta_regiones->execute();
$regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_ASSOC);

$agencias_total = 0;

foreach ($regiones as $departamento) {
  $agencias_total = $agencias_total + $departamento['agencias'];
};

require 'agencias.view.php';
?>
