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


      if (isset($_POST["sponsor_nombre_sent"])) {
        $sponsor_label = $_POST["sponsor_nombre_sent"];

        $consulta_sponsor_logo =	$conexion->prepare("SELECT label, logo FROM sponsors WHERE label=:label");
        $consulta_sponsor_logo->execute(['label' => $sponsor_label]);//SE PASA EL LABEL DEL SPONSOR
        $sponsor_logo = $consulta_sponsor_logo->fetchAll();
      };

      $newArr = array();//se define un array vacio para colocar los arrays unicos del nested array
      foreach ($sponsor_logo as $val) {//hagarramos el nested arrar que puede contener repeticiones
          $newArr[$val['logo']] = $val;//definimos el nuevo array con el valos y nombre de los array internos del nested, nota, cuando se llame al mismo nombre o indice, este se sobreescribe y no se suma
      }
      $array = array_values($newArr);//aqui se traspasa los valores del array anterior a una nueva variable
      // print_r($sponsor_logo);
      // print_r($array);

      foreach ($array as $logo) {//se para la variable que es un array que contiene logos sin repeticiones
        echo "
          <span class=\"logo existente\"><img src=\"" . $logo['logo'] . "\" alt=\"" . $logo['label'] . "\"></span>
        ";
      };


};

?>
