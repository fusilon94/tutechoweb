<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
if(isset($_POST["departamentoChoice"])){
    // Capture selected departamento
    $departamentoChoice = $_POST["departamentoChoice"];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    // Recuperar informacion de ciudades tipo C

    $consulta_ciudades_c =	$conexion->prepare("SELECT ciudad FROM ciudades WHERE departamento=:departamento AND ciudad_poblado='c' ORDER BY ciudad ASC");
    $consulta_ciudades_c->execute(['departamento' => $departamentoChoice]);
    $ciudades_c	=	$consulta_ciudades_c->fetchAll(PDO::FETCH_COLUMN, 0);

    // Recuperar informacion de ciudades tipo P

    $consulta_ciudades_p =	$conexion->prepare("SELECT ciudad FROM ciudades WHERE departamento=:departamento AND ciudad_poblado='p' ORDER BY ciudad ASC");
    $consulta_ciudades_p->execute(['departamento' => $departamentoChoice]);
    $ciudades_p	=	$consulta_ciudades_p->fetchAll(PDO::FETCH_COLUMN, 0);

    // Display city dropdown based on country name
    if($departamentoChoice !== ''){

      if (isset($_POST['ciudad_selected'])) {

        $ciudad_selected = $_POST['ciudad_selected'];

        echo "<option></option>";
        echo "<optgroup label='Urbes'>";
        foreach($ciudades_c as $ciudadOption){
          if ($ciudadOption == $ciudad_selected) {
            echo "<option selected>". $ciudadOption . "</option>";
          } else {
            echo "<option>". $ciudadOption . "</option>";
          }; 
        };
        echo "</optgroup>";
        if ($ciudades_p != NULL) {
          echo "<optgroup label='Poblados'>";
          foreach($ciudades_p as $ciudadOption){
            if ($ciudadOption == $ciudad_selected) {
              echo "<option selected>". $ciudadOption . "</option>";
            } else {
              echo "<option>". $ciudadOption . "</option>";
            };
          };
          echo "</optgroup>";
        };

      } else {

        echo "<option></option>";
        echo "<optgroup label='Urbes'>";
        foreach($ciudades_c as $ciudadOption){
            echo "<option>". $ciudadOption . "</option>";
        };
        echo "</optgroup>";
        if ($ciudades_p != NULL) {
          echo "<optgroup label='Poblados'>";
          foreach($ciudades_p as $ciudadOption){
              echo "<option>". $ciudadOption . "</option>";
          };
          echo "</optgroup>";
        };

      };
      
        
    };
}
?>
