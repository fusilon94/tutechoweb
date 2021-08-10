<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };
}else {
  header('Location: ../login.php');
};
if($_SERVER['REQUEST_METHOD'] == 'POST'){

  // Conexion con la database

  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };


  if (isset($_POST['modo_sent']) && isset($_POST['agencia_id_sent'])) {
    $modo = $_POST['modo_sent'];
    $agencia_id = $_POST['agencia_id_sent'];

    if ($_COOKIE['tutechopais'] == 'bolivia') {

      if ($modo == 'edit') {

        $consulta_agencia_info =	$conexion->prepare("SELECT * FROM agencias WHERE id =:id");
        $consulta_agencia_info->execute([':id' => $agencia_id]);
        $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

        $foto_agencia = "../../agencias/" . $_SESSION['cookie_pais'] . "/" . $agencia_info['departamento'] . "_" . $agencia_info['location_tag'] . "/foto_agencia.jpg" . "?=" . Date('U');
        $foto_agencia_frontis = "../../agencias/" . $_SESSION['cookie_pais'] . "/" . $agencia_info['departamento'] . "_" . $agencia_info['location_tag'] . "/foto_agencia_frontis.jpg" . "?=" . Date('U');

        echo"
        <div class=\"inputs_contenedor\">

          <div class=\"inputs_pak\">
            <span class=\"input_wrap\">
            <label for=\"direccion\">Dirección: </label>
            <input id=\"direccion\" type=\"text\" name=\"direccion\" value=\"" . $agencia_info['direccion'] . "\">
            </span>
            <span class=\"input_wrap\">
            <label for=\"direccion_complemento\">Complemento: </label>
            <input id=\"direccion_complemento\" type=\"text\" name=\"direccion_complemento\" value=\"" . $agencia_info['direccion_complemento'] . "\">
            </span>
          </div>
          <div class=\"inputs_pak\">
            <span class=\"input_wrap\">
            <label for=\"telefono\">Telefono: </label>
            <input id=\"telefono\" type=\"text\" name=\"telefono\" value=\"" . $agencia_info['telefono'] . "\">
            </span>
            <span class=\"input_wrap\">
            <label for=\"nit\">NIT: </label>
            <input id=\"nit\" type=\"text\" name=\"nit\" value=\"" . $agencia_info['NIT'] . "\">
            </span>
          </div>

        </div>

        <div class=\"fotos_wrap\">
          <div id=\"contenedor_foto\">
            <p style=\"text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default\"> Sube una foto de la Agencia</p>
            <div id=\"campo_foto\" class=\"campo_foto\">
              <img src=\"" . $foto_agencia . " alt=\"Foto Agencia\" class=\"thumb_foto_normal\">
              <div class=\"thumb_foto_normal_p_container\" onclick=\"thumb_click_operator(this)\">
                <p class=\"thumb_foto_normal_p\">Cambiar Fotografía</p>
              </div>
              <i class=\"fa fa-undo-alt return_change_foto return_foto\" onclick=\"return_foto_click_operator(this)\"></i>
              <label for=\"foto\" id=\"foto_label\"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
              <input type=\"file\" id=\"foto\" name=\"foto\" class=\"\" disabled>
            </div>
          </div>

          <div id=\"contenedor_foto2\">
            <p style=\"text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default\"> Sube una foto del Frontis</p>
            <div id=\"campo_foto2\" class=\"campo_foto\">
              <img src=\"" . $foto_agencia_frontis . " alt=\"Foto Agencia\" class=\"thumb_foto_normal\">
              <div class=\"thumb_foto_normal_p_container\" onclick=\"thumb_click_operator(this)\">
                <p class=\"thumb_foto_normal_p\">Cambiar Fotografía</p>
              </div>
              <i class=\"fa fa-undo-alt return_change_foto return_foto\" onclick=\"return_foto_click_operator(this)\"></i>
              <label for=\"foto2\" id=\"foto_label2\"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
              <input type=\"file\" id=\"foto2\" name=\"foto2\" class=\"\" disabled>
            </div>
          </div>
        </div>
        ";

      }elseif($modo == 'new'){
          
        echo"
        <div class=\"inputs_contenedor\">

          <div class=\"inputs_pak\">
              <span class=\"input_wrap\">
              <label for=\"direccion\">Dirección: </label>
              <input id=\"direccion\" type=\"text\" name=\"direccion\" value=\"\">
              </span>
              <span class=\"input_wrap\">
              <label for=\"direccion_complemento\">Complemento: </label>
              <input id=\"direccion_complemento\" type=\"text\" name=\"direccion_complemento\" value=\"\">
              </span>
          </div>
          <div class=\"inputs_pak\">
              <span class=\"input_wrap\">
              <label for=\"telefono\">Telefono: </label>
              <input id=\"telefono\" type=\"text\" name=\"telefono\" value=\"\">
              </span>
              <span class=\"input_wrap\">
              <label for=\"nit\">NIT: </label>
              <input id=\"nit\" type=\"text\" name=\"nit\" value=\"\">
              </span>
          </div>

        </div>

        <div class=\"fotos_wrap\">
          <div id=\"contenedor_foto\">
          <p style=\"text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default\"> Sube una foto de la Agencia</p>
          <div id=\"campo_foto\" class=\"campo_foto\">
              <label for=\"foto\" id=\"foto_label\"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
              <input type=\"file\" id=\"foto\" name=\"foto\" class=\"\">
              </div>
          </div>

          <div id=\"contenedor_foto2\">
              <p style=\"text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default\"> Sube una foto del Frontis</p>
              <div id=\"campo_foto2\" class=\"campo_foto\">
              <label for=\"foto2\" id=\"foto_label2\"><p> Sube la Foto</br><span>Click or Drop</span></p></label>
              <input type=\"file\" id=\"foto2\" name=\"foto2\" class=\"\">
              </div>
          </div>
        </div>
        "; 

      };
      
    };

  };


    
};
?>
