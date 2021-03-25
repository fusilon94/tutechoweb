<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["json_horario_sent"]) && isset($_POST["agencia_sent"])){

    $json_string = $_POST["json_horario_sent"];
    $pais = $_SESSION['cookie_pais'];
    $agencia = $_POST["agencia_sent"];

    $tutechodb = "tutechodb_" . $pais;

    try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

    $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
    $consulta_agencia_info->execute([':id' => $agencia]);
    $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

    $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

    $json_path = '../../agencias/' . $pais . '/' . $agencia_tag . '/horarios.json';
    
    file_put_contents($json_path, $json_string );
};


if (isset($_POST["json_excepciones_sent"]) && isset($_POST["agencia_sent"])) {

    $json_string = $_POST["json_excepciones_sent"];
    $pais = $_SESSION['cookie_pais'];
    $agencia = $_POST["agencia_sent"];

    $tutechodb = "tutechodb_" . $pais;

    try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

    $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
    $consulta_agencia_info->execute([':id' => $agencia]);
    $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

    $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

    $json_path = '../../agencias/' . $pais . '/' . $agencia_tag . '/horarios_excepciones.json';
    
    file_put_contents($json_path, $json_string );

}
?>
