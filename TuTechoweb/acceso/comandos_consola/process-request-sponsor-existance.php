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
      $ciudad_sent = $_POST["ciudad_sent"];
      $label_sent = $_POST["label_sent"];

      $consulta_sponsor_existe =	$conexion->prepare("SELECT direccion, visibilidad FROM sponsors WHERE label=:label AND barrio=:barrio ");
      $consulta_sponsor_existe->execute(['label' => $label_sent, 'barrio' => $ciudad_sent]);//SE PASA EL LABEL Y LA CIUDAD
      $sponsor_existe = $consulta_sponsor_existe->fetchAll();

      if ($sponsor_existe !== '') {
        echo json_encode($sponsor_existe);
      };

    };


    if (isset($_POST["barrio_sent"])) {
      $barrio_sent = $_POST["barrio_sent"];
      $label_sent = $_POST["label_sent"];

      $consulta_sponsor_existe =	$conexion->prepare("SELECT direccion, visibilidad FROM sponsors WHERE label=:label AND barrio=:barrio ");
      $consulta_sponsor_existe->execute(['label' => $label_sent, 'barrio' => $barrio_sent]);//SE PASA EL LABEL Y EL BARRIO
      $sponsor_existe = $consulta_sponsor_existe->fetchAll();

      if ($sponsor_existe !== '') {
        echo json_encode($sponsor_existe);
      };

    };



};

?>
