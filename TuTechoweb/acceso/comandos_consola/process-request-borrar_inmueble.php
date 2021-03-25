<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};


if ($_SERVER['REQUEST_METHOD'] == 'POST') {// Verificar que se envio la solicitud por AJAX
    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
      $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
      echo "Error: " . $e->getMessage();
    };


  if (isset($_POST["referencia"])) {
        $referencia = $_POST["referencia"];
        $tabla = '';
        $carpeta_fotos_path = '../../bienes_inmuebles/' . $_COOKIE['tutechopais'] . '/' . $referencia;

        if (strpos($referencia, 'C') !== false) {
          $tabla = "casa";
        } else {
          if (strpos($referencia, 'D') !== false) {
            $tabla = "departamento";
          } else {
            if (strpos($referencia, 'L') !== false) {
              $tabla = "local";
            } else {
              if (strpos($referencia, 'T') !== false) {
                $tabla = "terreno";
              };
            };
          };
        };

        function Delete($path){
            if (is_dir($path) === true){
                $files = array_diff(scandir($path), array('.', '..'));
                foreach ($files as $file){
                    Delete(realpath($path) . '/' . $file);
                };
                return rmdir($path);
            }else if (is_file($path) === true){
                return unlink($path);
            };

            return false;
        };

        Delete($carpeta_fotos_path);

        $statement_borrar =	$conexion->prepare("DELETE FROM $tabla WHERE referencia = :referencia");
        $statement_borrar->execute([':referencia' => $referencia]);

        echo $referencia . "borrado exitosamente";
  };


};

?>
