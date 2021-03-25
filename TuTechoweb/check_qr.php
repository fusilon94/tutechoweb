<?php
$php_view_entry_control = "algunvalor";

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

try {
	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
	echo "Error: " . $e->getMessage();
};


require 'check_qr.view.php';
?>
