<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
}else {
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,3,11,12,8,10];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $referencia = $_POST['nuevo_bien_referencia'];
    $tipo_bien = $_POST['nuevo_bien_tabla'];
    $_SESSION['referencia_bien'] = $referencia;
    $_SESSION['tabla_bien'] = $tipo_bien;
    header('Location: completar_fotos.php');
  };

  $consulta_agente = $conexion->prepare("SELECT id, agencia_id FROM agentes WHERE usuario=:usuario");
  $consulta_agente->execute(['usuario' => $usuario]);
  $agente_datos	= $consulta_agente->fetch();

  $agente_id = $agente_datos['id'];
  $agencia_id = $agente_datos['agencia_id'];

  if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {//acceso del admin, co admin y jefe de agencia central

    $consulta_formularios_casa_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM casa WHERE validacion_agente = 1 AND validacion_fotografo = 0 ");
    $consulta_formularios_casa_nuevos->execute();//SE PASA EL ID DEL AGENTE
    $formularios_casa_nuevos = $consulta_formularios_casa_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_departamento_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM departamento WHERE validacion_agente = 1 AND validacion_fotografo = 0 ");
    $consulta_formularios_departamento_nuevos->execute();//SE PASA EL ID DEL AGENTE
    $formularios_departamento_nuevos = $consulta_formularios_departamento_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_local_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM local WHERE validacion_agente = 1 AND validacion_fotografo = 0 ");
    $consulta_formularios_local_nuevos->execute();//SE PASA EL ID DEL AGENTE
    $formularios_local_nuevos = $consulta_formularios_local_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_terreno_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM terreno WHERE validacion_agente = 1 AND validacion_fotografo = 0 ");
    $consulta_formularios_terreno_nuevos->execute();//SE PASA EL ID DEL AGENTE
    $formularios_terreno_nuevos = $consulta_formularios_terreno_nuevos->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($nivel_acceso == 3 || $nivel_acceso == 8){ //acceso jefe de agencia local y fotografo

    $consulta_formularios_casa_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM casa WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id ");
    $consulta_formularios_casa_nuevos->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL ID DEL AGENTE
    $formularios_casa_nuevos = $consulta_formularios_casa_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_departamento_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM departamento WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id ");
    $consulta_formularios_departamento_nuevos->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL ID DEL AGENTE
    $formularios_departamento_nuevos = $consulta_formularios_departamento_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_local_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM local WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id ");
    $consulta_formularios_local_nuevos->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL ID DEL AGENTE
    $formularios_local_nuevos = $consulta_formularios_local_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_terreno_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM terreno WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id ");
    $consulta_formularios_terreno_nuevos->execute([':agencia_registro_id' => $agencia_id]);//SE PASA EL ID DEL AGENTE
    $formularios_terreno_nuevos = $consulta_formularios_terreno_nuevos->fetchAll(PDO::FETCH_ASSOC);

  }elseif ($nivel_acceso == 10) {//acceso agente express

    $consulta_formularios_casa_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM casa WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id AND agente_designado_id = :agente_designado_id ");
    $consulta_formularios_casa_nuevos->execute([
      ':agencia_registro_id' => $agencia_id,
      ':agente_designado_id' => $agente_id
      ]);//SE PASA EL ID DEL AGENTE
    $formularios_casa_nuevos = $consulta_formularios_casa_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_departamento_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM departamento WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id AND agente_designado_id = :agente_designado_id ");
    $consulta_formularios_departamento_nuevos->execute([
      ':agencia_registro_id' => $agencia_id,
      ':agente_designado_id' => $agente_id
      ]);//SE PASA EL ID DEL AGENTE
    $formularios_departamento_nuevos = $consulta_formularios_departamento_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_local_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM local WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id AND agente_designado_id = :agente_designado_id ");
    $consulta_formularios_local_nuevos->execute([
      ':agencia_registro_id' => $agencia_id,
      ':agente_designado_id' => $agente_id
      ]);//SE PASA EL ID DEL AGENTE
    $formularios_local_nuevos = $consulta_formularios_local_nuevos->fetchAll(PDO::FETCH_ASSOC);

    $consulta_formularios_terreno_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien FROM terreno WHERE validacion_agente = 1 AND validacion_fotografo = 0 AND agencia_registro_id = :agencia_registro_id AND agente_designado_id = :agente_designado_id ");
    $consulta_formularios_terreno_nuevos->execute([
      ':agencia_registro_id' => $agencia_id,
      ':agente_designado_id' => $agente_id
      ]);//SE PASA EL ID DEL AGENTE
    $formularios_terreno_nuevos = $consulta_formularios_terreno_nuevos->fetchAll(PDO::FETCH_ASSOC);

  }else;


}else {
  header('Location: ../login.php');
};


require 'consola_completar_fotos.view.php';
 ?>
