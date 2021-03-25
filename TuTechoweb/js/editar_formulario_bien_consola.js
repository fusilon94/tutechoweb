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
                    $('.switch_sponsors').css('visibility', 'hidden');
                    $('#switch_value').val('');
                });

            }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
              $("#ciudad").empty().prop('disabled', true).val('');
              $("#barrio").empty().prop('disabled', true).val('');
              $('.resultados_container').empty();
              $('.switch_sponsors').css('visibility', 'hidden');
              $('#switch_value').val('');
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
                    $('.switch_sponsors').css('visibility', 'hidden');
                    $('#switch_value').val('');
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
                  $('.switch_sponsors').css('visibility', 'hidden');
                  $('#switch_value').val('');
                  find_bien_inmueble(ciudadSelected);
                };
              });

            }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
              $("#barrio").empty().prop('disabled', true).val('');
              $('.resultados_container').empty();
              $('.switch_sponsors').css('visibility', 'hidden');
              $('#switch_value').val('');
            };
        });


//CODIGO PARA MOSTRAR RESULTADOS SPONSOR SEGUN EL BARRIO ESCOGIDO ###############################################

        $("select.barrio").change(function(){
          var barrioSelected = $(".barrio option:selected").val();

          if (barrioSelected !== '') { //
            $('.resultados_container').empty();
            $('.switch_sponsors').css('visibility', 'hidden');
            $('#switch_value').val('');
            find_bien_inmueble(barrioSelected);
          }else {//si se escogio barrio vacio a proposito se vacian los resultados sponsor
            $('.resultados_container').empty();
            $('.switch_sponsors').css('visibility', 'hidden');
            $('#switch_value').val('');
          };
        });

