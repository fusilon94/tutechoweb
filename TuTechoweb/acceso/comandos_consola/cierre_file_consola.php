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
  $array_acceso = [1,3,10,11,12];
  $usuario = $_SESSION['usuario'];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  } else {
    header('Location: ../acceso.php');
  }; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  // POST
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["pais_select"]) && isset($_POST["tipo_file_select"]) && isset($_POST["id_file"])) {
      $pais_selected = filter_var(strtolower($_POST['pais_select']), FILTER_SANITIZE_STRING);
      $tipo_cierre = filter_var(strtolower($_POST['tipo_file_select']), FILTER_SANITIZE_STRING);
      $id_file = filter_var($_POST['id_file'], FILTER_SANITIZE_STRING);

      $tutechodb = "tutechodb_" . $pais_selected;

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

      // Definir tipo de bien segun la referencia, para saber cual tabla cambiar
      if (strpos($id_file, 'C') !== false) { $tabla = "casa";}
      else { if (strpos($id_file, 'D') !== false) { $tabla = "departamento";}
        else { if (strpos($id_file, 'L') !== false) { $tabla = "local";}
          else { if (strpos($id_file, 'T') !== false) { $tabla = "terreno";}
            else {
              $_SESSION['mesage_file'] = 'Error de validacion de datos';
              header('Location: consola_registro_documentos.php');
              }; // Se bypasearon la validacion js, asi que los botamos
          };
        };
      };

      $_SESSION['file_cierre'] = [
        'pais'  => $pais_selected,
        'tipo' => $tipo_cierre,
        'file' => $id_file,
        'tabla' => $tabla
      ];

      header('Location: file_cierre.php');

    };
    
   
  };  

  $tutechodb_internacional = 'tutechodb_internacional';

  try {
    $conexion_db_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_paises = $conexion_db_internacional->prepare(" SELECT pais FROM paises WHERE activo = 1 ");
  $consulta_paises->execute();
  $paises = $consulta_paises->fetchAll(PDO::FETCH_ASSOC);

} else {
  header('Location: ../login.php');
};

require 'cierre_file_consola.view.php';
?>
