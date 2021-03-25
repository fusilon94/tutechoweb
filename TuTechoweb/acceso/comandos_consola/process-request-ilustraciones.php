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


      if (isset($_POST["categoria_sent"])) {
        $categoria_received = $_POST["categoria_sent"];

        $consulta_ilustraciones =	$conexion->prepare("SELECT nombre, url FROM logos_predeterminados WHERE categoria=:categoria AND tipo='ilustracion' ");
        $consulta_ilustraciones->execute(['categoria' => $categoria_received]);//SE PASA LA CATEGORIA
        $ilustraciones = $consulta_ilustraciones->fetchAll();
      };

      // print_r($sponsor_logo);
      foreach ($ilustraciones as $ilustracion) {
        echo "
          <span class=\"ilustracion\"><img src=\"" . $ilustracion['url'] . "\" alt=\"" . $ilustracion['nombre'] . "\"></span>
        ";
      };


};

?>
