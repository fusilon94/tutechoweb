<?php
if(isset($_SESSION['usuario'])){} else{header('Location: ../acceso.php');} //para evitar que alguien entre directamente al .view.php y porque en los view no se abre ninguna session

?>
<!DOCTYPE html>
<html lang="es">
 <head>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#2d89ef">
<meta name="theme-color" content="#ffffff">
      <title>Consola - Editor de Contratos</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/contrato_estilos_generales.css" >
      <link rel="stylesheet" href="../../css/jquery-ui.css">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">
      <!-- <link rel="stylesheet" href="../../css/contrato_estilos_generales_impresion.css" media="print"> -->

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/js.cookie.js"></script>
      <script src="../../js/menu.js"></script>
      <script src="../../js/qart.min.js"></script>
      <script src="../../js/numero_a_letras.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script>
        const tipo_selected = "<?php echo $tipo_selected; ?>";
        const sub_tipo_selected = "<?php echo $sub_tipo_selected; ?>";
        const pais_selected = "<?php echo $pais_selected; ?>";

        let etapa_actual = 1;
        let preguntas_grupos_cantidad;

        const agente_id = "<?php echo $agente_id[0]; ?>";
      </script>
      <script src="../../js/contrato_funciones_generales.js"></script>
      <script src="../../js/contrato_<?php echo $tipo_selected . '_' . $sub_tipo_selected . $pais_selected; ?>.js"></script>
      

 </head>
 <body>

 <div class="overlay">
     <div class="popup">
         <span class="popup_cerrar"><i class="fa fa-times"></i></span>
         <h2>Indicaciones</h2>
         <span class="indicaciones_impresion">
            <p>Margenes: Arriba 2cm, Izquierda 2cm, Derecha 2cm, Abajo 2.2cm</p>
            <p>Impresion en Doble cara, en Hoja A4</p>
            <p>Llenado, firmas y huellas en tinta azul</p>
         </span>
         <span class="btn_confirmar_impresion">CONTINUAR</span>
     </div>
 </div>

 <div class="overlay_datos">
     <div class="popup_datos">
         <span class="popup_datos_cerrar"><i class="fa fa-times"></i></span>
         
         <div class="popup_datos_contenido">

         </div>
     </div>
 </div>

 <div id="fondo"></div>
      <div id="contenedor_total">

<!-- BARRA DE NAVEGACION -->

      	  <header>
            <div class="regreso_boton_div_contenedor">
            <a href="consola_contratos_personal.php">
              <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
              <span><p>VOLVER ATRÁS</p></span>
            </a>
            </div>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="../../index.php"><img src="../../objetos/logotipo2.svg" alt="TuTecho.com" class="menu_logo"></a></li>
                </ul>
            </div>
      	    <nav class="scroll">
      	     <div id="menu">
                <ul class="ulmenu">
                  <li class="logo_element limenu"><a href="../../index.php" class="a_menu"><img src="../../objetos/logotipo2.svg" alt="Tu Techo.com" class="logo_img"></a></li>
                </ul>
             </div>
           </nav>
          </header>


<!-- CONTENIDO PRINCIPAL -->

        <div class="contenido_pagina">

            <div class="contrato_header">
                <span class="contrato_titulo"></span>
                <div class="barra_progreso_wrap">
                    <label for="" class="barra_progreso_label">Progreso:</label>
                    <div class="barra_progreso">
                        <span class="progreso"></span>
                        <span class="progreso_porcentaje"><p class="progreso_num">0</p><p>%</p></span>
                    </div>
                </div>
            </div>

            <div class="contrato_global_wrap">

                <div class="left_contenedor">
                    <div class="preguntas_contenedor">

                        <div class="preguntas_mini_contenedor">

                            <div class="etapas_wrap">

                            </div>


                        </div>

                        <hr class="barra_separacion_navigacion">


                        <div class="navegacion_wrap">
                            <span class="paso_anterior">
                                <i class="paso_btn_icon_left"> < </i>
                                <p class="nav_text">ATRÁS</p>
                            </span>
                            <span class="paso_adelante">
                                <i class="paso_btn_icon_right"> > </i>
                                <p class="nav_text">SIGUIENTE</p>
                            </span>
                        </div>


                    </div>

                    <div id="btn_imprimir" class="btn_imprimir">
                        <span>IMPRIMIR</span>
                        <img src="../../objetos/imprimir_btn.svg" alt="IMPRIMIR">
                    </div>
                </div>

                

                <div class="contrato_contenedor">

                </div>

                

            </div>


            
        </div>

    </div>
 </body>

 <?php //ASI NO TENEMOS PROBLEMAS DE VARIABLES PERSISTENTES, Y AL REFRESH OBLIGAMOS DE NUEVO AL CONTRATO DESDE EL INICIO
    // unset($_SESSION['tipo_selected']);
    // unset($_SESSION['pais_selected']);
    // unset($_SESSION['sub_tipo_selected']);
 ?>

</html>
