<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$tutechodb = '';

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
}else {
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,2,3,11,12];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

   $tutecho_db_internacional = "tutechodb_internacional";
   
   try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutecho_db_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_anticretico_existe =	$conexion_internacional->prepare("SELECT anticretico_existe FROM paises WHERE pais = :pais");
  $consulta_anticretico_existe->execute([':pais' => $_COOKIE['tutechopais']]);
  $anticretico_existe	=	$consulta_anticretico_existe->fetch(PDO::FETCH_ASSOC);

  //############################ LO QUE PASA SI SE AUTO-ENVIO ALGO POR METODO POST ##############################################

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agencia = $_POST['agencia'];

    $modo = $_POST['modo_input'];

    $compra_casa = $_POST['compra_casa'];
    $compra_departamento = $_POST['compra_departamento'];
    $compra_local = $_POST['compra_local'];
    $compra_terreno = $_POST['compra_terreno'];

    $renta_casa = $_POST['renta_casa'];
    $renta_departamento = $_POST['renta_departamento'];
    $renta_local = $_POST['renta_local'];
    $renta_terreno = $_POST['renta_terreno'];

    if (isset($_POST['anticretico'])) {
      $anticretico = $_POST['anticretico'];
    }else {
      $anticretico = 0;
    };

    print_r($_POST);

    $statement = $conexion->prepare(
     "UPDATE agencias SET modo_de_trabajo = :modo_de_trabajo, cap_compra_casa = :cap_compra_casa, cap_compra_departamento = :cap_compra_departamento, cap_compra_local = :cap_compra_local, cap_compra_terreno = :cap_compra_terreno, cap_renta_casa = :cap_renta_casa, cap_renta_departamento = :cap_renta_departamento, cap_renta_local = :cap_renta_local, cap_renta_terreno = :cap_renta_terreno, cap_anticretico = :cap_anticretico WHERE id = :id"
    );

    $statement->execute(array(
      ':id' => $agencia,
      ':modo_de_trabajo' => $modo,
      ':cap_compra_casa' => $compra_casa,
      ':cap_compra_departamento' => $compra_departamento,
      ':cap_compra_local' => $compra_local,
      ':cap_compra_terreno' => $compra_terreno,
      ':cap_renta_casa' => $renta_casa,
      ':cap_renta_departamento' => $renta_departamento,
      ':cap_renta_local' => $renta_local,
      ':cap_renta_terreno' => $renta_terreno,
      ':cap_anticretico' => $anticretico
    ));

    $_SESSION['exito_bien_registrado'] = 'Parametros de Agencia registrados exitosamente';
    header('Location: ../acceso.php');
  };


  // CARGA INICIAL ##############################################################################################################

  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {

    $consulta_agencias =	$conexion->prepare("SELECT id, location_tag FROM agencias");
    $consulta_agencias->execute();
    $agencias	=	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($nivel_acceso == 2) {

    $consulta_franquiciante =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
    $consulta_franquiciante->execute([":usuario" => $_SESSION['usuario']]);
    $franquiciante	=	$consulta_franquiciante->fetch();

    $consulta_agencias =	$conexion->prepare("SELECT id, location_tag FROM agencias WHERE franquiciante_id = :franquiciante_id");
    $consulta_agencias->execute([':franquiciante_id' => $franquiciante[0]]);
    $agencias	=	$consulta_agencias->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($nivel_acceso == 3) {
    $consulta_agencia_id =	$conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
    $consulta_agencia_id->execute([":usuario" => $_SESSION['usuario']]);
    $agencia_id	=	$consulta_agencia_id->fetch();

    $consulta_agencia_especifica =	$conexion->prepare("SELECT * FROM agencias WHERE id = :id");
    $consulta_agencia_especifica->execute([":id" => $agencia_id[0]]);
    $agencia_especifica	=	$consulta_agencia_especifica->fetch(PDO::FETCH_ASSOC);
  };


}else {
  header('Location: ../login.php');
};


require 'score_param.view.php';
 ?>
