<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
if(isset($_POST["ciudadesChoice"])){
    // Capture selected ciudad
    $ciudadesChoice = $_POST["ciudadesChoice"];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    // Recuperar informacion de barrios

    $consulta_barrios =	$conexion->prepare("SELECT barrio FROM barrios WHERE ciudad=:ciudad ORDER BY barrio ASC");
    $consulta_barrios->execute(['ciudad' => $ciudadesChoice]);
    $barriosList	=	$consulta_barrios->fetchAll(PDO::FETCH_COLUMN, 0);

    // Display barrios dropdown based on ciudad name
    if($ciudadesChoice !== ''){

      echo "<option></option>";

      if (isset($_POST['barrio_selected'])) {

          $barrio_selected = $_POST['barrio_selected'];

          foreach($barriosList as $barrioOption){
            if ($barrioOption == $barrio_selected) {
              echo "<option selected>". $barrioOption . "</option>";
            }else {
              echo "<option>". $barrioOption . "</option>";
            };
              
          };

      }else{

          foreach($barriosList as $barrioOption){
            echo "<option>". $barrioOption . "</option>";
          };

      };

    };
}
?>
