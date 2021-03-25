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

    $consulta_departamentos = $conexion->prepare("SELECT departamentos FROM regiones");
    $consulta_departamentos->execute();
    $departamentos = $consulta_departamentos->fetchAll(PDO::FETCH_COLUMN, 0);

    // echo json_encode($departamentos);
        echo "<option></option>";

        if (isset($_POST['departamento_selected'])) {

          $departamento_selected = $_POST['departamento_selected'];

          foreach($departamentos as $depa){
            if ($depa == $departamento_selected) {
              echo "<option selected>". $depa . "</option>";
            } else {
              echo "<option>". $depa . "</option>";
            };
          
          };

        } else {

          foreach($departamentos as $depa){
            echo "<option>". $depa . "</option>";
          };

        };
   
}
?>
