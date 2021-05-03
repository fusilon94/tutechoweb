<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

$tutechodb = '';

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../../tutechopais.php');
}else {
  $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];
};

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

  $nivel_acceso = $_SESSION['nivel_acceso'];
  $array_acceso = [1,11];

  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

  if (isset($_SESSION['tipo_file_selected']) && isset($_SESSION['id_file']) && isset($_SESSION['pais_selected'])) {
   
    $tipo_file_selected = $_SESSION['tipo_file_selected'];
    $id_file = $_SESSION['id_file'];

    $pais_selected = $_SESSION['pais_selected'];
    $tutechodb = "tutechodb_" . $pais_selected;

  }else {
    header('Location: consola_registro_documentos.php');
  };


  $carpeta_inicio = '';
  $list_of_files = ["*.jpg", "*.pdf", $pais_selected, $id_file];

  if ($tipo_file_selected == 'personal') {

    $carpeta_inicio = 'agentes/' . $pais_selected . '/';

  } else if ($tipo_file_selected == 'inmueble') {

    $carpeta_inicio = 'bienes_inmuebles_files/' . $pais_selected . '/';

  };


  function get_tabla($referencia) {
    $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
    return $dict[$referencia[5]];
  };

  $tutechodb_internacional = "tutechodb_internacional";

  try {
    $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };

  $consulta_php_zone =	$conexion_internacional->prepare("SELECT time_zone_php FROM paises WHERE pais = :pais");
  $consulta_php_zone->execute([':pais' => $pais_selected]);
  $php_zone = $consulta_php_zone->fetch(PDO::FETCH_ASSOC);

  date_default_timezone_set($php_zone['time_zone_php']);


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    
    $accion_selected = $_POST['accion_selected'];
    $reclamo = $_POST['mensaje'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };  

    function generateRandomString() {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < 10; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    };

    if ($tipo_file_selected == 'personal') {

        $tabla = 'agentes';

        $consulta_file_agente =	$conexion->prepare("SELECT registrador, nivel_acceso, agencia_id FROM $tabla WHERE id = :id");
        $consulta_file_agente->execute([':id' => $id_file]);
        $file_agente = $consulta_file_agente->fetch(PDO::FETCH_ASSOC);

        if ($file_agente['nivel_acceso'] == 6 || $file_agente['nivel_acceso'] == 11 || $file_agente['nivel_acceso'] == 12) {

          // destinatario ADmin y Co-Admins(lectura only)
          $consulta_id_admin =	$conexion->prepare("SELECT id FROM $tabla WHERE nivel_acceso = 1");
          $consulta_id_admin->execute();
          $id_admin = $consulta_id_admin->fetch(PDO::FETCH_ASSOC);

          $id_destinatario = $id_admin['id'];

        } elseif ($file_agente['nivel_acceso'] == 3){
          // destinatario Jefe agencia Central o bien Franquiciado
          $consulta_agencia_tipo =	$conexion->prepare("SELECT franquicia, franquiciante_id FROM agencias WHERE id = :id");
          $consulta_agencia_tipo->execute([":id" => $file_agente['agencia_id']]);
          $agencia_tipo = $consulta_agencia_tipo->fetch(PDO::FETCH_ASSOC);

          if ($agencia_tipo['franquicia'] == 1) {
            $id_destinatario = $agencia_tipo['franquiciante_id'];
          }else if($agencia_tipo['franquicia'] == 0){
            $id_destinatario = $file_agente['registrador'];
          };

        } elseif ($file_agente['nivel_acceso'] == 4 || $file_agente['nivel_acceso'] == 7) {
            // destinatarios jefe de agencia actual donde se regristró
            $consulta_jefe_agencia =	$conexion->prepare("SELECT gerente_id FROM agencias WHERE id = :id");
            $consulta_jefe_agencia->execute([":id" => $file_agente['agencia_id']]);
            $jefe_agencia = $consulta_jefe_agencia->fetch(PDO::FETCH_ASSOC);

            $id_destinatario = $jefe_agencia['gerente_id'];

        } elseif ($file_agente['nivel_acceso'] == 5 || $file_agente['nivel_acceso'] == 10) {
            // destinatarios registrador (que seria la jefa de agencia central)
            $id_destinatario = $file_agente['registrador'];

        };

        

        if ($accion_selected == 'reclamo') {

              $codigo = generateRandomString();
              $current_date = date("d-m-Y");

              $statement_reclamo = $conexion->prepare(
                "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, key_feature2) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :key_feature2)"
              );
    
              $statement_reclamo->execute(array(
                ':codigo' => $codigo,
                ':agente_id' => $id_destinatario,
                ':mensaje' => $reclamo,
                ':fecha_creacion' => $current_date,
                ':tipo' => 'reclamo_file',
                ':key_feature1' => $id_file,
                ':key_feature2' => 'Personal'
              ));

              $statement_edit_allow = $conexion->prepare(
                "UPDATE agentes SET edicion = :edicion, edicion_reclamo = :edicion_reclamo WHERE id = :id");
        
              $statement_edit_allow->execute(array(
                ':edicion' => $id_destinatario,
                ':edicion_reclamo' => $codigo,
                ':id' => $id_file
              ));
        
              $_SESSION['mesage_file'] = 'Reclamo registrado exitosamente';
              header('Location: validar_file_consola.php');
                       
        } else if($accion_selected == 'validacion'){
            // generar pendiente de tipo anuncio, borrable
            $consulta_datos_agente =	$conexion->prepare("SELECT nombre, apellido FROM agentes WHERE id = :id");
            $consulta_datos_agente->execute([":id" => $id_file]);
            $datos_agente = $consulta_datos_agente->fetch(PDO::FETCH_ASSOC);

            $consulta_nombres =	$conexion->prepare("SELECT id FROM agentes WHERE nombre = :nombre");
            $consulta_nombres->execute([":nombre" => $datos_agente['nombre']]);
            $nombres = $consulta_nombres->fetchAll(PDO::FETCH_ASSOC);

            $nombres_count = count($nombres);
            $usuario_string = $datos_agente['nombre'] . $nombres_count;
            
            $password_string = generateRandomString();
            $password_hashed = hash('sha512', $password_string);



            $statement_activate_agente = $conexion->prepare(
              "UPDATE agentes SET usuario = :usuario, pass = :pass, activo = 1, disponible = 0 WHERE id = :id");
      
            $statement_activate_agente->execute(array(
              ':usuario' => $usuario_string,
              ':pass' => $password_hashed,
              ':id' => $id_file
            ));

            $codigo = generateRandomString();
            $current_date = date("d-m-Y");
            $aviso = "El agente : " . $datos_agente['nombre'] . " " . $datos_agente['apellido'] . " ha sido validado.</br>Comunique los codigos temporales de acceso siguientes:</br>Usuario: " . $usuario_string . "</br>Contraseña: " . $password_string;

            $statement_aviso = $conexion->prepare(
              "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
            );
  
            $statement_aviso->execute(array(
              ':codigo' => $codigo,
              ':agente_id' => $id_destinatario,
              ':mensaje' => $aviso,
              ':fecha_creacion' => $current_date,
              ':tipo' => 'agente_validado',
              ':key_feature1' => $id_file
            ));

            $_SESSION['mesage_file'] = 'File Agente validado exitosamente';
            header('Location: validar_file_consola.php');

        };

    }elseif ($tipo_file_selected == 'inmueble') {

        $tabla = 'borradores_' . get_tabla($id_file);

        $consulta_agente_destinatario =	$conexion->prepare("SELECT jefe_agencia_id, conciliador, conciliacion_tipo FROM $tabla WHERE referencia = :referencia");
        $consulta_agente_destinatario->execute([':referencia' => $id_file]);
        $agente_destinatario = $consulta_agente_destinatario->fetch(PDO::FETCH_ASSOC);

        $id_destinatario = $agente_destinatario["jefe_agencia_id"];
        $conciliador = $agente_destinatario["conciliador"];
        $conciliacion_tipo = $agente_destinatario["conciliacion_tipo"];

        if ($accion_selected == 'reclamo') {
            // enviar reclamo (pendiente NO borrable) al jefe de agencia actual o bien al agente express registrador

              $codigo = generateRandomString();
              $current_date = date("d-m-Y");

              $statement_reclamo = $conexion->prepare(
                "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1, key_feature2) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1, :key_feature2)"
              );
    
              $statement_reclamo->execute(array(
                ':codigo' => $codigo,
                ':agente_id' => $id_destinatario,
                ':mensaje' => $reclamo,
                ':fecha_creacion' => $current_date,
                ':tipo' => 'reclamo_file',
                ':key_feature1' => $id_file,
                ':key_feature2' => 'Inmueble'
              ));

              $statement_edit_allow = $conexion->prepare(
                "UPDATE $tabla SET edicion_file = :edicion_file, edicion_reclamo = :edicion_reclamo WHERE referencia = :referencia");
        
              $statement_edit_allow->execute(array(
                ':edicion_file' => $id_destinatario,
                ':edicion_reclamo' => $codigo,
                ':referencia' => $id_file
              ));
        
              $_SESSION['mesage_file'] = 'Reclamo registrado exitosamente';
              header('Location: validar_file_consola.php');

        } else if ($accion_selected == 'validacion'){
            // enviar pendiente anuncio Borrable al jefe de agencia actual o bien al agente express registrador

            // Obtener la agencia corresppondiente a partir del ID del jefe de agencia
            $consulta_agencia_asociada =	$conexion->prepare("SELECT agencia_id, nivel_acceso FROM agentes WHERE id = :id");
            $consulta_agencia_asociada->execute([":id" => $id_destinatario]);
            $agencia_asociada = $consulta_agencia_asociada->fetch(PDO::FETCH_ASSOC);

            if ($conciliador !== '' && $conciliacion_tipo == '1 Mes') {
              
              $agente_designado = $conciliador;
              $today = date('d-m-Y');
              
              $conciliacion_fecha_limite = date('d-m-Y', strtotime('+1 months', strtotime($today)));

            }elseif ($agencia_asociada['nivel_acceso'] == 10) {

              $agente_designado = $id_destinatario;
              $conciliacion_fecha_limite = '';
  
            } else {

              $conciliacion_fecha_limite = '';

              // Buscar agentes inmmo activos en la agencia correspondiente
              $consulta_agentes_disponibles =	$conexion->prepare("SELECT id FROM agentes WHERE agencia_id = :agencia_id AND activo = 1 AND turno_campo = 0 AND disponible = 1 AND (nivel_acceso = 10 OR nivel_acceso = 4)");
              $consulta_agentes_disponibles->execute([":agencia_id" => $agencia_asociada['agencia_id']]);
              $agentes_disponibles = $consulta_agentes_disponibles->fetchAll(PDO::FETCH_NUM);
              $agentes_count = count($agentes_disponibles);

              if ($agentes_count == 0) {

                // Reseteamos el count a 0 de todos los agentes
                $statement = $conexion->prepare(
                  "UPDATE agentes SET turno_campo = 0 WHERE agencia_id = :agencia_id AND (nivel_acceso = '10' OR nivel_acceso = '4') ");          
                $statement->execute([":agencia_id" => $agencia_asociada['agencia_id']]);

                // Buscar de nuevo agentes inmmo activos en la agencia correspondiente
                $consulta_agentes_disponibles =	$conexion->prepare(" SELECT id FROM agentes WHERE agencia_id = :agencia_id AND activo = 1 AND turno_campo = 0 AND disponible = 1 AND (nivel_acceso = 10 OR nivel_acceso = 4) ");
                $consulta_agentes_disponibles->execute([":agencia_id" => $agencia_asociada['agencia_id']]);
                $agentes_disponibles = $consulta_agentes_disponibles->fetchAll(PDO::FETCH_NUM);
                $agentes_count = (count($agentes_disponibles) - 1);

                // Elegimos de manera aleatoria al siguiente inmo designado
                $rand_choice = rand(0,$agentes_count);
                $agente_designado = $agentes_disponibles[$rand_choice][0];
                
              } else {
                $agentes_count = $agentes_count - 1;
                $rand_choice = rand(0,$agentes_count);
                $agente_designado = $agentes_disponibles[$rand_choice][0];

              };
            };

        };

          

          


          $consulta_permisos_pais =	$conexion_internacional->prepare("SELECT 360_activo, vr_activo, 360_exclusivo, vr_exclusivo FROM paises WHERE pais = :pais");
          $consulta_permisos_pais->execute([":pais" => $pais_selected]);
          $permisos_pais = $consulta_permisos_pais->fetch(PDO::FETCH_ASSOC);

          $tabla = 'borradores_' . get_tabla($id_file);

          $consulta_exclusividad =	$conexion->prepare("SELECT exclusivo FROM $tabla WHERE referencia = :referencia");
          $consulta_exclusividad->execute([":referencia" => $id_file]);
          $exclusividad = $consulta_exclusividad->fetch(PDO::FETCH_ASSOC);

          if ($exclusividad['exclusivo'] == 1) {
            
            if ($permisos_pais['360_activo'] == 1 && $permisos_pais['360_exclusivo'] == 1) {
              $solicitar_360 = 1;
            } else {
              $solicitar_360 = 0;
            };
            if ($permisos_pais['vr_activo'] == 1 && $permisos_pais['vr_exclusivo'] == 1) {
              $solicitar_vr = 1;
            } else {
              $solicitar_vr = 0;
            };

          } else {

            if ($permisos_pais['360_activo'] == 1 && $permisos_pais['360_exclusivo'] == 0) {
              $solicitar_360 = 1;
            } else {
              $solicitar_360 = 0;
            };
            if ($permisos_pais['vr_activo'] == 1 && $permisos_pais['vr_exclusivo'] == 0) {
              $solicitar_vr = 1;
            } else {
              $solicitar_vr = 0;
            };

          };

          $consulta_datos_precio =	$conexion->prepare("SELECT precio, estado, anticretico, contrato_especial, exclusivo FROM $tabla WHERE referencia = :referencia");
          $consulta_datos_precio->execute([":referencia" => $id_file]);
          $datos_precio = $consulta_datos_precio->fetch(PDO::FETCH_ASSOC);

          $consulta_agencia_info =	$conexion->prepare("SELECT departamento, location_tag FROM agencias WHERE id = :id");
          $consulta_agencia_info->execute([':id' => $agencia_asociada['agencia_id']]);
          $agencia_info	=	$consulta_agencia_info->fetch(PDO::FETCH_ASSOC);

          $agencia_tag = $agencia_info['departamento'] . '_' . $agencia_info['location_tag'];

          $json_path = '../../agencias/' . $pais_selected . '/' . $agencia_tag . '/tabla_precios.json';
  
          $json = file_get_contents($json_path);
          $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

          if ($datos_precio['contrato_especial'] == 1) {//puede ser una venta por lotes, entonces la comision tiene un calculo especial

            if ($datos_precio['estado'] == 'En Venta' || $datos_precio['anticretico'] == 1 ) {
              
              if ($datos_precio['exclusivo'] == 1) {
                if ($data['venta']['lotes']['exclusivo']['tipo'] == 'fijo') {
                  $comision = $data['venta']['lotes']['exclusivo']['monto'];
                }elseif ($data['venta']['lotes']['exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data['venta']['lotes']['exclusivo']['monto']/100);
                };
              }else{
                if ($data['venta']['lotes']['no_exclusivo']['tipo'] == 'fijo') {
                  $comision = $data['venta']['lotes']['no_exclusivo']['monto'];
                }elseif ($data['venta']['lotes']['no_exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data['venta']['lotes']['no_exclusivo']['monto']/100);
                }elseif ($data['venta']['lotes']['no_exclusivo']['tipo'] == 'no_disponible') {
                  $comision = $datos_precio['precio'] * 0.03; //NO SE SUPONE QUE PASE ESTO, A NO SER QUE SEA UN DESCUIDO DEL ADMIN
                };
              };
              
            } else if ($datos_precio['estado'] == 'En Alquiler'){
              
              if ($datos_precio['exclusivo'] == 1) {
                if ($data['alquiler']['lotes']['exclusivo']['tipo'] == 'fijo') {
                  $comision = $data['alquiler']['lotes']['exclusivo']['monto'];
                }elseif ($data['alquiler']['lotes']['exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data['alquiler']['lotes']['exclusivo']['monto']/100);
                };
              }else{
                if ($data['alquiler']['lotes']['no_exclusivo']['tipo'] == 'fijo') {
                  $comision = $data['alquiler']['lotes']['no_exclusivo']['monto'];
                }elseif ($data['alquiler']['lotes']['no_exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data['alquiler']['lotes']['no_exclusivo']['monto']/100);
                }elseif ($data['alquiler']['lotes']['no_exclusivo']['tipo'] == 'no_disponible') {
                  $comision = $datos_precio['precio']; //NO SE SUPONE QUE PASE ESTO, A NO SER QUE SEA UN DESCUIDO DEL ADMIN
                };
              };
              
            };

          } else {
            
            if ($datos_precio['estado'] == 'En Venta' || $datos_precio['anticretico'] == 1 ) {

              $rangos = [];

              $rangos[0] = $data['venta']['first']['rango']['max'];

              $count_venta = 1;
              foreach ($data['venta']['intermediate'] as $elemento) {
                $rangos[$count_venta] = $elemento['rango']['max'];
                $count_venta += 1;
              };

              $rangos[$count_venta] = 10000000000000000;

              $count_lista_venta = 0;
              $rango_chosen = 0;
              while ($datos_precio['precio'] > $rangos[$count_lista_venta]) {
                $count_lista_venta += 1;
                $rango_chosen = $count_lista_venta;
              };

              if ($rango_chosen == 0) {
                $data_set = $data['venta']['first'];
              }elseif ($rango_chosen == (count($rangos)-1)) {
                $data_set = $data['venta']['last'];
              }else {
                $rango_intermediate = ($rango_chosen-1);
                $data_set = $data['venta']['intermediate'][$rango_intermediate];
              };

              if ($datos_precio['exclusivo'] == 1) {
                if ($data_set['exclusivo']['tipo'] == 'fijo') {
                  $comision = $data_set['exclusivo']['monto'];
                }elseif ($data_set['exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data_set['exclusivo']['monto']/100);
                };
              }else {
                if ($data_set['no_exclusivo']['tipo'] == 'fijo') {
                  $comision = $data_set['no_exclusivo']['monto'];
                }elseif ($data_set['no_exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data_set['no_exclusivo']['monto']/100);
                }elseif ($data_set['no_exclusivo']['tipo'] == 'no_disponible') {
                  $comision = $datos_precio['precio'] * 0.03;
                };
              };

            } elseif ($datos_precio['estado'] == 'En Alquiler') {

              $rangos = [];

              $rangos[0] = $data['alquiler']['first']['rango']['max'];

              $count_alquiler = 1;
              foreach ($data['alquiler']['intermediate'] as $elemento) {
                $rangos[$count_alquiler] = $elemento['rango']['max'];
                $count_alquiler += 1;
              };

              $rangos[$count_alquiler] = 10000000000000000;

              $count_lista_alquiler = 0;
              $rango_chosen = 0;
              while ($datos_precio['precio'] > $rangos[$count_lista_alquiler]) {
                $count_lista_alquiler += 1;
                $rango_chosen = $count_lista_alquiler;
              };

              if ($rango_chosen == 0) {
                $data_set = $data['alquiler']['first'];
              }elseif ($rango_chosen == (count($rangos)-1)) {
                $data_set = $data['alquiler']['last'];
              }else {
                $rango_intermediate = ($rango_chosen-1);
                $data_set = $data['alquiler']['intermediate'][$rango_intermediate];
              };

              if ($datos_precio['exclusivo'] == 1) {
                if ($data_set['exclusivo']['tipo'] == 'fijo') {
                  $comision = $data_set['exclusivo']['monto'];
                }elseif ($data_set['exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data_set['exclusivo']['monto']/100);
                };
              }else {
                if ($data_set['no_exclusivo']['tipo'] == 'fijo') {
                  $comision = $data_set['no_exclusivo']['monto'];
                }elseif ($data_set['no_exclusivo']['tipo'] == 'porcentage') {
                  $comision = $datos_precio['precio']*($data_set['no_exclusivo']['monto']/100);
                }elseif ($data_set['no_exclusivo']['tipo'] == 'no_disponible') {
                  $comision = $datos_precio['precio'];
                };
              };

            };
          };

          $current_date = date("d-m-Y");

          $statement_activate_agente = $conexion->prepare(
            "UPDATE $tabla SET validacion_jefe_agencia = 1, fecha_validacion_jefe_agencia = :fecha_validacion_jefe_agencia, fotos360_solicitados = :fotos360_solicitados, vr_solicitado = :vr_solicitado, comision = :comision, agente_designado_id = :agente_designado_id, conciliacion_fecha_limite = :conciliacion_fecha_limite  WHERE referencia = :referencia");
    
          $statement_activate_agente->execute(array(
            ':fecha_validacion_jefe_agencia' => $current_date,
            ':referencia' => $id_file,
            ':vr_solicitado' => $solicitar_vr,
            ':fotos360_solicitados' => $solicitar_360,
            ':comision' => $comision,
            ':agente_designado_id' => $agente_designado,
            ':conciliacion_fecha_limite' => $conciliacion_fecha_limite
          ));

          if ($conciliacion_tipo !== '1 Mes') {//Si se concilio con opcion de un mes, el conciliador debera registrar el bien, sin tomar en cuanta su turno de campo
            $statement_asignar = $conexion->prepare(
              "UPDATE agentes SET turno_campo = 1 WHERE id = :id");
      
            $statement_asignar->execute(array(
              ':id' => $agente_designado
            ));
          };

          if ($conciliador !== '') {//Si existio conciliacion para este cliente propietario, entonces se guarda constancia en el DATA STATs
            require 'data_day_conciliaciones.php';
          };
          // SE ENVIA LA NOTIFICACION AL JEFE DE AGENCIA O AGENTE EXPRESS PARA QUE INFORME AL AGENTE DESIGNADO DE SU TAREA

          $consulta_datos_agente =	$conexion->prepare("SELECT nombre, apellido FROM agentes WHERE id = :id");
          $consulta_datos_agente->execute([":id" => $agente_designado]);
          $datos_agente = $consulta_datos_agente->fetch(PDO::FETCH_ASSOC);

          $codigo = generateRandomString();
          $aviso = "La inscripcion del Inmueble ha sido validada</br> Se ha habilitado el llenado de formulario de datos y de fotografías</br>Agente Designado:&nbsp" . $datos_agente['nombre'] . '&nbsp' . $datos_agente['apellido'] . '</br>Con el ID:&nbsp' . $agente_designado;

          // PARA LA DESIGNACION DEL AGENTE REGISTRADOR, USAR UN PROCESS REQUEST AKA UNA API LOL

          $statement_aviso = $conexion->prepare(
            "INSERT INTO pendientes (codigo, agente_id, mensaje, fecha_creacion, tipo, key_feature1) VALUES (:codigo, :agente_id, :mensaje, :fecha_creacion, :tipo, :key_feature1)"
          );

          $statement_aviso->execute(array(
            ':codigo' => $codigo,
            ':agente_id' => $id_destinatario,
            ':mensaje' => $aviso,
            ':fecha_creacion' => $current_date,
            ':tipo' => 'inmueble_validado',
            ':key_feature1' => $id_file
          ));

          $_SESSION['mesage_file'] = 'File Inmueble validado exitosamente';
          header('Location: validar_file_consola.php');
    };
  };

    


}else {
  header('Location: ../login.php');
};

$tutechodb_internacional = "tutechodb_internacional";

try {
  $conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion repostarlo
  echo "Error: " . $e->getMessage();
};

$consulta_pais_moneda =	$conexion_internacional->prepare("SELECT moneda, moneda_code FROM paises WHERE pais=:pais ");
$consulta_pais_moneda->execute([':pais' => $pais_selected]);//SE PASA LA REFERENCIA
$pais_moneda = $consulta_pais_moneda->fetch(PDO::FETCH_ASSOC);


$moneda = $pais_moneda['moneda'] . ' ' . $pais_moneda['moneda_code'];

$entry_folder_path = '/' . $carpeta_inicio;

$entry_val = 'some_value';

$salir_url = 'validar_file_consola.php';

$modo_validacion = 'OK';

require 'tinyfilemanager.php';

?>
