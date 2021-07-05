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

    $consulta_datos_inmueble = $conexion->prepare("SELECT propietario_nombre, propietario_apellido, propietario_carnet, propietario_tipo_doc, location_tag, estado, anticretico, pre_venta, agencia_registro_id, direccion, conciliador, conciliacion_tipo, conciliacion_fecha_limite FROM $tabla WHERE referencia = :referencia AND inactivo = 0 AND visibilidad = 'visible'");
    $consulta_datos_inmueble->execute([':referencia' => $referencia]);
    $datos_inmueble = $consulta_datos_inmueble->fetch(PDO::FETCH_ASSOC);


    $tutechodb_internacional = "tutechodb_internacional";
    try {
        $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
    };
    $consulta_pais_info =	$conexion_internacional->prepare("SELECT time_zone_php FROM paises WHERE pais = :pais");
    $consulta_pais_info->execute([":pais" => $pais]);
    $pais_info	=	$consulta_pais_info->fetch(PDO::FETCH_ASSOC);

    date_default_timezone_set($pais_info['time_zone_php']);


    if ($datos_inmueble['conciliacion_tipo'] == '1 Mes') {

        $fecha_limite = new DateTime(date('d-m-Y', strtotime($datos_inmueble['conciliacion_fecha_limite'])));
        $fecha_actual = new DateTime(date('d-m-Y', strtotime('today')));

        $datos_inmueble['fecha_actual'] = date("d-m-Y", time()); 

        if ($fecha_actual > $fecha_limite) {
            $datos_inmueble['reservado'] = 0; 
        }else{
            $datos_inmueble['reservado'] = 1;
            
        };

    }else{
        $datos_inmueble['reservado'] = 0; 
    };

    if ($datos_inmueble['reservado'] == 1) {
        $consulta_agente = $conexion->prepare("SELECT nombre, apellido, id FROM agentes WHERE id = :id");
        $consulta_agente->execute([':id' => $datos_inmueble['conciliador']]);
        $agente = $consulta_agente->fetch(PDO::FETCH_ASSOC);
    }else{
        $consulta_agente = $conexion->prepare("SELECT nombre, apellido, id FROM agentes WHERE usuario = :usuario");
        $consulta_agente->execute([':usuario' => $_SESSION['usuario']]);
        $agente = $consulta_agente->fetch(PDO::FETCH_ASSOC);
    };

    

    $datos_inmueble['agente_cierre'] = $agente; 

    echo json_encode($datos_inmueble);
}
?>
