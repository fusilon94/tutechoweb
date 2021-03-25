<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

if (isset($_SESSION['usuario']) && isset($_SESSION['cookie_pais'])){ //si la SESSION a sido definida entonces dirijir a la consola agente o admin
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  }else {
    header('Location: comandos_consola/consola.php');
  }; 

};

$errores = ''; // definir la variable errores vacia

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //si se envio datos por POST entonces guardarlos en variables que seran temporales
  $usuario = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING); //sanitizar y poner en minusculas el texto
	$password = $_POST['password'];
  $password = hash('sha512', $password); //codificar por seguridad el password segun el metodo sha512

  try { //conectarse a la base de datos para empezar las verificaciones
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); //en caso de error de conexion reportarlo
  }

  $statement = $conexion->prepare('SELECT nivel_acceso, genero FROM agentes WHERE activo = 1 AND usuario = :usuario AND pass = :password');//preparar la consulta: el usuario y pass existen?
  $statement->execute(array(//ejecutar la consulta definiendo los datos a comparar
    ':usuario' => $usuario,
    ':password' => $password
  ));

  $resultado = $statement->fetch(); //traducir y guardar el resultado de la consulta

  if ($resultado !== false) { //si todo concuerda abrir la SESSION con el nombre de usuario indicado y luego redirigir a la consola
        $nivel_acceso = $resultado[0];
        $genero_agente = $resultado[1];

        $_SESSION['usuario'] = $usuario;
        $_SESSION['nivel_acceso'] = $nivel_acceso;
        $_SESSION['genero'] = $genero_agente;
        $_SESSION['cookie_pais'] = $_COOKIE['tutechopais'];

        $consulta_agente =	$conexion->prepare("SELECT id FROM agentes WHERE usuario=:usuario");
        $consulta_agente->execute(['usuario' => $usuario]);
        $agente_datos	=	$consulta_agente->fetch();

        $agente_id = $agente_datos['id'];

        setcookie('agente_id', $agente_id, time()+(86400*365), '/');

        if (isset($_SESSION['usuario'])) {
          header('Location:comandos_consola/consola.php');
      } else {// si los datos no concuerdan entonces mostrar un error
          $errores .= '<li><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>Los datos son incorrectos</span></li>';
      };

  };

};

require 'login.view.php';
?>
