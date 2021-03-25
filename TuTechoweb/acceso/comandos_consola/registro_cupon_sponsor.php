<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

$nivel_acceso = $_SESSION['nivel_acceso'];
$array_acceso = [1,5,11];
if (in_array($nivel_acceso, $array_acceso) !== false){
  //Todo OK
}
else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php

try {
	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
} catch (PDOException $e) { //en caso de error de conexion reportarlo
	echo "Error: " . $e->getMessage();
};

// ##########################  CODIGO Y FUNCIONES PARA INYECTAR VALORES EN CASO DE EDICION O BORRADOR ##############################

$nuevo_cupon = '';
$info_sponsor = '';
$ilustraciones = '';
$categorias_borrador = array(1 => 'Restaurantes', 2 => 'Bares & Cafés', 3 => 'Bienestar', 4 => 'Salud');//PARA POBLAR SELECT CATEGORIA

if (isset($_SESSION['nuevo_cupon']) && isset($_SESSION['usuario'])) {

if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
  header('Location: ../cerrar_session.php');
};

$nuevo_cupon = $_SESSION['nuevo_cupon'];

$consulta_info_sponsor = $conexion->prepare("SELECT nombre, label, logo, direccion, contacto, web, fecha_vencimiento, borde, categoria, ilustracion FROM sponsors WHERE nombre=:nombre");
$consulta_info_sponsor->execute(['nombre' => $nuevo_cupon]);
$info_sponsor = $consulta_info_sponsor->fetch(PDO::FETCH_ASSOC);

$categoria = $info_sponsor['categoria'];

$consulta_ilustraciones = $conexion->prepare(" SELECT url FROM logos_predeterminados WHERE categoria=:categoria AND tipo='ilustracion' ");
$consulta_ilustraciones->execute(['categoria' => $categoria]);
$ilustraciones = $consulta_ilustraciones->fetchAll();

unset($_SESSION['nuevo_cupon']);// SIEMPRE DESTRUIR ESTA VARIABLE DE SESSION PARA PODER ACCEDER AL REGISTRO SPONSOR NORMAL SIN PROBLEMAS
}else {
  header('Location: ../acceso.php');
};


function check_sponsor_editar_info($row, $array_sponsor_editar, $else_info) {//SI ES UN BORRADOR o EDITOR, PERMITE INYECTAR DATOS EN VALUES DE INPUTS
  if (isset($array_sponsor_editar[$row])) {
    if ($array_sponsor_editar[$row] !== '') {
      echo $array_sponsor_editar[$row];
    }else {
      echo $else_info;
    };
  }else {
    echo $else_info;
  };
};


