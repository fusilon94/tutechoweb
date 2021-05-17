<?php

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: ../tutechopais.php');
};

$tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

function get_tabla($referencia) {
  if (strpos($referencia, 'C') !== false) {
    return "casa";
  };
  if (strpos($referencia, 'D') !== false) {
    return "departamento";
  };
  if (strpos($referencia, 'L') !== false) {
    return "local";
  };
  if (strpos($referencia, 'T') !== false) {
    return "terreno";
  };
  return 'error';
};


if ($_SERVER['REQUEST_METHOD'] == 'POST') { //si se envio datos por POST entonces guardarlos en variables que seran temporales
  $apellido_sent = filter_var(strtolower($_POST['apellido']), FILTER_SANITIZE_STRING); //sanitizar y poner en minusculas el texto
  $referencia = filter_var($_POST['referencia'], FILTER_SANITIZE_STRING);

  try { //conectarse a la base de datos para empezar las verificaciones
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); //en caso de error de conexion reportarlo
  }

 

    $tabla = get_tabla($referencia);

    if ($tabla !== 'error') {
        $consulta_apellido = $conexion->prepare("SELECT propietario_apellido FROM $tabla WHERE referencia = :referencia AND visibilidad = 'visible' AND inactivo = 0 ");//preparar la consulta: el usuario y pass existen?
        $consulta_apellido->execute(array(//ejecutar la consulta definiendo los datos a comparar
            ':referencia' => $referencia,
        ));
        $apellido = $consulta_apellido->fetch(PDO::FETCH_ASSOC);
    
        if ($apellido !== false) { 
            $apellido = $apellido['propietario_apellido'];
            $primer_apellido = explode(" ", $apellido);
            if (strtolower($primer_apellido[0]) == strtolower($apellido_sent)) {
              header('Location: propietario_consola.php' . $referencia . '~' . $apellido);
            } else {
              $errores = 'Apellido incorrecto';      
            };
    
        }else {
            $errores = 'Referencia ingresada NO existe';
        };
    }else {
        $errores = 'Referencia ingresada NO existe';
    };

};



require 'propietario_login.view.php';
?>
