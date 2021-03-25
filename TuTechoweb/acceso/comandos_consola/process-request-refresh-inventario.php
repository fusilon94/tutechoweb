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

    if (isset($_POST['agenciaChoice']) && isset($_POST['modoChoice'])) {
        $agencia = $_POST['agenciaChoice'];
        $modo = $_POST['modoChoice'];

        $consulta_items =	$conexion->prepare("SELECT item, grupo, dimensiones, devuelto FROM inventario WHERE agencia = :agencia");
        $consulta_items->execute([':agencia' => $agencia]);
        $items_list = $consulta_items->fetchAll(PDO::FETCH_ASSOC);

        $currated_items_list = [];
        foreach ($items_list as $item) {
            if (array_key_exists($item['grupo'], $currated_items_list) == false) {
                $item_array = ['item' => $item['item'], 'dimensiones' => $item['dimensiones'], 'disponibles' => $item['devuelto'], 'total' => 1];
                $currated_items_list[$item['grupo']] = $item_array;
            }elseif (array_key_exists($item['grupo'], $currated_items_list) == true) {
                $currated_items_list[$item['grupo']]['disponibles'] += $item['devuelto'];
                $currated_items_list[$item['grupo']]['total'] += 1;
            };

        };


        if ($modo == 'read') {
            foreach ($currated_items_list as $key => $item) {
                echo "
                <li class=\"item_wrap\" name=" . $key . ">
                    <span class=\"item_name\">" . $item['item'] . "</span>
                    <span class=\"item_dimension\">" . $item['dimensiones'] . "</span>
                    <span class=\"item_availability\">" . $item['disponibles'] . "/" . $item['total'] . "</span>
                    <span class=\"escoger_btn_wrap\">";
                    if ($item['disponibles'] == 0) {
                        echo"<span class=\"escoger_btn apagado\">ACABADO</span>";
                    } else {
                        echo"<span class=\"escoger_btn\">ESCOGER</span>";
                    };
                echo"</span>
                </li>
                <hr class=\"barra_lista\">";
            };
        }elseif($modo == 'edit') {
            foreach ($currated_items_list as $key => $item) {
                echo "
                <li class=\"item_wrap\" name=" . $key . ">
                    <span class=\"item_name\">" . $item['item'] . "</span>
                    <span class=\"item_dimension\">" . $item['dimensiones'] . "</span>
                    <span class=\"item_availability\">" . $item['disponibles'] . "/" . $item['total'] . "</span>
                    <span class=\"escoger_btn_wrap\">";
                    if ($item['disponibles'] == 0) {
                        echo"<span class=\"escoger_btn apagado\">ACABADO</span>";
                    } else {
                        echo"<span class=\"escoger_btn\">ESCOGER</span>";
                    };
                echo"<span class=\"editar_btn\"><i class=\"fas fa-edit\"></i></span>
                    <span class=\"agregar_btn\"><i class=\"fas fa-plus\"></i></span>
                    </span>
                </li>
                <hr class=\"barra_lista\">";
            };
        };

  };

  if(isset($_POST['agenciaItems'])){
    $agencia = $_POST['agenciaItems'];

    $consulta_items =	$conexion->prepare("SELECT id, item, dimensiones, location_tag FROM inventario WHERE agencia = :agencia AND devuelto = 0");
    $consulta_items->execute([':agencia' => $agencia]);
    $items_list = $consulta_items->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items_list as $item) {
        echo "
        <li class=\"item_wrap\" name=\"" . $item['id'] . "\">
            <span class=\"item_name\">" . $item['item'] . "</span>
            <span class=\"item_dimension\">" . $item['dimensiones'] . "</span>
            <span class=\"item_localizacion\">" . $item['location_tag'] . "</span>
            <span class=\"info_btn_wrap\">
            <span class=\"extra_info_btn\"><i class=\"fas fa-info-circle\" title=\"Más Info\"></i></span>
            </span>
        </li>
        <hr class=\"barra_lista\">";
    };
  };

  if (isset($_POST['id_info_requested'])) {
    $id = $_POST['id_info_requested'];

    $consulta_info = $conexion->prepare("SELECT * FROM inventario WHERE id = :id");
    $consulta_info->execute([':id' => $id]);
    $info = $consulta_info->fetch(PDO::FETCH_ASSOC);

    $tabla_bien = '';

    if (strpos($info['localizacion'], 'C') !== false) {
        $tabla_bien = 'casa';
    }elseif(strpos($info['localizacion'], 'D') !== false){
        $tabla_bien = 'departamento';
    }elseif (strpos($info['localizacion'], 'L') !== false) {
        $tabla_bien = 'local';
    }elseif (strpos($info['localizacion'], 'T') !== false) {
        $tabla_bien = 'terreno';
    };

    $consulta_direccion = $conexion->prepare("SELECT direccion, direccion_complemento FROM $tabla_bien WHERE referencia = :referencia");
    $consulta_direccion->execute([':referencia' => $info['localizacion']]);
    $direccion = $consulta_direccion->fetch(PDO::FETCH_ASSOC);

    $consulta_agente = $conexion->prepare("SELECT nombre, apellido, cargo FROM agentes WHERE id = :id");
    $consulta_agente->execute([':id' => $info['agente_retiro']]);
    $agente = $consulta_agente->fetch(PDO::FETCH_ASSOC);

    echo"
        <h2>Información</h2>
        <hr class=\"trazo_popup\">
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Id:</span>
            <span class=\"contenido_linea\">" . $info['id'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Item:</span>
            <span class=\"contenido_linea\">" . $info['item'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Dimensiones:</span>
            <span class=\"contenido_linea\">" . $info['dimensiones'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Zona:</span>
            <span class=\"contenido_linea\">" . $info['location_tag'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Referencia Bien Inmueble:</span>
            <span class=\"contenido_linea\">" . $info['localizacion'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Dirección:</span>
            <span class=\"contenido_linea\">" . $direccion['direccion'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Dirección Complemento:</span>
            <span class=\"contenido_linea\">" . $direccion['direccion_complemento'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Estado:</span>
            <span class=\"contenido_linea\">" . $info['estado'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Retirado por:</span>
            <span class=\"contenido_linea\">ID: " . $info['agente_retiro'] . "</br>" . $agente['nombre'] . " " . $agente['apellido'] . " (" . $agente['cargo'] . ")</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Retirado en fecha:</span>
            <span class=\"contenido_linea\">" . $info['fecha_retiro'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Previamente retirado por:</span>
            <span class=\"contenido_linea\">" . $info['ultimo_retiro_agente'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Previo retiro fecha:</span>
            <span class=\"contenido_linea\">" . $info['ultimo_retiro_fecha'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Costo:</span>
            <span class=\"contenido_linea\">" . $info['costo'] . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Prestado:</span>
            <span class=\"contenido_linea\">" . (($info['prestado'] == 1) ? "SI" : "NO") . "</span>
        </span>
        <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Comentarios:</span>
            <span class=\"contenido_linea\">" . $info['comentarios'] . "</span>
        </span>
    ";
  };

  if (isset($_POST["group_info_requested"])) {
    $grupo = $_POST["group_info_requested"];

    $consulta_group_info = $conexion->prepare("SELECT id FROM inventario WHERE grupo = :grupo AND devuelto = 1");
    $consulta_group_info->execute([':grupo' => $grupo]);
    $group_info = $consulta_group_info->fetchAll(PDO::FETCH_ASSOC);

    echo "
    <div class=\"escoger_menus_wrap\">
    <div class=\"opciones_container\">
    <h2>Escoge un item por su ID</h2>
    <hr class=\"trazo_popup\">";
    foreach ($group_info as $element) {
        echo"<span class=\"opcion_wrap\">
        <span class=\"opcion_btn escoger\" name=\"" . $element["id"] . "\">" . $element["id"] . "</span>
        </span>";
    };

    echo"</div>
    <div class=\"retiro_form\">
    <span class=\"back_btn_retiro_form\"><i class=\"fas fa-arrow-circle-left\"></i></span>
    <h2>¿Donde se encontrará el item escogido?</h2>
    <hr class=\"trazo_popup\">
    <span class=\"id_option_title\"></span>
    <label for=\"referencia_localizacion\" class=\"referencia_localizacion_label\">Referencia del Bien Inmueble</label>
    <input type=\"text\" name=\"referencia_localizacion\" class=\"referencia_localizacion\" value=\"\">
    <span class=\"retirar_confirmar_btn\">RETIRAR ITEM</span>
    </div>
    </div>";
    
  };


  if (isset($_POST["item_id"]) && isset($_POST["referencia_bien"]) && isset($_POST["agente_id"])) {
    $item_id = $_POST["item_id"];
    $referencia_bien = $_POST["referencia_bien"];
    $agente_id = $_POST["agente_id"];

    $tabla_bien = '';

    if (strpos($referencia_bien, 'C') !== false) {
        $tabla_bien = 'casa';
    }elseif(strpos($referencia_bien, 'D') !== false){
        $tabla_bien = 'departamento';
    }elseif (strpos($referencia_bien, 'L') !== false) {
        $tabla_bien = 'local';
    }elseif (strpos($referencia_bien, 'T') !== false) {
        $tabla_bien = 'terreno';
    };

    $consulta_bien_exist = $conexion->prepare("SELECT referencia FROM $tabla_bien WHERE referencia = :referencia");
    $consulta_bien_exist->execute([':referencia' => $referencia_bien]);
    $bien_exist = $consulta_bien_exist->fetch(PDO::FETCH_ASSOC);

    if ($bien_exist == '') {
        echo"ERROR REFERENCIA";
    } else {

        $consulta_location = $conexion->prepare("SELECT location_tag FROM $tabla_bien WHERE referencia = :referencia");
        $consulta_location->execute([':referencia' => $referencia_bien]);
        $location = $consulta_location->fetch(PDO::FETCH_ASSOC);

        $current_date = date("Y-m-d");

        $statement_retiro = $conexion->prepare("UPDATE inventario SET localizacion = :localizacion, location_tag = :location_tag, agente_retiro = :agente_retiro, fecha_retiro = :fecha_retiro, devuelto = 0 WHERE id = :id");
        $statement_retiro->execute([':id' => $item_id, ':localizacion' => $referencia_bien, ':location_tag' => $location['location_tag'], ':agente_retiro' => $agente_id, ':fecha_retiro' => $current_date]);
        
        echo"EXITO";
    };

  };

  if (isset($_POST["group_add_code_requested"])) {
        $group_to_add = $_POST["group_add_code_requested"];

        function generateRandomString($length) {
            $characters = 'abefghijkmnopqrsuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        };

        $item_code = "#" . generateRandomString(5) . date("Ymd");

        $consulta_ids = $conexion->prepare("SELECT id FROM inventario");
        $consulta_ids->execute();
        $ids = $consulta_ids->fetchAll(PDO::FETCH_COLUMN);

        while (in_array($item_code, $ids, true)) {
            $item_code = "#" . generateRandomString(5) . date("Ymd");
        };

        echo"
        <h2>Incrementar un tipo de Item</h2>
        <hr class=\"trazo_popup\">
        <input type=\"hidden\" name=\"hidden_group_item\" class=\"hidden_group_item\" value=\"" . $group_to_add . "\">
        <label for=\"nuevo_id_item\" class=\"nuevo_id_item_label\">ID a colocar en el Item</label>
        <input readonly type=\"text\" name=\"nuevo_id_item\" class=\"nuevo_id_item\" value=\"" . $item_code . "\">
        <label for=\"nuevo_id_costo\" class=\"nuevo_id_costo_label\">Costo del Item</label>
        <input type=\"text\" name=\"nuevo_id_costo\" class=\"nuevo_id_costo\" value=\"\" placeholder=\"Ej: 50 Bs\">
        <span class=\"mensaje_advertensia_agregar_item\">Asegurese de anotar el ID sobre el Item antes de pulsar el siguiente botón</span>
        <span class=\"agregar_item_suplementario_confirmar\">AGREGAR ITEM</span>
        ";

  };

  if (isset($_POST["id_new_item"]) && isset($_POST["id_new_group"]) && isset($_POST["id_new_agencia"]) && isset($_POST["id_new_costo"])) {
        $id_new = $_POST["id_new_item"];
        $grupo = $_POST["id_new_group"];
        $agencia = $_POST["id_new_agencia"];
        $costo= $_POST["id_new_costo"];

        $consulta_datos_grupo = $conexion->prepare("SELECT item, dimensiones FROM inventario WHERE grupo = :grupo");
        $consulta_datos_grupo->execute([":grupo" => $grupo]);
        $datos_grupo = $consulta_datos_grupo->fetch(PDO::FETCH_ASSOC);

        $statement_registrar = $conexion->prepare(
            "INSERT INTO inventario (id, item, dimensiones, grupo, agencia, costo, devuelto, estado) VALUES (:id, :item, :dimensiones, :grupo, :agencia, :costo, :devuelto, :estado)"
           );

        $statement_registrar->execute(array(
            ':id' => $id_new,
            ':item' => $datos_grupo['item'],
            ':dimensiones' => $datos_grupo['dimensiones'],
            ':grupo' => $grupo,
            ':agencia' => $agencia,
            ':costo' => $costo,
            ':devuelto' => 1,
            ':estado' => 'Nuevo',
        ));

        echo"EXITO";
    };

  if(isset($_POST["new_item_requested_agencia"])){

        function generateRandomString($length) {
            $characters = 'abefghijkmnopqrsuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        };

        $item_code = "#" . generateRandomString(5) . date("Ymd");

        $consulta_ids = $conexion->prepare("SELECT id FROM inventario");
        $consulta_ids->execute();
        $ids = $consulta_ids->fetchAll(PDO::FETCH_COLUMN);

        while (in_array($item_code, $ids, true)) {
            $item_code = "#" . generateRandomString(5) . date("Ymd");
        };

        $new_group = generateRandomString(7);

        $consulta_grupos = $conexion->prepare("SELECT grupo FROM inventario");
        $consulta_grupos->execute();
        $grupos = $consulta_grupos->fetchAll(PDO::FETCH_COLUMN);

        while (in_array($new_group, $grupos, true)) {
            $new_group = generateRandomString(7);
        };

        echo"
        <h2>Agregar un Nuevo tipo de Item</h2>
        <hr class=\"trazo_popup\">
        <input type=\"hidden\" name=\"hidden_group_item\" class=\"hidden_group_item\" value=\"" . $new_group . "\">
        <label for=\"nuevo_id_item\" class=\"nuevo_id_item_label\">ID a colocar en el Item</label>
        <input readonly type=\"text\" name=\"nuevo_id_item\" class=\"nuevo_id_item\" value=\"" . $item_code . "\">
        <label for=\"nuevo_id_costo\" class=\"nuevo_id_costo_label\">Costo del Item</label>
        <input type=\"text\" name=\"nuevo_id_costo\" class=\"nuevo_id_costo\" placeholder=\"Ej: 50 Bs\">

        <label for=\"nuevo_id_descipcion\" class=\"nuevo_id_descipcion_label\">Descripción del Item</label>
        <textarea name=\"nuevo_id_descipcion\" class=\"nuevo_id_descipcion\" placeholder=\"Ej: Cartel plastificado a suejetar con tornillos a pared\" cols=\"60\" rows=\"6\"></textarea>

        <label for=\"nuevo_id_dimensiones\" class=\"nuevo_id_dimensiones_label\">Dimensiones del Item</label>
        <input type=\"text\" name=\"nuevo_id_dimensiones\" class=\"nuevo_id_dimensiones\" placeholder=\"Ej: 1.2m x 1.5m\">

        <span class=\"mensaje_advertensia_agregar_item\">Asegurese de anotar el ID sobre el Item antes de pulsar el siguiente botón</span>
        <span class=\"agregar_nuevo_item_confirmar\">AGREGAR ITEM</span>
        ";
    };

    if (isset($_POST["new_item_id"]) && isset($_POST["new_item_group"]) && isset($_POST["new_item_agencia"]) && isset($_POST["new_item_costo"]) && isset($_POST["new_item_descripcion"]) && isset($_POST["new_item_dimensiones"])) {
        $id_new = $_POST["new_item_id"];
        $grupo = $_POST["new_item_group"];
        $agencia = $_POST["new_item_agencia"];
        $costo= $_POST["new_item_costo"];
        $descripcion = $_POST["new_item_descripcion"];
        $dimenciones = $_POST["new_item_dimensiones"];

        $statement_registrar_new = $conexion->prepare(
            "INSERT INTO inventario (id, item, dimensiones, grupo, agencia, costo, devuelto, estado) VALUES (:id, :item, :dimensiones, :grupo, :agencia, :costo, :devuelto, :estado)"
           );

        $statement_registrar_new->execute(array(
            ':id' => $id_new,
            ':item' => $descripcion,
            ':dimensiones' => $dimenciones,
            ':grupo' => $grupo,
            ':agencia' => $agencia,
            ':costo' => $costo,
            ':devuelto' => 1,
            ':estado' => 'Nuevo',
        ));

        echo"EXITO";
    };

    if (isset($_POST["retornar_popup_request"])) {

        echo"
        <h2>Retornar un Item</h2>
        <hr class=\"trazo_popup\">
        <label for=\"id_item_retornar\" class=\"id_item_retornar_label\">ID del ITEM</label>
        <input type=\"text\" name=\"id_item_retornar\" class=\"id_item_retornar\">
        <label for=\"retorno_estado\" class=\"retorno_estado_label\">Estado del Item</label>
        <select name=\"retorno_estado\" class=\"retorno_estado\">
            <option>Nuevo</option>
            <option>Buen Estado</option>
            <option>Usado</option>
            <option>Maltratado</option>
            <option>Dañado</option>
        </select>
        <label for=\"retorno_comentario\" class=\"retorno_comentario_label\">Commentarios (opcional)</label>
        <textarea name=\"retorno_comentario\" class=\"retorno_comentario\" placeholder=\"Ej: Se despinto por el sol\" cols=\"60\" rows=\"6\"></textarea>
        <span class=\"mensaje_advertensia_agregar_item\">Devuelvalo a la Agencia Correcta y que los comentarios sean breves</span>
        <span class=\"retornar_item_confirmar\">RETORNAR ITEM</span>
        ";
    };

    if (isset($_POST['retornar_id']) && isset($_POST['retornar_estado']) && isset($_POST['retornar_comentario']) && isset($_POST['agente_retorno'])) {
       $id = $_POST['retornar_id'];
       $estado = $_POST['retornar_estado'];
       $comentario = $_POST['retornar_comentario'];
       $agente_id = $_POST['agente_retorno'];

       $consulta_ids = $conexion->prepare("SELECT id FROM inventario");
       $consulta_ids->execute();
       $ids = $consulta_ids->fetchAll(PDO::FETCH_COLUMN);

       if (in_array($id, $ids, true)) {
        if($comentario !== ''){
            $consulta_agente = $conexion->prepare("SELECT nombre, apellido FROM agentes WHERE id = :id");
            $consulta_agente->execute([':id' => $agente_id]);
            $agente = $consulta_agente->fetch(PDO::FETCH_ASSOC);
    
            $consulta_datos = $conexion->prepare("SELECT comentarios, agente_retiro, fecha_retiro FROM inventario WHERE id = :id");
            $consulta_datos->execute([":id" => $id]);
            $datos = $consulta_datos->fetch(PDO::FETCH_ASSOC);

            $comentario_final = '';

            if ($datos['comentarios'] == '') {
                $comentario_final = "(" . $agente['nombre'] . " " . $agente['apellido'] . ") " . $comentario;
            } else {
                $comentario_final = $datos['comentarios'] . "</br>(" . $agente['nombre'] . " " . $agente['apellido'] . ") " . $comentario;
            };
    
            $statement_retorno1 = $conexion->prepare("UPDATE inventario SET localizacion = '', location_tag = '', agente_retiro = '', fecha_retiro = '0000-00-00', devuelto = 1, prestado = 0, estado = :estado, comentarios = :comentarios, ultimo_retiro_agente = :ultimo_retiro_agente, ultimo_retiro_fecha = :ultimo_retiro_fecha WHERE id = :id");
            $statement_retorno1->execute([':id' => $id, ':estado' => $estado, ':comentarios' => $comentario_final, ':ultimo_retiro_agente' => $datos['agente_retiro'], ':ultimo_retiro_fecha' => $datos['fecha_retiro']]);
           }else {
            $consulta_datos = $conexion->prepare("SELECT agente_retiro, fecha_retiro FROM inventario WHERE id = :id");
            $consulta_datos->execute([":id" => $id]);
            $datos = $consulta_datos->fetch(PDO::FETCH_ASSOC);
    
            $statement_retorno2 = $conexion->prepare("UPDATE inventario SET localizacion = '', location_tag = '', agente_retiro = '', fecha_retiro = '0000-00-00', devuelto = 1, prestado = 0, estado = :estado, ultimo_retiro_agente = :ultimo_retiro_agente, ultimo_retiro_fecha = :ultimo_retiro_fecha WHERE id = :id");
            $statement_retorno2->execute([':id' => $id, ':estado' => $estado, ':ultimo_retiro_agente' => $datos['agente_retiro'], ':ultimo_retiro_fecha' => $datos['fecha_retiro']]);
           };
    
           echo"Exito";
       }else {
            echo"ERROR: ID no existe";
       };

    };

    if (isset($_POST["group_edition_requested"])) {
        $grupo = $_POST["group_edition_requested"];

        $consulta_group_info = $conexion->prepare("SELECT id, devuelto FROM inventario WHERE grupo = :grupo");
        $consulta_group_info->execute([':grupo' => $grupo]);
        $group_info = $consulta_group_info->fetchAll(PDO::FETCH_ASSOC);
    
        echo "
        <div class=\"escoger_menus_wrap\">
        <div class=\"opciones_container\">
        <h2>Items del mismo grupo</h2>
        <hr class=\"trazo_popup\">";
        foreach ($group_info as $element) {
            if ($element['devuelto'] == 1) {
                echo"<span class=\"opcion_wrap\">
                <span class=\"opcion_btn edicion\" name=\"" . $element["id"] . "\">" . $element["id"] . "</span>
                </span>";
            }elseif($element['devuelto'] == 0){
                echo"<span class=\"opcion_wrap\">
                <span class=\"opcion_btn edicion out\" name=\"" . $element["id"] . "\">" . $element["id"] . "</span>
                </span>";
            };
            
        };
    
        echo"</div>

        <div class=\"opciones_edicion_wrap\">
        <span class=\"back_btn_edicion_list\"><i class=\"fas fa-arrow-circle-left\"></i></span>
        <h2>Opciones de Edición</h2>
        <hr class=\"trazo_popup\">
        <span class=\"id_option_title unique\"></span>
        <span class=\"prestar_btn\">PRESTAR ITEM</span>
        <div class=\"remover_btn_wrap\">
        <span class=\"remover_btn\">REMOVER ITEM</span>
        <span class=\"remover_confirmar_btn\"><i class=\"fa fa-trash-alt\"></i></span>
        </div>
        </div>

        <div class=\"retiro_form\">
        <span class=\"back_btn_edicion_options\"><i class=\"fas fa-arrow-circle-left\"></i></span>
        <h2>¿Donde se encontrará el item escogido?</h2>
        <hr class=\"trazo_popup\">
        <span class=\"id_option_title\"></span>
        <label for=\"referencia_localizacion\" class=\"referencia_localizacion_label\">Referencia del Bien Inmueble</label>
        <input type=\"text\" name=\"referencia_localizacion\" class=\"referencia_localizacion\" value=\"\">
        <label for=\"id_prestatario\" class=\"id_prestatario_label\">ID del Agente Prestatario</label>
        <input type=\"text\" name=\"id_prestatario\" class=\"id_prestatario\" value=\"\">
        <span class=\"verificar_prestamo_btn\">VERIFICAR</span>
        </div>

        <div class=\"prestamo_datos\">
        <span class=\"back_btn_prestamo\"><i class=\"fas fa-arrow-circle-left\"></i></span>
        <h2>Verifique los datos del Agente Prestatario</h2>
        <hr class=\"trazo_popup\">
        <span class=\"id_option_title\"></span>
        <div class=\"info_prestatario_lista\">
        </div>
        <span class=\"confirmar_prestamo_btn\">CONFIRMAR PRESTAMO</span>
        </div>
        </div>";

    };

    if (isset($_POST["referencia_check"]) && isset($_POST["agente_check"])) {
        $referencia = $_POST["referencia_check"];
        $agente = $_POST["agente_check"];

        $tabla_bien = '';

        if (strpos($referencia, 'C') !== false) {
            $tabla_bien = 'casa';
        }elseif(strpos($referencia, 'D') !== false){
            $tabla_bien = 'departamento';
        }elseif (strpos($referencia, 'L') !== false) {
            $tabla_bien = 'local';
        }elseif (strpos($referencia, 'T') !== false) {
            $tabla_bien = 'terreno';
        };

        $consulta_bien_exist = $conexion->prepare("SELECT referencia FROM $tabla_bien WHERE referencia = :referencia");
        $consulta_bien_exist->execute([':referencia' => $referencia]);
        $bien_exist = $consulta_bien_exist->fetch(PDO::FETCH_ASSOC);

        $consulta_agente_exist = $conexion->prepare("SELECT id, nombre, apellido, cargo, agencia_id FROM agentes WHERE id = :id");
        $consulta_agente_exist->execute([':id' => $agente]);
        $agente_exist = $consulta_agente_exist->fetch(PDO::FETCH_ASSOC);

        if ($bien_exist == '' || $agente_exist == '') {
            echo"ERROR DE DATOS";
        }else {
            $consulta_agencia = $conexion->prepare("SELECT location_tag FROM agencias WHERE id = :id");
            $consulta_agencia->execute([':id' => $agente_exist['agencia_id']]);
            $agencia = $consulta_agencia->fetch(PDO::FETCH_ASSOC);
           echo"
            <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Nombre:</span>
            <span class=\"contenido_linea\">" . $agente_exist['nombre'] . " " . $agente_exist['apellido'] . "</span>
            </span>
            <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Cargo:</span>
            <span class=\"contenido_linea\">" . $agente_exist['cargo'] . "</span>
            </span>
            <span class=\"linea_wrap\">
            <span class=\"titulo_linea\">Agencia:</span>
            <span class=\"contenido_linea\">" . $agencia['location_tag'] . "</span>
            </span>
           ";
        };
    };

    if (isset($_POST["item_prestamo"]) && isset($_POST["referencia_prestamo"]) && isset($_POST["agente_prestamo"])) {
       $id = $_POST["item_prestamo"];
       $localizacion = $_POST["referencia_prestamo"];
       $agente_retiro = $_POST["agente_prestamo"];

       $tabla_bien = '';

        if (strpos($localizacion, 'C') !== false) {
            $tabla_bien = 'casa';
        }elseif(strpos($localizacion, 'D') !== false){
            $tabla_bien = 'departamento';
        }elseif (strpos($localizacion, 'L') !== false) {
            $tabla_bien = 'local';
        }elseif (strpos($localizacion, 'T') !== false) {
            $tabla_bien = 'terreno';
        };

        $consulta_location = $conexion->prepare("SELECT location_tag FROM $tabla_bien WHERE referencia = :referencia");
        $consulta_location->execute([':referencia' => $localizacion]);
        $location = $consulta_location->fetch(PDO::FETCH_ASSOC);

        $current_date = date("Y-m-d");

        $statement_retiro = $conexion->prepare("UPDATE inventario SET localizacion = :localizacion, location_tag = :location_tag, agente_retiro = :agente_retiro, fecha_retiro = :fecha_retiro, devuelto = 0, prestado = 1 WHERE id = :id");
        $statement_retiro->execute([':id' => $id, ':localizacion' => $localizacion, ':location_tag' => $location['location_tag'], ':agente_retiro' => $agente_retiro, ':fecha_retiro' => $current_date]);
        
        echo"EXITO";
    };

    if (isset($_POST["item_remover"])) {
        $item_remover = $_POST["item_remover"];

        $consulta_borrar_item =	$conexion->prepare("DELETE FROM inventario WHERE id = :id");
        $consulta_borrar_item->execute([':id' => $item_remover]);

        echo"EXITO";
    };

};

?>