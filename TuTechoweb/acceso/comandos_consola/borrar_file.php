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
  } else {header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  // POST
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["pais_select"]) && isset($_POST["tipo_file_select"]) && isset($_POST["id_file"])) {
      $pais_selected = filter_var(strtolower($_POST['pais_select']), FILTER_SANITIZE_STRING);
      $tipo_file_selected = filter_var(strtolower($_POST['tipo_file_select']), FILTER_SANITIZE_STRING);
      $id_file = filter_var($_POST['id_file'], FILTER_SANITIZE_STRING);

      $tutechodb = "tutechodb_" . $pais_selected;

      function DeleteFiles($path){
        if (is_dir($path) === true){
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file){
              DeleteFiles(realpath($path) . '/' . $file);
            };
            return rmdir($path);
        }else if (is_file($path) === true){
            return unlink($path);
        };

        return false;
      };

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };

      if ($tipo_file_selected == 'inmueble') {

        // Definir tipo de bien segun la referencia, para saber cual tabla cambiar
        if (strpos($id_file, 'C') !== false) { $tipo_bien = "casa";}
        else { if (strpos($id_file, 'D') !== false) { $tipo_bien = "departamento";}
          else { if (strpos($id_file, 'L') !== false) { $tipo_bien = "local";}
            else { if (strpos($id_file, 'T') !== false) { $tipo_bien = "terreno";}
              else {
                $_SESSION['mesage_file'] = 'Error de validacion de datos';
                header('Location: consola_registro_documentos.php');
               }; // Se bypasearon la validacion js, asi que los botamos
            };
          };
        };

        $file_path = '../../bienes_inmuebles_files/' . $pais_selected . '/' . $id_file;
        $file_photos_path = '../../bienes_inmuebles/' . $pais_selected . '/' . $id_file; // A CAMBIAR CUANDO USEMOS S3 BUCKET AMAZON AWS

        // Llamamos a la funcion que borran los files
        DeleteFiles($file_path);
        DeleteFiles($file_photos_path);

        // Borramos la fila respectiva de la database
        $borrar_bien =	$conexion->prepare("DELETE FROM $tipo_bien WHERE referencia = :referencia");
        $borrar_bien->execute(array(':referencia' => $id_file));

        // Luego de borrar los files y la row en la database, los sacamos de la herramienta con un mensaje de exito
        $_SESSION['mesage_file'] = "El Inmueble y todos los files asociados han sido borrados";
        header('Location: consola_registro_documentos.php');



      } else if ($tipo_file_selected == 'personal') {

        $file_path = '../../agentes/' . $pais_selected . '/' . $id_file;

        // Llamamos a la funcion que borran los files
        DeleteFiles($file_path);

        // Borramos la fila respectiva de la database
        $borrar_agente =	$conexion->prepare("DELETE FROM agentes WHERE id = :id");
        $borrar_agente->execute(array(':id' => $id_file));


        // Luego de borrar los files y la row en la database, los sacamos de la herramienta con un mensaje de exito
        $_SESSION['mesage_file'] = "El agente y todos sus files asociados han sido borrados";
        header('Location: consola_registro_documentos.php');

      };
    
    } else {
      $_SESSION['mesage_file'] = 'Error de validacion de datos';
      header('Location: consola_registro_documentos.php');
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

require 'borrar_file.view.php';
?>
