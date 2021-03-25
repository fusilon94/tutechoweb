<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["codigo_actual_sent"]) && isset($_POST["new_codigo_sent"])){
    // Capture selected departamento
    $codigo_actual = filter_var($_POST["codigo_actual_sent"], FILTER_SANITIZE_STRING);
    $new_codigo = filter_var($_POST["new_codigo_sent"], FILTER_SANITIZE_STRING);

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_SESSION['cookie_pais'];

    $nivel_acceso = $_SESSION['nivel_acceso'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    $consulta_codigo_correcto = $conexion->prepare("SELECT pass FROM agentes WHERE usuario = :usuario");
    $consulta_codigo_correcto->execute([":usuario" => $_SESSION['usuario']]);
    $codigo_correcto = $consulta_codigo_correcto->fetch(PDO::FETCH_ASSOC);

    $new_password = hash('sha512', $new_codigo);
    $current_password = hash('sha512', $codigo_actual);

    if ($codigo_actual == '' || $new_codigo == '') {
        echo "Error: Datos Faltantes";
    }else {
        
        if ($new_password == $codigo_correcto['pass']) {
            echo "Error: Nueva contraseña identica a la actual";
        }else{
            if ($current_password !== $codigo_correcto['pass']) {
                echo "Error: Codigo actual incorrecto";
            }else {
                $statement = $conexion->prepare("UPDATE agentes SET pass = :pass WHERE usuario=:usuario");
                $statement->execute([
                ':usuario' => $_SESSION['usuario'],
                ':pass' => $new_password
                ]);

                echo"Cambios Guardados Exitosamente";
            };
        };
        

        

        

    };

  
   
}
?>