// CODIGO PARA BUSQUEDA SEGUN REFERENCIA ############################################################

   $(".input_referencia_btn").on("click", function(){
     var referencia_picked = $("#input_referencia").val();
     $('.switch_sponsors').css('visibility', 'hidden');
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
                url: "process-request-bienes-formularios-all.php",
                data: { barrio_sent : barrio_param, agencia_sent : agencia_id },
                dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
            }).done(function(data){
              bienes_array = data;
              location_tag = barrio_param;
              $('.resultados_container').empty();//se vacian los resultados

              if (Object.entries(bienes_array).length > 0) {//si no es un array vacio
                $('.switch_sponsors').css('visibility', 'visible');
                if ($('.switch_activos').hasClass('active') !== true) {
                  $(".switch_activos").toggleClass("active");
                };
                if ($('.switch_inactivos').hasClass('active') == true) {
                  $(".switch_inactivos").toggleClass("active");
                };
                $('#switch_value').val('activos');
                $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + barrio_param + "</span>");

                bienes_array.forEach(function(bien){
                  if (bien['visibilidad'] == 'visible') {
                    $('.resultados_container').append("<div class='boton_borrador_mini_contenedor'><div id='" + bien['referencia'] + "' name='" + bien['tipo_bien'] + "' class='boton_borrador_formulario'><i class='fas fa-search' aria-hidden='true'></i><p><span class='nombre'>" + bien['referencia'] + "</span></p></div><div class='boton_borrador_formulario_borrar' onclick='confirmacion(this)'><i class='fas fa-clipboard-list' aria-hidden='true'></i></div><div class='boton_borrador_formulario_borrar_confirmar' onclick='confirmacion_borrar(this)'><p>EDITAR?</p></div></div>");
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
                url: "process-request-bienes-formularios-all.php",
                data: { reference_sent : reference_param, agencia_sent : agencia_id },
                dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
            }).done(function(data){
              bienes_array = data;
              $('.resultados_container').empty();//se vacian los resultados

              if (bienes_array.length > 0) {//si no es vacio
                $('.switch_sponsors').css('visibility', 'visible');
                if ($('.switch_activos').hasClass('active') !== true) {
                  $(".switch_activos").toggleClass("active");
                };
                if ($('.switch_inactivos').hasClass('active') == true) {
                  $(".switch_inactivos").toggleClass("active");
                };
                $('#switch_value').val('activos');
                $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + bienes_array[0]['location_tag'] + "</span>");

                  if (bienes_array[0]['visibilidad'] == 'visible') {
                    $('.resultados_container').append("<div class='boton_borrador_mini_contenedor'><div id='" + bienes_array[0]['referencia'] + "' name='" + bienes_array[0]['tipo_bien'] + "' class='boton_borrador_formulario'><i class='fas fa-search' aria-hidden='true'></i><p><span class='nombre'>" + bienes_array[0]['referencia'] + "</span></p></div><div class='boton_borrador_formulario_borrar' onclick='confirmacion(this)'><i class='fas fa-clipboard-list' aria-hidden='true'></i></div><div class='boton_borrador_formulario_borrar_confirmar' onclick='confirmacion_borrar(this)'><p>EDITAR?</p></div></div>");
                  };

              };

              if ($(".boton_borrador_mini_contenedor").length == 0) {
                $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
              };

            });
          };

// ############################## SWITCH #######################################

      $('.switch_sponsors').on('click', '.switch', function(){

        $(".switch_activos").toggleClass("active");
        $(".switch_inactivos").toggleClass("active");

        var switch_current_val = $('#switch_value').val();

        if (switch_current_val == 'activos' || switch_current_val == '') {//se recupera el valor actual del switch en un input tipo hidden
          $('#switch_value').val('inactivos');
        }else {
          $('#switch_value').val('activos');
        };

        $('.resultados_container').empty();

        if ($('#switch_value').val() == 'activos') {//si estamos en activos, mostrar los sponsors activos

            if (Object.entries(bienes_array).length > 0) {//si no es un array vacio
              $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + location_tag + "</span>");

              bienes_array.forEach(function(bien){
                if (bien['visibilidad'] == 'visible') {
                  $('.resultados_container').append("<div class='boton_borrador_mini_contenedor'><div id='" + bien['referencia'] + "' name='" + bien['tipo_bien'] + "' class='boton_borrador_formulario'><i class='fas fa-search' aria-hidden='true'></i><p><span class='nombre'>" + bien['referencia'] + "</span></p></div><div class='boton_borrador_formulario_borrar' onclick='confirmacion(this)'><i class='fas fa-clipboard-list' aria-hidden='true'></i></div><div class='boton_borrador_formulario_borrar_confirmar' onclick='confirmacion_borrar(this)'><p>EDITAR?</p></div></div>");
                };
              });

            };

            if ($(".boton_borrador_mini_contenedor").length == 0) {
              $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
            };


        }else {//si estamos en inactivos, mostrar los sponsors inactivos

            if (Object.entries(bienes_array).length > 0) {//si no es un array vacio
              $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes inactivos en: " + location_tag + "</span>");

              bienes_array.forEach(function(bien){
                if (bien['visibilidad'] == 'no_visible') {
                  $('.resultados_container').append("<div class='boton_borrador_mini_contenedor'><div id='" + bien['referencia'] + "' name='" + bien['tipo_bien'] + "' class='boton_borrador_formulario'><i class='fas fa-search' aria-hidden='true'></i><p><span class='nombre'>" + bien['referencia'] + "</span></p></div><div class='boton_borrador_formulario_borrar' onclick='confirmacion(this)'><i class='fas fa-clipboard-list' aria-hidden='true'></i></div><div class='boton_borrador_formulario_borrar_confirmar' onclick='confirmacion_borrar(this)'><p>EDITAR?</p></div></div>");
                };
              });

            };
            if ($(".boton_borrador_mini_contenedor").length == 0) {
              $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
            };

        };

      });

// ################### CODIGO PARA MOSTRAR INFO DEL BIEN INMUEBLE #####################################

    $(".resultados_container").on("click", '.boton_borrador_formulario', function(){
      var formulario_referencia = $(this).attr('id');
      var formulario_tabla = $(this).attr('name');

      $.ajax({
          type: "POST",
          url: "process-request-form-info-editar-formulario.php",
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
