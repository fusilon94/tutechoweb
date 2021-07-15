<?php session_start(); //si se usan $_SESSION hay que poner esto al principio

if (isset($_SESSION['usuario'])) {//si una SESSION a sido definida entonces dejar pasar, si no redirigir a login.php
  if ($_SESSION['cookie_pais'] !== $_COOKIE['tutechopais']) {
    header('Location: ../cerrar_session.php');
  };

}else {
  header('Location: ../login.php');
};
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Conexion con la database

    $tutechodb = "tutechodb_" . $_COOKIE['tutechopais'];

    try {
    	$conexion = new PDO('mysql:host=localhost;dbname=' . $tutechodb . ';charset=utf8', 'root', '');
    } catch (PDOException $e) { //en caso de error de conexion repostarlo
    	echo "Error: " . $e->getMessage();
    };

    // function get_tabla($referencia) {
    //   $dict = ['C' => 'casa', 'D' => 'departamento', 'L' => 'local', 'T'=> 'terreno'];
    //   return $dict[$referencia[5]];
    // };

    $lista_spanish = [
        '01' => 'Enero',
        '02' => 'Febrero',
        '03' => 'Marzo',
        '04' => 'Abril',
        '05' => 'Mayo',
        '06' => 'Junio',
        '07' => 'Julio',
        '08' => 'Agosto',
        '09' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
    ];

 // --> ACA AGREGAR OTRAS LISTAS PA OTROS IDIOMAS


    if ( isset($_POST['agencia_id']) && isset($_POST['id_factura']) ) {//INTERMEDIACION INMOBILIARIA
        $agencia_id = $_POST['agencia_id'];
        $id_factura = $_POST['id_factura'];

        if ($_COOKIE['tutechopais'] == 'bolivia') {

            $consulta_agencia =	$conexion->prepare("SELECT departamento, ciudad, barrio, direccion, direccion_complemento, NIT, location_tag, razon_social FROM agencias WHERE id = :id");
            $consulta_agencia->execute([':id' => $agencia_id]);
            $agencia_datos = $consulta_agencia->fetch(PDO::FETCH_ASSOC);

            $current_date = date("d/m/Y");
            $fecha_array = explode("/", $current_date);
            $meses = $lista_spanish;
            $fecha_string = $fecha_array[0] . " de " . $meses[$fecha_array[1]] . " de " . $fecha_array[2];

            $agencia_datos['fecha_string'] = $fecha_string;

          
            $consulta_factura = $conexion->prepare("SELECT detalle FROM facturas WHERE id = :id");
            $consulta_factura->execute([':id' => $id_factura]);
            $factura_datos = $consulta_factura->fetch(PDO::FETCH_COLUMN, 0);

            $agencia_datos['detalle_factura'] = json_decode($factura_datos);


        };
        
        // --> ACA COLOCAR ELSEIFs PARA OTROS PAISES 
        

        echo json_encode($agencia_datos);
        
    };

    // --> AGREGAR LOS IF ISSET PARA EVENTOS OTROS QUE INTERMEDIACION


    
}
?>
