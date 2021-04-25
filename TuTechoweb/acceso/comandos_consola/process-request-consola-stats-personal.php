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


    if (isset($_POST["action_sent"])) {

    $action = $_POST["action_sent"];

    
        if ($action == 'get_categorias') {
           
            $consulta_agente =	$conexion->prepare("SELECT id, nivel_acceso FROM agentes WHERE usuario = :usuario");
            $consulta_agente->execute([":usuario" => $_SESSION['usuario']]);
            $agente =	$consulta_agente->fetch(PDO::FETCH_ASSOC);

            if ($agente['nivel_acceso'] == 4 || $agente['nivel_acceso'] == 10) {
                echo"
                    <span class=\"categoria\" data=\"visitas\">
                        <img src=\"../../objetos/visita_categoria.svg\" alt=\"\">
                        <p>Visitas</p>
                    </span>

                    <span class=\"categoria\" data=\"registros\">
                        <img src=\"../../objetos/registros_categoria.svg\" alt=\"\">
                        <p>Registros</p>
                    </span>

                    <span class=\"categoria\" data=\"conciliaciones\">
                        <img src=\"../../objetos/conciliacion_categoria.svg\" alt=\"\">
                        <p>Conciliaciones</p>
                    </span>

                    <span class=\"categoria\" data=\"cierres\">
                        <img src=\"../../objetos/alquilar_icon.svg\" alt=\"\">
                        <p>Cierres</p>
                    </span>

                    <span class=\"categoria\" data=\"comisiones\">
                        <img src=\"../../objetos/pagos_icon.svg\" alt=\"\">
                        <p>Comisiones</p>
                    </span>
                ";
            }elseif ($agente['nivel_acceso'] == 7) {
                echo"
                    <span class=\"categoria\" data=\"registros_fotografo\">
                        <img src=\"../../objetos/registros_categoria.svg\" alt=\"\">
                        <p>Registros</p>
                    </span>
                ";
            }elseif ($agente['nivel_acceso'] == 3) {
                echo"
                    <span class=\"categoria\" data=\"tramites\">
                        <img src=\"../../objetos/soporte_legal_icon.svg\" alt=\"\">
                        <p>Tramites</p>
                    </span>

                    <span class=\"categoria\" data=\"cierres_jefe\">
                        <img src=\"../../objetos/alquilar_icon.svg\" alt=\"\">
                        <p>Cierres</p>
                    </span>
                ";
            };





        }elseif ($action == 'get_sub_categorias' && isset($_POST['categoria_sent'])) {
            $categoria = $_POST['categoria_sent'];

            if ($categoria == 'visitas') {
                echo"
                    <span class=\"sub_categorie_btn activo\" data=\"histograma_count_total_visitas\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"histograma_count_stacked_exito_visitas\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Exito</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"histograma_count_stacked_tipo_inmueble_visitas\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Inmuebles</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"donut_tipo_inmueble_visitas\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Tipo Inmuebles</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"bar_chart_visitas_tipo_inmueble\">
                        <img src=\"../../objetos/bar_chart.svg\" alt=\"\">
                        <p>Tiempos</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"line_plot_visitas_tipo_inmueble\">
                        <img src=\"../../objetos/curve_comparison.svg\" alt=\"\">
                        <p>Tiempos</p>
                    </span>
                ";
            }elseif ($categoria == 'registros') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"histograma_count_total_overlayed_registros\">
                        <img src=\"../../objetos/histogram_overlayed.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"donut_tipo_inmueble_registros\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Tipo Inmuebles</p>
                    </span>
                ";
            }elseif ($categoria == 'conciliaciones') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"histograma_count_total_conciliaciones\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"histograma_count_stacked_conciliaciones\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo</p>
                    </span>
                ";
            }elseif ($categoria == 'cierres') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"histograma_count_overlayed_cierres\">
                        <img src=\"../../objetos/histogram_overlayed.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"histograma_count_stacked_tipo_cierres\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Cierre</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"histograma_count_stacked_tipo_inmueble_cierres\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Inmueble</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"bar_chart_vertical_cierres\">
                        <img src=\"../../objetos/bar_chart_vertical.svg\" alt=\"\">
                        <p>Total/Tipo Cierre</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"bar_chart_grouped_cierres\">
                        <img src=\"../../objetos/bar_chart_grouped.svg\" alt=\"\">
                        <p>Total/Grupos</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"pie_reservados_cierres\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>Reservados</p>
                    </span>


                ";
                
            }elseif ($categoria == 'comisiones') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"histograma_sum_total_comisiones\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total</p>
                    </span>
                    
                    <span class=\"sub_categorie_btn\" data=\"histograma_sum_stacked_tipo_inmueble_comisiones\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Cierre</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"donut_tipo_inmueble_comisiones\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Tipo Cierre</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"bar_chart_vertical_comisiones\">
                        <img src=\"../../objetos/bar_chart_vertical.svg\" alt=\"\">
                        <p>Total/Tipo Inmueble</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"line_plot_comisiones\">
                        <img src=\"../../objetos/curve_growth.svg\" alt=\"\">
                        <p>Acumulado</p>
                    </span>
                ";
            }elseif ($categoria == 'registros_fotografo') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"histograma_count_total_registros_fotografo\">
                        <img src=\"../../objetos/histogram_overlayed.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"donut_tipo_inmueble_registros_fotografo\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Tipo Inmuebles</p>
                    </span>
                ";
            }elseif ($categoria == 'tramites') {
                echo"          
                    <span class=\"sub_categorie_btn\" data=\"histograma_count_stacked_tramites\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Tramite</p>
                    </span>
                ";
            }elseif ($categoria == 'cierres_jefe') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"histograma_count_total_cierres_jefe\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"donut_tipo_inmueble_cierres_jefe\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Tipo Cierre</p>
                    </span>
                ";
            };
        };

    };

};

?>
