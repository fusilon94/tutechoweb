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
      <title>Consola - Imprimir Cupónes</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/consola_imprimir_cupones.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>

      <script>

        function confirmacion(oObject){
          var parent = oObject.parentNode.querySelector("div.boton_borrador_formulario_borrar_confirmar");
          var trashicon = oObject.querySelector("i.fas");

          $(trashicon).toggleClass("fa-file-pdf fa-times");

          if ($(parent).is(":hidden")) {
            $(parent).show("slide", { direction: "left" }, 800);
          } else {
            $(parent).hide("slide", { direction: "left" }, 800);
          };
        }
      </script>

      <script>
        function confirmacion_activar(oObject){
                  var codigo_cupon = $("#codigo_cupon_input").val();
                  $.ajax({
                      type: "POST",
                      url: "process-request-validar-codigo-cupon.php",
                      data: { codigo_cupon_sent : codigo_cupon },
                  }).done(function(data){

                    if (data == '') {
                      $('.popup_success').css('visibility',  'visible');
                      $('.popup_success_text').html('Codigo NO valido');
                    }else {
                      var barrio_cupones = data;
                      $("#barrio_cupones_sent").val(barrio_cupones);
                      $("#codigo").val(codigo_cupon);

                      $('.overlay_popup_aviso_advertencia').css('visibility',  'visible');
                    }


                  });
        }
      </script>

      <script>
      $(document).ready(function(){
        jQuery(function($){

              $('.popup_success_cerrar i.fa-times').on("click", function(){
                $('.popup_success').css('visibility',  'hidden');
              });

              $('.btn_cancelar').on("click", function(){
                $('.overlay_popup_aviso_advertencia').css('visibility',  'hidden');
              });

              $('.btn_aceptar').on("click", function(){
                $('#codigo_cupones_form').submit();
              });

          });
        });
      </script>


 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">

<!-- BARRA DE NAVEGACION -->
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
  <a href="sponsors_cupones_consola.php">
    <span class="fas fa-arrow-circle-left fa-2x regreso_boton"></span>
    <span><p>VOLVER ATRÁS</p></span>
  </a>
  </div>

  <div class="popup_success">
    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text"></p>
  </div>

  <div class="overlay_popup_aviso_advertencia">
      <div class="popup_aviso_advertencia">
        <p class="popup_aviso_texto">El codigo ingresado es válido y solo podrá ser usado UNA SOLA VEZ.</p>
        <p class="popup_aviso_pregunta">¿Desea continuar?</p>
        <div class="popup_aviso_botones_container">
          <span class="btn_cancelar"><i class="fa fa-times"></i>Cancelar</span>
          <span class="btn_aceptar"><i class="fa fa-check"></i><span>Aceptar</span></span>
        </div>
      </div>
  </div>

  <h1 class="titulo">Consola - Imprimir Cupónes</h1>
  <hr class="barra">

  <div class="contenedor_borradores">



      <div class="boton_borrador_mini_contenedor">
        <div id="codigo_cupon_input_container" class="boton_borrador_formulario">
          <label for="codigo_cupon_input"></label>
          <input type="text" id="codigo_cupon_input" name="codigo_cupon_input" placeholder="Ingrese el Código" value="">
        </div>
        <div class="boton_borrador_formulario_borrar" onclick="confirmacion(this)">
          <i class="fas fa-file-pdf" aria-hidden="true"></i>
        </div>
        <div class="boton_borrador_formulario_borrar_confirmar" onclick="confirmacion_activar(this)">
          <p>IMPRIMIR?</p>
        </div>
      </div>


      <form id="codigo_cupones_form" autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="hidden" id="barrio_cupones_sent" name="barrio_cupones_sent" value="">
        <input type="hidden" id="codigo" name="codigo" value="">
      </form>




  </div>

</main>
    </div>

 </body>
</html>
