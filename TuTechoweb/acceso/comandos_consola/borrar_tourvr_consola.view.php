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
      <title>Consola - Re/Inactivar Tour VR</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="../../estilos.css">
      <link rel="stylesheet" type="text/css" href="../../css/flexslider.css">
      <link rel="stylesheet" href="../../css/font_awesome.css">
      <link rel="stylesheet" href="../../css/consola_agente.css">
      <link rel="stylesheet" href="../../css/borrar_tourvr_consola.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
      <link rel="stylesheet" href="../../css/consola_agente_jquery-ui.css">

      <script src="http://code.jquery.com/jquery-latest.js" onerror="this.onerror=null;this.src='../../js/jquery-latest.js';"></script>
      <script src="../../js/js.cookie.js"></script>
<script src="../../js/menu.js"></script>
      <script src="../../js/slider.js"></script>
      <script src="../../js/jquery.flexslider.js"></script>
      <script src="../../js/consola_agente_jquery-ui.min.js"></script>
      <script src="../../js/consola_agente_admin.js"></script>
      <script src="../../js/borrar_tourvr_consola.js"></script>

      <script>
        function confirmacion(e){
          var parent = $(e).parent().find("div.boton_borrador_formulario_borrar_confirmar");
          var trashicon = $(e).find("i.fas");

          $(trashicon).toggleClass("fa-trash-alt fa-times");

          if ($(parent).is(":hidden")) {
            $(parent).show("slide", { direction: "left" }, 800);
          } else {
            $(parent).hide("slide", { direction: "left" }, 800);
          };
        }

        function confirmacion2(e){
          var parent = $(e).parent().find("div.boton_borrador_formulario_borrar_confirmar");
          var trashicon = $(e).find("i.fas");

          $(trashicon).toggleClass("fa-power-off fa-times");

          if ($(parent).is(":hidden")) {
            $(parent).show("slide", { direction: "left" }, 800);
          } else {
            $(parent).hide("slide", { direction: "left" }, 800);
          };
        }
      </script>

      <script>
        function confirmacion_borrar(e){
                  var referencia_clicked = $(e).parent().find('.boton_borrador_formulario').attr('id');
                  var tabla_clicked = $(e).parent().find('.boton_borrador_formulario').attr('name');
                  var action_clicked = 'Inactivar';
                  var contenedor_botones = $(e).parent().remove();
                  $.ajax({
                      type: "POST",
                      url: "process-request-tourvr-inactivar-reactivar.php",
                      data: { referencia_sent : referencia_clicked, tabla_sent : tabla_clicked, action_sent : action_clicked },
                      dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
                  })
                  $('.popup_success').css('visibility',  'visible');
                  $('.popup_success_text').html('Tour VR Inactivado exitosamente');
        }

        function confirmacion_reactivar(e){
                  var referencia_clicked = $(e).parent().find('.boton_borrador_formulario').attr('id');
                  var tabla_clicked = $(e).parent().find('.boton_borrador_formulario').attr('name');
                  var action_clicked = 'Reactivar';
                  var contenedor_botones = $(e).parent().remove();
                  $.ajax({
                      type: "POST",
                      url: "process-request-tourvr-inactivar-reactivar.php",
                      data: { referencia_sent : referencia_clicked, tabla_sent : tabla_clicked, action_sent : action_clicked },
                      dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
                  })
                  $('.popup_success').css('visibility',  'visible');
                  $('.popup_success_text').html('Tour VR Inactivado exitosamente');
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

  <div class="popup_success">
    <span class="popup_success_cerrar"><i class="fa fa-times"></i></span>
    <i class="fa fa-exclamation-circle"></i><p class="popup_success_text"></p>
  </div>

  <h1 class="titulo">Inactivar/Reactivar Tour VR - Consola</h1>
  <hr class="barra">
  <p style="color: #fff; margin: 0.5em 0em 0.5em 2em;">* Solo podrán acceder a los TourVR de Bienes-Inmuebles registrados en su agencia</p>

    <div class="contenedor_consola">

      <span class="label_select_contenedor">- Busca Tour Vr según Referencia o Poblado/Barrio del Bien Inmueble -</span>
      <div class="select_contenedor">

          <div class="elemento_formulario input_referencia_container">
            <label for="input_referencia"> Referencia: </label>
            <input type="text" id="input_referencia" name="input_referencia" value="">
            <span class="input_referencia_btn">Buscar</span>
          </div>

          <div class="elemento_formulario">
            <hr><!-- barra separadora -->
          </div>

          <div class="elemento_formulario">
            <label for="departamento" class="departamento_label"></label>
            <select name="departamento" id="departamento" class="departamento">
              <option></option>
              <?php foreach ($regiones as $value): ?>
                <option><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="elemento_formulario">
              <label for="ciudad">Ciudad: </label>
              <select name="ciudad" id="ciudad" class="ciudad" onfocus='this.size=7;'
              onblur='this.size=0;'
              onchange='this.size=0; this.blur();' disabled>
              <option></option>
              </select>
          </div>

          <div class="elemento_formulario">
              <label for="barrio">Barrio: </label>
              <select name="barrio" id="barrio" onfocus='this.size=7;'
              onblur='this.size=0;'
              onchange='this.size=0; this.blur();' class="barrio" disabled>
              </select>
          </div>


      </div>



      <div class="resultados_sponsors">
        <div class="switch_container">
          <div class="switch_sponsors">
            <span class="switch_activos switch active">Activos</span>
            <span class="switch_inactivos switch">Inactivos</span>
          </div>
        </div>

        <div class="resultados_container">
        </div>

      </div>

      <input type="hidden" id="switch_value" name="switch_value" value="">
      <input type="hidden" id="agencia_id" name="agencia_id" value="<?php echo $agencia_id;?>">
      <input type="hidden" id="switch_reference_param" name="switch_reference_param" value="">
      <input type="hidden" id="switch_barrio_param" name="switch_barrio_param" value="">


    </div>



</main>
    </div>

 </body>
</html>
