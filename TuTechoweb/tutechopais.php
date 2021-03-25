<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

session_destroy(); // destruye la SESSION actual
$_SESSION = array(); // limpia los parametros de la SESSION para que la proxima comience bien

  $php_view_entry_control = "algunvalor";

    if (isset($_COOKIE['tutechopais'])) {
      header('Location: index.php');
    };

// Condicionales IF para cada opcion de pais

    if (isset($_POST['bolivia'])) {
      $tutechopais = $_POST['bolivia'];
    } else {};

    if (isset($_POST['peru'])) {
      $tutechopais = $_POST['peru'];
    } else {};
    
    if (isset($_POST['chile'])) {
      $tutechopais = $_POST['chile'];
    } else {};
    
    if (isset($_POST['argentina'])) {
      $tutechopais = $_POST['argentina'];
    } else {};
    
    if (isset($_POST['paraguay'])) {
      $tutechopais = $_POST['paraguay'];
    } else {};
    
    if (isset($_POST['uruguay'])) {
      $tutechopais = $_POST['uruguay'];
    } else {};
    
    if (isset($_POST['ecuador'])) {
      $tutechopais = $_POST['ecuador'];
    } else {};
    
    if (isset($_POST['colombia'])) {
      $tutechopais = $_POST['colombia'];
    } else {};
    
    if (isset($_POST['mexico'])) {
      $tutechopais = $_POST['mexico'];
    } else {};
    
    if (isset($_POST['espana'])) {
      $tutechopais = $_POST['espana'];
    } else {};



// creacion de la COOKIE si se escojio un pais

    if (isset($tutechopais)){
      setcookie('tutechopais', $tutechopais, time()+(86400*365), '/');
      header('Location: index.php');
    } else{};

    $tutechodb = 'tutechodb_internacional';

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $consulta_paises = $conexion->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
    $consulta_paises->execute();
    $paises = $consulta_paises->fetchAll(PDO::FETCH_ASSOC);

  require 'tutechopais.view.php';
 ?>
