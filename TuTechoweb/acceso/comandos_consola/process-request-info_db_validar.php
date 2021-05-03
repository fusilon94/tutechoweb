<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX


    if (isset($_POST["pais_sent"]) && isset($_POST['tabla_sent']) && isset($_POST['id_sent'])) {
      $pais = $_POST["pais_sent"];
      $tabla = $_POST['tabla_sent'];
      $id = $_POST['id_sent'];

      $tutechodb = "tutechodb_" . $pais;

      try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
      } catch (PDOException $e) { //en caso de error de conexion repostarlo
        echo "Error: " . $e->getMessage();
      };
      
      if ($tabla == "agentes") {

        $consulta_datos_db =	$conexion->prepare("SELECT nombre, apellido, doc_identidad, email, domicilio, agencia_id FROM $tabla WHERE id=:id ");
        $consulta_datos_db->execute([':id' => $id]);
        $datos_db = $consulta_datos_db->fetch(PDO::FETCH_ASSOC);

        echo json_encode($datos_db);

      }else {
          if ($tabla == "borradores_casa") {

            $consulta_datos_db =	$conexion->prepare("SELECT location_tag, direccion, direccion_complemento, anticretico, pre_venta, exclusivo, estado, mantenimiento, avaluo, base_imponible, precio, superficie_inmueble, superficie_terreno, contrato_especial, contrato_especial_comentario, gestion_acordada, conciliador, conciliacion_tipo FROM $tabla WHERE referencia=:referencia ");
            $consulta_datos_db->execute([':referencia' => $id]);
            $datos_db = $consulta_datos_db->fetch(PDO::FETCH_ASSOC);

          }else if($tabla == "borradores_departamento" || $tabla == "borradores_local"){

            $consulta_datos_db =	$conexion->prepare("SELECT location_tag, direccion, direccion_complemento, anticretico, pre_venta, exclusivo, estado, mantenimiento, avaluo, base_imponible, precio, superficie_inmueble  contrato_especial, contrato_especial_comentario, gestion_acordada, conciliador, conciliacion_tipo FROM $tabla WHERE referencia=:referencia ");
            $consulta_datos_db->execute([':referencia' => $id]);
            $datos_db = $consulta_datos_db->fetch(PDO::FETCH_ASSOC);

          }else if($tabla == "borradores_terreno"){

            $consulta_datos_db =	$conexion->prepare("SELECT location_tag, direccion, direccion_complemento, anticretico, pre_venta, exclusivo, estado, mantenimiento, avaluo, base_imponible, precio, superficie_terreno contrato_especial, contrato_especial_comentario, gestion_acordada, conciliador, conciliacion_tipo FROM $tabla WHERE referencia=:referencia ");
            $consulta_datos_db->execute([':referencia' => $id]);
            $datos_db = $consulta_datos_db->fetch(PDO::FETCH_ASSOC);
            
          };

          echo json_encode($datos_db);
      };



    };

};

?>






