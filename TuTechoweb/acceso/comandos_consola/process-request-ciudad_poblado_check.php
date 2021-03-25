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


      if (isset($_POST["ciudad_sent"])) {

        $ciudad_chosen = $_POST["ciudad_sent"];

        $consulta_ciudad_poblado =	$conexion->prepare("SELECT ciudad_poblado FROM ciudades WHERE ciudad=:ciudad");
        $consulta_ciudad_poblado->execute(['ciudad' => $ciudad_chosen]);//SE PASA LA CIUDAD
        $ciudad_poblado = $consulta_ciudad_poblado->fetch(PDO::FETCH_ASSOC);

        if ($ciudad_poblado['ciudad_poblado'] == 'c') {
          echo "ciudad";
        }else {
          echo "poblado";
        }

      };




};

?>