// ############################### CODIGO DE VALICACION FORMULARIO ############################################################

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //si se envio datos por POST guardar los datos en variables que seran temporales

  print_r($_POST);

    $sponsor = $_POST['sponsor'];
    $promo_info_sponsor_posicion = $_POST['promo_info_sponsor_posicion'];
    $tipo_promocion = $_POST['tipo_promocion'];
    $promo_var1 = $_POST['promo_var1'];

    $promo_var2 = '';
    if (isset($_POST['promo_var2'])) {
      $promo_var2 = $_POST['promo_var2'];
    };

    $promo_var3 = '';
    if (isset($_POST['promo_var3'])) {
      $promo_var3 = $_POST['promo_var3'];
    };

    $promo_var4 = '';
    if (isset($_POST['promo_var4'])) {
      $promo_var4 = $_POST['promo_var4'];
    };

    $promo_font_size1 = ($_POST['font_size1_input'] / 16) . "em";

    $promo_font_size2 = '';
    if (isset($_POST['font_size2_input'])) {
      $promo_font_size2 = ($_POST['font_size2_input'] / 16) . "em";
    };

    $promo_tipo_texto = $_POST['tipo_texto'];
    $promo_inclinacion = $_POST['inclinacion_input'];
    $promo_top = $_POST['promo_top_position'];
    $promo_left = $_POST['promo_left_position'];
    $promo_color = $_POST['opcion_colores_input'];
    $promo_info1 = filter_var($_POST['promo_info1'], FILTER_SANITIZE_STRING);
    $promo_info2 = filter_var($_POST['promo_info2'], FILTER_SANITIZE_STRING);
    $promo_info_font_size = ($_POST['promo_info_font_size_input'] / 16) . "em";
    $promo_info_posicion = $_POST['promo_info_posicion'];
    $ilustracion_fondo = $_POST['galeria_ilustraciones_input'];
    $borde = $_POST['galeria_colores_input'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $seguridad_extra = $_POST['seguridad_extra'];
    $codigo_sponsor = '';
    $codigo_respuesta = '';

    if ($seguridad_extra == 'SI') {
      $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789@#$%&'); // and any other characters
      shuffle($seed); // probably optional since array_is randomized; this may be redundant
      foreach (array_rand($seed, 6) as $k) $codigo_sponsor .= $seed[$k];
      foreach (array_rand($seed, 6) as $k) $codigo_respuesta .= $seed[$k];
    };

    $usuario = $_SESSION['usuario'];

    $consulta_agente =	$conexion->prepare("SELECT id FROM agentes WHERE usuario=:usuario");
    $consulta_agente->execute(['usuario' => $usuario]);
    $agente_datos	=	$consulta_agente->fetch();

    $agente_id = $agente_datos['id'];
    $fecha_registro = date("Y/m/d");

    $error = '';


    if(empty($sponsor) or empty($tipo_promocion) or empty($promo_var1)
    or empty($promo_tipo_texto) or empty($promo_info1) or empty($ilustracion_fondo)
    or empty($borde) or empty($fecha_vencimiento) or empty($agente_id)
    or empty($fecha_registro)){
      $error = 'ERRRRROR';
    };

        if($error == '') {

          $statement = $conexion->prepare(
           "INSERT INTO cupones_sponsor (sponsor, agente_id, fecha_registro, info_sponsor_right,  tipo_promocion, promo_var1, promo_var2, promo_var3, promo_var4, promo_font_size1, promo_font_size2, promo_tipo_texto, promo_inclinacion, promo_top, promo_left, promo_color, promo_info1, promo_info2, promo_info_font_size, promo_info_posicion, ilustracion_fondo, borde, fecha_vencimiento) VALUES (:sponsor, :agente_id, :fecha_registro, :info_sponsor_right, :tipo_promocion, :promo_var1, :promo_var2, :promo_var3, :promo_var4, :promo_font_size1, :promo_font_size2, :promo_tipo_texto, :promo_inclinacion, :promo_top, :promo_left, :promo_color, :promo_info1, :promo_info2, :promo_info_font_size, :promo_info_posicion, :ilustracion_fondo, :borde, :fecha_vencimiento)"
          );

          $statement->execute(array(
            ':sponsor' => $sponsor,
            ':agente_id' => $agente_id,
            ':fecha_registro' => $fecha_registro,
            ':info_sponsor_right' => $promo_info_sponsor_posicion,
            ':tipo_promocion' => $tipo_promocion,
            ':promo_var1' => $promo_var1,
            ':promo_var2' => $promo_var2,
            ':promo_var3' => $promo_var3,
            ':promo_var4' => $promo_var4,
            ':promo_font_size1' => $promo_font_size1,
            ':promo_font_size2' => $promo_font_size2,
            ':promo_tipo_texto' => $promo_tipo_texto,
            ':promo_inclinacion' => $promo_inclinacion,
            ':promo_top' => $promo_top,
            ':promo_left' => $promo_left,
            ':promo_color' => $promo_color,
            ':promo_info1' => $promo_info1,
            ':promo_info2' => $promo_info2,
            ':promo_info_font_size' => $promo_info_font_size,
            ':promo_info_posicion' => $promo_info_posicion,
            ':ilustracion_fondo' => $ilustracion_fondo,
            ':borde' => $borde,
            ':fecha_vencimiento' => $fecha_vencimiento
          ));


          $statement2 = $conexion->prepare(
           "UPDATE sponsors SET cupon_agregado = 1, codigo_sponsor = :codigo_sponsor, codigo_respuesta = :codigo_respuesta WHERE nombre = :nombre"
          );

          $statement2->execute(array(
            ':codigo_sponsor' => $codigo_sponsor,
            ':codigo_respuesta' => $codigo_respuesta,
            ':nombre' => $sponsor
          ));

          // MENSAJE DE REGISTRO EXITOSO
          $_SESSION['exito_bien_registrado'] = 'Cupón Sponsor registrado exitosamente';

        }else {
      // MENSAJE DE ERROR

      		$_SESSION['exito_bien_registrado'] = 'Error - No se pudo registrar el Cupón Sponsor';

       };

    header('Location: ../acceso.php');

};


require 'registro_cupon_sponsor.view.php';
?>
