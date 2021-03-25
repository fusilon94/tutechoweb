<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };


    if (isset($_POST['agente_id'])) {
        $agente_id = $_POST['agente_id'];

        $consulta_agente =	$conexion->prepare("SELECT agencia_id, poder, notaria, notario_nombre, genero, nombre, apellido, doc_identidad, doc_tipo, domicilio FROM agentes WHERE id = :id");
        $consulta_agente->execute([':id' => $agente_id]);
        $agente =	$consulta_agente->fetch(PDO::FETCH_ASSOC);

        if ($agente["agencia_id"] == 0) {
            $agencia = ['departamento' => "______",
            'ciudad' => "______",
            'barrio' => "______",
            'direccion' => "______",
            'direccion_complemento' => "______",
            'NIT' => "______",
            'location_tag' => "______"];
        }else{
            $consulta_agencia =	$conexion->prepare("SELECT departamento, ciudad, barrio, direccion, direccion_complemento, NIT, location_tag FROM agencias WHERE id = :id");
            $consulta_agencia->execute([':id' => $agente['agencia_id']]);
            $agencia =	$consulta_agencia->fetch(PDO::FETCH_ASSOC);
        };

        $datos = ['agente'=> $agente,
         'agencia'=> $agencia];

        echo json_encode($datos);
        
    };




    
}
?>
