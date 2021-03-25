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
      <title>Consola - Crear Tour Virtual</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_agregar_bien_inmueble.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script type="text/javascript">
        $(document).ready(function(){
          jQuery(function($){

            $(".boton_borrador_formulario").on("click", function(){
              var formulario_referencia = $(this).attr('id');
              var formulario_tabla = $(this).attr('name');

              $.ajax({
                  type: "POST",
                  url: "process-request-form-info-agregar-fotos.php",
                  data: { referencia_sent : formulario_referencia, tabla_sent : formulario_tabla}
              }).done(function(data){
                var info_form_bien = data;
                  $('.overlay_sponsor_previsualizacion').toggleClass("active").html(info_form_bien);
              });

            });

          //CODIGO QUE EVITA QUE SE ABRAN MAS POPUP SPONSORS AL HACER CLICK EN EL MISMO YA QUE ES UN ELEMENTO CHILD DEL SPONSOR

              $('.overlay_sponsor_previsualizacion').on('click',function(){

              $(".info_adicional_container").remove();
              $('.overlay_sponsor_previsualizacion').toggleClass("active");

            });

            $('.overlay_sponsor_previsualizacion').on('click', '.previsualizacion_container' ,function(e){
                    e.stopPropagation();//evita que active el click event de su contenedor, la elemento sponsor de la ficha bien
            });

          });

        });
      </script>
      <script>
        function confirmacion(e){
          var parent = $(e).parent().find("div.boton_borrador_formulario_borrar_confirmar");
          var trashicon = $(e).find("i.fas");

          $(trashicon).toggleClass("fa-globe fa-times");

          if ($(parent).is(":hidden")) {
            $(parent).show("slide", { direction: "left" }, 800);
          } else {
            $(parent).hide("slide", { direction: "left" }, 800);
          };
        }
      </script>

      <script>
        function confirmacion_borrar(e){
                  var nuevo_formulario_referencia = $(e).parent().find('.boton_borrador_formulario').attr('id');
                  var nuevo_formulario_tabla = $(e).parent().find('.boton_borrador_formulario').attr('name');
                  $("#nuevo_bien_referencia").val(nuevo_formulario_referencia);
                  $("#nuevo_bien_tabla").val(nuevo_formulario_tabla);
                  $("#nuevo_bien_form").submit();
        }
      </script>

 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
<div class="overlay_sponsor_previsualizacion">

</div>
      	  <header>
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
<main>
  <div class="regreso_boton_div_contenedor">
  <a href="vr_consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>



  <h1 class="titulo">Consola - Crear Tour Virtual</h1>
  <hr class="barra">

  <div class="contenedor_borradores">

    <?php
    foreach ($formularios_casa_nuevos as $formularios) {
      echo "
      <div class=\"boton_borrador_mini_contenedor\">
        <div id=\"" . $formularios['referencia'] . "\" name=\"" . $formularios['tipo_bien'] . "\" class=\"boton_borrador_formulario\">
          <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class='nombre'>" . $formularios['referencia'] . "</span></p>
        </div>
        <div class=\"boton_borrador_formulario_borrar\" onclick=\"confirmacion(this)\">
          <i class=\"fas fa-globe\" aria-hidden=\"true\"></i>
        </div>
        <div class=\"boton_borrador_formulario_borrar_confirmar\" onclick=\"confirmacion_borrar(this)\">
          <p>CREAR?</p>
        </div>
      </div>

      ";
    };

    foreach ($formularios_departamento_nuevos as $formularios) {
      echo "
      <div class=\"boton_borrador_mini_contenedor\">
        <div id=\"" . $formularios['referencia'] . "\" name=\"" . $formularios['tipo_bien'] . "\" class=\"boton_borrador_formulario\">
          <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class='nombre'>" . $formularios['referencia'] . "</span></p>
        </div>
        <div class=\"boton_borrador_formulario_borrar\" onclick=\"confirmacion(this)\">
          <i class=\"fas fa-globe\" aria-hidden=\"true\"></i>
        </div>
        <div class=\"boton_borrador_formulario_borrar_confirmar\" onclick=\"confirmacion_borrar(this)\">
          <p>CREAR?</p>
        </div>
      </div>

      ";
    };

    foreach ($formularios_local_nuevos as $formularios) {
      echo "
      <div class=\"boton_borrador_mini_contenedor\">
        <div id=\"" . $formularios['referencia'] . "\" name=\"" . $formularios['tipo_bien'] . "\" class=\"boton_borrador_formulario\">
          <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class='nombre'>" . $formularios['referencia'] . "</span></p>
        </div>
        <div class=\"boton_borrador_formulario_borrar\" onclick=\"confirmacion(this)\">
          <i class=\"fas fa-globe\" aria-hidden=\"true\"></i>
        </div>
        <div class=\"boton_borrador_formulario_borrar_confirmar\" onclick=\"confirmacion_borrar(this)\">
          <p>CREAR?</p>
        </div>
      </div>

      ";
    };

    foreach ($formularios_terreno_nuevos as $formularios) {
      echo "
      <div class=\"boton_borrador_mini_contenedor\">
        <div id=\"" . $formularios['referencia'] . "\" name=\"" . $formularios['tipo_bien'] . "\" class=\"boton_borrador_formulario\">
          <i class=\"fas fa-search\" aria-hidden=\"true\"></i><p><span class='nombre'>" . $formularios['referencia'] . "</span></p>
        </div>
        <div class=\"boton_borrador_formulario_borrar\" onclick=\"confirmacion(this)\">
          <i class=\"fas fa-globe\" aria-hidden=\"true\"></i>
        </div>
        <div class=\"boton_borrador_formulario_borrar_confirmar\" onclick=\"confirmacion_borrar(this)\">
          <p>CREAR?</p>
        </div>
      </div>

      ";
    };

    ?>



  </div>

  <form id="nuevo_bien_form" autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <input type="hidden" id="nuevo_bien_referencia" name="nuevo_bien_referencia" value="">
    <input type="hidden" id="nuevo_bien_tabla" name="nuevo_bien_tabla" value="">
  </form>

</main>
    </div>

 </body>
</html>
