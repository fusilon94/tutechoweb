<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    

    if (isset($_POST["pais_sent"])) {
      $tutechopais = $_POST['pais_sent'];


      $_SESSION['cookie_pais'] = $tutechopais;
      setcookie('tutechopais', $tutechopais, time()+(86400*365), '/');
      
      
      echo"Exito";

    };


};

?>
