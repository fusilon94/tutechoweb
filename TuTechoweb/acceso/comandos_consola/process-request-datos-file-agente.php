<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["agente_id_sent"]) && isset($_POST["pais_sent"])){
    // Capture selected departamento
    $agente_id = $_POST["agente_id_sent"];
    $pais = $_POST["pais_sent"];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $pais;

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    $consulta_datos_agente = $conexion->prepare("SELECT nombre, apellido, genero, agencia_id, pais, doc_identidad, doc_tipo, domicilio, domicilio_complemento, email, departamento, ciudad FROM agentes WHERE id = :id");
    $consulta_datos_agente->execute([':id' => $agente_id]);
    $datos_agente = $consulta_datos_agente->fetch(PDO::FETCH_ASSOC);

    echo json_encode($datos_agente);
}
?>
