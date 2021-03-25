<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    

    if (isset($_POST["cupon_para_activar"]) && isset($_POST['pais_sent'])) {

      $cupon_para_activar = $_POST["cupon_para_activar"];
      $pais = $_POST['pais_sent'];

      // Conexion con la database
      $tutechodb = "tutechodb_" . $pais;

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };


      $statement = $conexion->prepare(
       "UPDATE sponsors SET cupon_visible = 1 WHERE nombre = :nombre"
      );

      $statement->execute(array(
        ':nombre' => $cupon_para_activar
      ));

      echo "Cupón activado exitosamente";

    };


};

?>
