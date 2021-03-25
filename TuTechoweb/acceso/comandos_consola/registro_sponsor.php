<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };
}else {
  header('Location: ../login.php');
};

$nivel_acceso = $_SESSION['nivel_acceso'];
$array_acceso = [1,11,5,12];
if (in_array($nivel_acceso, $array_acceso) !== false){
  //Todo OK
}
else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

try {
	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion reportarlo
	echo "Error: " . $e->getMessage();
};

//############################ CONSULTA PARA POBLAR SELECT DEPARTAMENTOS - CARGA INICIAL #########################################
$consulta_regiones =	$conexion->prepare("SELECT departamentos FROM regiones");
$consulta_regiones->execute();
$regiones	=	$consulta_regiones->fetchAll(PDO::FETCH_COLUMN, 0);

// ##########################  CODIGO Y FUNCIONES PARA INYECTAR VALORES EN CASO DE EDICION O BORRADOR ##############################

$borrador_call = '';
$editor_call = '';
$the_call = '';
$tabla_editor_borrador = '';
$info_borrador = '';//SE DEFINE PARA PODER LLENARLA LUEGO
$categorias_borrador = array(1 => 'Restaurantes', 2 => 'Bares & Cafés', 3 => 'Bienestar', 4 => 'Salud');//PARA POBLAR SELECT CATEGORIA
$ciudad_borrador = '';
$departamento_borrador = '';
$barrio_borrador = '';

if (isset($_SESSION['borrador_nombre']) || isset($_SESSION['editar_nombre'])) {//CHECK SI SE HABRE UN BORRADOR ############################

  if (isset($_SESSION['borrador_nombre'])) {
    $the_call = $_SESSION['borrador_nombre'];//PARA HACER LOS CAMBIOS NECESARIOS A LA DB
    $borrador_call = $_SESSION['borrador_nombre'];//PARA SABER EL MODO DEL FORM Y SABER QUE HAY QUE VERIFICAR SI EXISTEN SUCURSALES EN ESE BARRIO
    $tabla_editor_borrador = 'sponsors_borradores';
  } else {
    $the_call = $_SESSION['editar_nombre'];//PARA HACER LOS CAMBIOS NECESARIOS A LA DB
    $editor_call = $_SESSION['editar_nombre'];//PARA SABER EL MODO DEL FORM Y SABER QUE NO HAY QUE VERIFICAR POR SUCURSALES
    $tabla_editor_borrador = 'sponsors';
  };

  $consulta_info_borrador = $conexion->prepare("SELECT * FROM $tabla_editor_borrador WHERE nombre=:nombre");
  $consulta_info_borrador->execute(['nombre' => $the_call]);
  $info_borrador = $consulta_info_borrador->fetch(PDO::FETCH_ASSOC);

  if (isset($info_borrador['barrio'])) {
    if ($info_borrador['barrio'] !== '') {
      $consulta_info_barrio_borrador = $conexion->prepare("SELECT * FROM barrios WHERE barrio=:barrio");
      $consulta_info_barrio_borrador->execute(['barrio' => $info_borrador['barrio']]);
      $info_barrio_borrador = $consulta_info_barrio_borrador->fetch(PDO::FETCH_ASSOC);

      if ($info_barrio_borrador !== false) {
        $barrio_borrador = $info_barrio_borrador['barrio'];
        $ciudad_borrador = $info_barrio_borrador['ciudad'];

        $consulta_info_departamento_borrador = $conexion->prepare("SELECT departamento FROM ciudades WHERE ciudad=:ciudad");
        $consulta_info_departamento_borrador->execute(['ciudad' => $ciudad_borrador]);
        $info_departamento_borrador = $consulta_info_departamento_borrador->fetch(PDO::FETCH_ASSOC);

        $departamento_borrador = $info_departamento_borrador['departamento'];

      }else {
        $ciudad_borrador = $info_borrador['barrio'];

        $consulta_info_departamento_borrador = $conexion->prepare("SELECT departamento FROM ciudades WHERE ciudad=:ciudad");
        $consulta_info_departamento_borrador->execute(['ciudad' => $ciudad_borrador]);
        $info_departamento_borrador = $consulta_info_departamento_borrador->fetch(PDO::FETCH_ASSOC);

        $departamento_borrador = $info_departamento_borrador['departamento'];
      };
    };
  };


  unset($_SESSION['borrador_nombre']);// SIEMPRE DESTRUIR ESTA VARIABLE DE SESSION PARA PODER ACCEDER AL REGISTRO SPONSOR NORMAL SIN PROBLEMAS
  unset($_SESSION['editar_nombre']);// SIEMPRE DESTRUIR ESTA VARIABLE DE SESSION PARA PODER ACCEDER AL REGISTRO SPONSOR NORMAL SIN PROBLEMAS
};


