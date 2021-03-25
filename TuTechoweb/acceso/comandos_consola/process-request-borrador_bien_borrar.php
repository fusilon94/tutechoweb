<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
if( isset($_POST["referencia_sent"]) && isset($_POST['tabla_sent']) ){
    // Capture referencia que borrar
    $referencia = $_POST["referencia_sent"];
    $tabla = 'borradores_' . $_POST['tabla_sent'];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion reportarlo
    	echo "Error: " . $e->getMessage();
    };

    // Cambiar estado del bien (borrador)  a 'borrado'

    $consulta_sponsor_borrar =	$conexion->prepare("DELETE FROM $tabla WHERE referencia = :referencia");
    $consulta_sponsor_borrar->execute([':referencia' => $referencia]);

    echo "Borrador eliminado exitosamente";

}else {
  echo "Error";
}
?>
