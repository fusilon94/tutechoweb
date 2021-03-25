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
                    $('.resultados_sponsors').empty();
                });

            }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
              $("#ciudad").empty().prop('disabled', true).val('');
              $("#barrio").empty().prop('disabled', true).val('');
              $('.resultados_sponsors').empty();
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
                    $('.resultados_sponsors').empty();
                  }else { // si no hubo resultados se desactiva y vacia el select barrios
                    $("#barrio").empty().prop('disabled', true);
                  };
              });

              $.ajax({
                  type: "POST",
                  url: "process-request-ciudad_poblado_check.php",
                  data: { ciudad_sent : ciudadSelected }
              }).done(function(data){
                if (data == 'poblado') {
                  $('.resultados_sponsors').empty();
                  find_sponsors(ciudadSelected);
                };
              });

            }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
              $("#barrio").empty().prop('disabled', true).val('');
              $('.resultados_sponsors').empty();
            };
        });


//CODIGO PARA MOSTRAR RESULTADOS SPONSOR SEGUN EL BARRIO ESCOGIDO ###############################################

        $("select.barrio").change(function(){
          var barrioSelected = $(".barrio option:selected").val();

          if (barrioSelected !== '') { //
            $('.resultados_sponsors').empty();
            find_sponsors(barrioSelected);
          }else {//si se escogio barrio vacio a proposito se vacian los resultados sponsor
            $('.resultados_sponsors').empty();
          };
        });

// SE DEFINE LA FUNCION QUE PERMITE TRAER LOS SPONSORS DE LA BASE DE DATOS #####################################

      function find_sponsors(barrio_param){

            $.ajax({
                type: "POST",
                url: "process-request-sponsors.php",
                data: { barrio_sent : barrio_param },
                dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
            }).done(function(data){
              var sponsors_array = data;

              if (Object.entries(sponsors_array).length > 0) {//si no es un array vacio
                $('.resultados_sponsors').append("<span class='label_resultadors_contenedor'>Sponsors encontrados en: " + barrio_param + "</span>");

                sponsors_array.forEach(function(sponsor){
                  $('.resultados_sponsors').append("<span id='" + sponsor['nombre'] + "' class='elemento_resultado'><i class='far fa-edit' aria-hidden='true'></i><p><span class='nombre'>" + sponsor['label'] + " - </span><span>" + sponsor['direccion'] + "</span></p></span>");
                });

              }else {
                $('.label_resultadors_contenedor').css('display', 'none');
              };






            });

      };

// CODIGO QUE CONTROLA EL ENVIO DE INFO TRAS HACER CLICK EN UN ELEMENTO SPONSOR QUE EDITAR ###################################

  $('.resultados_sponsors').on('click', 'span.elemento_resultado', function(){

    var sponsor_nombre = $(this).attr('id');
    $('#editor_sponsor_nombre').val(sponsor_nombre);
    $('#editor_form').submit();

  });


  });
});
