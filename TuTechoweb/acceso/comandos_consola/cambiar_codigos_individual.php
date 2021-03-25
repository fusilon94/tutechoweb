<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
	if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
	  header('Location: cerrar_session.php');
	};

}else {
	header('Location: ../login.php');
};
  

$nivel_acceso = $_SESSION['nivel_acceso'];
$array_acceso = [1,2,3,4,5,6,7,8,10,11,12];
if (in_array($nivel_acceso, $array_acceso) !== false){
  //Todo OK
}
else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

require 'cambiar_codigos_individual.view.php';

?>
