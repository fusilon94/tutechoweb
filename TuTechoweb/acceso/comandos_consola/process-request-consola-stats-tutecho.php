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
                <span class=\"categoria\" data=\"agencias\">
                    <img src=\"../../objetos/agencias_consola.svg\" alt=\"\">
                    <p>Agencias</p>
                </span>

                <span class=\"categoria\" data=\"franquicias\">
                    <img src=\"../../objetos/franquicias_categoria.svg\" alt=\"\">
                    <p>Franquicias</p>
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

            if ($categoria == 'agencias') {
                echo"
                    <span class=\"sub_categorie_btn activo\" data=\"\">
                        <img src=\"../../objetos/curve_growth.svg\" alt=\"\">
                        <p>Agencias</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/curve_comparison.svg\" alt=\"\">
                        <p>Ganancias</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/scatter_plot.svg\" alt=\"\">
                        <p>Agencias Detalle</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Avance Teck.</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>% Express</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_average_line.svg\" alt=\"\">
                        <p>Agentes</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart_average_line.svg\" alt=\"\">
                        <p>Agentes Express</p>
                    </span>

                ";
            }elseif ($categoria == 'franquicias') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/bar_chart.svg\" alt=\"\">
                        <p>Agencias</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Tipo Agencia</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Agentes</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/curve_comparison.svg\" alt=\"\">
                        <p>Ganancias</p>
                    </span>

                ";
            }elseif ($categoria == 'agentes') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/curve_evolution.svg\" alt=\"\">
                        <p>Agentes</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>% Express</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/cake_chart.svg\" alt=\"\">
                        <p>Genero</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/box_plot.svg\" alt=\"\">
                        <p>Antiguedad</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/scatter_plot.svg\" alt=\"\">
                        <p>Agentes Detalle</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/box_plot.svg\" alt=\"\">
                        <p>Comisiones</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/box_plot.svg\" alt=\"\">
                        <p>Comision/Genereo</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/box_plot.svg\" alt=\"\">
                        <p>Comision/Tipo Agente</p>
                    </span>

                ";
            }elseif ($categoria == 'ganancias') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Ganancias Totales</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Detalle</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Cuotas Iniciales</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Royalties</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Agencias Propias</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/curve_growth.svg\" alt=\"\">
                        <p>Acumulado</p>
                    </span>
                ";
            }elseif ($categoria == 'gastos') {
                echo"
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_tot.svg\" alt=\"\">
                        <p>Gastos</p>
                    </span>
                    
                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/histogram_stack.svg\" alt=\"\">
                        <p>Detalle</p>
                    </span>

                    <span class=\"sub_categorie_btn\" data=\"\">
                        <img src=\"../../objetos/donut_chart.svg\" alt=\"\">
                        <p>Total Segmentado</p>
                    </span>
                ";
            };
        };

    };

};

?>
