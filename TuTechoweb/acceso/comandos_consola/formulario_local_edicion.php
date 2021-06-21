<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

$nivel_acceso = $_SESSION['nivel_acceso'];
$array_acceso = [1,2,3,4,10,11];
if (in_array($nivel_acceso, $array_acceso) !== false){
  //Todo OK
}
else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php
$referencia = '';
$tabla_bien = '';
$modo = '';

if (isset($_SESSION['referencia_bien']) && isset($_SESSION['tabla_bien']) && isset($_SESSION['usuario'])) {
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
		header('Location: ../cerrar_session.php');
  };
  
  $referencia = $_SESSION['referencia_bien'];
	$tabla_bien = $_SESSION['tabla_bien'];
} else {
	header('Location: ../acceso.php');
};

try {
	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
	echo "Error: " . $e->getMessage();
};


$consulta =	$conexion->prepare("SELECT * FROM $tabla_bien WHERE referencia=:referencia");
$consulta->execute(['referencia' => $referencia]);
$info_bien_all	=	$consulta->fetch();
// print_r($info_bien_all);

function fill_info_bien($row, $info_function){
  if(isset($info_function[$row])){
    if ($info_function[$row] !== '') {
      echo $info_function[$row];
    };
  };
};

if (strpos($tabla_bien, 'borradores') !== false) {
  $modo = 'first_entry';
}else {
  $modo = 'edicion';
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //si se envio datos por POST guardar los datos en variables que seran temporales

  // print_r($_POST);

  $fecha_registro = date("Y/m/d");
  $usuario = $_SESSION['usuario'];

  $consulta_agente =	$conexion->prepare("SELECT id, agencia_id FROM agentes WHERE usuario=:usuario");
  $consulta_agente->execute(['usuario' => $usuario]);
  $agente_datos	=	$consulta_agente->fetch();

  $agente_id = $agente_datos['id'];
  $agencia_id = $agente_datos['agencia_id'];

  $referencia_received = $_POST['referencia'];
  $modo_received = $_POST['modo'];
  $tabla_bien_received = $_POST['tabla'];

  $mapa_coordenada_lat = $_POST['mapa_coordenada_lat'];
  $mapa_coordenada_lng = $_POST['mapa_coordenada_lng'];
  $mapa_zoom = $_POST['mapa_zoom'];
	$tipo_via = $_POST['tipo_via'];
	$aceras = $_POST['aceras'];
	$residencia = $_POST['residencia'];
	$espacios = $_POST['espacios'];
	$handicap = $_POST['handicap'];
	$parqueos = $_POST['parqueos'];
  $piso = $_POST['piso'];
	$niveles = $_POST['niveles'];
	$acensor = $_POST['acensor'];
	$intercomunicador = $_POST['intercomunicador'];
	$balcon = $_POST['balcon'];
  $balcon_superficie = $_POST['balcon_superficie'];
  $baulera = $_POST['baulera'];
	$cocina = $_POST['cocina'];
	$equipada = $_POST['equipada'];
  $alacena = $_POST['alacena'];
	$wc = $_POST['wc'];
  $tipo_local = $_POST['tipo_local'];
	$adaptacion = $_POST['adaptacion'];
	$tipo_ducha = $_POST['tipo_ducha'];
	$wc_separado = $_POST['wc_separado'];
	$gaz_domiciliario = $_POST['gaz_domiciliario'];
	$aire_acondicionado = $_POST['aire_acondicionado'];
	$chimenea = $_POST['chimenea'];
	$ventanas = $_POST['ventanas'];
	$calefaccion = $_POST['calefaccion'];
	$conexion_electrica = $_POST['conexion_electrica'];
	$cobertura = $_POST['cobertura'];
	$internet = $_POST['internet'];
	$tv_cable = $_POST['tv_cable'];
	$ruido_interno = $_POST['ruido_interno'];
	$interior_estado = $_POST['interior_estado'];
	$patio = $_POST['patio'];
	$cesped = $_POST['cesped'];
	$terraza = $_POST['terraza'];
  $terraza_superficie = $_POST['terraza_superficie'];
	$patio_superficie = $_POST['patio_superficie'];
	$jardin_superficie_medida = $_POST['patio_superficie_medida'];
	$parqueo_techado = $_POST['parqueo_techado'];
	$porton = $_POST['porton'];
	$parqueo_recarga = $_POST['parqueo_recarga'];
	$vista = $_POST['vista'];
	$exposicion = $_POST['exposicion'];
	$ruido_externo = $_POST['ruido_externo'];
	$picina = $_POST['picina'];
	$parrillero = $_POST['parrillero'];
  $animales_domesticos = $_POST['animales_domesticos'];
	$jardin_estado = $_POST['jardin_estado'];
	$sauna = $_POST['sauna'];
	$jacuzzi = $_POST['jacuzzi'];
	$gimnasio = $_POST['gimnasio'];
	$desague = $_POST['desague'];
	$descripcion_bien = filter_var($_POST['descripcion_bien'], FILTER_SANITIZE_STRING);
  $edificio = $_POST['edificio'];
  $fecha_construccion = $_POST['fecha_construccion'];
	$salida_emergencia = $_POST['salida_emergencia'];
  $camaras = $_POST['camaras'];
  $extintores = $_POST['extintores'];
  $portero = $_POST['portero'];
	$parada_bus = $_POST['parada_bus'];
	$teleferico = $_POST['teleferico'];
	$supermercado = $_POST['supermercado'];
	$farmacia = $_POST['farmacia'];
	$guarderia = $_POST['guarderia'];
  $escuela = $_POST['escuela'];
  $hospital = $_POST['hospital'];
  $policia = $_POST['policia'];
  $area_verde = $_POST['area_verde'];
	$comentarios_bien = filter_var($_POST['comentarios_bien'], FILTER_SANITIZE_STRING);

	$errores = ''; //define la variable errores vacia

	if (isset($_POST['guardar_borrador'])) {
			// no pasa nada
	} else {

		if (empty($tipo_via) or empty($aceras) or empty($tipo_local) or !isset($espacios) or !isset($parqueos) or
				empty($ventanas) or !isset($piso) or empty($calefaccion) or empty($conexion_electrica) or empty($cobertura) or empty($internet) or
        empty($tv_cable) or empty($interior_estado) or empty($descripcion_bien) or empty($jardin_estado) or
        empty($mapa_coordenada_lat) or empty($mapa_coordenada_lng)) { //si hay algun campo obligatorio vacio mostrar un error

			$errores .= '<li><span class="fas fa-exclamation-circle"></span><span> Quedan campos obligatorios* por completar</span></li>';

		};
	};


	if ($errores == '') { //si no hubo ningun error entonces adjuntar los datos a la base de datos

    $tabla_to_update = '';

    if (isset($_POST['guardar_borrador'])) {//se pareto en guardar borrado
          $tabla_to_update = $tabla_bien_received;

          $statement_borrador = $conexion->prepare(
    				"UPDATE $tabla_to_update SET borrador = 1 WHERE referencia = :referencia");

    			$statement_borrador->execute(array(
    				':referencia' => $referencia_received
    			));

          $_SESSION['exito_bien_registrado'] = 'Borrador guardado exitosamente';

    }else {//se pareto en registrar cambios
      if ($modo_received == 'first_entry') {//es la primera vez en el formulario o es un borrador y esta en la tabla borradores

            $tabla_to_update = str_replace("borradores_", "", $tabla_bien_received);

            $statement_registrar = $conexion->prepare(
             "INSERT INTO $tabla_to_update (referencia, validacion_agente, registrador_id, fecha_registro, agencia_registro_id) VALUES (:referencia, :validacion_agente, :registrador_id, :fecha_registro, :agencia_registro_id)"
            );

            $statement_registrar->execute(array(
              ':referencia' => $referencia_received,
              ':validacion_agente' => 1,
              ':registrador_id' => $agente_id,
              ':fecha_registro' => $fecha_registro,
              ':agencia_registro_id' => $agencia_id
            ));

            $statement_registrar_mas = $conexion->prepare(
      				"UPDATE $tabla_to_update SET tipo_bien = :tipo_bien, precio = :precio, mantenimiento = :mantenimiento, base_imponible = :base_imponible, estado = :estado, exclusivo = :exclusivo, pre_venta = :pre_venta, anticretico = :anticretico, pais = :pais, ciudad = :ciudad, departamento = :departamento, barrio = :barrio, location_tag = :location_tag, direccion = :direccion, direccion_complemento = :direccion_complemento, superficie_inmueble = :superficie_inmueble, llave = :llave WHERE referencia = :referencia");

      			$statement_registrar_mas->execute(array(
      				':referencia' => $referencia_received,
              ':tipo_bien' => $info_bien_all['tipo_bien'],
              ':precio' => $info_bien_all['precio'],
              ':mantenimiento' => $info_bien_all['mantenimiento'],
              ':base_imponible' => $info_bien_all['base_imponible'],
              ':estado' => $info_bien_all['estado'],
              ':exclusivo' => $info_bien_all['exclusivo'],
              ':pre_venta' => $info_bien_all['pre_venta'],
              ':anticretico' => $info_bien_all['anticretico'],
              ':pais' => $info_bien_all['pais'],
              ':ciudad' => $info_bien_all['ciudad'],
              ':departamento' => $info_bien_all['departamento'],
              ':barrio' => $info_bien_all['barrio'],
              ':location_tag' => $info_bien_all['location_tag'],
              ':direccion' => $info_bien_all['direccion'],
              ':direccion_complemento' => $info_bien_all['direccion_complemento'],
              ':superficie_inmueble' => $info_bien_all['superficie_inmueble'],
              ':llave' => $info_bien_all['llave']
      			));

            $statement_registrar_mas2 = $conexion->prepare(
      				"UPDATE $tabla_to_update SET impuestos = :impuestos, impuestos_moneda = :impuestos_moneda, propietario_nombre = :propietario_nombre, propietario_apellido = :propietario_apellido, propietario_direccion = :propietario_direccion, propietario_telefono = :propietario_telefono, propietario_email = :propietario_email, propietario_carnet = :propietario_carnet, contrato_especial = :contrato_especial, gestion_acordada = :gestion_acordada, validacion_jefe_agencia = :validacion_jefe_agencia, jefe_agencia_id = :jefe_agencia_id, fecha_validacion_jefe_agencia = :fecha_validacion_jefe_agencia, avaluo = :avaluo, comision = :comision, propietario_tipo_doc = :propietario_tipo_doc, contrato_especial_comentario = :contrato_especial_comentario, agente_designado_id = :agente_designado_id, conciliador = :conciliador, conciliacion_tipo = :conciliacion_tipo, conciliacion_fecha_limite = :conciliacion_fecha_limite WHERE referencia = :referencia");

      			$statement_registrar_mas2->execute(array(
      				':referencia' => $referencia_received,
              ':impuestos' => $info_bien_all['impuestos'],
              ':impuestos_moneda' => $info_bien_all['impuestos_moneda'],
              ':propietario_nombre' => $info_bien_all['propietario_nombre'],
              ':propietario_apellido' => $info_bien_all['propietario_apellido'],
              ':propietario_direccion' => $info_bien_all['propietario_direccion'],
              ':propietario_telefono' => $info_bien_all['propietario_telefono'],
              ':propietario_email' => $info_bien_all['propietario_email'],
              ':propietario_carnet' => $info_bien_all['propietario_carnet'],
              ':contrato_especial' => $info_bien_all['contrato_especial'],
              ':gestion_acordada' => $info_bien_all['gestion_acordada'],
              ':validacion_jefe_agencia' => $info_bien_all['validacion_jefe_agencia'],
              ':jefe_agencia_id' => $info_bien_all['jefe_agencia_id'],
              ':fecha_validacion_jefe_agencia' => $info_bien_all['fecha_validacion_jefe_agencia'],
              ':avaluo' => $info_bien_all['avaluo'],
              ':comision' => $info_bien_all['comision'],
              ':propietario_tipo_doc' => $info_bien_all['propietario_tipo_doc'],
              ':contrato_especial_comentario' => $info_bien_all['contrato_especial_comentario'],
              ':agente_designado_id' => $info_bien_all['agente_designado_id'],
              ':conciliador' => $info_bien_all['conciliador'],
              ':conciliacion_tipo' => $info_bien_all['conciliacion_tipo'],
              ':conciliacion_fecha_limite' => $info_bien_all['conciliacion_fecha_limite']
      			));

            $statement_borrar =	$conexion->prepare("DELETE FROM $tabla_bien_received WHERE referencia = :referencia");
            $statement_borrar->execute([':referencia' => $referencia_received]);

            $_SESSION['exito_bien_registrado'] = 'Formulario registrado exitosamente';

      };

      if ($modo_received == 'edicion') {//se esta editando un formulario ya validado

          $tabla_to_update = $tabla_bien_received;

          $consulta_reclamo =	$conexion->prepare("SELECT revision_form_solicitada FROM $tabla_to_update WHERE referencia=:referencia");
          $consulta_reclamo->execute([':referencia' => $referencia_received]);
          $reclamo	=	$consulta_reclamo->fetch(PDO::FETCH_ASSOC);

          if ($reclamo['revision_form_solicitada'] !== '') {

            $statement_reclamo = $conexion->prepare("UPDATE $tabla_to_update SET revision_form_solicitada = '' WHERE referencia = :referencia");
            $statement_reclamo->execute([':referencia' => $referencia_received]);

            $statement_borrar_reclamo =	$conexion->prepare("DELETE FROM pendientes WHERE codigo = :codigo ");
            $statement_borrar_reclamo->execute([':codigo' => $reclamo['revision_form_solicitada']]);

            $_SESSION['exito_bien_registrado'] = 'Correciones a reclamo registradas exitosamente';

          }else {

              $consulta_autorizacion =	$conexion->prepare("SELECT edicion_form_autorizacion FROM $tabla_to_update WHERE referencia=:referencia");
              $consulta_autorizacion->execute([':referencia' => $referencia_received]);
              $autorizacion	=	$consulta_autorizacion->fetch(PDO::FETCH_ASSOC);

              if ($autorizacion['edicion_form_autorizacion'] == 1) {
                $statement_quitar_autorizacion = $conexion->prepare("UPDATE $tabla_to_update SET edicion_form_autorizacion = 0 WHERE referencia = :referencia");
                $statement_quitar_autorizacion->execute([':referencia' => $referencia_received]);
              };

              $statement_edicion = $conexion->prepare(
                "UPDATE $tabla_to_update SET ultima_edicion_info = :ultima_edicion_info, editor_info_id = :editor_info_id WHERE referencia = :referencia");

              $statement_edicion->execute(array(
                ':referencia' => $referencia_received,
                ':ultima_edicion_info' => $fecha_registro,
                ':editor_info_id' => $agente_id
              ));

              $_SESSION['exito_bien_registrado'] = 'Formulario editado exitosamente';

          };

      };

    };

	$statement1 = $conexion->prepare(
		"UPDATE $tabla_to_update SET tipo_via = :tipo_via, aceras = :aceras, residencia = :residencia, espacios = :espacios, handicap = :handicap, parqueos = :parqueos, acensor = :acensor, intercomunicador = :intercomunicador, balcon = :balcon, cocina = :cocina, equipada = :equipada, wc = :wc, niveles = :niveles, adaptacion = :adaptacion, salida_emergencia = :salida_emergencia, baulera = :baulera, animales_domesticos = :animales_domesticos, extintores = :extintores WHERE referencia = :referencia"
	 );

	$statement1->execute(array(
		':referencia' => $referencia_received,
    ':tipo_via' => $tipo_via,
		':aceras' => $aceras,
		':residencia' => $residencia,
		':espacios' => $espacios,
		':handicap' => $handicap,
		':parqueos' => $parqueos,
		':acensor' => $acensor,
		':intercomunicador' => $intercomunicador,
		':balcon' => $balcon,
		':cocina' => $cocina,
		':equipada' => $equipada,
		':wc' => $wc,
    ':niveles' => $niveles,
		':adaptacion' => $adaptacion,
		':salida_emergencia' => $salida_emergencia,
    ':baulera' => $baulera,
    ':animales_domesticos' => $animales_domesticos,
    ':extintores' => $extintores
	));

	$statement2 = $conexion->prepare(
		"UPDATE $tabla_to_update SET tipo_ducha = :tipo_ducha, wc_separado = :wc_separado, gaz_domiciliario = :gaz_domiciliario, aire_acondicionado = :aire_acondicionado, chimenea = :chimenea, ventanas = :ventanas, calefaccion = :calefaccion, conexion_electrica = :conexion_electrica, cobertura = :cobertura, internet = :internet, tv_cable = :tv_cable, ruido_interno = :ruido_interno, interior_estado = :interior_estado, patio = :patio, cesped = :cesped, terraza = :terraza, patio_superficie = :patio_superficie, parqueo_techado = :parqueo_techado, porton = :porton, parqueo_recarga = :parqueo_recarga, vista = :vista, exposicion = :exposicion, balcon_superficie = :balcon_superficie WHERE referencia = :referencia");

	$statement2->execute(array(
		':referencia' => $referencia_received,
		':tipo_ducha' => $tipo_ducha,
		':wc_separado' => $wc_separado,
		':gaz_domiciliario' => $gaz_domiciliario,
		':aire_acondicionado' => $aire_acondicionado,
		':chimenea' => $chimenea,
		':ventanas' => $ventanas,
		':calefaccion' => $calefaccion,
		':conexion_electrica' => $conexion_electrica,
		':cobertura' => $cobertura,
		':internet' => $internet,
		':tv_cable' => $tv_cable,
		':ruido_interno' => $ruido_interno,
		':interior_estado' => $interior_estado,
		':patio' => $patio,
		':cesped' => $cesped,
		':terraza' => $terraza,
		':patio_superficie' => $patio_superficie,
		':parqueo_techado' => $parqueo_techado,
		':porton' => $porton,
		':parqueo_recarga' => $parqueo_recarga,
		':vista' => $vista,
		':exposicion' => $exposicion,
    ':balcon_superficie' => $balcon_superficie
	));

	$statement3 = $conexion->prepare(
		"UPDATE $tabla_to_update SET ruido_externo = :ruido_externo, picina = :picina, parrillero = :parrillero, jardin_estado = :jardin_estado, sauna = :sauna, jacuzzi = :jacuzzi, gimnasio = :gimnasio, desague = :desague, descripcion_bien = :descripcion_bien, parada_bus = :parada_bus, teleferico = :teleferico, supermercado = :supermercado, farmacia = :farmacia, guarderia = :guarderia, terraza_superficie = :terraza_superficie, fecha_construccion = :fecha_construccion, camaras = :camaras, portero = :portero WHERE referencia = :referencia");

	$statement3->execute(array(
		':referencia' => $referencia_received,
		':ruido_externo' => $ruido_externo,
		':picina' => $picina,
		':parrillero' => $parrillero,
		':jardin_estado' => $jardin_estado,
		':sauna' => $sauna,
		':jacuzzi' => $jacuzzi,
		':gimnasio' => $gimnasio,
		':desague' => $desague,
		':descripcion_bien' => $descripcion_bien,
		':parada_bus' => $parada_bus,
		':teleferico' => $teleferico,
		':supermercado' => $supermercado,
		':farmacia' => $farmacia,
		':guarderia' => $guarderia,
    ':terraza_superficie' => $terraza_superficie,
    ':fecha_construccion' => $fecha_construccion,
    ':camaras' => $camaras,
    ':portero' => $portero
	));


	$statement4 = $conexion->prepare(
		"UPDATE $tabla_to_update SET piso = :piso, tipo_local = :tipo_local, edificio = :edificio, alacena = :alacena, escuela = :escuela, policia = :policia, hospital = :hospital, area_verde = :area_verde, mapa_coordenada_lat = :mapa_coordenada_lat, mapa_coordenada_lng = :mapa_coordenada_lng, mapa_zoom = :mapa_zoom, comentarios_bien = :comentarios_bien WHERE referencia = :referencia");

	$statement4->execute(array(
		':referencia' => $referencia_received,
    ':piso' => $piso,
    ':tipo_local' => $tipo_local,
    ':edificio' => $edificio,
    ':alacena' => $alacena,
    ':escuela' => $escuela,
    ':hospital' => $hospital,
    ':policia' => $policia,
    ':area_verde' => $area_verde,
    ':mapa_coordenada_lat' => $mapa_coordenada_lat,
    ':mapa_coordenada_lng' => $mapa_coordenada_lng,
    ':mapa_zoom' => $mapa_zoom,
    ':comentarios_bien' => $comentarios_bien
	));

  unset($_SESSION['referencia_bien']);
  unset($_SESSION['tabla_bien']);

	header('Location: ../acceso.php');

	};
};

$tutechodb_internacional = "tutechodb_internacional";

try {
  $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};

$consulta_pais_moneda =	$conexion_internacional->prepare("SELECT moneda, moneda_code FROM paises WHERE pais=:pais ");
$consulta_pais_moneda->execute([':pais' => $_COOKIE['tutechopais']]);//SE PASA LA REFERENCIA
$pais_moneda = $consulta_pais_moneda->fetch(PDO::FETCH_ASSOC);


$moneda = $pais_moneda['moneda'] . ' ' . $pais_moneda['moneda_code'];


require 'formulario_local_edicion.view.php';
?>
