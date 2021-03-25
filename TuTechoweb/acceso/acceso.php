<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../tutechopais.php');
};

if (isset($_SESSION['usuario']) && isset($_SESSION['cookie_pais'])){ //si la SESSION a sido definida entonces dirijir a la consola agente o admin
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  }else {
    header('Location: comandos_consola/consola.php');
  }; 

}else {
  header('Location: login.php');//si no al login
};


?>
