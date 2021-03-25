<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};


if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX


      if (isset($_POST["id_file_sent"]) && isset($_POST["tipo_file_sent"]) && isset($_POST["pais_sent"])) {

        $id_file = $_POST["id_file_sent"];
        $tipo_file = $_POST["tipo_file_sent"];
        $pais = $_POST["pais_sent"];
        $mensaje = '';

        $tutechodb = "tutechodb_" . $pais;

        try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
        } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
        };

        $check_file = '';

        if ($tipo_file == 'personal') {
            $tabla = 'agentes';

            $consulta_check_file =	$conexion->prepare("SELECT id FROM $tabla WHERE id = :id ");
            $consulta_check_file->execute([':id' => $id_file]);
            $check_file = $consulta_check_file->fetch(PDO::FETCH_COLUMN, 0);

        }else if($tipo_file == 'inmueble'){

            if (isset($_POST["borrador_sent"])) {
                if($_POST["borrador_sent"] == 1){

                  if (strpos($id_file, 'C') !== false) { $tabla = "borradores_casa";}
                  else { if (strpos($id_file, 'D') !== false) { $tabla = "borradores_departamento";}
                    else { if (strpos($id_file, 'L') !== false) { $tabla = "borradores_local";}
                      else { if (strpos($id_file, 'T') !== false) { $tabla = "borradores_terreno";}
                        else { $tabla = '';};
                     };
                   };
                 };
                 
                }else if($_POST["borrador_sent"] == 0){

                  if (strpos($id_file, 'C') !== false) { $tabla = "casa";}
                  else { if (strpos($id_file, 'D') !== false) { $tabla = "departamento";}
                    else { if (strpos($id_file, 'L') !== false) { $tabla = "local";}
                      else { if (strpos($id_file, 'T') !== false) { $tabla = "terreno";}
                        else { $tabla = '';};
                     };
                   };
                 };

                };
            } else {

              if (strpos($id_file, 'C') !== false) { $tabla = "casa";}
                else { if (strpos($id_file, 'D') !== false) { $tabla = "departamento";}
                  else { if (strpos($id_file, 'L') !== false) { $tabla = "local";}
                    else { if (strpos($id_file, 'T') !== false) { $tabla = "terreno";}
                      else { $tabla = '';};
                  };
                };
              };

            };

            
            
            if ($tabla !== '') {
                $consulta_check_file =	$conexion->prepare("SELECT referencia FROM $tabla WHERE referencia = :referencia ");
                $consulta_check_file->execute([':referencia' => $id_file]);
                $check_file = $consulta_check_file->fetch(PDO::FETCH_COLUMN, 0);
            };
            
        };

        if ($check_file == '') {
            $mensaje .= '- File Inexistente - ';
        };


        if (isset($_POST["id_agente_autorizacion"])) {
            $agente = $_POST["id_agente_autorizacion"];

            $consulta_check_agente =    $conexion->prepare("SELECT id FROM agentes WHERE id= :id ");
            $consulta_check_agente->execute([':id' => $agente]);
            $check_agente = $consulta_check_agente->fetch(PDO::FETCH_COLUMN, 0);

            if ($check_agente == '') {
                $mensaje .= '- Agente Inexistente - ';
            };

        };


        if ($mensaje == '') {
            $mensaje = 'exito';
        };

        echo $mensaje;

      };




};

?>
