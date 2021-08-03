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

if (isset($_POST['modo']) && isset($_POST['factura_id'])) {

    $modo = $_POST['modo'];
    $factura_id = $_POST['factura_id'];

    if ($modo == 'anular') {
        
      // ACA PONER LA LLAMADA A IMPUESTOS Y ANULAR FACTURA + CAMBIAR ESTADO DE FACTURA EN NUESTRA DB

    }elseif ($modo == 'revertir') {
        
      // ACA PONER LA LLAMADA A IMPUESTOS Y REVERTIR ANULACION DE FACTURA + CAMBIAR ESTADO DE FACTURA EN NUESTRA DB

    };

};


?>
