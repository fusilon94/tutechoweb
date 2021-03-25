<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$tutechodb = '';

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

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['tipo_file_select']) && isset($_POST['pais_select']) && isset($_POST['id_agente']) && isset($_POST['id_file'])) {

        $tipo_file = filter_var(strtolower($_POST['tipo_file_select']), FILTER_SANITIZE_STRING);
        $pais = filter_var(strtolower($_POST['pais_select']), FILTER_SANITIZE_STRING);
        $id_agente = filter_var($_POST['id_agente'], FILTER_SANITIZE_STRING);
        $id_file = filter_var($_POST['id_file'], FILTER_SANITIZE_STRING);

        if (isset($_POST['borrador'])) {
            $borrador = filter_var($_POST['borrador'], FILTER_SANITIZE_STRING);
        };
        

        $db_name = 'tutechodb_' . $pais;

        try {
            $conexion_update = new PDO('mysql:host=localhost;dbname=' . $db_name . ';charset=utf8', 'root', '');
          } catch (PDOException $e) { //en caso de error de conexion repostarlo
            echo "Error: " . $e->getMessage();
          };

          function generateRandomString($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
          };

          $reclamo_id = generateRandomString(10);
          $current_date = date("Y/m/d");

        if ($tipo_file == 'personal'){
            
            $statement_json = $conexion_update->prepare(
                "UPDATE agentes SET edicion = :edicion, edicion_reclamo = :edicion_reclamo WHERE id = :id");
    
            $statement_json->execute(array(
                ':edicion' => $id_agente,
                ':edicion_reclamo' => $reclamo_id,
                ':id' => $id_file
            ));

            $reclamo = 'La autorizacion de edicion del File Agente fue concedida';

            $statement_reclamo = $conexion_update->prepare(
                "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, key_feature2) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :key_feature2)"
              );
    
            $statement_reclamo->execute(array(
            ':codigo' => $reclamo_id,
            ':agente_id' => $id_agente,
            ':mensaje' => $reclamo,
            ':fecha_creacion' => $current_date,
            ':tipo' => 'autorizacion',
            ':key_feature1' => $id_file,
            ':key_feature2' => 'Personal'
            ));


        }else if($tipo_file == 'inmueble'){

            if(isset($borrador)){

                if (strpos($id_file, 'C') !== false) { $tabla = "borradores_casa";}
                  else { if (strpos($id_file, 'D') !== false) { $tabla = "borradores_departamento";}
                    else { if (strpos($id_file, 'L') !== false) { $tabla = "borradores_local";}
                      else { if (strpos($id_file, 'T') !== false) { $tabla = "borradores_terreno";}
                            else {
                                $_SESSION['mesage_file'] = 'Error de validacion de datos';
                                header('Location: consola_registro_documentos.php');
                            };
                        };
                    };
                };

            }else{

                if (strpos($id_file, 'C') !== false) { $tabla = "casa";}
                  else { if (strpos($id_file, 'D') !== false) { $tabla = "departamento";}
                    else { if (strpos($id_file, 'L') !== false) { $tabla = "local";}
                      else { if (strpos($id_file, 'T') !== false) { $tabla = "terreno";}
                            else {
                                $_SESSION['mesage_file'] = 'Error de validacion de datos';
                                header('Location: consola_registro_documentos.php');
                            };
                        };
                    };
                };

            };

            
            
            $statement_json = $conexion_update->prepare(
                "UPDATE $tabla SET edicion_file = :edicion_file, edicion_reclamo = :edicion_reclamo WHERE referencia = :referencia");
    
            $statement_json->execute(array(
                ':edicion_file' => $id_agente,
                ':edicion_reclamo' => $reclamo_id,
                ':referencia' => $id_file
            ));

            $reclamo = 'La autorizacion de edicion del File Inmueble fue concedida';
    
            $statement_reclamo = $conexion_update->prepare(
                "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, key_feature2) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :key_feature2)"
              );

            $statement_reclamo->execute(array(
            ':codigo' => $reclamo_id,
            ':agente_id' => $id_agente,
            ':mensaje' => $reclamo,
            ':fecha_creacion' => $current_date,
            ':tipo' => 'autorizacion',
            ':key_feature1' => $id_file,
            ':key_feature2' => 'Inmueble'
            ));


        };

        $_SESSION['mesage_file'] = 'Autorización de Edicion de File Concedida';
        header('Location: consola_registro_documentos.php');
    }else {
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


}else {
  header('Location: ../login.php');
};



require 'autorizar_edicion_file.view.php';
 ?>
