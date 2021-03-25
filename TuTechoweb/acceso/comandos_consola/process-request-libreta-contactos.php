<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};


if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };

    $pais = $_COOKIE['tutechopais'];

    function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    };


    if (isset($_POST['action_sent'])) {

      $action_requested = $_POST['action_sent'];

        
        if (isset($_POST['agente_id_sent'])) {

          if (isset($_POST['pais_sent'])) {

            $agente_id = $_POST['agente_id_sent'];
            $pais_requested = $_POST['pais_sent'];
         
            $json_contactos_personales_path = '../../agentes/' . $pais_requested . '/' . $agente_id . '/contactos_personales.json';

          }else {

            $agente_id = $_POST['agente_id_sent'];
         
            $json_contactos_personales_path = '../../agentes/' . $pais . '/' . $agente_id . '/contactos_personales.json';

          }; 

        }else {
          
          $consulta_agente_info =	$conexion->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
          $consulta_agente_info->execute([":usuario" => $_SESSION['usuario']]);
          $agente_id	=	$consulta_agente_info->fetch(PDO::FETCH_ASSOC);

          $json_contactos_personales_path = '../../agentes/' . $pais . '/' . $agente_id['id'] . '/contactos_personales.json';

        };


        if (file_exists($json_contactos_personales_path)) {

          $json_contactos_personales = file_get_contents($json_contactos_personales_path);
          $data_contactos_personales = json_decode($json_contactos_personales, true);

        } else {

          $json_constructor = array();

          $json_data = json_encode($json_constructor);

          file_put_contents($json_contactos_personales_path, $json_data);


          $json_contactos_personales = file_get_contents($json_contactos_personales_path);
          $data_contactos_personales = json_decode($json_contactos_personales, true);
        };


        function mostrar_lista_contactos($data){// funcion para cargar los contactos en la tabla

            foreach ($data as $key => $value) {//PRIMERO se cargan aquellos destacados

              if ($value['destacado'] == 1) {
                echo"
                  <div class=\"elemento_agenda\" id=\"" . $key . "\" gender=\"" . $value['genero'] . "\">

                      <span class=\"elemento_nombre contact_accion\">
                          <img src=\"../../objetos/" . $value['genero'] . "_icono_min_gold.svg\" alt=\"icono\" class=\"elemento_nombre_foto\">
                          <span class=\"elemento_nombre_text\">" . $value['nombre'] . "</span>
                      </span>
                      <span class=\"elemento_telefono contact_accion\">
                          <span class=\"elemento_telefono_text\">" . $value['telefono'] . "</span>";

                          if ($value['whatsapp'] == 1) {
                            echo"
                            <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                                <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                                <i class=\"fa fa-circle\"></i>
                            </span>
                            ";
                          }else {
                            echo"
                            <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp\">
                                <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                                <i class=\"fa fa-circle\"></i>
                            </span>
                            ";
                          };
                          
                echo"</span>
                      <span class=\"elemento_email contact_accion\" title=\"" . $value['email'] . "\">" . $value['email'] . "</span>
                      <span class=\"elemento_info contact_accion\" title=\"" . $value['info'] . "\">" . $value['info'] . "</span>

                      <span class=\"elemento_actions excluded\">
                          <span class=\"elemento_star excluded activo\"><i class=\"fa fa-star excluded\"></i></span>
                          <span class=\"elemento_edit excluded\"><i class=\"fa fa-edit excluded\"></i></span>
                          <span class=\"elemento_more excluded\">
                            <i class=\"fas fa-ellipsis-v excluded\"></i>
                          </span>
                      </span>

                  </div>
                
                ";
              ;

            };

          };


          foreach ($data as $key => $value) {//LUEGO se cargan aquellos NO destacados

            if ($value['destacado'] == 0) {
              echo"
                <div class=\"elemento_agenda\" id=\"" . $key . "\" gender=\"" . $value['genero'] . "\">

                    <span class=\"elemento_nombre contact_accion\">
                        <img src=\"../../objetos/" . $value['genero'] . "_icono_min_blue.svg\" alt=\"icono\" class=\"elemento_nombre_foto\">
                        <span class=\"elemento_nombre_text\">" . $value['nombre'] . "</span>
                    </span>
                    <span class=\"elemento_telefono contact_accion\">
                        <span class=\"elemento_telefono_text\">" . $value['telefono'] . "</span>";

                        if ($value['whatsapp'] == 1) {
                          echo"
                            <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                                <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                                <i class=\"fa fa-circle\"></i>
                            </span>
                          ";
                        }else {
                          echo"
                          <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp\">
                              <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                              <i class=\"fa fa-circle\"></i>
                          </span>
                          ";
                        };
                        
              echo"</span>
                    <span class=\"elemento_email contact_accion\" title=\"" . $value['email'] . "\">" . $value['email'] . "</span>
                    <span class=\"elemento_info contact_accion\" title=\"" . $value['info'] . "\">" . $value['info'] . "</span>

                    <span class=\"elemento_actions excluded\">
                        <span class=\"elemento_star excluded\"><i class=\"fa fa-star excluded\"></i></span>
                        <span class=\"elemento_edit excluded\"><i class=\"fa fa-edit excluded\"></i></span>
                        <span class=\"elemento_more excluded\">
                            <i class=\"fas fa-ellipsis-v excluded\"></i>
                        </span>
                    </span>

                </div>
              
              ";

            };

          };

        };

        function mostrar_lista_contactos_de_otro_agente($data){// funcion para cargar los contactos en la tabla

          foreach ($data as $key => $value) {//PRIMERO se cargan aquellos destacados

            if ($value['destacado'] == 1) {
              echo"
                <div class=\"elemento_agenda\" id=\"" . $key . "\" gender=\"" . $value['genero'] . "\">

                    <span class=\"elemento_nombre contact_accion\">
                        <img src=\"../../objetos/" . $value['genero'] . "_icono_min_gold.svg\" alt=\"icono\" class=\"elemento_nombre_foto\">
                        <span class=\"elemento_nombre_text\">" . $value['nombre'] . "</span>
                    </span>
                    <span class=\"elemento_telefono contact_accion\">
                        <span class=\"elemento_telefono_text\">" . $value['telefono'] . "</span>";

                        if ($value['whatsapp'] == 1) {
                          echo"
                          <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                              <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                              <i class=\"fa fa-circle\"></i>
                          </span>
                          ";
                        }else {
                          echo"
                          <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp\">
                              <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                              <i class=\"fa fa-circle\"></i>
                          </span>
                          ";
                        };
                        
              echo"</span>
                    <span class=\"elemento_email contact_accion\" title=\"" . $value['email'] . "\">" . $value['email'] . "</span>
                    <span class=\"elemento_info contact_accion\" title=\"" . $value['info'] . "\">" . $value['info'] . "</span>

                    <span class=\"elemento_actions excluded\">
                        
                    </span>

                </div>
              
              ";
            ;

          };

        };


        foreach ($data as $key => $value) {//LUEGO se cargan aquellos NO destacados

          if ($value['destacado'] == 0) {
            echo"
              <div class=\"elemento_agenda\" id=\"" . $key . "\" gender=\"" . $value['genero'] . "\">

                  <span class=\"elemento_nombre contact_accion\">
                      <img src=\"../../objetos/" . $value['genero'] . "_icono_min_blue.svg\" alt=\"icono\" class=\"elemento_nombre_foto\">
                      <span class=\"elemento_nombre_text\">" . $value['nombre'] . "</span>
                  </span>
                  <span class=\"elemento_telefono contact_accion\">
                      <span class=\"elemento_telefono_text\">" . $value['telefono'] . "</span>";

                      if ($value['whatsapp'] == 1) {
                        echo"
                          <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                              <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                              <i class=\"fa fa-circle\"></i>
                          </span>
                        ";
                      }else {
                        echo"
                        <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp\">
                            <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                            <i class=\"fa fa-circle\"></i>
                        </span>
                        ";
                      };
                      
            echo"</span>
                  <span class=\"elemento_email contact_accion\" title=\"" . $value['email'] . "\">" . $value['email'] . "</span>
                  <span class=\"elemento_info contact_accion\" title=\"" . $value['info'] . "\">" . $value['info'] . "</span>

                  <span class=\"elemento_actions excluded\">
                      
                  </span>

              </div>
            
            ";

          };

        };

      };

        function mostrar_lista_contactos_utiles($data){// funcion para cargar los contactos en la tabla

        foreach ($data as $key => $value) {//LUEGO se cargan aquellos NO destacados

            echo"
              <div class=\"elemento_agenda\" id=\"" . $key . "\" gender=\"" . $value['genero'] . "\">

                  <span class=\"elemento_nombre contact_accion\">
                      <img src=\"../../objetos/" . $value['genero'] . "_icono_min_blue.svg\" alt=\"icono\" class=\"elemento_nombre_foto\">
                      <span class=\"elemento_nombre_text\">" . $value['nombre'] . "</span>
                  </span>
                  <span class=\"elemento_telefono contact_accion\">
                      <span class=\"elemento_telefono_text\">" . $value['telefono'] . "</span>";

                      if ($value['whatsapp'] == 1) {
                        echo"
                          <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                              <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                              <i class=\"fa fa-circle\"></i>
                          </span>
                        ";
                      }else {
                        echo"
                        <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp\">
                            <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                            <i class=\"fa fa-circle\"></i>
                        </span>
                        ";
                      };
                      
            echo"</span>
                  <span class=\"elemento_email contact_accion\" title=\"" . $value['email'] . "\">" . $value['email'] . "</span>
                  <span class=\"elemento_info contact_accion\" title=\"" . $value['info'] . "\">" . $value['info'] . "</span>

                  <span class=\"elemento_actions excluded\">
                      <span class=\"elemento_edit excluded\"><i class=\"fa fa-edit excluded\"></i></span>
                      <span class=\"elemento_more excluded\">
                          <i class=\"fas fa-ellipsis-v excluded\"></i>
                      </span>
                  </span>

              </div>
            
            ";

        };

      };




        function mostrar_lista_agentes($data, $pais){// funcion para cargar los contactos en la tabla

          foreach ($data as $agente) {//LUEGO se cargan aquellos NO destacados

            $foto_agente_path = '../../agentes/' . $pais . '/' . $agente['id'] . '/foto_plomo_min.jpg';

            if (file_exists($foto_agente_path)) {
              $foto_src = $foto_agente_path . "?t=" . time();
            }else {
              $foto_src = "../../objetos/" . $agente['genero'] . "_icono_min_blue.svg";
            };

              echo"
                <div class=\"elemento_agenda\" id=\"" . $agente['id'] . "\" gender=\"" . $agente['genero'] . "\">

                    <span class=\"elemento_nombre contact_accion\">
                        <img src=\"" . $foto_src . "\" alt=\"icono\" class=\"elemento_nombre_foto\">
                        <span class=\"elemento_nombre_text\">" . $agente['nombre'] . " " . $agente['apellido'] . "</span>
                    </span>
                    <span class=\"elemento_telefono contact_accion\">
                        <span class=\"elemento_telefono_text\">" . $agente['contacto'] . "</span>

                        <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                            <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                            <i class=\"fa fa-circle\"></i>
                        </span>

                    </span>
                    <span class=\"elemento_email contact_accion\" title=\"" . $agente['email'] . "\">" . $agente['email'] . "</span>
                    <span class=\"elemento_info contact_accion\" title=\"" . $agente['cargo'] . " - ID: " . $agente['id'] . "\">" . $agente['cargo'] . "<br> ID: " . $agente['id'] . "</span>

                    <span class=\"elemento_actions excluded\">
                      <span class=\"elemento_more excluded\">
                        <i class=\"fas fa-ellipsis-v excluded\"></i>
                      </span>
                    </span>

                </div>
              
              ";

          };

        };

        
        
        


        if($action_requested == 'ver_contactos'){//carga inicial o bien vista de contactos de algun agente

          if (empty($data_contactos_personales)) {//Si no hay contactos en la agenda
            echo "
            <div class=\"elemento_agenda_vacio\">
              <h2>La Agenda de Contactos está vacía</h2>
            </div>
            ";
          } else {
            mostrar_lista_contactos($data_contactos_personales);
          };

        }elseif ($action_requested == 'nuevo_contacto') {//agregar contacto nuevo y mostrar la lista despues

          if (isset($_POST['nombre_contacto_sent']) && isset($_POST['genero_contacto_sent']) && isset($_POST['contacto_email_sent']) && isset($_POST['contacto_telefono_sent']) && isset($_POST['contacto_whatsapp_sent']) && isset($_POST['contacto_info_sent']) && isset($_POST['tab_sent'])) {

            $new_element = [//se estructura los datos del nuevo contacto en forma de array
              "nombre" => $_POST['nombre_contacto_sent'],
              "genero" => $_POST['genero_contacto_sent'],
              "email" => $_POST['contacto_email_sent'],
              "telefono" => $_POST['contacto_telefono_sent'],
              "whatsapp" => $_POST['contacto_whatsapp_sent'],
              "info" => filter_var($_POST['contacto_info_sent'], FILTER_SANITIZE_STRING),
              "destacado" => 0
            ];


            $tab = $_POST['tab_sent'];

            $error = false;

            if ($tab == 'mis_contactos') {

              // Verificamos que el contacto no exista ya
              foreach ($data_contactos_personales as $key => $value) {
                if ($value['telefono'] == $new_element['telefono']) {
                  $error = true;
                };
              };

              if($error == false) {
                array_push($data_contactos_personales, $new_element);// se incorpora el array del nuevo contacto a la lista de contactos

                usort($data_contactos_personales,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente

                // Se llama la funciona para mostrar la lista de contactos
                mostrar_lista_contactos($data_contactos_personales);

                $data_json = json_encode($data_contactos_personales);// transformar el array en codigo json

                file_put_contents($json_contactos_personales_path, $data_json); // FINALMENTE se guarda el data en un Json file
              }else {
                echo "error";
              };


            }elseif ($tab == 'contactos_utiles') {

              
              if (isset($_POST['agencia_sent'])) {

                $agencia_id = $_POST['agencia_sent'];

                if (isset($_POST['pais_sent'])) {//Si se especificó el Pais

                  $pais_especifico = $_POST['pais_sent'];
    
                  $tutechodb_especifico = "tutechodb_" . $pais_especifico;
    
                  try {
                    $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
                  } catch (PDOException $e) { //en caso de error de conexion repostarlo
                    echo "Error: " . $e->getMessage();
                  };
    
                  $consulta_agencia_info =	$conexion_especifica->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
                  $consulta_agencia_info->execute([":id" => $agencia_id]);
                  $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);
    
                  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
                 
                  $json_contactos_utiles_path = '../../agencias/' . $pais_especifico . '/' . $agencia_tag . '/contactos_utiles.json';
    
    
                }else {//Si NO se especificó el Pais
    
                  $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
                  $consulta_agencia_info->execute([":id" => $agencia_id]);
                  $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);
    
                  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
                  
                  $json_contactos_utiles_path = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/contactos_utiles.json';
    
                };
    
    
                if (file_exists($json_contactos_utiles_path)) {
    
                  $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
                  $data_contactos_utiles = json_decode($json_contactos_utiles, true);
    
                }else {
                  $json_constructor = array();
    
                  $json_data = json_encode($json_constructor);
    
                  file_put_contents($json_contactos_utiles_path, $json_data);
    
    
                  $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
                  $data_contactos_utiles = json_decode($json_contactos_utiles, true);
                };

                // Verificamos que el contacto no exista ya
                foreach ($data_contactos_utiles as $key => $value) {
                  if ($value['telefono'] == $new_element['telefono']) {
                    $error = true;
                  };
                };

                if ($error == false) {
                  array_push($data_contactos_utiles, $new_element);// se incorpora el array del nuevo contacto a la lista de contactos

                usort($data_contactos_utiles,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
  
                // Se llama la funciona para mostrar la lista de contactos
                mostrar_lista_contactos_utiles($data_contactos_utiles);
  
                $data_json = json_encode($data_contactos_utiles);// transformar el array en codigo json
  
                file_put_contents($json_contactos_utiles_path, $data_json); // FINALMENTE se guarda el data en un Json file
                }else {
                  echo "error";
                };            

              };


            };
            

          };
          
          

        }elseif ($action_requested == 'editar_contacto') {//editar contacto de la lista y mostrarla despues
          
          // CODIGO QUE SE ENCARGA DE LA EDICION DE LOS DATOS DEL CONTACTO
          if (isset($_POST['contacto_index_sent']) && isset($_POST['nombre_contacto_sent']) && isset($_POST['genero_contacto_sent']) && isset($_POST['contacto_email_sent']) && isset($_POST['contacto_telefono_sent']) && isset($_POST['contacto_whatsapp_sent']) && isset($_POST['contacto_info_sent']) && isset($_POST['tab_sent'])) {
            
            $contacto_index = $_POST['contacto_index_sent'];
            $tab = $_POST['tab_sent'];

            if ($tab == 'mis_contactos') {

              $error = false;

              if ($data_contactos_personales[$contacto_index]['telefono'] !== $_POST['contacto_telefono_sent']) {
                // Verificamos que el contacto no exista ya
                foreach ($data_contactos_personales as $key => $value) {
                  if ($value['telefono'] == $_POST['contacto_telefono_sent']) {
                    $error = true;
                  };
                };
              };

              if ($error == false) {

                //SE MODIFICAN UNO A UNO LOS DATOS DEL CONTACTO EDITADO
                $data_contactos_personales[$contacto_index]['nombre'] = $_POST['nombre_contacto_sent'];
                $data_contactos_personales[$contacto_index]['genero'] = $_POST['genero_contacto_sent'];
                $data_contactos_personales[$contacto_index]['email'] = $_POST['contacto_email_sent'];
                $data_contactos_personales[$contacto_index]['telefono'] = $_POST['contacto_telefono_sent'];
                $data_contactos_personales[$contacto_index]['whatsapp'] = $_POST['contacto_whatsapp_sent'];
                $data_contactos_personales[$contacto_index]['info'] = filter_var($_POST['contacto_info_sent'], FILTER_SANITIZE_STRING);

                $valor_busqueda = "";
                
                if (isset($_POST['busqueda_valor_sent'])) {
                  $valor_busqueda = $_POST['busqueda_valor_sent'];
                };

                if ($valor_busqueda !== '') {// SI EXISTEN VALORES DE BUSQUEDA AVANZADA
                  $datos_filtrados = [];

                  foreach ($data_contactos_personales as $key => $value) {//SE FILTRAN LOS CONTACTOS SEGUN LOS PARAMETROS DE BUSQUEDA
                    if (strpos(strtolower($value['nombre']), strtolower($valor_busqueda)) !== false) {
                      $datos_filtrados[$key] = $value;
                    };
                  };

                  // Si no existen resultatos de la busqueda, se envia un mensaje en vez
                  if (empty($datos_filtrados)) {
                    echo "
                      <div class=\"elemento_agenda_vacio\">
                        <h2>No se encontraron resultados</h2>
                      </div>
                    ";
                  }else { // Si existen resultados, los pasamos a la funcion PARA MOSTRAR LOS CONTACTOS
                    mostrar_lista_contactos($datos_filtrados);
                  };

                  usort($data_contactos_personales,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
                }else { // SI NO EXISTEN VALORES DE BUSQUEDA  SE MUESTRAN LOS CONTACTOS ALFABETICAMENTE

                  usort($data_contactos_personales,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
                  mostrar_lista_contactos($data_contactos_personales);
                };

                $data_json = json_encode($data_contactos_personales); // SE TRANSFORMA EL ARRAY EN JSON

                file_put_contents($json_contactos_personales_path, $data_json); // SE GUARDA EL DATA EN UN JSON FILE

              }else {
                echo "error";
              };;

              
            }elseif ($tab == 'contactos_utiles') {
              

              if (isset($_POST['agencia_sent'])) {

                $agencia_id = $_POST['agencia_sent'];

                if (isset($_POST['pais_sent'])) {//Si se especificó el Pais

                  $pais_especifico = $_POST['pais_sent'];
    
                  $tutechodb_especifico = "tutechodb_" . $pais_especifico;
    
                  try {
                    $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
                  } catch (PDOException $e) { //en caso de error de conexion repostarlo
                    echo "Error: " . $e->getMessage();
                  };
    
                  $consulta_agencia_info =	$conexion_especifica->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
                  $consulta_agencia_info->execute([":id" => $agencia_id]);
                  $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);
    
                  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
                 
                  $json_contactos_utiles_path = '../../agencias/' . $pais_especifico . '/' . $agencia_tag . '/contactos_utiles.json';
    
    
                }else {//Si NO se especificó el Pais
    
                  $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
                  $consulta_agencia_info->execute([":id" => $agencia_id]);
                  $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);
    
                  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
                  
                  $json_contactos_utiles_path = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/contactos_utiles.json';
    
                };
    
    
                if (file_exists($json_contactos_utiles_path)) {
    
                  $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
                  $data_contactos_utiles = json_decode($json_contactos_utiles, true);
    
                }else {
                  $json_constructor = array();
    
                  $json_data = json_encode($json_constructor);
    
                  file_put_contents($json_contactos_utiles_path, $json_data);
    
    
                  $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
                  $data_contactos_utiles = json_decode($json_contactos_utiles, true);
                };

                $error = false;

                if ($data_contactos_utiles[$contacto_index]['telefono'] !== $_POST['contacto_telefono_sent']) {
                   // Verificamos que el contacto no exista ya
                  foreach ($data_contactos_utiles as $key => $value) {
                    if ($value['telefono'] == $_POST['contacto_telefono_sent']) {
                      $error = true;
                    };
                  };
                };
               

                if ($error == false) {
                  //SE MODIFICAN UNO A UNO LOS DATOS DEL CONTACTO EDITADO
                  $data_contactos_utiles[$contacto_index]['nombre'] = $_POST['nombre_contacto_sent'];
                  $data_contactos_utiles[$contacto_index]['genero'] = $_POST['genero_contacto_sent'];
                  $data_contactos_utiles[$contacto_index]['email'] = $_POST['contacto_email_sent'];
                  $data_contactos_utiles[$contacto_index]['telefono'] = $_POST['contacto_telefono_sent'];
                  $data_contactos_utiles[$contacto_index]['whatsapp'] = $_POST['contacto_whatsapp_sent'];
                  $data_contactos_utiles[$contacto_index]['info'] = filter_var($_POST['contacto_info_sent'], FILTER_SANITIZE_STRING);
  
                  usort($data_contactos_utiles,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
                  mostrar_lista_contactos_utiles($data_contactos_utiles);
                 
  
                  $data_json = json_encode($data_contactos_utiles); // SE TRANSFORMA EL ARRAY EN JSON
  
                  file_put_contents($json_contactos_utiles_path, $data_json); // SE GUARDA EL DATA EN UN JSON FILE
                }else {
                  echo "error";
                };


              };


            };


          };

          // CODIGO ENCARGADO DE COLOCAR LA ESTRELLA DE DESTACADOS Y ACTUALIZAR LA AGENDA DE CONTACTOS
          if (isset($_POST['contacto_index_sent']) && isset($_POST['contacto_destacado_sent']) && isset($_POST['busqueda_valor_sent'])) {
            
            $contacto_index = $_POST['contacto_index_sent'];
            $contacto_destacado = $_POST['contacto_destacado_sent'];
            $valor_busqueda = $_POST['busqueda_valor_sent'];

            $data_contactos_personales[$contacto_index]['destacado'] = $contacto_destacado;//SE CAMBIA EPARAMETRO DESTACADO EN EL ARRAY CONTACTOS

            if ($valor_busqueda !== '') {//SI EXISTE DATOS DE BUSQUEDA AVANZADA
              $datos_filtrados = [];

              foreach ($data_contactos_personales as $key => $value) {//SE FILTRA SEGUN PARAMETROS DE BUSQUEDA
                if (strpos(strtolower($value['nombre']), strtolower($valor_busqueda)) !== false) {
                  $datos_filtrados[$key] = $value;
                };
              };

              if (empty($datos_filtrados)) {//SI NO HAY RESULTADOS DE BUSQUEDA
                echo "
                  <div class=\"elemento_agenda_vacio\">
                    <h2>No se encontraron resultados</h2>
                  </div>
                ";
              }else {
                mostrar_lista_contactos($datos_filtrados);
              };

              usort($data_contactos_personales,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
            } else {

              usort($data_contactos_personales,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
              mostrar_lista_contactos($data_contactos_personales);
            };

            $data_json = json_encode($data_contactos_personales);

            file_put_contents($json_contactos_personales_path, $data_json);
          };
          

        }elseif ($action_requested == 'borrar_contacto') {//quitar contacto de la lista y mostrarla despues

          if (isset($_POST['contacto_index_sent']) && isset($_POST['tab_sent'])) {

            $contacto_index = $_POST['contacto_index_sent'];
            $tab = $_POST['tab_sent'];

            if ($tab == 'mis_contactos') {
              
              unset($data_contactos_personales[$contacto_index]);// SE BORRA EL INDEX DENTRO DEL ARRAY CONTACTOS

              usort($data_contactos_personales,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
  
              mostrar_lista_contactos($data_contactos_personales);
  
              $data_json = json_encode($data_contactos_personales);
  
              file_put_contents($json_contactos_personales_path, $data_json);


            }elseif ($tab == 'contactos_utiles') {
             
              if (isset($_POST['agencia_sent'])) {

                $agencia_id = $_POST['agencia_sent'];

                if (isset($_POST['pais_sent'])) {//Si se especificó el Pais

                  $pais_especifico = $_POST['pais_sent'];
    
                  $tutechodb_especifico = "tutechodb_" . $pais_especifico;
    
                  try {
                    $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
                  } catch (PDOException $e) { //en caso de error de conexion repostarlo
                    echo "Error: " . $e->getMessage();
                  };
    
                  $consulta_agencia_info =	$conexion_especifica->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
                  $consulta_agencia_info->execute([":id" => $agencia_id]);
                  $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);
    
                  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
                 
                  $json_contactos_utiles_path = '../../agencias/' . $pais_especifico . '/' . $agencia_tag . '/contactos_utiles.json';
    
    
                }else {//Si NO se especificó el Pais
    
                  $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
                  $consulta_agencia_info->execute([":id" => $agencia_id]);
                  $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);
    
                  $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
                  
                  $json_contactos_utiles_path = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/contactos_utiles.json';
    
                };
    
    
                if (file_exists($json_contactos_utiles_path)) {
    
                  $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
                  $data_contactos_utiles = json_decode($json_contactos_utiles, true);
    
                }else {
                  $json_constructor = array();
    
                  $json_data = json_encode($json_constructor);
    
                  file_put_contents($json_contactos_utiles_path, $json_data);
    
    
                  $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
                  $data_contactos_utiles = json_decode($json_contactos_utiles, true);
                };


                unset($data_contactos_utiles[$contacto_index]);// SE BORRA EL INDEX DENTRO DEL ARRAY CONTACTOS

                usort($data_contactos_utiles,function($a,$b) {return strnatcasecmp($a['nombre'],$b['nombre']);});//ordena el array segun nombre, alfabeticamente
    
                mostrar_lista_contactos_utiles($data_contactos_utiles);
    
                $data_json = json_encode($data_contactos_utiles);
    
                file_put_contents($json_contactos_utiles_path, $data_json);

              };


            };

          };
            
        }elseif ($action_requested == 'busqueda_contacto') {
          
          if (isset($_POST['busqueda_valor_sent'])) {
            
            $valor_busqueda = $_POST['busqueda_valor_sent'];

            if ($valor_busqueda == '') {
              
              if (empty($data_contactos_personales)) {
                echo "
                <div class=\"elemento_agenda_vacio\">
                  <h2>La Agenda de Contactos está vacía</h2>
                </div>
                ";
              }else {
                mostrar_lista_contactos($data_contactos_personales);
              };

            }else{

              $datos_filtrados = [];

              foreach ($data_contactos_personales as $key => $value) {
                if (strpos(strtolower($value['nombre']), strtolower($valor_busqueda)) !== false) {
                  $datos_filtrados[$key] = $value;
                };
              };

              if (empty($datos_filtrados)) {
                echo "
                <div class=\"elemento_agenda_vacio\">
                  <h2>No se encontraron resultados</h2>
                </div>
                ";
              }else {

                mostrar_lista_contactos($datos_filtrados);
              };

              

            };

            


          };

        }elseif ($action_requested == 'ver_contactos_agencia') {//para mostrar contacto de una Agencia Tutecho
         
          if (isset($_POST['agencia_sent'])) {

            $agencia_id = $_POST['agencia_sent'];
           
            if (isset($_POST['pais_sent'])) {//Si se especificó el Pais

              $pais_especifico = $_POST['pais_sent'];
             
              $tutechodb_especifico = "tutechodb_" . $pais_especifico;

              try {
                $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
              } catch (PDOException $e) { //en caso de error de conexion repostarlo
                echo "Error: " . $e->getMessage();
              };

              $consulta_agentes =	$conexion_especifica->prepare(" SELECT id, nombre, apellido, contacto, email, genero, cargo FROM agentes WHERE agencia_id = :agencia_id AND activo = 1 ");
              $consulta_agentes->execute([':agencia_id' => $agencia_id]);
              $agentes = $consulta_agentes->fetchAll(PDO::FETCH_ASSOC);

              mostrar_lista_agentes($agentes, $pais_especifico);


            }else {//Si NO se especificó el Pais
              
              $consulta_agentes =	$conexion->prepare(" SELECT id, nombre, apellido, contacto, email, genero, cargo FROM agentes WHERE agencia_id = :agencia_id AND activo = 1 ");
              $consulta_agentes->execute([':agencia_id' => $agencia_id]);
              $agentes = $consulta_agentes->fetchAll(PDO::FETCH_ASSOC);

              mostrar_lista_agentes($agentes, $_COOKIE['tutechopais']);
            };


          };


        }elseif ($action_requested == 'ver_contactos_utiles') {

          if (isset($_POST['agencia_sent'])) {

            $agencia_id = $_POST['agencia_sent'];
           
            if (isset($_POST['pais_sent'])) {//Si se especificó el Pais

              $pais_especifico = $_POST['pais_sent'];

              $tutechodb_especifico = "tutechodb_" . $pais_especifico;

              try {
                $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
              } catch (PDOException $e) { //en caso de error de conexion repostarlo
                echo "Error: " . $e->getMessage();
              };

              $consulta_agencia_info =	$conexion_especifica->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
              $consulta_agencia_info->execute([":id" => $agencia_id]);
              $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

              $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
             
              $json_contactos_utiles_path = '../../agencias/' . $pais_especifico . '/' . $agencia_tag . '/contactos_utiles.json';


            }else {//Si NO se especificó el Pais

              $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
              $consulta_agencia_info->execute([":id" => $agencia_id]);
              $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

              $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];
              
              $json_contactos_utiles_path = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/contactos_utiles.json';

            };


            if (file_exists($json_contactos_utiles_path)) {

              $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
              $data_contactos_utiles = json_decode($json_contactos_utiles, true);

            }else {
              $json_constructor = array();

              $json_data = json_encode($json_constructor);

              file_put_contents($json_contactos_utiles_path, $json_data);


              $json_contactos_utiles = file_get_contents($json_contactos_utiles_path);
              $data_contactos_utiles = json_decode($json_contactos_utiles, true);
            };


            if (empty($data_contactos_utiles)) {//Si no hay contactos en la agenda
              echo "
              <div class=\"elemento_agenda_vacio\">
                <h2>La Agenda de Contactos Útiles está vacía</h2>
              </div>
              ";
            } else {
              mostrar_lista_contactos_utiles($data_contactos_utiles);
            };


          };



        }elseif ($action_requested == 'referencia_search') {
          
          if (isset($_POST['referencia_sent'])) {
            
            $referencia = $_POST['referencia_sent'];

            $tabla = '';

            if (strpos($referencia, 'C') !== false) { $tabla = "casa";}
                else { if (strpos($referencia, 'D') !== false) { $tabla = "departamento";}
                  else { if (strpos($referencia, 'L') !== false) { $tabla = "local";}
                    else { if (strpos($referencia, 'T') !== false) { $tabla = "terreno";}
                          else {
                            $tabla = ''; 
                          };
                      };
                  };
              };

            if ($tabla == '') {
              echo "
                <div class=\"elemento_agenda_vacio\">
                  <h2>La Referencia ingresada no existe</h2>
                </div>
              ";
            }else {
              
              if (isset($_POST['pais_sent'])) {

                $pais_selected = $_POST['pais_sent'];
  
                $tutechodb_especifico = "tutechodb_" . $pais_selected;
  
                try {
                  $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
                } catch (PDOException $e) { //en caso de error de conexion repostarlo
                  echo "Error: " . $e->getMessage();
                };
  
                $consulta_inmueble_info =	$conexion_especifica->prepare("SELECT registrador_id, propietario_nombre, propietario_apellido, propietario_telefono, propietario_email FROM $tabla WHERE referencia = :referencia");
                $consulta_inmueble_info->execute([":referencia" => $referencia]);
                $inmueble_info	=	$consulta_inmueble_info->fetch(PDO::FETCH_ASSOC);

                $consulta_registrador_info =	$conexion_especifica->prepare("SELECT id, nombre, apellido, genero, contacto, email FROM agentes WHERE id = :id");
                $consulta_registrador_info->execute([":id" => $inmueble_info['registrador_id']]);
                $registrador_info	=	$consulta_registrador_info->fetch(PDO::FETCH_ASSOC);

                $foto_agente_path = '../../agentes/' . $pais_selected . '/' . $registrador_info['id'] . '/foto_plomo_min.jpg';

  
              }else {
                
                $consulta_inmueble_info =	$conexion->prepare("SELECT registrador_id, propietario_nombre, propietario_apellido, propietario_telefono, propietario_email FROM $tabla WHERE referencia = :referencia");
                $consulta_inmueble_info->execute([":referencia" => $referencia]);
                $inmueble_info	=	$consulta_inmueble_info->fetch(PDO::FETCH_ASSOC);

                $consulta_registrador_info =	$conexion->prepare("SELECT id, nombre, apellido, genero, contacto, email FROM agentes WHERE id = :id");
                $consulta_registrador_info->execute([":id" => $inmueble_info['registrador_id']]);
                $registrador_info	=	$consulta_registrador_info->fetch(PDO::FETCH_ASSOC);
  
                $foto_agente_path = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $registrador_info['id'] . '/foto_plomo_min.jpg';
              };


              if (!empty($registrador_info)) {

                if (file_exists($foto_agente_path)) {
                  $foto_src = $foto_agente_path . "?t=" . time();
                }else {
                  $foto_src = "../../objetos/" . $registrador_info['genero'] . "_icono_min_blue.svg";
                };

                echo"
                  <div class=\"elemento_agenda registrador\" id=\"" . $registrador_info['id'] . "\" gender=\"" . $registrador_info['genero'] . "\">

                      <span class=\"elemento_nombre contact_accion\">
                          <img src=\"" . $foto_src . "\" alt=\"icono\" class=\"elemento_nombre_foto\">
                          <span class=\"elemento_nombre_text\">" . $registrador_info['nombre'] . " " . $registrador_info['apellido'] . "</span>
                      </span>
                      <span class=\"elemento_telefono contact_accion\">
                          <span class=\"elemento_telefono_text\">" . $registrador_info['contacto'] . "</span>

                          <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                              <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                              <i class=\"fa fa-circle\"></i>
                          </span>

                      </span>
                      <span class=\"elemento_email contact_accion\" title=\"" . $registrador_info['email'] . "\">" . $registrador_info['email'] . "</span>
                      <span class=\"elemento_info contact_accion\">REGISTRADOR<br> ID: " . $registrador_info['id'] . "</span>

                      <span class=\"elemento_actions\">
                        
                      </span>

                  </div>
            
                ";

              };

              echo"
                  <div class=\"elemento_agenda\" id=\"" . $referencia . "\" gender=\"hombre\">

                      <span class=\"elemento_nombre contact_accion\">
                          <img src=\"../../objetos/hombre_icono_min_blue.svg\" alt=\"icono\" class=\"elemento_nombre_foto\">
                          <span class=\"elemento_nombre_text\">" . $inmueble_info['propietario_nombre'] . " " . $inmueble_info['propietario_apellido'] . "</span>
                      </span>
                      <span class=\"elemento_telefono contact_accion\">
                          <span class=\"elemento_telefono_text\">" . $inmueble_info['propietario_telefono'] . "</span>

                          <span class=\"elemento_telefono_whatsapp fa-stack icon_stacks_whatsapp activo\">
                              <i class=\"fab fa-whatsapp fa-stack-2x\"></i>
                              <i class=\"fa fa-circle\"></i>
                          </span>

                      </span>
                      <span class=\"elemento_email contact_accion\" title=\"" . $inmueble_info['propietario_email'] . "\">" . $inmueble_info['propietario_email'] . "</span>
                      <span class=\"elemento_info contact_accion\">PROPIETARIO/CONTACTO</span>

                      <span class=\"elemento_actions\">
                        
                      </span>

                  </div>
            
                ";

            };


          };


        }elseif ($action_requested == 'compartir_contacto') {
          
          if (isset($_POST['destinatario_sent']) && isset($_POST['index_sent'])) {

            $destinatario_id = $_POST['destinatario_sent'];
            $index = $_POST['index_sent'];

            $consulta_destinatario_existe =	$conexion->prepare("SELECT id FROM agentes WHERE id = :id");
            $consulta_destinatario_existe->execute([":id" => $destinatario_id]);
            $destinatario_existe	=	$consulta_destinatario_existe->fetch(PDO::FETCH_ASSOC);

            if (empty($destinatario_existe)) {
              echo "error";
            }else{

              $datos_compartidos = $data_contactos_personales[$index];

              $consulta_agente_emisor =	$conexion->prepare("SELECT id, nombre, apellido FROM agentes WHERE usuario = :usuario");
              $consulta_agente_emisor->execute([":usuario" => $_SESSION['usuario']]);
              $agente_emisor	=	$consulta_agente_emisor->fetch(PDO::FETCH_ASSOC);

              $emisor_dato = $agente_emisor['nombre'] . ' ' . $agente_emisor['apellido'] . '(ID: ' . $agente_emisor['id'] . ')';

              $mensaje = 'Contacto compartido:<br>Nombre: ' . $datos_compartidos['nombre'] . '<br>Telefono: ' . $datos_compartidos['telefono'] . '<br>Email: ' . $datos_compartidos['email'] . '<br>Info: ' . $datos_compartidos['info'] . '<br>Este mensaje expira automaticamente en 7 dias';

              $codigo = generateRandomString();
              $current_date = date("Y/m/d");
              $expiration_date = date("Y/m/d", strtotime("+ 7 day"));

              $datos_feature = json_encode($datos_compartidos);
        
              $statement_respuesta = $conexion->prepare(
               "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, key_feature2, fecha_expiracion) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :key_feature2, :fecha_expiracion)"
              );
        
              $statement_respuesta->execute(array(
                ':codigo' => $codigo,
                ':agente_id' => $destinatario_id,
                ':mensaje' => $mensaje,
                ':fecha_creacion' => $current_date,
                ':tipo' => 'contacto_compartido',
                ':key_feature1' => $emisor_dato,
                ':key_feature2' => $datos_feature,
                ':fecha_expiracion' => $expiration_date
              ));

              echo "exito";

            };

          };


        }elseif ($action_requested == 'agente_contactos_search') {
          
          if (isset($_POST['id_sent'])) {

            $agente_id = $_POST['id_sent'];

            if (isset($_POST['pais_sent'])) {

              $pais_selected = $_POST['pais_sent'];

              $tutechodb_especifico = "tutechodb_" . $pais_selected;

              try {
                $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
              } catch (PDOException $e) { //en caso de error de conexion repostarlo
                echo "Error: " . $e->getMessage();
              };


              $consulta_agente_exist =	$conexion_especifica->prepare("SELECT id FROM agentes WHERE id = :id");
              $consulta_agente_exist->execute([":id" => $agente_id]);
              $agente_exist	=	$consulta_agente_exist->fetch(PDO::FETCH_ASSOC);

              if ($agente_exist !== '') {
                $json_contactos_agente = '../../agentes/' . $pais_selected . '/' . $agente_id . '/contactos_personales.json';
              };


            } else {
              
              $consulta_agente_exist =	$conexion->prepare("SELECT id FROM agentes WHERE id = :id");
              $consulta_agente_exist->execute([":id" => $agente_id]);
              $agente_exist	=	$consulta_agente_exist->fetch(PDO::FETCH_ASSOC);

              if ($agente_exist !== '') {
                $json_contactos_agente = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id . '/contactos_personales.json';
              };

            };

         
            if (file_exists($json_contactos_agente)) {
              $json_contactos_agente = file_get_contents($json_contactos_agente);
              $data_contactos_agente = json_decode($json_contactos_agente, true);

              if (empty($json_contactos_agente)) {

                echo "
                  <div class=\"elemento_agenda_vacio\">
                    <h2>La Agenda de Contactos está vacía</h2>
                  </div>
                ";

              }else {
               
                mostrar_lista_contactos_de_otro_agente($data_contactos_agente);

              };
            }else {
              echo "
              <div class=\"elemento_agenda_vacio\">
                <h2>La Agenda de Contactos está vacía</h2>
              </div>
              ";
            };




          };

        }elseif ($action_requested == 'mensaje_interno') {
          
          if (isset($_POST['agente_id_sent']) && isset($_POST['mensaje_sent'])) {
            
            $agente_id = $_POST['agente_id_sent'];
            $mensaje = filter_var($_POST['mensaje_sent'], FILTER_SANITIZE_STRING);
            

            $consulta_agente_emisor =	$conexion->prepare("SELECT id, nombre, apellido FROM agentes WHERE usuario = :usuario");
            $consulta_agente_emisor->execute([":usuario" => $_SESSION['usuario']]);
            $agente_emisor	=	$consulta_agente_emisor->fetch(PDO::FETCH_ASSOC);

            $emisor_dato = $agente_emisor['nombre'] . ' ' . $agente_emisor['apellido'] . '(ID: ' . $agente_emisor['id'] . ')';

            $codigo = generateRandomString();
            $current_date = date("Y/m/d");

            if (isset($_POST['pais_sent'])) {
              
              $pais_selected = $_POST['pais_sent'];

              $tutechodb_especifico = "tutechodb_" . $pais_selected;

              try {
                $conexion_especifica = new PDO('mysql:host=localhost;dbname=' . $tutechodb_especifico . ';charset=utf8', 'root', '');
              } catch (PDOException $e) { //en caso de error de conexion repostarlo
                echo "Error: " . $e->getMessage();
              };

              $statement_respuesta = $conexion_especifica->prepare(
                "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
              );
        
              $statement_respuesta->execute(array(
                ':codigo' => $codigo,
                ':agente_id' => $agente_id,
                ':mensaje' => $mensaje,
                ':fecha_creacion' => $current_date,
                ':tipo' => 'mensaje_interno',
                ':key_feature1' => $emisor_dato
              ));
  
              echo "exito";

            }else {
              
              $statement_respuesta = $conexion->prepare(
                "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
              );
        
              $statement_respuesta->execute(array(
                ':codigo' => $codigo,
                ':agente_id' => $agente_id,
                ':mensaje' => $mensaje,
                ':fecha_creacion' => $current_date,
                ':tipo' => 'mensaje_interno',
                ':key_feature1' => $emisor_dato
              ));
  
              echo "exito";

            };
            

          };




        }elseif ($action_requested == 'get_visitas') {

          $tutechodb_internacional = "tutechodb_internacional";

          try {
            $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
          } catch (PDOException $e) { //en caso de error de conexion repostarlo
            echo "Error: " . $e->getMessage();
          };
          
          $consulta_pais = $conexion_internacional->prepare(" SELECT time_zone_php FROM paises WHERE pais = :pais ");
          $consulta_pais->execute([":pais" => $_COOKIE['tutechopais']]);
          $pais_info = $consulta_pais->fetch(PDO::FETCH_ASSOC);

          date_default_timezone_set($pais_info['time_zone_php']);

          $consulta_agente_id = $conexion->prepare(" SELECT id, agencia_id FROM agentes WHERE usuario = :usuario ");
          $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
          $agente_info = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);

          $consulta_agencia_info = $conexion->prepare(" SELECT departamento, location_tag FROM agencias WHERE id = :id ");
          $consulta_agencia_info->execute([":id" => $agente_info['agencia_id']]);
          $agencia_info = $consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

          $agencia_tag = $agencia_info['departamento'] . '_' .$agencia_info['location_tag'];

          $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';

          $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
          $data_agentes_tareas = json_decode($json_agentes_tareas, true);

          $visitas_tot = array_filter($data_agentes_tareas[$agente_info['id']]['visita'], function($element) {

            $visita_fecha = new DateTime(date('d-m-Y',strtotime($element['fecha'])));
            $today = new DateTime(date("d-m-Y", time()));

            if ($visita_fecha >= $today) {
                return true;
            };
          });

          uasort($visitas_tot,function($a,$b) {

            preg_match_all('!\d+!', $a['hora'], $a_matches);
            $a_hora = intval(implode('', $a_matches[0]));

            preg_match_all('!\d+!', $b['hora'], $b_matches);
            $b_hora = intval(implode('', $b_matches[0]));
            
            return $a_hora - $b_hora;
          
          });//ordena el array segun hora

          uasort($visitas_tot,function($a,$b) {

            $a_fecha = new DateTime(date('d-m-Y', strtotime($a['fecha'])));
            $b_fecha = new DateTime(date("d-m-Y", strtotime($b['fecha'])));
            
            if ($a_fecha > $b_fecha) {
              return 1;
            }elseif ($a_fecha < $b_fecha) {
              return -1;
            }elseif ($a_fecha == $b_fecha) {
            return 0;
            };
          
          });//re-ordena el array segun fecha
          
            function get_inmueble_tipo($referencia){

            if (strpos($referencia, 'C') !== false) {
              return "casa";
            } else {
              if (strpos($referencia, 'D') !== false) {
                return "departamento";
              } else {
                if (strpos($referencia, 'L') !== false) {
                  return "local";
                } else {
                  if (strpos($referencia, 'T') !== false) {
                    return "terreno";
                  };
                };
              };
            };

          };

          function get_inmueble_location($referencia, $conexion){

            $tabla = get_inmueble_tipo($referencia);

            $consulta_location = $conexion->prepare(" SELECT location_tag FROM $tabla WHERE referencia = :referencia ");
            $consulta_location->execute([":referencia" => $referencia]);
            $location = $consulta_location->fetch(PDO::FETCH_ASSOC);

            return $location['location_tag'];
          };

          echo"<option></option>";
          foreach ($visitas_tot as $key => $visita) {
            echo "
            <option key=\"" . $key . "\" agencia_tag=\"" . $agencia_tag . "\" referencia=\"" . $visita['referencia'] . "\"><p>" . ucfirst(get_inmueble_tipo($visita['referencia'])) . " - " . $visita['fecha'] . " - "  . $visita['hora'] . "</p></option>
            ";
          };



        }elseif ($action_requested == 'agregar_a_visita') {
          
          if (isset($_POST['index_sent']) && isset($_POST['agencia_tag_sent']) && isset($_POST['referencia_sent']) && isset($_POST['index_contacto_sent']) && isset($_POST['tipo_contacto_sent'])) {

            $index_visita = $_POST['index_sent'];
            $agencia_tag = $_POST['agencia_tag_sent'];
            $referencia = $_POST['referencia_sent'];
            $index_contacto = $_POST['index_contacto_sent'];
            $tipo_contacto = $_POST['tipo_contacto_sent'];

            $json_path_agentes_tareas = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/agentes_tareas.json';

            $consulta_agente_id = $conexion->prepare(" SELECT id FROM agentes WHERE usuario = :usuario ");
            $consulta_agente_id->execute([":usuario" => $_SESSION['usuario']]);
            $agente_id = $consulta_agente_id->fetch(PDO::FETCH_ASSOC);

            $json_agentes_tareas = file_get_contents($json_path_agentes_tareas);
            $data_agentes_tareas = json_decode($json_agentes_tareas, true);

            if ($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['referencia'] == $referencia) {
              
              if (!isset($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['contactos_extra'])) {
                $data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['contactos_extra'] = array();
              };

              if ($tipo_contacto == 'tab_mis_contactos') {

                $json_path_contactos = '../../agentes/' . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/contactos_personales.json';
                $json_contactos_get = file_get_contents($json_path_contactos);
                $data_contactos_get = json_decode($json_contactos_get, true);

                if ($data_contactos_get[$index_contacto]['genero'] == 'hombre') {
                  $scr_selected = "../../objetos/hombre_icono_min_blue.svg";
                }elseif ($data_contactos_get[$index_contacto]['genero'] == 'mujer') {
                  $scr_selected = "../../objetos/mujer_icono_min_blue.svg";
                }else{
                  $scr_selected = "../../objetos/hombre_icono_min_blue.svg";
                };

                $contacto_selected = array();
                $contacto_selected['nombre'] = $data_contactos_get[$index_contacto]['nombre'];
                $contacto_selected['src'] = $scr_selected;
                $contacto_selected['telefono'] = $data_contactos_get[$index_contacto]['telefono'];
                $contacto_selected['info'] = $data_contactos_get[$index_contacto]['info'];

                array_push($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['contactos_extra'], $contacto_selected);// se incorpora el array del nuevo contacto a la lista de contactos

              }elseif ($tipo_contacto == 'tab_tu_agencia') {

                $consulta_agente_extra = $conexion->prepare(" SELECT id, nombre, apellido, contacto, cargo FROM agentes WHERE id = :id ");
                $consulta_agente_extra->execute([":id" => $index_contacto]);
                $agente_extra = $consulta_agente_extra->fetch(PDO::FETCH_ASSOC);

                $scr_selected = "../../agentes/" . $_COOKIE['tutechopais'] . '/' . $agente_id['id'] . '/' . 'foto_blanco.jpg';

                $contacto_selected = array();
                $contacto_selected['nombre'] = $agente_extra['nombre'] . ' ' . $agente_extra['apellido'];
                $contacto_selected['src'] = $scr_selected;
                $contacto_selected['telefono'] = $agente_extra['contacto'];
                $contacto_selected['info'] = $agente_extra['cargo'];

                array_push($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['contactos_extra'], $contacto_selected);// se incorpora el array del nuevo contacto a la lista de contactos

              }elseif ($tipo_contacto == 'tab_contactos_utiles') {

                $json_path_contactos = '../../agencias/' . $_COOKIE['tutechopais'] . '/' . $agencia_tag . '/contactos_utiles.json';
                $json_contactos_get = file_get_contents($json_path_contactos);
                $data_contactos_get = json_decode($json_contactos_get, true);

                if ($data_contactos_get[$index_contacto]['genero'] == 'hombre') {
                  $scr_selected = "../../objetos/hombre_icono_min_blue.svg";
                }elseif ($data_contactos_get[$index_contacto]['genero'] == 'mujer') {
                  $scr_selected = "../../objetos/mujer_icono_min_blue.svg";
                }else{
                  $scr_selected = "../../objetos/hombre_icono_min_blue.svg";
                };

                $contacto_selected = array();
                $contacto_selected['nombre'] = $data_contactos_get[$index_contacto]['nombre'];
                $contacto_selected['src'] = $scr_selected;
                $contacto_selected['telefono'] = $data_contactos_get[$index_contacto]['telefono'];
                $contacto_selected['info'] = $data_contactos_get[$index_contacto]['info'];

                array_push($data_agentes_tareas[$agente_id['id']]['visita'][$index_visita]['contactos_extra'], $contacto_selected);// se incorpora el array del nuevo contacto a la lista de contactos
              };

              $data_json = json_encode($data_agentes_tareas);
    
              file_put_contents($json_path_agentes_tareas, $data_json);

              echo"exito";
            };


          }else {
            echo"error";
          };

        };


    };

};

?>
