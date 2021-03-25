<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$tutechodb = '';

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

  $tutechodb = "tutechodb_" . $_SESSION['cookie_pais'];

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,2,3,4,5,6,7,8,10,11,12];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  // CARGA INICIAL ##############################################################################################################

    if (isset($_SESSION['usuario'])) {

      function filtro($var, $id){
        $array_id = explode('-', $var['agente_id']);
        if (in_array($id, $array_id)) {
          return true;
        };
      };

      if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {//SI es ADMIN, CO-ADMIN o Jefe de Agencia Central, cargar pendientes de todos los paises

        $tutechodb_internacional = "tutechodb_internacional";

        try {
          $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
        } catch (PDOException $e) { //en caso de error de conexion repostarlo
          echo "Error: " . $e->getMessage();
        };

        $consulta_paises = $conexion_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
        $consulta_paises->execute();
        $paises = $consulta_paises->fetchAll(PDO::FETCH_COLUMN, 0);

        $pendientes_agente = [];
        $array_pendientes_grupo = [];

        foreach ($paises as $pais) {

          $tutechodb = "tutechodb_" . $pais;

          try {
            $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
          } catch (PDOException $e) { //en caso de error de conexion repostarlo
            echo "Error: " . $e->getMessage();
          };

          $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
          $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
          $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

          $consulta_pendientes_agente =	$conexion->prepare("SELECT codigo, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 0 AND agente_id = :agente_id AND borrado = 0 ORDER BY fecha_creacion DESC ");
          $consulta_pendientes_agente->execute([":agente_id" => $agente_id['id']]);
          $pendientes_agente_especial	=	$consulta_pendientes_agente->fetchAll(PDO::FETCH_ASSOC);

          foreach ($pendientes_agente_especial as $pendiente) {
            $pendientes_agente[] = $pendiente;
          };

          $consulta_pendientes_grupo =	$conexion->prepare("SELECT codigo, agente_id, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 1 ORDER BY fecha_creacion DESC ");
          $consulta_pendientes_grupo->execute();
          $pendientes_grupo_especial	=	$consulta_pendientes_grupo->fetchAll(PDO::FETCH_ASSOC);

          foreach ($pendientes_grupo_especial as $pendiente) {
            if (filtro($pendiente, $agente_id['id'])) {
              $array_pendientes_grupo[] = $pendiente;
            };
          };


        };

      }else {//SE CARGAN LOS PENDIENTES DE UN SOLO PAIS
        
        $consulta_agente_id =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
        $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
        $agente_id	=	$consulta_agente_id->fetch(PDO::FETCH_ASSOC);

        $consulta_pendientes_agente =	$conexion->prepare("SELECT codigo, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 0 AND agente_id = :agente_id AND borrado = 0 ORDER BY fecha_creacion DESC ");
        $consulta_pendientes_agente->execute([":agente_id" => $agente_id['id']]);
        $pendientes_agente	=	$consulta_pendientes_agente->fetchAll(PDO::FETCH_ASSOC);

        $consulta_pendientes_grupo =	$conexion->prepare("SELECT codigo, agente_id, mensaje, visto, fecha_creacion, tipo, key_feature1, key_feature2, key_feature3, pais FROM pendientes WHERE grupal = 1 ORDER BY fecha_creacion DESC ");
        $consulta_pendientes_grupo->execute();
        $pendientes_grupo	=	$consulta_pendientes_grupo->fetchAll(PDO::FETCH_ASSOC);

        $array_pendientes_grupo = [];

        foreach ($pendientes_grupo as $pendiente) {
          if (filtro($pendiente, $agente_id['id'])) {
            $array_pendientes_grupo[] = $pendiente;
          };
        };


      };


      



    };



}else {
  header('Location: ../login.php');
};


require 'pendientes.view.php';
 ?>
