<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    // Recuperar informacion de ciudades tipo C

    $consulta_agencia_id =	$conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
    $consulta_agencia_id->execute([':usuario' => $_SESSION['usuario']]);
    $agencia_id	=	$consulta_agencia_id->fetch(PDO::FETCH_ASSOC);

    // Recuperar informacion de ciudades tipo P

    $consulta_agentes =	$conexion->prepare("SELECT id, nombre, apellido FROM agentes WHERE agencia_id = :agencia_id AND activo = 1 AND nivel_acceso = 4 ");
    $consulta_agentes->execute([':agencia_id' =>  $agencia_id['agencia_id']]);
    $agentes = $consulta_agentes->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['conciliador_sent'])) {

      $conciliador = $_POST['conciliador_sent'];

      foreach ($agentes as $agente) {
        if ($agente['id'] == $conciliador) {
          echo "<option value=\"" . $agente['id'] . "\" selected>" . $agente['nombre'] . " " . $agente['apellido'] . "</option>";
        }else {
          echo "<option value=\"" . $agente['id'] . "\">" . $agente['nombre'] . " " . $agente['apellido'] . "</option>";
        }
      };

    } else {

      foreach ($agentes as $agente) {
        echo "<option value=\"" . $agente['id'] . "\">" . $agente['nombre'] . " " . $agente['apellido'] . "</option>";
      };

    };
    

?>
