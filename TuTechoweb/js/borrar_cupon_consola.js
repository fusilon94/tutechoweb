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
                    $('.resultados_container').empty();
                  };
              });

              $.ajax({
                  type: "POST",
                  url: "process-request-ciudad_poblado_check.php",
                  data: { ciudad_sent : ciudadSelected }
              }).done(function(data){
                if (data == 'poblado') {
                  $('.resultados_container').empty();
                  find_sponsors(ciudadSelected);
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
            find_sponsors(barrioSelected);
          }else {//si se escogio barrio vacio a proposito se vacian los resultados sponsor
            $('.resultados_container').empty();
          };
        });

// SE DEFINE LA FUNCION QUE PERMITE TRAER LOS SPONSORS DE LA BASE DE DATOS #####################################


      function find_sponsors(barrio_param){

            $.ajax({
                type: "POST",
                url: "process-request-sponsors-con-cupon.php",
                data: { barrio_sent : barrio_param },
                dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
            }).done(function(data){
              var sponsors_array = data;
              var barrio_sponsor = barrio_param;

              if (Object.entries(sponsors_array).length > 0) {//si no es un array vacio

                $('.resultados_container').empty();//se vacian los resultados
                $('.resultados_container').append("<span class='label_resultadors_contenedor'>Sponsors con Cupón en: " + barrio_param + "</span>");

                sponsors_array.forEach(function(sponsor){

                    $('.resultados_container').append("<div class='boton_borrador_mini_contenedor'><div id='" + sponsor['nombre'] + "' class='boton_borrador_formulario'><i class='fas fa-search' aria-hidden='true'></i><p><span class='nombre'>" + sponsor['label']  + "- </span><span>"  + sponsor['direccion'] + "</span></p></div><div class='boton_borrador_formulario_borrar' onclick='confirmacion(this)'><i class='fas fa-trash-alt' aria-hidden='true'></i></div><div class='boton_borrador_formulario_borrar_confirmar' onclick='confirmacion_agregar(this)'><p>BORRAR?</p></div></div>");

                });

              };

              if ($(".boton_borrador_mini_contenedor").length == 0) {
                $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
              };

            });

          };


// ################### CODIGO PARA MOSTRAR VISTA PRELIMINAR #####################################

  $('.resultados_container').on('click', '.boton_borrador_formulario', function(){
    $('.overlay_sponsor_previsualizacion').toggleClass("active");

    var nombre_sponsor = $(this).attr('id');
    $.ajax({
        type: "POST",
        url: "process-request-cupon_visualizar.php",
        data: { cupon_sent : nombre_sponsor },
    }).done(function(data){
      var popup_sponsor_received = data;

      $('.overlay_sponsor_previsualizacion').html(popup_sponsor_received);

    });

  });


  //CODIGO QUE EVITA QUE SE ABRAN MAS POPUP SPONSORS AL HACER CLICK EN EL MISMO YA QUE ES UN ELEMENTO CHILD DEL SPONSOR

    $('.overlay_sponsor_previsualizacion').on('click',function(){

    $(".popup_sponsor").remove();
    $('.overlay_sponsor_previsualizacion').toggleClass("active");

  });

  $('.overlay_sponsor_previsualizacion').on('click', 'span.popup_sponsor_cerrar' ,function(){

    $(".popup_sponsor").remove();
    $('.overlay_sponsor_previsualizacion').toggleClass("active");

  });

  $('.overlay_sponsor_previsualizacion').on('click', '.previsualizacion_container' ,function(e){
          if($(e.target).not('span.popup_sponsor_cerrar')){
          e.stopPropagation();//evita que active el click event de su contenedor, la elemento sponsor de la ficha bien
        }

  });


  //################################# CODIGO PARA CERRAR EL POPUP SUCCESS ########################################################

    $('.popup_success_cerrar i.fa-times').on("click", function(){
        $('.popup_success').css('visibility',  'hidden');
      });


  });
});
