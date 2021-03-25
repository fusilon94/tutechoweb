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


    if (isset($_POST["barrio_sent"]) && isset($_POST['tipo_sent'])) {
      $barrio = $_POST["barrio_sent"];
      $tabla = $_POST['tipo_sent'];
      $column = '';
      if ($tabla == 'ciudades') {
        $column = 'ciudad';
      }else {
        $column = 'barrio';
      };

      $consulta_qr_code =	$conexion->prepare("SELECT activacion_sponsors FROM $tabla WHERE $column=:columna ");
      $consulta_qr_code->execute([':columna' => $barrio]);
      $qr_code_respuesta = $consulta_qr_code->fetch(PDO::FETCH_ASSOC);

      if ($qr_code_respuesta['activacion_sponsors'] == 0) {
        echo "inactivo";
      };
      if ($qr_code_respuesta['activacion_sponsors'] == 1) {
        echo "activo";
      };



    };






};

?>
