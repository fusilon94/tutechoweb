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


    if (isset($_POST["barrio_sent"])) {
      $barrio = $_POST["barrio_sent"];

      $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789!@#$%^&*()'); // and any other characters
      shuffle($seed); // probably optional since array_is randomized; this may be redundant
      $rand = '';
      foreach (array_rand($seed, 15) as $k) $rand .= $seed[$k];

      $statement = $conexion->prepare(
       "INSERT INTO codigos_impresion_cupones (codigo, barrio) VALUES (:codigo, :barrio)"
      );

      $statement->execute(array(
        ':codigo' => $rand,
        ':barrio' => $barrio
      ));

      echo $rand;



    };






};

?>
