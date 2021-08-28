<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  
  function get_tabla($referencia) {
    $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
    if(isset($referencia[5])) {
      if (isset($dict[$referencia[5]])){
        return $dict[$referencia[5]];
      };
    };
    return '';
  };

  // Conexion con la database
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };


  if (isset($_POST['referencia_sent'])) {
    $referencia = $_POST['referencia_sent'];

    $tabla = get_tabla($referencia);

    if ($tabla == '') {
      echo json_encode("error");
    } else {

      $consulta_datos =	$conexion->prepare("SELECT * FROM $tabla WHERE referencia = :referencia");
      $consulta_datos->execute([':referencia' => $referencia]);
      $datos =	$consulta_datos->fetch(PDO::FETCH_ASSOC);

      if (empty($datos)) {
        echo json_encode("error");
      } else {

        echo json_encode($datos);
        
      };
      
      
    };
      
  };




    
}
?>
