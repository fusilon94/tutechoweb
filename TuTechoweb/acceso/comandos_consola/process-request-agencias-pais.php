<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["paisChoice"])){
    // Capture selected departamento
    $paisChoice = $_POST["paisChoice"];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $paisChoice;

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    // Recuperar departamentos

    $nivel_acceso = $_SESSION['nivel_acceso'];
    $array_acceso = [1,11,12];

    if (in_array($nivel_acceso, $array_acceso) !== false) {
        $consulta_agencias = $conexion->prepare("SELECT id, location_tag FROM agencias");
        $consulta_agencias->execute();
        $agencias = $consulta_agencias->fetchAll(PDO::FETCH_ASSOC);
        echo "<option></option>";

        if(isset($_POST['agencia_selected'])){

          $agencia_selected = $_POST['agencia_selected'];

          foreach($agencias as $agencia){
            if ($agencia['id'] == $agencia_selected) {
              echo "<option value=\"" . $agencia['id'] . "\" selected>". $agencia['location_tag'] . "</option>";
            } else {
              echo "<option value=\"" . $agencia['id'] . "\">". $agencia['location_tag'] . "</option>";
            };           
          };

        }else{

          foreach($agencias as $agencia){
            echo "<option value=\"" . $agencia['id'] . "\">". $agencia['location_tag'] . "</option>";
          };

        };
        
    }else{

        $usuario = $_SESSION['usuario'];

        $consulta_agencia_usuario = $conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
        $consulta_agencia_usuario->execute([":usuario" => $usuario]);
        $agencia_usuario = $consulta_agencia_usuario->fetch();

        $consulta_agencias = $conexion->prepare("SELECT id, location_tag FROM agencias WHERE id = :id");
        $consulta_agencias->execute([":id" => $agencia_usuario[0]]);
        $agencias = $consulta_agencias->fetch(PDO::FETCH_ASSOC);
        echo "<option></option>";
        if (isset($_POST['agencia_selected'])) {
          echo "<option value=\"" . $agencias['id'] . "\" selected>". $agencias['location_tag'] . "</option>";
        } else {
          echo "<option value=\"" . $agencias['id'] . "\">". $agencias['location_tag'] . "</option>";
        };
    };
   
}
?>
