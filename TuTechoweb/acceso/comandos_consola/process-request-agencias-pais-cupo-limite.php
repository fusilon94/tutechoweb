<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["paisChoice"])){
    // Capture selected departamento
    $paisChoice = $_POST["paisChoice"];

    // Conexion con la database

    $tutechodb = "tutechodb_" . $paisChoice;

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    $tutechodb_internacional = "tutechodb_internacional";

    try {
    	$conexion_internacional = new PDO('mysql:host=localhost;dbname=' . $tutechodb_internacional . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    // Recuperar departamentos

    $nivel_acceso = $_SESSION['nivel_acceso'];

    if ($nivel_acceso == 1 || $nivel_acceso == 11 || $nivel_acceso == 12) {

        $consulta_agentes_cupo = $conexion_internacional->prepare("SELECT agencia_cupo, agencia_express_cupo FROM paises WHERE pais = :pais");
        $consulta_agentes_cupo->execute([":pais" => $paisChoice]);
        $agentes_cupo = $consulta_agentes_cupo->fetch(PDO::FETCH_ASSOC);

        $consulta_agencias = $conexion->prepare("SELECT id, location_tag, express FROM agencias");
        $consulta_agencias->execute();
        $agencias = $consulta_agencias->fetchAll(PDO::FETCH_ASSOC);


        echo "<option></option>";

        if(isset($_POST['agencia_selected'])){

          $agencia_selected = $_POST['agencia_selected'];

          foreach($agencias as $agencia){

            $consulta_agentes_total = $conexion->prepare("SELECT id FROM agentes WHERE agencia_id = :agencia_id AND (nivel_acceso = 4 OR nivel_acceso = 10) ");
            $consulta_agentes_total->execute([":agencia_id" => $agencia['id']]);
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

            if ($cupo_agente_disponible == true) {
                if ($agencia['id'] == $agencia_selected) {
                    echo "<option value=\"" . $agencia['id'] . "\" selected>". $agencia['location_tag'] . "</option>";
                } else {
                    echo "<option value=\"" . $agencia['id'] . "\">". $agencia['location_tag'] . "</option>";
                }; 
            };

                      
          };

        }else{
            
          foreach($agencias as $agencia){

            $consulta_agentes_total = $conexion->prepare("SELECT id FROM agentes WHERE agencia_id = :agencia_id AND (nivel_acceso = 4 OR nivel_acceso = 10) ");
            $consulta_agentes_total->execute([":agencia_id" => $agencia['id']]);
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

            if ($cupo_agente_disponible == true) {
                echo "<option value=\"" . $agencia['id'] . "\">". $agencia['location_tag'] . "</option>";
            };
            
          };

        };
        
    }elseif ($nivel_acceso == 3) {

        $usuario = $_SESSION['usuario'];

        $consulta_agentes_cupo = $conexion_internacional->prepare("SELECT agencia_cupo, agencia_express_cupo FROM paises WHERE pais = :pais");
        $consulta_agentes_cupo->execute([":pais" => $paisChoice]);
        $agentes_cupo = $consulta_agentes_cupo->fetch(PDO::FETCH_ASSOC);

        $consulta_agencia_usuario = $conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
        $consulta_agencia_usuario->execute([":usuario" => $usuario]);
        $agencia_usuario = $consulta_agencia_usuario->fetch();

        $consulta_agencias = $conexion->prepare("SELECT id, location_tag, express FROM agencias WHERE id = :id");
        $consulta_agencias->execute([":id" => $agencia_usuario[0]]);
        $agencias = $consulta_agencias->fetch(PDO::FETCH_ASSOC);

        $consulta_agentes_total = $conexion->prepare("SELECT id FROM agentes WHERE agencia_id = :agencia_id AND (nivel_acceso = 4 OR nivel_acceso = 10) ");
        $consulta_agentes_total->execute([":agencia_id" => $agencia_usuario[0]]);
        $agentes_total = $consulta_agentes_total->fetchAll(PDO::FETCH_ASSOC);

        $cupo_agente_disponible = false;

        if ($agencias['express'] == 1) {
            if (count($agentes_total) < $agentes_cupo['agencia_express_cupo']) {
                $cupo_agente_disponible = true;
            };
        }elseif ($agencias['express'] == 0) {
            if (count($agentes_total) < $agentes_cupo['agencia_cupo']) {
                $cupo_agente_disponible = true;
            };
        };

        echo "<option></option>";
        if ($cupo_agente_disponible == true) {
            if (isset($_POST['agencia_selected'])) {
                echo "<option value=\"" . $agencias['id'] . "\" selected>". $agencias['location_tag'] . "</option>";
            } else {
                echo "<option value=\"" . $agencias['id'] . "\">". $agencias['location_tag'] . "</option>";
            };
        };
        
    };
   
}
?>
