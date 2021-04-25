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

                <span class=\"categoria\" data=\"tramites\">
                    <img src=\"../../objetos/soporte_legal_icon.svg\" alt=\"\">
                    <p>Tramites</p>
                </span>

                <span class=\"categoria\" data=\"agentes\">
                    <img src=\"../../objetos/agentes_admin_consola.svg\" alt=\"\">
                    <p>Agentes</p>
                </span>

                <span class=\"categoria\" data=\"ganancias\">
                    <img src=\"../../objetos/pagos_icon.svg\" alt=\"\">
                    <p>Ganancias</p>
                </span>

                <span class=\"categoria\" data=\"gastos\">
                    <img src=\"../../objetos/pagos_gastos_icon.svg\" alt=\"\">
                    <p>Gastos</p>
                </span>
                
            ";

        }elseif ($action == 'get_sub_categorias' && isset($_POST['categoria_sent'])) {
            $categoria = $_POST['categoria_sent'];

            if ($categoria == 'visitas') {
                echo"
                    <span class=\"sub_categorie_btn activo\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Exito</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>Total Exito</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Inmuebles</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/box_plot.svg\" alt=\"\">
                        <p>Totat</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart.svg\" alt=\"\">
                        <p>Tot de Agentes</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_vertical.svg\" alt=\"\">
                        <p>Top Agentes</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Tipo Inmuebles</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart.svg\" alt=\"\">
                        <p>Tiempos</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/box_plot.svg\" alt=\"\">
                        <p>Tiempos</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/curve_comparison.svg\" alt=\"\">
                        <p>Tiempos</p>
                    </span>
                ";
            }elseif ($categoria == 'registros') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_overlayed.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Inmueble</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart.svg\" alt=\"\">
                        <p>Total Agentes</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Tipo Inmuebles</p>
                    </span>
                ";
            }elseif ($categoria == 'conciliaciones') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart.svg\" alt=\"\">
                        <p>Total Personales</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart.svg\" alt=\"\">
                        <p>Total Proyectos</p>
                    </span>
                ";
            }elseif ($categoria == 'cierres') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_overlayed.svg\" alt=\"\">
                        <p>Total</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Cierre</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Inmueble</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_vertical.svg\" alt=\"\">
                        <p>Total/Tipo Cierre</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_grouped.svg\" alt=\"\">
                        <p>Grupos Cierres</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_grouped.svg\" alt=\"\">
                        <p>Grupos Inmuebles</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>Reservados</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Cierre Doc</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_vertical.svg\" alt=\"\">
                        <p>Total Agentes</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_vertical.svg\" alt=\"\">
                        <p>Total Zonas</p>
                    </span>
                ";
                
            }elseif ($categoria == 'ganancias') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total Bruto</p>
                    </span>
                    
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Bruto/Inmuebles</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total Neto</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Neto/Inmuebles</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/curve_growth.svg\" alt=\"\">
                        <p>ACumulado</p>
                    </span>
                ";
            }elseif ($categoria == 'gastos') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Total</p>
                    </span>
                    
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Total Tipos</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Total Tipos</p>
                    </span>
                ";
            }elseif ($categoria == 'tramites') {
                echo"          
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Tramite</p>
                    </span>
                ";
            }elseif ($categoria == 'agentes') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/curve_evolution.svg\" alt=\"\">
                        <p>Total/Tiempo</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/scatter_plot.svg\" alt=\"\">
                        <p>Agentes Detalle</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>Genero</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>Rango</p>
                    </span>
                ";
            };
        };

    };

};

?>
