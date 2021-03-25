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
  $array_acceso = [1,11];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

    $db_internacional = "tutechodb_internacional";

    try {
      $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $db_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


    $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
    $consulta_paises->execute();
    $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);


    $formularios_casa_nuevos = [];
    $formularios_departamento_nuevos = [];
    $formularios_local_nuevos = [];
    $formularios_terreno_nuevos = [];


    foreach ($paises as $pais) {

      $tutechodb = "tutechodb_" . $pais;

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };


      $consulta_formularios_casa_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien, pais FROM casa WHERE revision_form_solicitada = '' AND revision_fotos_solicitada = '' AND revision_vr_solicitada = '' AND visibilidad = :visibilidad AND validacion_agente = 1 AND validacion_fotografo = 1 AND validacion_jefe_agencia = 1 ");
      $consulta_formularios_casa_nuevos->execute([':visibilidad' => 'no_visible']);//SE PASA EL ID DEL AGENTE
      $formularios_casa_nuevos_especial = $consulta_formularios_casa_nuevos->fetchAll(PDO::FETCH_ASSOC);
  
      $consulta_formularios_departamento_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien, pais FROM departamento WHERE revision_form_solicitada = '' AND revision_fotos_solicitada = '' AND revision_vr_solicitada = '' AND visibilidad = :visibilidad AND validacion_agente = 1 AND validacion_fotografo = 1 AND validacion_jefe_agencia = 1 ");
      $consulta_formularios_departamento_nuevos->execute([':visibilidad' => 'no_visible']);//SE PASA EL ID DEL AGENTE
      $formularios_departamento_nuevos_especial = $consulta_formularios_departamento_nuevos->fetchAll(PDO::FETCH_ASSOC);
  
      $consulta_formularios_local_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien, pais FROM local WHERE revision_form_solicitada = '' AND revision_fotos_solicitada = '' AND revision_vr_solicitada = '' AND visibilidad = :visibilidad AND validacion_agente = 1 AND validacion_fotografo = 1 AND validacion_jefe_agencia = 1 ");
      $consulta_formularios_local_nuevos->execute([':visibilidad' => 'no_visible']);//SE PASA EL ID DEL AGENTE
      $formularios_local_nuevos_especial = $consulta_formularios_local_nuevos->fetchAll(PDO::FETCH_ASSOC);
  
      $consulta_formularios_terreno_nuevos = $conexion->prepare(" SELECT referencia, tipo_bien, pais FROM terreno WHERE revision_form_solicitada = '' AND revision_fotos_solicitada = '' AND revision_vr_solicitada = '' AND visibilidad = :visibilidad AND validacion_agente = 1 AND validacion_fotografo = 1 AND validacion_jefe_agencia = 1 ");
      $consulta_formularios_terreno_nuevos->execute([':visibilidad' => 'no_visible']);//SE PASA EL ID DEL AGENTE
      $formularios_terreno_nuevos_especial = $consulta_formularios_terreno_nuevos->fetchAll(PDO::FETCH_ASSOC);



      foreach ($formularios_casa_nuevos_especial as $formulario) {
        $formularios_casa_nuevos[] = $formulario;
      };
      foreach ($formularios_departamento_nuevos_especial as $formulario) {
        $formularios_departamento_nuevos[] = $formulario;
      };
      foreach ($formularios_local_nuevos_especial as $formulario) {
        $formularios_local_nuevos[] = $formulario;
      };
      foreach ($formularios_terreno_nuevos_especial as $formulario) {
        $formularios_terreno_nuevos[] = $formulario;
      };
      

    };

    

}else {
  header('Location: ../login.php');
};



require 'activar_bien_inmueble.view.php';
 ?>
