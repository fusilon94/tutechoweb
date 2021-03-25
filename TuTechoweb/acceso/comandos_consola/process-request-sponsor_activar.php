<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX

    if (isset($_POST["sponsor_para_activar"]) && isset($_POST["pais_sent"])) {

      $sponsor_para_activar = $_POST["sponsor_para_activar"];
      $pais = $_POST["pais_sent"];

      // Conexion con la database
      $tutechodb = "tutechodb_" . $pais;

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

      $statement = $conexion->prepare(
       "UPDATE sponsors_borradores SET visibilidad = :visibilidad, validacion_admin = 1 WHERE nombre = :nombre"
      );

      $statement->execute(array(
        ':nombre' => $sponsor_para_activar,
        ':visibilidad' => 'visible'
      ));

      $consulta_activar_sponsor = $conexion->prepare('INSERT INTO sponsors(nombre, barrio, fecha_de_registro, agente_id, visibilidad, validacion_agente, validacion_admin, pagos_historial, fecha_vencimiento, responsable, responsable_contacto, categoria, label, logo, subtitulo, direccion, contacto, web, latitud, longitud, zoom, ilustracion, borde)
      SELECT nombre, barrio, fecha_de_registro, agente_id, visibilidad, validacion_agente, validacion_admin, pagos_historial, fecha_vencimiento, responsable, responsable_contacto, categoria, label, logo, subtitulo, direccion, contacto, web, latitud, longitud, zoom, ilustracion, borde FROM sponsors_borradores
      WHERE nombre = :nombre'
      );
      $consulta_activar_sponsor->execute([':nombre' => $sponsor_para_activar]);



      $consulta_sponsor_borrador_borrar =	$conexion->prepare("DELETE FROM sponsors_borradores WHERE nombre =:nombre");
      $consulta_sponsor_borrador_borrar->execute([':nombre' => $sponsor_para_activar]);//SE PASA EL LABEL Y LA CIUDAD

      echo "Sponsor activado exitosamente";

    };


};

?>
