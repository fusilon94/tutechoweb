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


    if (isset($_POST["sponsor_para_inactivar"])) {

        $sponsor_nombre = $_POST["sponsor_para_inactivar"];

          $statement = $conexion->prepare(
           "UPDATE sponsors SET visibilidad = :visibilidad, cupon_agregado = 0, cupon_visible = 0, inactivacion_autorizacion = 0 WHERE nombre = :nombre"
          );

          $statement->execute(array(
            ':nombre' => $sponsor_nombre,
            ':visibilidad' => 'no_visible'
          ));

          $consulta_cupon_borrar =	$conexion->prepare("DELETE FROM cupones_sponsor WHERE sponsor = :sponsor");
          $consulta_cupon_borrar->execute([':sponsor' => $sponsor_nombre]);

          echo "Sponsor Inactivado Exitosamente";

    };

    if (isset($_POST["sponsor_para_reactivar"])) {

        $sponsor_nombre = $_POST["sponsor_para_reactivar"];

          $statement = $conexion->prepare(
           "UPDATE sponsors SET visibilidad = :visibilidad, reactivacion_autorizacion = 0 WHERE nombre = :nombre"
          );

          $statement->execute(array(
            ':nombre' => $sponsor_nombre,
            ':visibilidad' => 'visible'
          ));

          echo "Sponsor Reactivado Exitosamente";

    };


};

?>
