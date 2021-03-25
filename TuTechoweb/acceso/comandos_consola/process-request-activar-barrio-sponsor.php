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


    if (isset($_POST["barrio_sent"]) && isset($_POST['tipo_sent']) && isset($_POST['accion_sent'])) {

      $barrio = $_POST["barrio_sent"];
      $tabla = $_POST['tipo_sent'];
      $column = '';
      if ($tabla == 'ciudades') {
        $column = 'ciudad';
      }else {
        $column = 'barrio';
      };
      $accion = $_POST['accion_sent'];
      $mensaje_exito = '';
      if ($accion == 1) {
        $mensaje_exito = 'Activada';
      }else {
        $mensaje_exito = 'Inactivada';
      };
      $safe_table_to_use = '';

      if ($tabla == 'ciudades' || $tabla == 'barrios') {
        $safe_table_to_use = $tabla;
      };

      if ($safe_table_to_use !== '') {

            $statement = $conexion->prepare(
             "UPDATE $safe_table_to_use SET activacion_sponsors = :activacion_sponsors WHERE $column=:columna"
            );

            $statement->execute(array(
              ':activacion_sponsors' => $accion,
              ':columna' => $barrio
            ));

            echo "Función Sponsors " . $mensaje_exito . " exitosamente";

      }else {
        echo "error de conexion - cambios no realizados";
      };



    };






};

?>
