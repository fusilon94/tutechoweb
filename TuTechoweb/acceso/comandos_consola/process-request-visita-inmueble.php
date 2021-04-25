<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
};

if(isset($_POST["action_sent"])){

    $action = $_POST["action_sent"];

    if ($action == 'check_element') {

        if (isset($_POST["agente_id_sent"]) && isset($_POST['action_listened']) && isset($_POST['key_check_sent']) && isset($_POST['key_to_do_sent']) && isset($_POST['titulo_sent']) && isset($_POST['agencia_tag_sent']) && isset($_POST['visita_key_sent'])) {

            $agente_id = $_POST["agente_id_sent"];
            $action = $_POST['action_listened'];
            $key_elemento = $_POST['key_check_sent'];
            $key_to_to = $_POST['key_to_do_sent'];
            $titulo = $_POST['titulo_sent'];
            $agencia_tag = $_POST['agencia_tag_sent'];
            $visita_key = $_POST['visita_key_sent'];

            $json_path_tareas= '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
            $json = file_get_contents($json_path_tareas);
            $data = json_decode($json, true);

            $to_do = $data[$agente_id]['visita'][$visita_key]['check_lists_extra'][$key_to_to];
            if($to_do['titulo'] == $titulo) {
                $array_edit = json_decode($to_do['check_list'], true);
                $array_edit[$key_elemento]['checked'] = $action;
                
                $array_encoded = json_encode($array_edit);
                $data[$agente_id]['visita'][$visita_key]['check_lists_extra'][$key_to_to]['check_list'] = $array_encoded;
                
                $data_json = json_encode($data);// transformar el array en codigo json
                file_put_contents($json_path_tareas, $data_json); // FINALMENTE se guarda el data en un Json fill
    
            }else {
                echo"error";
            };


        } else {
          echo"error";
        };


    }else if ($action == 'delete_check_list') {

        if (isset($_POST["agente_id_sent"]) && isset($_POST['key_to_do_sent']) && isset($_POST['titulo_sent']) && isset($_POST['agencia_tag_sent']) && isset($_POST['visita_key_sent'])) {

          $agente_id = $_POST["agente_id_sent"];
          $key_to_to = $_POST['key_to_do_sent'];
          $titulo = $_POST['titulo_sent'];
          $agencia_tag = $_POST['agencia_tag_sent'];
          $visita_key = $_POST['visita_key_sent'];

          $json_path_tareas= '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
          $json = file_get_contents($json_path_tareas);
          $data = json_decode($json, true);
          
          $to_do = $data[$agente_id]['visita'][$visita_key]['check_lists_extra'][$key_to_to];

          if($to_do['titulo'] == $titulo) {

            unset($data[$agente_id]['visita'][$visita_key]['check_lists_extra'][$key_to_to]);

            $data_json = json_encode($data);// transformar el array en codigo json
            file_put_contents($json_path_tareas, $data_json); // FINALMENTE se guarda el data en un Json file

          }else {
              echo"error";
          };

          
        }else { 
            echo"error";
        };

    }else if ($action == 'save_comentarios') {

        if (isset($_POST["referencia_sent"]) && isset($_POST['tabla_sent']) && isset($_POST['comentario_sent'])) {

            $comentario = filter_var($_POST['comentario_sent'], FILTER_SANITIZE_STRING);;
            $referencia = $_POST["referencia_sent"];
            $tabla = $_POST['tabla_sent'];

            $statement = $conexion->prepare(
            "UPDATE $tabla SET comentarios_bien = :comentarios_bien WHERE referencia = :referencia"
            );
            $statement->execute(array(
            ':comentarios_bien' => $comentario,
            ':referencia' => $referencia
            ));

            print_r($comentario);
            print_r($referencia);
            print_r($tabla);

        }else {
            echo"error";
        };

    }else if ($action == 'eliminar_contacto_extra') {

      if (isset($_POST["agente_id_sent"]) && isset($_POST['key_sent']) && isset($_POST['agencia_tag_sent']) && isset($_POST['visita_key_sent']) && isset($_POST['telefono_sent'])) {

        $agente_id = $_POST["agente_id_sent"];
        $key = $_POST['key_sent'];
        $telefono = $_POST['telefono_sent'];
        $agencia_tag = $_POST['agencia_tag_sent'];
        $visita_key = $_POST['visita_key_sent'];

        $json_path_tareas= '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
        $json = file_get_contents($json_path_tareas);
        $data = json_decode($json, true);

        $contacto_extra = $data[$agente_id]['visita'][$visita_key]['contactos_extra'][$key];

        if($contacto_extra['telefono'] == $telefono) {

          unset($data[$agente_id]['visita'][$visita_key]['contactos_extra'][$key]);

          $data_json = json_encode($data);// transformar el array en codigo json
          file_put_contents($json_path_tareas, $data_json); // FINALMENTE se guarda el data en un Json file

        }else {
            echo"error";
        };


      }else {
          echo"error";
      };

    }else if ($action == 'update_status') {

      if (isset($_POST["agente_id_sent"]) && isset($_POST['agencia_tag_sent']) && isset($_POST['visita_key_sent']) && isset($_POST['status_sent']) && isset($_POST["tiempo_visita_sent"])) {
        
        $agente_id = $_POST["agente_id_sent"];
        $agencia_tag = $_POST['agencia_tag_sent'];
        $visita_key = $_POST['visita_key_sent'];
        $status = $_POST['status_sent'];
        $tiempo_visita = $_POST["tiempo_visita_sent"];

        $json_path_tareas= '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';
        $json = file_get_contents($json_path_tareas);
        $data = json_decode($json, true);

        $data[$agente_id]['visita'][$visita_key]['exito_check'] = $status;
        $data[$agente_id]['visita'][$visita_key]['tiempo'] = $tiempo_visita;

        $data_json = json_encode($data);// transformar el array en codigo json
        file_put_contents($json_path_tareas, $data_json); // FINALMENTE se guarda el data en un Json file


      }else {
        echo"error";
      };
    };
   
};
?>
