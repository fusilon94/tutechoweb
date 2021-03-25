<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
	if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
	  header('Location: cerrar_session.php');
	};

}else {
	header('Location: ../login.php');
};
  

$nivel_acceso = $_SESSION['nivel_acceso'];
$array_acceso = [1,3,11,12];
if (in_array($nivel_acceso, $array_acceso) !== false){
  //Todo OK
}
else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


if ($_SERVER['REQUEST_METHOD'] == 'POST') { //si se envio datos por POST guardar los datos en variables que seran temporales
	$usuario = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING); //sanitizar el texto y reducirlo a minusculas
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	$errores = ''; //define la variable errores vacia

	if (empty($usuario) or empty($password) or empty($password2)) { //si hay algun campo vacio mostrar un error
		$errores .= '<li><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>Por favor rellena todos los datos correctamente</span></li>';
	} else { //si no entonces conectarse a la base de datos para empezar las verificaciones
		try {
			$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
		} catch (PDOException $e) { //en caso de error de conexion repostarlo
			echo "Error: " . $e->getMessage();
		}

		$statement = $conexion->prepare('SELECT * FROM agentes WHERE usuario = :usuario LIMIT 1'); //preparar la consulta: usuario ya existe?
		$statement->execute(array(':usuario' => $usuario)); //ejecutar la consulta
		$resultado = $statement->fetch(); //guardar y traducir el resultado de la consulta

		if ($resultado != false) { //si el usuario existe entonces mostrar error
			$errores .= '<li><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>El nombre de usuario ya existe</span></li>';
		}

		$password = hash('sha512', $password); // codificar el password por seguridad segun el modo sha512
		$password2 = hash('sha512', $password2);

		if ($password != $password2) { //si los dos password no son los mismos entonces mostrar error
			$errores .= '<li><i class="fa fa-exclamation-circle" aria-hidden="true"></i><span>Las contraseñas no son iguales</span></li>';
		}
	}

	if ($errores == '') { //si no hubo ningun error entonces adjuntar los datos a la base de datos
		$statement = $conexion->prepare('INSERT INTO agentes (id, usuario, pass) VALUES (null, :usuario, :pass)'); //preparar la consulata INSERT
		$statement->execute(array( //ejecutar la consulta y definir los datos a insertar
			':usuario' => $usuario,
			':pass' => $password,
		));
    $exito = '<li><i class="far fa-check-circle" aria-hidden="true"></i><span>Nueva cuenta creada exitosamente</span></li>'; //crear un valor a exito
	}
}

require 'registro.view.php';

?>
