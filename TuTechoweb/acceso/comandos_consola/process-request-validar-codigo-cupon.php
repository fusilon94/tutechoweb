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


    if (isset($_POST['codigo_cupon_sent'])) {

      $codigo = $_POST['codigo_cupon_sent'];

      $consulta_codigo_cupon =	$conexion->prepare("SELECT barrio FROM codigos_impresion_cupones WHERE codigo=:codigo AND usado = 0");
      $consulta_codigo_cupon->execute([':codigo' => $codigo]);
      $codigo_cupon = $consulta_codigo_cupon->fetch(PDO::FETCH_ASSOC);

      echo $codigo_cupon['barrio'];

    };



};

?>
