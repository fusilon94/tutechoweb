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


    if (isset($_POST['qr_list_sent'])) {

      $lista_QR_cupones = $_POST['qr_list_sent'];
      $fecha_actual = date("Y/m/d");

      foreach ($lista_QR_cupones as $QR_cupon) {

            $statement = $conexion->prepare(
             "INSERT INTO qr_cupones (codigoQR, sponsor_cupon, fecha_creacion, fecha_validez) VALUES (:codigoQR, :sponsor_cupon, :fecha_creacion, :fecha_validez)"
            );

            $statement->execute(array(
              ':codigoQR' => $QR_cupon[0],
              ':sponsor_cupon' => $QR_cupon[1],
              ':fecha_creacion' => $fecha_actual,
              ':fecha_validez' => $QR_cupon[2]
            ));

      };
      echo "todo ok";

    };


};

?>
