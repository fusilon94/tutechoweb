<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};

if(isset($_POST["agente_id_sent"]) && isset($_POST["agente_telefono_sent"])){
    // Capture selected departamento
    $agente_id = filter_var($_POST["agente_id_sent"], FILTER_SANITIZE_STRING);
    $agente_telefono = filter_var($_POST["agente_telefono_sent"], FILTER_SANITIZE_STRING);

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_SESSION['cookie_pais'];

    $nivel_acceso = $_SESSION['nivel_acceso'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    if ($agente_id == '' || $agente_telefono == '') {

    echo "Error: Datos Faltantes";

    }else{

        $consulta_agente_existe = $conexion->prepare("SELECT id, agencia_id, nivel_acceso FROM agentes WHERE id = :id");
        $consulta_agente_existe->execute([":id" => $agente_id]);
        $agente_existe = $consulta_agente_existe->fetch(PDO::FETCH_ASSOC);

        if($agente_existe == '') {

        echo "#ID Agente no existe";

        }else {
        
        if ($nivel_acceso == 1 || $nivel_acceso == 11) {

            $statement = $conexion->prepare("UPDATE agentes SET disponible = 1, contacto = :contacto WHERE id=:id");
            $statement->execute([
            ':id' => $agente_id,
            ':contacto' => $agente_telefono
            ]);

            echo"Agente Habilitado Exitosamente";

        }elseif ($nivel_acceso == 12) {
            if ($agente_existe['nivel_acceso'] == 4) {

                echo "Corresponde al Jefe de Agencia Local habilitar a este Agente";

            }elseif ($agente_existe['nivel_acceso'] == 10) {

                $statement = $conexion->prepare("UPDATE agentes SET disponible = 1, contacto = :contacto WHERE id=:id");
                $statement->execute([
                ':id' => $agente_id,
                ':contacto' => $agente_telefono
                ]);

                echo"Agente Habilitado Exitosamente";

            };
        }elseif ($nivel_acceso == 3) {
            if ($agente_existe['nivel_acceso'] == 10) {

                echo "No tienes autorización para habilitar Agentes Express";
                
            }elseif ($agente_existe['nivel_acceso'] == 4) {

                $consulta_agencia_id = $conexion->prepare("SELECT agencia_id FROM agentes WHERE usuario = :usuario");
                $consulta_agencia_id->execute([":usuario" => $_SESSION['usuario']]);
                $agencia_id = $consulta_agencia_id->fetch(PDO::FETCH_ASSOC);

                if ($agencia_id['agencia_id'] !== $agente_existe['agencia_id']) {

                    echo "No tienes autorización para habilitar a este Agente";

                }elseif ($agencia_id['agencia_id'] == $agente_existe['agencia_id']) {
                   
                    $statement = $conexion->prepare("UPDATE agentes SET disponible = 1, contacto = :contacto WHERE id=:id");
                    $statement->execute([
                    ':id' => $agente_id,
                    ':contacto' => $agente_telefono
                    ]);
    
                    echo"Agente Habilitado Exitosamente";

                };

                

            };
        };

    };

  
   
  };
};
?>
