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


      if (isset($_POST["sponsor_borrar_sent"])) {

        $sponsor_borrar = $_POST["sponsor_borrar_sent"];


        $consulta_sponsor_info =	$conexion->prepare("SELECT cupon_agregado FROM sponsors");
        $consulta_sponsor_info->execute();
        $sponsor_info	=	$consulta_sponsor_info->fetch(PDO::FETCH_ASSOC);

        $consulta_sponsor_borrar =	$conexion->prepare("DELETE FROM sponsors WHERE nombre = :nombre");
        $consulta_sponsor_borrar->execute([':nombre' => $sponsor_borrar]);

        if ($sponsor_info['cupon_agregado'] == 1) {

          $consulta_cupon_borrar =	$conexion->prepare("DELETE FROM cupones_sponsor WHERE sponsor = :sponsor");
          $consulta_cupon_borrar->execute([':sponsor' => $sponsor_borrar]);

        };


        echo "Sponsor borrado exitosamente";


      };




};

?>
