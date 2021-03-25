<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
if(isset($_POST["referencia_para_borrar"])){
    // Capture referencia que borrar
    $referencia_para_borrar = $_POST["referencia_para_borrar"];

    // Definir tipo de bien segun la referencia, para saber cual tabla cambiar

    if (strpos($referencia_para_borrar, 'C') !== false) {$tipo_bien = 'casa';};
    if (strpos($referencia_para_borrar, 'D') !== false) {$tipo_bien = 'departamento';};
    if (strpos($referencia_para_borrar, 'L') !== false) {$tipo_bien = 'local';};
    if (strpos($referencia_para_borrar, 'T') !== false) {$tipo_bien = 'terreno';};

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion reportarlo
    	echo "Error: " . $e->getMessage();
    };

    // Cambiar estado del bien (borrador)  a 'borrado'

    $update_borrar_borrador =	$conexion->prepare("UPDATE $tipo_bien SET estado = :estado WHERE referencia = :referencia");
    $update_borrar_borrador->execute(array(':estado' => 'borrado', ':referencia' => $referencia_para_borrar));

}
?>
