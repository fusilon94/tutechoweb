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


    if (isset($_POST["sponsor_para_borrar"])) {

      $sponsor_para_borrar = $_POST["sponsor_para_borrar"];

      $consulta_sponsor_borrar =	$conexion->prepare("DELETE FROM sponsors_borradores WHERE nombre =:nombre");
      $consulta_sponsor_borrar->execute(['nombre' => $sponsor_para_borrar]);//SE PASA EL LABEL Y LA CIUDAD

      echo "Borrador Sponsor eliminado exitosamente";

    };


};

?>
