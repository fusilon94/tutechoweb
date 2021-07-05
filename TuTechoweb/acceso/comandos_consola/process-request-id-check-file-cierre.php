<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

function get_tabla($referencia) {
  $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
  return $dict[$referencia[5]];
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX

  if (isset($_POST["id_file_sent"]) && isset($_POST["pais_sent"])) {

    $id_file = $_POST["id_file_sent"];
    $pais = $_POST["pais_sent"];

    $tutechodb = "tutechodb_" . $pais;
    try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
    };

    $check_file = '';

    $tabla = get_tabla($id_file);

    $consulta_check_file =	$conexion->prepare("SELECT referencia FROM $tabla WHERE referencia = :referencia AND inactivo = 0 ");
    $consulta_check_file->execute([':referencia' => $id_file]);
    $check_file = $consulta_check_file->fetch(PDO::FETCH_COLUMN, 0);

    $mensaje = '';

    if ($check_file == '') {
        $mensaje .= '- File Inexistente - ';
    };

    if ($mensaje == '') {
        $mensaje = 'exito';
    };

    echo $mensaje;

  };




};

?>
