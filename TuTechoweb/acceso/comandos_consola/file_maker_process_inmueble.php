<?php //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
    if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
      header('Location: ../cerrar_session.php');
    };
}else {
    header('Location: ../login.php');
};

if (!isset($file_maker_entry)) {
    header('Location: ../acceso.php');
};

$consulta_registrador =	$conexion_load->prepare("SELECT id FROM agentes WHERE usuario = :usuario");
$consulta_registrador->execute([":usuario" => $_SESSION['usuario']]);
$registrador	=	$consulta_registrador->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['nombre_cliente']) || isset($_POST['apellido_cliente']) && isset($_POST['tipo_doc_identidad']) && isset($_POST['numero_doc_identidad']) && isset($_POST['email_cliente']) && isset($_POST['telefono_cliente']) && isset($_POST['direccion_cliente']) && isset($_POST['agencia_id']) && isset($_POST['pais']) && isset($_POST['departamento']) && isset($_POST['ciudad']) && isset($_POST['direccion_cliente']) && isset($_POST['direccion_inmueble']) && isset($_POST['direccion_inmueble_complemento']) && isset($_POST['base_imponible']) && isset($_POST['impuestos']) && isset($_POST['avaluo']) && isset($_POST['mantenimiento']) && isset($_POST['precio_inmueble']) && isset($_POST['exclusivo']) && isset($_POST['contrato_especial']) && isset($_POST['doc_selected']) && isset($_POST['tipo_inmueble'])){

    $nombre_cliente = filter_var($_POST['nombre_cliente'], FILTER_SANITIZE_STRING);
    $apellido_cliente = filter_var($_POST['apellido_cliente'], FILTER_SANITIZE_STRING);
    $tipo_doc_identidad = filter_var($_POST['tipo_doc_identidad'], FILTER_SANITIZE_STRING);
    $numero_doc_identidad = filter_var($_POST['numero_doc_identidad'], FILTER_SANITIZE_STRING);
    $email_cliente = filter_var($_POST['email_cliente'], FILTER_SANITIZE_EMAIL);
    $telefono_cliente = filter_var($_POST['telefono_cliente'], FILTER_SANITIZE_STRING);
    $direccion_cliente = filter_var($_POST['direccion_cliente'], FILTER_SANITIZE_STRING);
    
    $agencia_id = filter_var($_POST['agencia_id'], FILTER_SANITIZE_STRING);
    $pais = filter_var($_POST['pais'], FILTER_SANITIZE_STRING);
    $departamento = filter_var($_POST['departamento'], FILTER_SANITIZE_STRING);
    $ciudad = filter_var($_POST['ciudad'], FILTER_SANITIZE_STRING);
    if (isset($_POST['barrio'])) {
        $barrio = filter_var($_POST['barrio'], FILTER_SANITIZE_STRING);
    }else {
        $barrio = '';
    };
    $direccion_inmueble = filter_var($_POST['direccion_inmueble'], FILTER_SANITIZE_STRING);
    $direccion_inmueble_complemento = filter_var($_POST['direccion_inmueble_complemento'], FILTER_SANITIZE_STRING);
    $base_imponible = filter_var($_POST['base_imponible'], FILTER_SANITIZE_NUMBER_INT);
    $impuestos = filter_var($_POST['impuestos'], FILTER_SANITIZE_NUMBER_INT);
    $avaluo = filter_var($_POST['avaluo'], FILTER_SANITIZE_NUMBER_INT);
    $mantenimiento = filter_var($_POST['mantenimiento'], FILTER_SANITIZE_NUMBER_INT);
    if (isset($_POST['superficie_inmueble'])) {
        $superficie_inmueble = filter_var($_POST['superficie_inmueble'], FILTER_SANITIZE_NUMBER_INT);
    };
    if (isset($_POST['superficie_terreno'])) {
        $superficie_terreno = filter_var($_POST['superficie_terreno'], FILTER_SANITIZE_NUMBER_INT);
    };
    $precio_inmueble = filter_var($_POST['precio_inmueble'], FILTER_SANITIZE_NUMBER_INT);
    if (isset($_POST['pre_venta'])) {
        $pre_venta = filter_var($_POST['pre_venta'], FILTER_SANITIZE_NUMBER_INT);
    }else {
        $pre_venta = 0;
    };
    $exclusivo = filter_var($_POST['exclusivo'], FILTER_SANITIZE_NUMBER_INT);
    $contrato_especial = filter_var($_POST['contrato_especial'], FILTER_SANITIZE_NUMBER_INT);
    if (isset($_POST['contrato_especial_comentario'])) {
        $contrato_especial_comentario = filter_var($_POST['contrato_especial_comentario'], FILTER_SANITIZE_STRING);
    }else {
        $contrato_especial_comentario = '';
    };
    if (isset($_POST['anticretico'])) {
        $anticretico = filter_var($_POST['anticretico'], FILTER_SANITIZE_NUMBER_INT);
    }else {
        $anticretico = 0;
    };
    if (isset($_POST['gestion'])) {
        $gestion = filter_var($_POST['gestion'], FILTER_SANITIZE_NUMBER_INT);
    }else {
        $gestion = 0;
    };

    if (isset($_POST['conciliador'])) {
        $conciliador = filter_var($_POST['conciliador'], FILTER_SANITIZE_STRING);
    }else {
        $conciliador = '';
    };

    if (isset($_POST['opcion_conciliacion']) && $conciliador !== '') {
        $opcion_conciliacion = filter_var($_POST['opcion_conciliacion'], FILTER_SANITIZE_STRING);
    }else {
        $opcion_conciliacion = '';
    };


    $tipo_bien = filter_var($_POST['tipo_inmueble'], FILTER_SANITIZE_STRING);
    $date = new DateTime();
    $fecha = $date->format("mdy");

    $referencia = "#" . generateRandomString(4) . strtoupper($tipo_bien[0]) . $fecha;
    $referencia_edicion = filter_var($_POST['id_edicion'], FILTER_SANITIZE_STRING);
    
    $tipo_file = filter_var($_POST['tipo_file'], FILTER_SANITIZE_STRING);
    $estado = 'En ' . ucfirst($tipo_file);



    if(isset($_POST['barrio'])){
        $location_tag = $_POST['barrio'];
    } else {
        $location_tag = $_POST['ciudad'];
    };

    if (isset($_POST['superficie_terreno'])) {
       if ($_POST['superficie_terreno'] > 10000) {
            $superficie_terreno_medida = 'hect';
       } else {
            $superficie_terreno_medida = 'm&sup2';
       };
    };
    

    if ($modo == 'first_entry') {
        $carpeta_destino = '../../bienes_inmuebles_files/' . $pais_sent . '/' . $referencia;
    } else if($modo == 'edicion'){
        $carpeta_destino = '../../bienes_inmuebles_files/' . $pais_sent . '/' . $referencia_edicion;
    };
    
    $keys_array_docs = array_keys($_FILES);
    
    foreach ($keys_array_docs as $doc) { //SE FILTRAN LAS FOTOS EN LOS DISTINTOS ARRAYS DE ARRIBA
        
        $temp_name = $_FILES[$doc]['tmp_name'];
    
        if ($temp_name !== '') {
            if (mime_content_type($temp_name) !== 'application/pdf') {
                $_SESSION['exito_bien_registrado'] = 'Error - Se intentó ingresar un documento con extension erronea';
                header('Location: ../acceso.php');
            }; 
        };
        
    
    };
    
    foreach ($keys_array_docs as $doc) { //SE FILTRAN LAS FOTOS EN LOS DISTINTOS ARRAYS DE ARRIBA
        
        $temp_name = $_FILES[$doc]['tmp_name'];

        if ($temp_name !== '') {
    
            if (mime_content_type($temp_name) == 'application/pdf') {
                $doc_dir = $carpeta_destino . '/' . $doc . '.pdf';
            };
        
            if(!is_dir($carpeta_destino)){
                @mkdir($carpeta_destino, 0700);
            };
        
            move_uploaded_file($temp_name, $doc_dir);//subimos la nueva foto con el nuevo titulo al file o lo sobreescribe si es modo edicion
        };
    };
    
    if ($modo == 'first_entry') {

        $tabla = "borradores_" . $tipo_bien;
    
        if ($tipo_bien == "casa") {
            $statement = $conexion_load->prepare("INSERT INTO $tabla (referencia, tipo_bien, precio, base_imponible, mantenimiento, avaluo, estado, exclusivo, pre_venta, anticretico, pais, departamento, ciudad, barrio, location_tag, direccion, direccion_complemento, superficie_inmueble, superficie_terreno, superficie_terreno_medida, impuestos, impuestos_moneda, propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, contrato_especial, contrato_especial_comentario, gestion_acordada, jefe_agencia_id, agencia_registro_id, propietario_tipo_doc, conciliador, conciliacion_tipo) VALUES (:referencia, :tipo_bien, :precio, :base_imponible, :mantenimiento, :avaluo, :estado, :exclusivo, :pre_venta, :anticretico, :pais, :departamento, :ciudad, :barrio, :location_tag, :direccion, :direccion_complemento, :superficie_inmueble, :superficie_terreno, :superficie_terreno_medida, :impuestos, :impuestos_moneda, :propietario_nombre, :propietario_apellido, :propietario_telefono, :propietario_email, :propietario_direccion, :propietario_carnet, :contrato_especial, :contrato_especial_comentario, :gestion_acordada, :jefe_agencia_id, :agencia_registro_id, :propietario_tipo_doc, :conciliador, :conciliacion_tipo)"); //preparar la consulata INSERT
            $statement->execute([
                ':referencia' => $referencia,
                ':tipo_bien' => $tipo_bien,
                ':precio' => $precio_inmueble,
                ':base_imponible' => $base_imponible,
                ':mantenimiento' => $mantenimiento,
                ':avaluo' => $avaluo,
                ':estado' => $estado,
                ':exclusivo' => $exclusivo,
                ':pre_venta' => $pre_venta,
                ':anticretico' => $anticretico,
                ':pais' => $pais,
                ':departamento' => $departamento,
                ':ciudad' => $ciudad,
                ':barrio' => $barrio,
                ':location_tag' => $location_tag,
                ':direccion' => $direccion_inmueble,
                ':direccion_complemento' => $direccion_inmueble_complemento,
                ':superficie_inmueble' => $superficie_inmueble,
                ':superficie_terreno' => $superficie_terreno,
                ':superficie_terreno_medida' => $superficie_terreno_medida,
                ':impuestos' => $impuestos,
                ':impuestos_moneda' => '$us',
                ':propietario_nombre' => $nombre_cliente,
                ':propietario_apellido' => $apellido_cliente,
                ':propietario_telefono' => $telefono_cliente,
                ':propietario_email' => $email_cliente,
                ':propietario_direccion' => $direccion_cliente,
                ':propietario_carnet' => $numero_doc_identidad,
                ':contrato_especial' => $contrato_especial,
                ':contrato_especial_comentario' => $contrato_especial_comentario,
                ':gestion_acordada' => $gestion,
                ':jefe_agencia_id' => $registrador['id'],
                ':agencia_registro_id' => $agencia_id,
                ':propietario_tipo_doc' => $tipo_doc_identidad,
                ':conciliador' => $conciliador,
                ':conciliacion_tipo' => $opcion_conciliacion
                ]);


        } else if ($tipo_bien == "departamento" || $tipo_bien == "local"){

            $statement = $conexion_load->prepare("INSERT INTO $tabla (referencia, tipo_bien, precio, base_imponible, mantenimiento, avaluo, estado, exclusivo, pre_venta, anticretico, pais, departamento, ciudad, barrio, location_tag, direccion, direccion_complemento, superficie_inmueble, impuestos, impuestos_moneda, propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, contrato_especial, contrato_especial_comentario, gestion_acordada, jefe_agencia_id, agencia_registro_id, propietario_tipo_doc, conciliador, conciliacion_tipo) VALUES (:referencia, :tipo_bien, :precio, :base_imponible, :mantenimiento, :avaluo, :estado, :exclusivo, :pre_venta, :anticretico, :pais, :departamento, :ciudad, :barrio, :location_tag, :direccion, :direccion_complemento, :superficie_inmueble, :impuestos, :impuestos_moneda, :propietario_nombre, :propietario_apellido, :propietario_telefono, :propietario_email, :propietario_direccion, :propietario_carnet, :contrato_especial, :contrato_especial_comentario, :gestion_acordada, :jefe_agencia_id, :agencia_registro_id, :propietario_tipo_doc, :conciliador, :conciliacion_tipo)"); //preparar la consulata INSERT
            $statement->execute([
                ':referencia' => $referencia,
                ':tipo_bien' => $tipo_bien,
                ':precio' => $precio_inmueble,
                ':base_imponible' => $base_imponible,
                ':mantenimiento' => $mantenimiento,
                ':avaluo' => $avaluo,
                ':estado' => $estado,
                ':exclusivo' => $exclusivo,
                ':pre_venta' => $pre_venta,
                ':anticretico' => $anticretico,
                ':pais' => $pais,
                ':departamento' => $departamento,
                ':ciudad' => $ciudad,
                ':barrio' => $barrio,
                ':location_tag' => $location_tag,
                ':direccion' => $direccion_inmueble,
                ':direccion_complemento' => $direccion_inmueble_complemento,
                ':superficie_inmueble' => $superficie_inmueble,
                ':impuestos' => $impuestos,
                ':impuestos_moneda' => '$us',
                ':propietario_nombre' => $nombre_cliente,
                ':propietario_apellido' => $apellido_cliente,
                ':propietario_telefono' => $telefono_cliente,
                ':propietario_email' => $email_cliente,
                ':propietario_direccion' => $direccion_cliente,
                ':propietario_carnet' => $numero_doc_identidad,
                ':contrato_especial' => $contrato_especial,
                ':contrato_especial_comentario' => $contrato_especial_comentario,
                ':gestion_acordada' => $gestion,
                ':jefe_agencia_id' => $registrador['id'],
                ':agencia_registro_id' => $agencia_id,
                ':propietario_tipo_doc' => $tipo_doc_identidad,
                ':conciliador' => $conciliador,
                ':conciliacion_tipo' => $opcion_conciliacion
                ]);



        } else if ($tipo_bien == "terreno"){
            
            $statement = $conexion_load->prepare("INSERT INTO $tabla (referencia, tipo_bien, precio, base_imponible, mantenimiento, avaluo, estado, exclusivo, pre_venta, anticretico, pais, departamento, ciudad, barrio, location_tag, direccion, direccion_complemento, superficie_terreno, superficie_terreno_medida, impuestos, impuestos_moneda, propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, contrato_especial, contrato_especial_comentario, gestion_acordada, jefe_agencia_id, agencia_registro_id, propietario_tipo_doc, conciliador, conciliacion_tipo) VALUES (:referencia, :tipo_bien, :precio, :base_imponible, :mantenimiento, :avaluo, :estado, :exclusivo, :pre_venta, :anticretico, :pais, :departamento, :ciudad, :barrio, :location_tag, :direccion, :direccion_complemento, :superficie_terreno, :superficie_terreno_medida, :impuestos, :impuestos_moneda, :propietario_nombre, :propietario_apellido, :propietario_telefono, :propietario_email, :propietario_direccion, :propietario_carnet, :contrato_especial, :contrato_especial_comentario, :gestion_acordada, :jefe_agencia_id, :agencia_registro_id, :propietario_tipo_doc, :conciliador, :conciliacion_tipo)"); //preparar la consulata INSERT
            $statement->execute([
                ':referencia' => $referencia,
                ':tipo_bien' => $tipo_bien,
                ':precio' => $precio_inmueble,
                ':base_imponible' => $base_imponible,
                ':mantenimiento' => $mantenimiento,
                ':avaluo' => $avaluo,
                ':estado' => $estado,
                ':exclusivo' => $exclusivo,
                ':pre_venta' => $pre_venta,
                ':anticretico' => $anticretico,
                ':pais' => $pais,
                ':departamento' => $departamento,
                ':ciudad' => $ciudad,
                ':barrio' => $barrio,
                ':location_tag' => $location_tag,
                ':direccion' => $direccion_inmueble,
                ':direccion_complemento' => $direccion_inmueble_complemento,
                ':superficie_terreno' => $superficie_terreno,
                ':superficie_terreno_medida' => $superficie_terreno_medida,
                ':impuestos' => $impuestos,
                ':impuestos_moneda' => '$us',
                ':propietario_nombre' => $nombre_cliente,
                ':propietario_apellido' => $apellido_cliente,
                ':propietario_telefono' => $telefono_cliente,
                ':propietario_email' => $email_cliente,
                ':propietario_direccion' => $direccion_cliente,
                ':propietario_carnet' => $numero_doc_identidad,
                ':contrato_especial' => $contrato_especial,
                ':contrato_especial_comentario' => $contrato_especial_comentario,
                ':gestion_acordada' => $gestion,
                ':jefe_agencia_id' => $registrador['id'],
                ':agencia_registro_id' => $agencia_id,
                ':propietario_tipo_doc' => $tipo_doc_identidad,
                ':conciliador' => $conciliador,
                ':conciliacion_tipo' => $opcion_conciliacion
            ]);

        };
    
    } else if($modo == 'edicion'){
        
        $tabla = "borradores_" . $tipo_bien;

        $consulta_tipo_tabla =	$conexion_load->prepare("SELECT referencia FROM $tabla WHERE referencia = :referencia");
        $consulta_tipo_tabla->execute([":referencia" => $referencia_edicion]);
        $tipo_tabla	=	$consulta_tipo_tabla->fetch(PDO::FETCH_ASSOC);

        if ($tipo_tabla['referencia'] == '') {
            $tabla = $tipo_bien;
        };
    
        if ($tipo_bien == "casa") {
            $statement2 = $conexion_load->prepare("UPDATE $tabla SET tipo_bien = :tipo_bien , precio = :precio , base_imponible = :base_imponible, mantenimiento = :mantenimiento, avaluo = :avaluo, estado = :estado, exclusivo = :exclusivo, pre_venta = :pre_venta, anticretico = :anticretico, pais = :pais, departamento = :departamento, ciudad = :ciudad, barrio = :barrio, location_tag = :location_tag, direccion = :direccion, direccion_complemento = :direccion_complemento, superficie_inmueble = :superficie_inmueble, superficie_terreno = :superficie_terreno, superficie_terreno_medida = :superficie_terreno_medida, impuestos = :impuestos, impuestos_moneda = :impuestos_moneda, propietario_nombre = :propietario_nombre, propietario_apellido = :propietario_apellido, propietario_telefono = :propietario_telefono, propietario_email = :propietario_email, propietario_direccion = :propietario_direccion, propietario_carnet = :propietario_carnet, contrato_especial = :contrato_especial, contrato_especial_comentario = :contrato_especial_comentario, gestion_acordada = :gestion_acordada, jefe_agencia_id = :jefe_agencia_id, agencia_registro_id = :agencia_registro_id, propietario_tipo_doc = :propietario_tipo_doc, conciliador =  :conciliador, conciliacion_tipo = :conciliacion_tipo WHERE referencia = :referencia"); //preparar la consulata INSERT
            $statement2->execute([
                ':referencia' => $referencia_edicion,
                ':tipo_bien' => $tipo_bien,
                ':precio' => $precio_inmueble,
                ':base_imponible' => $base_imponible,
                ':mantenimiento' => $mantenimiento,
                ':avaluo' => $avaluo,
                ':estado' => $estado,
                ':exclusivo' => $exclusivo,
                ':pre_venta' => $pre_venta,
                ':anticretico' => $anticretico,
                ':pais' => $pais,
                ':departamento' => $departamento,
                ':ciudad' => $ciudad,
                ':barrio' => $barrio,
                ':location_tag' => $location_tag,
                ':direccion' => $direccion_inmueble,
                ':direccion_complemento' => $direccion_inmueble_complemento,
                ':superficie_inmueble' => $superficie_inmueble,
                ':superficie_terreno' => $superficie_terreno,
                ':superficie_terreno_medida' => $superficie_terreno_medida,
                ':impuestos' => $impuestos,
                ':impuestos_moneda' => '$us',
                ':propietario_nombre' => $nombre_cliente,
                ':propietario_apellido' => $apellido_cliente,
                ':propietario_telefono' => $telefono_cliente,
                ':propietario_email' => $email_cliente,
                ':propietario_direccion' => $direccion_cliente,
                ':propietario_carnet' => $numero_doc_identidad,
                ':contrato_especial' => $contrato_especial,
                ':contrato_especial_comentario' => $contrato_especial_comentario,
                ':gestion_acordada' => $gestion,
                ':jefe_agencia_id' => $registrador['id'],
                ':agencia_registro_id' => $agencia_id,
                ':propietario_tipo_doc' => $tipo_doc_identidad,
                ':conciliador' => $conciliador,
                ':conciliacion_tipo' => $opcion_conciliacion
                ]);

        } else if ($tipo_bien == "departamento" || $tipo_bien == "local"){

            $statement = $conexion_load->prepare("UPDATE $tabla SET tipo_bien = :tipo_bien , precio = :precio , base_imponible = :base_imponible, mantenimiento = :mantenimiento, avaluo = :avaluo, estado = :estado, exclusivo = :exclusivo, pre_venta = :pre_venta, anticretico = :anticretico, pais = :pais, departamento = :departamento, ciudad = :ciudad, barrio = :barrio, location_tag = :location_tag, direccion = :direccion, direccion_complemento = :direccion_complemento, superficie_inmueble = :superficie_inmueble, impuestos = :impuestos, impuestos_moneda = :impuestos_moneda, propietario_nombre = :propietario_nombre, propietario_apellido = :propietario_apellido, propietario_telefono = :propietario_telefono, propietario_email = :propietario_email, propietario_direccion = :propietario_direccion, propietario_carnet = :propietario_carnet, contrato_especial = :contrato_especial, contrato_especial_comentario = :contrato_especial_comentario, gestion_acordada = :gestion_acordada, jefe_agencia_id = :jefe_agencia_id, agencia_registro_id = :agencia_registro_id, propietario_tipo_doc = :propietario_tipo_doc, conciliador =  :conciliador, conciliacion_tipo = :conciliacion_tipo WHERE referencia = :referencia"); //preparar la consulata INSERT
            $statement->execute([
                ':referencia' => $referencia_edicion,
                ':tipo_bien' => $tipo_bien,
                ':precio' => $precio_inmueble,
                ':base_imponible' => $base_imponible,
                ':mantenimiento' => $mantenimiento,
                ':avaluo' => $avaluo,
                ':estado' => $estado,
                ':exclusivo' => $exclusivo,
                ':pre_venta' => $pre_venta,
                ':anticretico' => $anticretico,
                ':pais' => $pais,
                ':departamento' => $departamento,
                ':ciudad' => $ciudad,
                ':barrio' => $barrio,
                ':location_tag' => $location_tag,
                ':direccion' => $direccion_inmueble,
                ':direccion_complemento' => $direccion_inmueble_complemento,
                ':superficie_inmueble' => $superficie_inmueble,
                ':impuestos' => $impuestos,
                ':impuestos_moneda' => '$us',
                ':propietario_nombre' => $nombre_cliente,
                ':propietario_apellido' => $apellido_cliente,
                ':propietario_telefono' => $telefono_cliente,
                ':propietario_email' => $email_cliente,
                ':propietario_direccion' => $direccion_cliente,
                ':propietario_carnet' => $numero_doc_identidad,
                ':contrato_especial' => $contrato_especial,
                ':contrato_especial_comentario' => $contrato_especial_comentario,
                ':gestion_acordada' => $gestion,
                ':jefe_agencia_id' => $registrador['id'],
                ':agencia_registro_id' => $agencia_id,
                ':propietario_tipo_doc' => $tipo_doc_identidad,
                ':conciliador' => $conciliador,
                ':conciliacion_tipo' => $opcion_conciliacion
                ]);



        } else if ($tipo_bien == "terreno"){
            
            $statement = $conexion_load->prepare("UPDATE $tabla SET tipo_bien = :tipo_bien , precio = :precio , base_imponible = :base_imponible, mantenimiento = :mantenimiento, avaluo = :avaluo, estado = :estado, exclusivo = :exclusivo, pre_venta = :pre_venta, anticretico = :anticretico, pais = :pais, departamento = :departamento, ciudad = :ciudad, barrio = :barrio, location_tag = :location_tag, direccion = :direccion, direccion_complemento = :direccion_complemento, superficie_terreno = :superficie_terreno, superficie_terreno_medida = :superficie_terreno_medida, impuestos = :impuestos, impuestos_moneda = :impuestos_moneda, propietario_nombre = :propietario_nombre, propietario_apellido = :propietario_apellido, propietario_telefono = :propietario_telefono, propietario_email = :propietario_email, propietario_direccion = :propietario_direccion, propietario_carnet = :propietario_carnet, contrato_especial = :contrato_especial, contrato_especial_comentario = :contrato_especial_comentario, gestion_acordada = :gestion_acordada, jefe_agencia_id = :jefe_agencia_id, agencia_registro_id = :agencia_registro_id, propietario_tipo_doc = :propietario_tipo_doc, conciliador =  :conciliador, conciliacion_tipo = :conciliacion_tipo WHERE referencia = :referencia"); //preparar la consulata INSERTconsulata INSERT
            $statement->execute([
                ':referencia' => $referencia_edicion,
                ':tipo_bien' => $tipo_bien,
                ':precio' => $precio_inmueble,
                ':base_imponible' => $base_imponible,
                ':mantenimiento' => $mantenimiento,
                ':avaluo' => $avaluo,
                ':estado' => $estado,
                ':exclusivo' => $exclusivo,
                ':pre_venta' => $pre_venta,
                ':anticretico' => $anticretico,
                ':pais' => $pais,
                ':departamento' => $departamento,
                ':ciudad' => $ciudad,
                ':barrio' => $barrio,
                ':location_tag' => $location_tag,
                ':direccion' => $direccion_inmueble,
                ':direccion_complemento' => $direccion_inmueble_complemento,
                ':superficie_terreno' => $superficie_terreno,
                ':superficie_terreno_medida' => $superficie_terreno_medida,
                ':impuestos' => $impuestos,
                ':impuestos_moneda' => '$us',
                ':propietario_nombre' => $nombre_cliente,
                ':propietario_apellido' => $apellido_cliente,
                ':propietario_telefono' => $telefono_cliente,
                ':propietario_email' => $email_cliente,
                ':propietario_direccion' => $direccion_cliente,
                ':propietario_carnet' => $numero_doc_identidad,
                ':contrato_especial' => $contrato_especial,
                ':contrato_especial_comentario' => $contrato_especial_comentario,
                ':gestion_acordada' => $gestion,
                ':jefe_agencia_id' => $registrador['id'],
                ':agencia_registro_id' => $agencia_id,
                ':propietario_tipo_doc' => $tipo_doc_identidad,
                ':conciliador' => $conciliador,
                ':conciliacion_tipo' => $opcion_conciliacion
            ]);

        };

        // Borrar reclamo de la tabla reclamos
        $consulta_reclamo_id =	$conexion_load->prepare("SELECT edicion_reclamo FROM $tabla WHERE referencia = :referencia");
        $consulta_reclamo_id->execute([":referencia" => $referencia_edicion]);
        $reclamo_id	= $consulta_reclamo_id->fetch(PDO::FETCH_ASSOC);
    
        $consulta_borrar_reclamo =	$conexion->prepare("DELETE FROM pendientes WHERE codigo = :codigo");
        $consulta_borrar_reclamo->execute([':codigo' => $reclamo_id['edicion_reclamo']]);

        // Volver a solicitar validacion del inmueble
        $statement = $conexion_load->prepare("UPDATE $tabla SET edicion_file = :edicion_file, edicion_reclamo = :edicion_reclamo WHERE referencia = :referencia"); //preparar la consulata INSERTconsulata INSERT
        $statement->execute([
            ':referencia' => $referencia_edicion,
            ':edicion_file' => '',
            ':edicion_reclamo' => ''
        ]);
    };
    
    


}else {
    $_SESSION['mesage_file'] = 'Error en el ingreso de Datos';
    header('Location: consola_registro_documentos.php');
};

?>
