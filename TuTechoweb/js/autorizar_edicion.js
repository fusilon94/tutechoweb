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
                    $('.resultados_container').empty();
                });

            }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
              $("#ciudad").empty().prop('disabled', true).val('');
              $("#barrio").empty().prop('disabled', true).val('');
              $('.resultados_container').empty();
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
                    $('.resultados_container').empty();
                  }else { // si no hubo resultados se desactiva y vacia el select barrios
                    $("#barrio").empty().prop('disabled', true);
                    $('.resultados_container').empty();//se vacian los resultados
                  };
              });

              $.ajax({
                  type: "POST",
                  url: "process-request-ciudad_poblado_check.php",
                  data: { ciudad_sent : ciudadSelected }
              }).done(function(data){
                if (data == 'poblado') {
                  $('.resultados_container').empty();
                  find_bien_inmueble(ciudadSelected);
                };
              });

            }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
              $("#barrio").empty().prop('disabled', true).val('');
              $('.resultados_container').empty();
            };
        });


//CODIGO PARA MOSTRAR RESULTADOS SPONSOR SEGUN EL BARRIO ESCOGIDO ###############################################

        $("select.barrio").change(function(){
          var barrioSelected = $(".barrio option:selected").val();

          if (barrioSelected !== '') { //
            $('.resultados_container').empty();
            find_bien_inmueble(barrioSelected);
          }else {//si se escogio barrio vacio a proposito se vacian los resultados sponsor
            $('.resultados_container').empty();
          };
        });

// CODIGO PARA BUSQUEDA SEGUN REFERENCIA ############################################################

   $(".input_referencia_btn").on("click", function(){
     var referencia_picked = $("#input_referencia").val();
     $("#departamento").val('');
     $("#ciudad").empty().prop('disabled', true).val('');
     $("#barrio").empty().prop('disabled', true).val('');
     find_bien_inmueble_by_reference(referencia_picked);
   });

// SE DEFINE LA FUNCION QUE PERMITE TRAER LOS SPONSORS DE LA BASE DE DATOS #####################################
      var bienes_array = '';
      var location_tag = '';
      var agencia_id = $("#agencia_id").val();

      function find_bien_inmueble(barrio_param){
            $.ajax({
                type: "POST",
                url: "process-request-autorizar_edicion_all.php",
                data: { barrio_sent : barrio_param },
                dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
            }).done(function(data){
              bienes_array = data;
              location_tag = barrio_param;
              $('.resultados_container').empty();//se vacian los resultados

              if (Object.entries(bienes_array).length > 0) {//si no es un array vacio
                $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + barrio_param + "</span>");

                bienes_array.forEach(function(bien){
                  if (bien['visibilidad'] == 'visible') {
                    $('.resultados_container').append("<div class='boton_borrador_mini_contenedor'><div id='" + bien['referencia'] + "' name='" + bien['tipo_bien'] + "' class='boton_borrador_formulario'><i class='fas fa-search' aria-hidden='true'></i><p><span class='nombre'>" + bien['referencia'] + "</span></p></div><div class='boton_borrador_formulario_borrar' onclick='confirmacion(this)'><i class='fas fa-clipboard-check' aria-hidden='true'></i></div><div class='boton_formulario_borrar_confirmar'><p>AUTORIZAR?</p></div></div>");
                  };
                });

              };

              if ($(".boton_borrador_mini_contenedor").length == 0) {
                $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
              };

            });

          };

          function find_bien_inmueble_by_reference(reference_param){
            $.ajax({
                type: "POST",
                url: "process-request-autorizar_edicion_all.php",
                data: { reference_sent : reference_param },
                dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
            }).done(function(data){
              bienes_array = data;
              $('.resultados_container').empty();//se vacian los resultados

              if (bienes_array.length > 0) {//si no es vacio
                $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + bienes_array[0]['location_tag'] + "</span>");

                  if (bienes_array[0]['visibilidad'] == 'visible') {
                    $('.resultados_container').append("<div class='boton_borrador_mini_contenedor'><div id='" + bienes_array[0]['referencia'] + "' name='" + bienes_array[0]['tipo_bien'] + "' class='boton_borrador_formulario'><i class='fas fa-search' aria-hidden='true'></i><p><span class='nombre'>" + bienes_array[0]['referencia'] + "</span></p></div><div class='boton_borrador_formulario_borrar' onclick='confirmacion(this)'><i class='fas fa-clipboard-check' aria-hidden='true'></i></div><div class='boton_formulario_borrar_confirmar'><p>AUTORIZAR?</p></div></div>");
                  };

              };

              if ($(".boton_borrador_mini_contenedor").length == 0) {
                $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
              };

            });
          };


  });
});
