<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

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

$tutechodb_internacional = "tutechodb_internacional";

try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
};

if(isset($_POST['nombre_agente']) || isset($_POST['apellido_agente']) && isset($_POST['tipo_doc_identidad']) && isset($_POST['numero_doc_identidad']) && isset($_POST['genero_agente']) && isset($_POST['email_agente']) && isset($_POST['direccion_cliente']) && isset($_POST['agencia_id']) && isset($_POST['departamento']) && isset($_POST['ciudad']) && isset($_POST['direccion_agente']) && isset($_POST['direccion_complemento'])){

    $nombre_agente = filter_var($_POST['nombre_agente'], FILTER_SANITIZE_STRING);
    $apellido_agente = filter_var($_POST['apellido_agente'], FILTER_SANITIZE_STRING);
    $tipo_doc_identidad = $_POST['tipo_doc_identidad'];
    $numero_doc_identidad = filter_var($_POST['numero_doc_identidad'], FILTER_SANITIZE_STRING);
    $genero_agente = $_POST['genero_agente'];
    $email_agente = filter_var($_POST['email_agente'], FILTER_SANITIZE_EMAIL);
    $agencia_id = $_POST['agencia_id'];
    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $direccion_agente = filter_var($_POST['direccion_agente'], FILTER_SANITIZE_STRING);
    $direccion_complemento = filter_var($_POST['direccion_complemento'], FILTER_SANITIZE_STRING);

    $id = generateRandomString(10);
    $id_edicion = filter_var($_POST['id_edicion'], FILTER_SANITIZE_STRING);

    if ($modo == 'first_entry') {
        $carpeta_destino = '../../agentes/' . $pais_sent . '/' . $id;
    } else if($modo == 'edicion'){
        $carpeta_destino = '../../agentes/' . $pais_sent . '/' . $id_edicion;
    };


    $consulta_agentes_cupo = $conexion_internacional->prepare("SELECT agencia_cupo, agencia_express_cupo FROM paises WHERE pais = :pais");
    $consulta_agentes_cupo->execute([":pais" => $paisChoice]);
    $agentes_cupo = $consulta_agentes_cupo->fetch(PDO::FETCH_ASSOC);

    $consulta_agencia_express = $conexion_load->prepare("SELECT id, location_tag, express FROM agencias WHERE id = :id");
    $consulta_agencia_express->execute([':id' => $agencia_id]);
    $agencia_express = $consulta_agencia_express->fetch(PDO::FETCH_ASSOC);

    $consulta_agentes_total = $conexion_load->prepare("SELECT id FROM agentes WHERE agencia_id = :agencia_id AND (nivel_acceso = 4 OR nivel_acceso = 10) ");
    $consulta_agentes_total->execute([":agencia_id" => $agencia_id]);
    $agentes_total = $consulta_agentes_total->fetchAll(PDO::FETCH_ASSOC);

    $cupo_agente_disponible = false;

    if ($agencia['express'] == 1) {
        if (count($agentes_total) < $agentes_cupo['agencia_express_cupo']) {
            $cupo_agente_disponible = true;
        };
    }elseif ($agencia['express'] == 0) {
        if (count($agentes_total) < $agentes_cupo['agencia_cupo']) {
            $cupo_agente_disponible = true;
        };
    };

    if ($cupo_agente_disponible == false) {
        $_SESSION['exito_bien_registrado'] = 'Error - Se intentó ingresar más agentes de lo permitido';
        header('Location: ../acceso.php');
    };


    $keys_array_docs = array_keys($_FILES);

    foreach ($keys_array_docs as $doc) { //SE FILTRAN LAS FOTOS EN LOS DISTINTOS ARRAYS DE ARRIBA
        
        $temp_name = $_FILES[$doc]['tmp_name'];

        if (mime_content_type($temp_name) !== 'application/pdf' && mime_content_type($temp_name) !== 'image/jpeg') {
            $_SESSION['exito_bien_registrado'] = 'Error - Se intentó ingresar un documento con extension erronea';
            header('Location: ../acceso.php');
        }; 

    };

    foreach ($keys_array_docs as $doc) { //SE FILTRAN LAS FOTOS EN LOS DISTINTOS ARRAYS DE ARRIBA
        
        $temp_name = $_FILES[$doc]['tmp_name'];

        if (mime_content_type($temp_name) == 'application/pdf') {
            $doc_dir = $carpeta_destino . '/' . $doc . '.pdf';
        }else if (mime_content_type($temp_name) == 'image/jpeg') {
            $doc_dir = $carpeta_destino . '/' . $doc . '.jpg';
        };

        if(!is_dir($carpeta_destino)){
            @mkdir($carpeta_destino, 0700);
        };

        move_uploaded_file($temp_name, $doc_dir);//subimos la nueva foto con el nuevo titulo al file o lo sobreescribe si es modo edicion
        
    };

    $foto_blanco_dir = $carpeta_destino . '/' . 'foto_blanco.jpg';
    $foto_plomo_dir = $carpeta_destino . '/' . 'foto_plomo.jpg';

    $foto_blanco_min_dir = $carpeta_destino . '/' . 'foto_blanco_min.jpg';
    $foto_plomo_min_dir = $carpeta_destino . '/' . 'foto_plomo_min.jpg';

    redimJPG($foto_blanco_dir, $foto_blanco_min_dir, 300, 300, 100);

    redimJPG($foto_plomo_dir, $foto_plomo_min_dir, 300, 300, 100);

    if ($modo == 'first_entry') {

        $statement = $conexion_load->prepare('INSERT INTO agentes (id, nombre, apellido, genero, nivel_acceso, cargo, rango, agencia_id, pais, doc_identidad, doc_tipo, domicilio, domicilio_complemento, email, departamento, ciudad, registrador) VALUES (:id, :nombre, :apellido, :genero, :nivel_acceso, :cargo, :rango, :agencia_id, :pais, :doc_identidad, :doc_tipo, :domicilio, :domicilio_complemento, :email, :departamento, :ciudad, :registrador)'); //preparar la consulata INSERT
        $statement->execute([':id' => $id,
            ':nombre' => $nombre_agente,
            ':apellido' => $apellido_agente,
            ':genero' => $genero_agente,
            ':nivel_acceso' => '4',
            ':cargo' => 'agente_inmobiliario',
            ':rango' => 'A',
            ':agencia_id' => $agencia_id,
            ':pais' => $pais_sent,
            ':doc_identidad' => $numero_doc_identidad,
            ':doc_tipo' => $tipo_doc_identidad,
            ':domicilio' => $direccion_agente,
            ':domicilio_complemento' => $direccion_complemento,
            ':email' => $email_agente,
            ':departamento' => $departamento,
            ':ciudad' => $ciudad,
            ':registrador' => $registrador['id']]);

    } else if($modo == 'edicion'){

        $consulta_reclamo_id =	$conexion_load->prepare("SELECT edicion_reclamo FROM agentes WHERE id = :id");
        $consulta_reclamo_id->execute([":id" => $id_edicion]);
        $reclamo_id	=	$consulta_reclamo_id->fetch(PDO::FETCH_ASSOC);
        
        $statement = $conexion_load->prepare("UPDATE agentes SET nombre = :nombre, apellido = :apellido, genero = :genero, agencia_id = :agencia_id, pais = :pais, doc_identidad = :doc_identidad, doc_tipo = :doc_tipo, domicilio = :domicilio, domicilio_complemento = :domicilio_complemento, email = :email, departamento = :departamento, ciudad = :ciudad, edicion = :edicion, edicion_reclamo = :edicion_reclamo WHERE id = :id");

        $statement->execute([':id' => $id_edicion,
        ':nombre' => $nombre_agente,
        ':apellido' => $apellido_agente,
        ':genero' => $genero_agente,
        ':agencia_id' => $agencia_id,
        ':pais' => $pais_sent,
        ':doc_identidad' => $numero_doc_identidad,
        ':doc_tipo' => $tipo_doc_identidad,
        ':domicilio' => $direccion_agente,
        ':domicilio_complemento' => $direccion_complemento,
        ':email' => $email_agente,
        ':departamento' => $departamento,
        ':ciudad' => $ciudad,
        ':edicion' => '',
        ':edicion_reclamo' => '']);

        $consulta_borrar_reclamo =	$conexion->prepare("DELETE FROM pendientes WHERE codigo = :codigo");
        $consulta_borrar_reclamo->execute([':codigo' => $reclamo_id['edicion_reclamo']]);

    };


}else {
    $_SESSION['mesage_file'] = 'Error en el ingreso de Datos';
    header('Location: consola_registro_documentos.php');
};

?>
