<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

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
  $array_acceso = [1,2,3,4,10,11];
  $usuario = $_SESSION['usuario'];
  if (in_array($nivel_acceso, $array_acceso) !== false){
    //Todo OK
  }
  else{header('Location: ../acceso.php');}; //si el usuario no tiene el nivel de acceso necesario redirijir al acceso.php


  try {
    $conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
  } catch (PDOException $e) { //en caso de error de conexion repostarlo
    echo "Error: " . $e->getMessage();
  };



    if(isset($_SESSION['cupon_barrio'])){

      $barrio_cupones = $_SESSION['cupon_barrio'];

      $consulta_sponsors =	$conexion->prepare("SELECT nombre, label, logo, direccion, contacto, web FROM sponsors WHERE barrio=:barrio AND cupon_agregado = 1 AND cupon_visible = 1");
      $consulta_sponsors->execute([':barrio' => $barrio_cupones]);
      $info_sponsor = $consulta_sponsors->fetchAll();

      $newArr = array();
      foreach ($info_sponsor as $sponsor) {
          $newArr[] = $sponsor[0];
      }
      $lista_sponsors = array_values($newArr);

      $in = str_repeat('?,', count($lista_sponsors) - 1) . '?';


      $consulta_cupones =	$conexion->prepare("SELECT * FROM cupones_sponsor WHERE sponsor IN ($in)");
      $consulta_cupones->execute($lista_sponsors);
      $cupones = $consulta_cupones->fetchAll(PDO::FETCH_ASSOC);

      $i=0;
      $newArray2 = array();
      foreach($cupones as $cupon) {
          $newArray2[] = array_merge($cupon,$info_sponsor[$i]);
          $i++;
      };
      $lista_cupones = array_values($newArray2);

      unset($_SESSION['cupon_barrio']);


    }else {
      header('Location: ../acceso.php');
    };


}else {
  header('Location: ../login.php');
};


require 'ficha_cupones_sponsor.view.php';
 ?>
