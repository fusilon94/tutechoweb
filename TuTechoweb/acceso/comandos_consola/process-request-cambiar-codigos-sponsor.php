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


      if (isset($_POST["sponsor_sent"])) {

        $sponsor = $_POST["sponsor_sent"];

        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                   .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                   .'0123456789@#$%&'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant

        $nuevo_codigo_sponsor = '';
        foreach (array_rand($seed, 6) as $k) $nuevo_codigo_sponsor .= $seed[$k];
        $nuevo_codigo_respuesta = '';
        foreach (array_rand($seed, 6) as $k) $nuevo_codigo_respuesta .= $seed[$k];


        $statement = $conexion->prepare(
         "UPDATE sponsors SET codigo_sponsor = :codigo_sponsor, codigo_respuesta = :codigo_respuesta WHERE nombre = :nombre"
        );

        $statement->execute(array(
          ':nombre' => $sponsor,
          ':codigo_sponsor' => $nuevo_codigo_sponsor,
          ':codigo_respuesta' => $nuevo_codigo_respuesta
        ));



        echo "Nuevos Códigos Creados Exitosamente";


      };




};

?>