function check_borrador_info($row, $array_borrador, $else_borrador) {//SI ES UN BORRADOR o EDITOR, PERMITE INYECTAR DATOS EN VALUES DE INPUTS
  if (isset($array_borrador[$row])) {
    if ($array_borrador[$row] !== '') {
      echo $array_borrador[$row];
    }else {
      echo $else_borrador;
    };
  }else {
    echo $else_borrador;
  };
};


// ############################### CODIGO DE VALICACION FORMULARIO ############################################################

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //si se envio datos por POST guardar los datos en variables que seran temporales

    $nombre = '';
    if (isset($_POST['nombre'])) {
      $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    };
    $categoria = $_POST['categoria'];
    $departamento = '';
    if (isset($_POST['departamento'])) {
      $departamento = $_POST['departamento'];
    };
    $ciudad = '';
    if (isset($_POST['ciudad'])) {
      $ciudad = $_POST['ciudad'];
    };
    $barrio = '';
    if (isset($_POST['barrio'])) {
      $ciudad = $_POST['barrio'];
    };

    $subtitulo = filter_var($_POST['subtitulo'], FILTER_SANITIZE_STRING);

    $direccion = '';
    if (isset($_POST['direccion'])) {
      $direccion = filter_var($_POST['direccion'], FILTER_SANITIZE_STRING);
    };

    $contacto =  filter_var($_POST['contacto'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $mapa_latitud = $_POST['mapa_sponsor_coordenada_lat'];
    $mapa_longitud = $_POST['mapa_sponsor_coordenada_lng'];
    $mapa_zoom = $_POST['mapa_sponsor_zoom'];

    $logo_predeterminado = '';
    if (isset($_POST['galeria_logos_input'])) {
      $logo_predeterminado = $_POST['galeria_logos_input'] ;
    };

    $ilustracion = $_POST['galeria_ilustraciones_input'];
    $color_borde = $_POST['galeria_colores_input'];

    $responsable = '';
    if (isset($_POST['responsable'])) {
      $responsable = filter_var($_POST['responsable'], FILTER_SANITIZE_STRING);
    };

    $responsable_contacto = '';
    if (isset($_POST['responsable_contacto'])) {
      $responsable_contacto = filter_var($_POST['responsable_contacto'], FILTER_SANITIZE_STRING);
    };

    $fecha_vencimiento = '';
    if (isset($_POST['fecha_vencimiento'])) {
      $fecha_vencimiento = $_POST['fecha_vencimiento'];
    };

    $nombre_empresa = '';

    $barrio_selected = '';

    if($barrio == '') {
        $barrio_selected = $ciudad;
        $nombre_empresa = str_replace(" ", "_", $nombre) . " - " . $ciudad . " - " . $direccion;
     }else {
       $barrio_selected = $barrio;
       $nombre_empresa = str_replace(" ", "_", $nombre) . " - " . $barrio . " - " . $direccion;
     };

    $fecha_registro = date("Y/m/d");
    $usuario = $_SESSION['usuario'];

    $consulta_agente =	$conexion->prepare("SELECT id FROM agentes WHERE usuario=:usuario");
    $consulta_agente->execute(['usuario' => $usuario]);
    $agente_datos	=	$consulta_agente->fetch();

    $agente_id = $agente_datos['id'];


    // LOGOS
    $carpeta_destino = '../../sponsors/' . $_COOKIE['tutechopais'] . '/sponsors_logos/';
    $directorio_logos = '../../sponsors/' . $_COOKIE['tutechopais'] . '/sponsors_logos';

    $nombre_logo = str_replace(" ", "_", $nombre) . "-" . str_replace("/", "_", $fecha_registro);
    $image_uploaded_name = '';
    $formato_img = '';

    $logo = '';

    $error = '';

    function endsWith($string, $endString) {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    };

    if($_FILES['logo']['name'] == '') {//No se ingreso ningun logo de empresa, se toma el valor del predeterminado, que tambien puede ser vacio

        if ($_POST['modo_borrador_edicion'] == 'modo_borrador') {//si estamos en modo Borrador
          $consulta_logo_borrador_distinto = $conexion->prepare("SELECT logo FROM sponsors_borradores WHERE nombre=:nombre");
          $consulta_logo_borrador_distinto->execute(['nombre' => $_POST['borrador_editor_old_name']]);
          $logo_borrador_distinto = $consulta_logo_borrador_distinto->fetch(PDO::FETCH_ASSOC);

          if ($logo_borrador_distinto['logo'] !== '') {//si hubo un logo ingresado
            if (file_exists($logo_borrador_distinto['logo'])) {//chequea primero si existe en el folder
              if (strpos($logo_borrador_distinto['logo'], "/sponsors/" . $_COOKIE['tutechopais'] . "/sponsors_logos/") !== false) {//luego chequea que no sea un logo predeterminado ni un logo de otro sponsor ya validado
                if (strpos($logo_predeterminado, "/sponsors/" . $_COOKIE['tutechopais'] . "/sponsors_logos/") == false) {//si el nuevo logo es el antiguo ya ingresado NO borrarlo
                  unlink($logo_borrador_distinto['logo']);//luego recien lo elimina

                };
              };
            };
          };

        };

        $logo = $logo_predeterminado;//esto puede que sea una seleccion vacia
    }else {//Si se ingreso un logo por el input file Logo

      $temp_name = $_FILES['logo']['tmp_name'];

      if (mime_content_type($temp_name) !== 'image/x-png' && mime_content_type($temp_name) !== 'image/jpeg' && mime_content_type($temp_name) !== 'image/svg+xml') {
          $_SESSION['exito_bien_registrado'] = 'Error - Se intentó ingresar un documento con extension erronea';
          header('Location: ../acceso.php');
      }; 

      $image_uploaded_name = $_FILES['logo']['name'];
      if (endsWith( $image_uploaded_name, '.jpg' ) == true){
      $formato_img = '.jpg';
      } else if (endsWith( $image_uploaded_name, '.svg' ) == true){
      $formato_img = '.svg';
      } else if (endsWith( $image_uploaded_name, '.jpeg' ) == true){
      $formato_img = '.jpeg';
      } else if (endsWith( $image_uploaded_name, '.png' ) == true){
      $formato_img = '.png';
      };

      if ($formato_img === '') {
        $error = 'erooooor';
      }else {
        if(!is_dir($directorio_logos)){//POR SI ACASO NO EXISTIERA LA CARPETA DONDE PONER LOS LOGOS
      		@mkdir($directorio_logos, 0700);
      	};
        $logo_dir = $carpeta_destino . $nombre_logo . $formato_img;

        if ($_POST['borrador_editor_old_name'] !== '') {//si estamos en modo Borrador o modo Editor

              $consulta_logo_borrador_distinto = $conexion->prepare("SELECT logo FROM sponsors_borradores WHERE nombre=:nombre");
              $consulta_logo_borrador_distinto->execute(['nombre' => $_POST['borrador_editor_old_name']]);
              $logo_borrador_distinto = $consulta_logo_borrador_distinto->fetch(PDO::FETCH_ASSOC);

              if ($logo_dir == $logo_borrador_distinto['logo']) {//si el nombre del logo es el mismo, entonces se sobreescribe el archivo

                move_uploaded_file($_FILES['logo']['tmp_name'], $logo_dir);
                $logo = $logo_dir;

              }else {//si el nombre no es el mismo, borrar el logo borrador anterior antes de cargar uno nuevo a la db

                if (file_exists($logo_borrador_distinto['logo'])) {//chequea primero si existe en el folder
                  if (strpos($logo_borrador_distinto['logo'], "/sponsors/" . $_COOKIE['tutechopais'] . "/sponsors_logos/") !== false) {//luego chequea que no sea un logo predeterminado ni un logo de otro sponsor ya validado
                      unlink($logo_borrador_distinto['logo']);//luego recien lo elimina
                  };

                };

                move_uploaded_file($_FILES['logo']['tmp_name'], $logo_dir);//y se carga el siguiente logo que se ingrese
                $logo = $logo_dir;

              };

        }else {//si estamos en modo normal
          move_uploaded_file($_FILES['logo']['tmp_name'], $logo_dir);//y se carga el siguiente logo que se ingrese
          $logo = $logo_dir;
        };

      };


    };



    if($_POST['boton_form_input'] == 'borrador') {// SI SE ENVIO UN BORRADOR A LA DB
        if(empty($nombre_empresa)){
          $error = 'ERRRRROR';
        };
        if($error == '') {
          if ($_POST['modo_borrador_edicion'] == '') {//click en guardar borrador estando en modo normal

              $statement = $conexion->prepare(
               "INSERT INTO sponsors_borradores (nombre, visibilidad, validacion_agente) VALUES (:nombre, :visibilidad, :validacion_agente)"
              );

              $statement->execute(array(
                ':nombre' => $nombre_empresa,
                ':visibilidad' => 'no_visible',
                ':validacion_agente' => 0
              ));

          }else {
            if ($_POST['modo_borrador_edicion'] == 'modo_borrador') {//click en guardar borrador, estando en modo borrador

              $statement = $conexion->prepare(
               "UPDATE sponsors_borradores SET nombre = :new_nombre, visibilidad = :visibilidad, validacion_agente = :validacion_agente WHERE nombre = :old_nombre"
              );

              $statement->execute(array(
                ':old_nombre' => $_POST['borrador_editor_old_name'],
                ':new_nombre' => $nombre_empresa,
                ':visibilidad' => 'no_visible',
                ':validacion_agente' => 0
              ));

            };//ELSE CORRESPONDERIA AL MODO EDITOR QUE NO CENECITA NINGUN CAMBIO EN ESTOS PARAMETROS

          };


        };
    };

    if($_POST['boton_form_input'] == 'registrar'){//SE SE PRETENDE REGISTRAR EL SPONSOR EN LA DB
      if(empty($nombre_empresa) or empty($categoria) or empty($departamento)
      or empty($ciudad) or empty($subtitulo) or empty($direccion)
      or empty($contacto) or empty($email) or empty($mapa_latitud)
      or empty($mapa_longitud) or empty($mapa_zoom)
      or empty($ilustracion) or empty($color_borde) or empty($responsable)
      or empty($responsable_contacto) or empty($fecha_vencimiento)){
        $error = 'ERRRRROR';
      };

        if($error == '') {
          if ($_POST['modo_borrador_edicion'] == '') {//click en registrar estando en modo normal

              $statement = $conexion->prepare(
               "INSERT INTO sponsors_borradores (nombre, visibilidad, validacion_agente) VALUES (:nombre, :visibilidad, :validacion_agente)"
              );

              $statement->execute(array(
                ':nombre' => $nombre_empresa,
                ':visibilidad' => 'no_visible',
                ':validacion_agente' => 1
              ));

          }else {

            if ($_POST['modo_borrador_edicion'] == 'modo_borrador') {//click en registrar, estando en modo borrador

                $statement = $conexion->prepare(
                 "UPDATE sponsors_borradores SET nombre = :new_nombre, visibilidad = :visibilidad, validacion_agente = :validacion_agente WHERE nombre = :old_nombre"
                );

                $statement->execute(array(
                  ':old_nombre' => $_POST['borrador_editor_old_name'],
                  ':new_nombre' => $nombre_empresa,
                  ':visibilidad' => 'no_visible',
                  ':validacion_agente' => 1
                ));

            };//ELSE CORRESPONDE AL MODO EDITOR QUE NO LLEVA CAMBIOS DE ESTE TIPO

          };

        };
    };

    if($error == '') { //si no hubo ningun error entonces adjuntar los datos a la base de datos

      $tabla_to_update = '';

      if ($_POST['modo_borrador_edicion'] == 'modo_borrador' || $_POST['modo_borrador_edicion'] == '') {//si en modo normal o modo borrador

        $statement = $conexion->prepare(
      		"UPDATE sponsors_borradores SET barrio = :barrio, fecha_de_registro = :fecha_de_registro, agente_id = :agente_id, fecha_vencimiento = :fecha_vencimiento, responsable = :responsable, responsable_contacto = :responsable_contacto, categoria = :categoria, label = :label, logo = :logo, subtitulo = :subtitulo, direccion = :direccion, contacto = :contacto, web = :web, latitud = :latitud, longitud = :longitud, zoom = :zoom, ilustracion = :ilustracion, borde = :borde WHERE nombre = :nombre"
      	 );

        $statement->execute(array(
          ':nombre' => $nombre_empresa,
          ':barrio' => $barrio_selected,
          ':fecha_de_registro' => $fecha_registro,
          ':agente_id' => $agente_id,
          ':fecha_vencimiento' => $fecha_vencimiento,
          ':responsable' => $responsable,
          ':responsable_contacto' => $responsable_contacto,
          ':categoria' => $categoria,
          ':label' => $nombre,
          ':logo' => $logo,
          ':subtitulo' => $subtitulo,
          ':direccion' => $direccion,
          ':contacto' => $contacto,
          ':web' => $email,
          ':latitud' => $mapa_latitud,
          ':longitud' => $mapa_longitud,
          ':zoom' => $mapa_zoom,
          ':ilustracion' => $ilustracion,
          ':borde' => $color_borde
        ));

      } else {//se modifica un modo editor

        $statement = $conexion->prepare(
      		"UPDATE sponsors SET categoria = :categoria, subtitulo = :subtitulo, contacto = :contacto, web = :web, latitud = :latitud, longitud = :longitud, zoom = :zoom, ilustracion = :ilustracion, borde = :borde, id_ultima_edicion = :id_ultima_edicion, fecha_ultima_edicion = :fecha_ultima_edicion WHERE nombre = :nombre"
      	 );

        $statement->execute(array(
          ':nombre' => $_POST['borrador_editor_old_name'],
          ':categoria' => $categoria,
          ':subtitulo' => $subtitulo,
          ':contacto' => $contacto,
          ':web' => $email,
          ':latitud' => $mapa_latitud,
          ':longitud' => $mapa_longitud,
          ':zoom' => $mapa_zoom,
          ':ilustracion' => $ilustracion,
          ':borde' => $color_borde,
          ':id_ultima_edicion' => $agente_id,
          ':fecha_ultima_edicion' => $fecha_registro
        ));
      };


    // MENSAJE DE REGISTRO EXITOSO
      if ($_POST['boton_form_input'] == 'borrador') {
    		$_SESSION['exito_bien_registrado'] = 'Borrador Sponsor guardado exitosamente';
    	};

      if ($_POST['boton_form_input'] == 'registrar') {
    		$_SESSION['exito_bien_registrado'] = 'Sponsor registrado exitosamente';
      };

      if ($_POST['boton_form_input'] == 'editar') {
    		$_SESSION['exito_bien_registrado'] = 'Sponsor editado exitosamente';
      };



    }else {
      // MENSAJE DE ERROR
        if ($_POST['boton_form_input'] == 'borrador') {
      		$_SESSION['exito_bien_registrado'] = 'Error - No se pudo guardar Borrador Sponsor';
      	};
        if($_POST['boton_form_input'] == 'registrar') {
      		$_SESSION['exito_bien_registrado'] = 'Error - No se pudo registrar Sponsor';
        };
        if($_POST['boton_form_input'] == 'editar'){
          $_SESSION['exito_bien_registrado'] = 'Error - No se pudo editar Sponsor';
        };
    };

    header('Location: ../acceso.php');

};


require 'registro_sponsor.view.php';
?>
