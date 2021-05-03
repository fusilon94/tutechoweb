<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["referencia_sent"]) && isset($_POST["pais_sent"]) && isset($_POST["tabla_sent"])){
    // Capture selected departamento
    $referencia = $_POST["referencia_sent"];
    $pais = $_POST["pais_sent"];
    $tabla = $_POST["tabla_sent"];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $pais;

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };


    if ($tabla == 'borradores_casa') {

        $consulta_datos_inmueble = $conexion->prepare("SELECT propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, propietario_tipo_doc, direccion, direccion_complemento, pais, departamento, ciudad, barrio, location_tag, base_imponible, avaluo, impuestos, mantenimiento, precio, pre_venta, exclusivo, contrato_especial, contrato_especial_comentario, gestion_acordada, anticretico, agencia_registro_id, superficie_inmueble, superficie_terreno, conciliador, conciliacion_tipo FROM $tabla WHERE referencia = :referencia");
        $consulta_datos_inmueble->execute([':referencia' => $referencia]);
        $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);

    }elseif ($tabla == 'borradores_departamento' || $tabla == 'borradores_local') {

        $consulta_datos_inmueble = $conexion->prepare("SELECT propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, propietario_tipo_doc, direccion, direccion_complemento, pais, departamento, ciudad, barrio, location_tag, base_imponible, avaluo, impuestos, mantenimiento, precio, pre_venta, exclusivo, contrato_especial, contrato_especial_comentario, gestion_acordada, anticretico, agencia_registro_id, superficie_inmueble, conciliador, conciliacion_tipo FROM $tabla WHERE referencia = :referencia");
        $consulta_datos_inmueble->execute([':referencia' => $referencia]);
        $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);

    }elseif ($tabla == 'borradores_terreno') {

        $consulta_datos_inmueble = $conexion->prepare("SELECT propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, propietario_tipo_doc, direccion, direccion_complemento, pais, departamento, ciudad, barrio, location_tag, base_imponible, avaluo, impuestos, mantenimiento, precio, pre_venta, exclusivo, contrato_especial, contrato_especial_comentario, gestion_acordada, anticretico, agencia_registro_id, superficie_terreno, conciliador, conciliacion_tipo FROM $tabla WHERE referencia = :referencia");
        $consulta_datos_inmueble->execute([':referencia' => $referencia]);
        $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);

    };

    if ($datos_inmueble == '') {

        $tabla = str_replace("borradores_","",$tabla);

        if ($tabla == 'casa') {

            $consulta_datos_inmueble = $conexion->prepare("SELECT propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, propietario_tipo_doc, direccion, direccion_complemento, pais, departamento, ciudad, barrio, location_tag, base_imponible, avaluo, impuestos, mantenimiento, precio, pre_venta, exclusivo, contrato_especial, contrato_especial_comentario, gestion_acordada, anticretico, agencia_registro_id, superficie_inmueble, superficie_terreno, conciliador, conciliacion_tipo FROM $tabla WHERE referencia = :referencia");
            $consulta_datos_inmueble->execute([':referencia' => $referencia]);
            $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);
    
        }elseif ($tabla == 'departamento' || $tabla == 'local') {
    
            $consulta_datos_inmueble = $conexion->prepare("SELECT propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, propietario_tipo_doc, direccion, direccion_complemento, pais, departamento, ciudad, barrio, location_tag, base_imponible, avaluo, impuestos, mantenimiento, precio, pre_venta, exclusivo, contrato_especial, contrato_especial_comentario, gestion_acordada, anticretico, agencia_registro_id, superficie_inmueble, conciliador, conciliacion_tipo FROM $tabla WHERE referencia = :referencia");
            $consulta_datos_inmueble->execute([':referencia' => $referencia]);
            $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);
    
        }elseif ($tabla == 'terreno') {
    
            $consulta_datos_inmueble = $conexion->prepare("SELECT propietario_nombre, propietario_apellido, propietario_telefono, propietario_email, propietario_direccion, propietario_carnet, propietario_tipo_doc, direccion, direccion_complemento, pais, departamento, ciudad, barrio, location_tag, base_imponible, avaluo, impuestos, mantenimiento, precio, pre_venta, exclusivo, contrato_especial, contrato_especial_comentario, gestion_acordada, anticretico, agencia_registro_id, superficie_terreno, conciliador, conciliacion_tipo FROM $tabla WHERE referencia = :referencia");
            $consulta_datos_inmueble->execute([':referencia' => $referencia]);
            $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);
    
        };

    };

    if (file_exists('../../bienes_inmuebles_files/' . $pais . '/' . $referencia . '/poder_notariado.pdf')) {
        $datos_inmueble['poder_notariado'] = 1;
    }else {
        $datos_inmueble['poder_notariado'] = 0;
    };

    if (file_exists('../../bienes_inmuebles_files/' . $pais . '/' . $referencia . '/doc_identidad_apoderado.pdf')) {
        $datos_inmueble['doc_identidad_apoderado'] = 1;
    }else {
        $datos_inmueble['doc_identidad_apoderado'] = 0;
    };

    if (file_exists('../../bienes_inmuebles_files/' . $pais . '/' . $referencia . '/aprobacion_planos.pdf')) {
        $datos_inmueble['aprobacion_planos'] = 1;
    }else {
        $datos_inmueble['aprobacion_planos'] = 0;
    };

    if (file_exists('../../bienes_inmuebles_files/' . $pais . '/' . $referencia . '/pagos_impuestos.pdf')) {
        $datos_inmueble['pagos_impuestos'] = 1;
    }else {
        $datos_inmueble['pagos_impuestos'] = 0;
    };
    

    echo json_encode($datos_inmueble);
}
?>
