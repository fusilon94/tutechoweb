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


  if (isset($_POST["referencia_sent"]) && isset($_POST["tabla_sent"]) && isset($_POST["action_sent"])) {
    $referencia = $_POST["referencia_sent"];
    $tabla = $_POST["tabla_sent"];
    $action = $_POST["action_sent"];

    if ($action == 'Inactivar') {

        $statement = $conexion->prepare(
          "UPDATE $tabla SET inactivo = 1, inactivacion_autorizacion = 0 WHERE referencia = :referencia");

        $statement->execute(array(
          ':referencia' => $referencia
        ));
    };

    if ($action == 'Reactivar') {
      $statement = $conexion->prepare(
        "UPDATE $tabla SET inactivo = 0, reactivacion_autorizacion = 0 WHERE referencia = :referencia");

      $statement->execute(array(
        ':referencia' => $referencia
      ));

    };

    echo "exito";
  };


};

?>
