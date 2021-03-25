$(document).ready(function(){
  jQuery(function($){

  let datos_pais;
  $.ajax({
    type: "POST",
    url: "../../contenido/m5/process-request-coordenadas-paises.php",
    dataType: 'json',
    async: false,
  }).done(function(data){
    datos_pais = data;
  });    
  
  $(".departamento_label").html(`${datos_pais['org_territorial']} :`);



//CODIGO PARA POBLAR SELECT CIUDADES SEGUN EL DEPARTAMENTO ESCOGIDO ###############################################

        $("select.departamento").change(function(){
            var departamentoSelected = $(".departamento option:selected").val();
            if (departamentoSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

                $.ajax({
                    type: "POST",
                    url: "process-request-ciudades.php",
                    data: { departamentoChoice : departamentoSelected }
                }).done(function(data){
                    $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
                    $("#barrio").empty().prop('disabled', true);//se vacia y bloquea el select barrios si tenia algo
                    $(".resultados_sponsors").empty();

                });

            }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
              $("#ciudad").empty().prop('disabled', true).val('');
              $("#barrio").empty().prop('disabled', true).val('');
              $(".resultados_sponsors").empty();

            };
        });


//CODIGO PARA POBLAR SELECT BARRIOS SEGUN LA CIUDAD ESCOGIDA  Y MOSTRAR RESULTADOS SPONSORS###############################################

        $("select.ciudad").change(function(){
            var ciudadSelected = $(".ciudad option:selected").val();

            if (ciudadSelected !== '') { // si hubo seleccion se cargan los barrios de la db
              $.ajax({
                  type: "POST",
                  url: "process-request-barrios.php",
                  data: { ciudadesChoice : ciudadSelected }
              }).done(function(data){
                  if (data !== '<option></option>') {//si hubo resultados entonces se pobla y activa  el select barrios
                    $("#barrio").prop('disabled', false).html(data);
                    $(".resultados_sponsors").empty();

                  }else { // si no hubo resultados se desactiva y vacia el select barrios
                    $("#barrio").empty().prop('disabled', true);
                    $(".resultados_sponsors").empty();
                  };
              });

              $.ajax({
                  type: "POST",
                  url: "process-request-ciudad_poblado_check.php",
                  data: { ciudad_sent : ciudadSelected }
              }).done(function(data){
                if (data == 'poblado') {

                  check_sponsors_activacion(ciudadSelected, 'ciudades');
                };
              });

            }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
              $("#barrio").empty().prop('disabled', true).val('');
              $(".resultados_sponsors").empty();

            };
        });


//CODIGO PARA MOSTRAR RESULTADOS SPONSOR SEGUN EL BARRIO ESCOGIDO ###############################################

        $("select.barrio").change(function(){
          var barrioSelected = $(".barrio option:selected").val();

          if (barrioSelected !== '') { //

            check_sponsors_activacion(barrioSelected, 'barrios');
          }else {//si se escogio barrio vacio a proposito se vacian los resultados sponsor
            $(".resultados_sponsors").empty();
          };
        });

// SE DEFINE LA FUNCION QUE PERMITE TRAER LOS SPONSORS DE LA BASE DE DATOS #####################################


      function check_sponsors_activacion(barrio_param, tipo_param){

            $.ajax({
                type: "POST",
                url: "process-request-check_sponsors_activacion.php",
                data: { barrio_sent : barrio_param, tipo_sent : tipo_param }
            }).done(function(data){
              if (data == 'inactivo') {
                $(".resultados_sponsors").html("<div class=\"mensaje_error\">La función Sponsors NO está activa en este barrio/poblado</div>");
              };
              if (data == 'activo') {
                $(".resultados_sponsors").html("<div class=\"resultados_container\"><div id=\"boton_crear_codigo\">Crear Nuevo Código</div><label for=\"new_code_input\">El nuevo código creado es:</label><input type=\"text\" id=\"new_code_input\" name=\"new_code_input\" value=\"\"></div>");
                $("#barrio_selected_input").val(barrio_param);
              };

            });

          };
// #####################################CODIGO PARA CREAR CODIGO DE IMPRESION DE CUPONES ####################################

        $(".resultados_sponsors").on("click", "#boton_crear_codigo", function(){
          var barrio_input =  $("#barrio_selected_input").val();

          $.ajax({
              type: "POST",
              url: "process-request-crear-codigo-impresion-cupones.php",
              data: { barrio_sent : barrio_input}
          }).done(function(data){

            var new_codigo = data;
            $("#boton_crear_codigo").remove();
            $("#new_code_input").attr("value", new_codigo).change();

          });
        });

  });
});
