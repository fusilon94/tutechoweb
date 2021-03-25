$(document).ready(function(){
  jQuery(function($){

// CODIGO VALIDACION FORMULARIO - al hacer click en VALIDAR ###################################

    $('#boton_validar_form').on('click', function () {
      var boton_validation = document.getElementById('#boton_validar_form');
      var boton_submit = document.getElementById('#boton_submit_form');

      var reqlength = $('.campo_req').length;
      console.log(reqlength);
      var value = $('.campo_req').filter(function () {
          return this.value != '';
      });
      if (value.length>=0 && (value.length !== reqlength)) {
          $( "#dialog" ).dialog( "open" );

          if ($('#tipo_via').val() == '' || $('#aceras').val() == '') {
            $('#panel_3 i.fa-circle').css('display', 'inline-block');
          };
          if ($('#mapa_coordenada_lat').val() == '' || $('#mapa_coordenada_lng').val() == '') {
            $('#panel_MAPA i.fa-circle').css('display', 'inline-block');
          };
          if ($('#espacios').val() == '' || $('#parqueos').val() == '' || $('#piso').val() == '') {
            $('#panel_4 i.fa-circle').css('display', 'inline-block');
          };
          if ($('#calefaccion').val() == '' || $('#ventanas').val() == '' || $('#conexion_electrica').val() == '' || $('#cobertura').val() == '' || $('#internet').val() == '' || $('#tv_cable').val() == '' || $('#interior_estado').val() == '') { //Aun con el cambio de color en Atom, el codigo funciona muy bien
            $('#panel_5 i.fa-circle').css('display', 'inline-block');
          };
          if ($('#exposicion').val() == '' || $('#jardin_estado').val() == '') {
            $('#panel_6 i.fa-circle').css('display', 'inline-block');
          };
          if ($('#descripcion_bien').val() == '') {
            $('#panel_8 i.fa-circle').css('display', 'inline-block');
          };
      } else {
          $('#boton_submit_form').css('display', 'flex');
      }
    });

// ++++ OPENER VENTA INFO
        $( "#opener_propietario_id" ).click( function(event) { //usar el id del opener
        event.preventDefault();  // evita el error de submit/page-refresh cuando haces click a un boton de un form-html
        $( "#info_ventana_propietario_id" ).dialog( "open" );  //usar el id de la ventana info
        });

        $( "#opener_anticretico" ).click( function(event) {
        event.preventDefault();
        $( "#info_ventana_anticretico" ).dialog( "open" );
        });

        $( "#opener_handicap" ).click( function(event) {
        event.preventDefault();
        $( "#info_ventana_handicap" ).dialog( "open" );
        });

// CHECKBOX ####################################################

        // LOS CHECKBOX FUERON DEFINIDOS USANDO UNICAMENTE CSS, LOS DE JQUERY UI PROVOCAN PROBLEMAS DE MAQUETACION

// SPINNER #####################################################

        $("#espacios").spinner({
          min: -1,
        });
        $("#parqueos").spinner({
          min: -1,
        });
        $("#piso").spinner({
          min: 1,
        });
        $("#niveles").spinner({
          min: -1,
        });
        $("#wc").spinner({
          min: -1,
        });
        $("#parada_bus").spinner({
          min: -1,
        });
        $("#teleferico").spinner({
          min: -1,
        });
        $("#supermercado").spinner({
          min: -1,
        });
        $("#farmacia").spinner({
          min: -1,
        });
        $("#guarderia").spinner({
          min: -1,
        });
        $("#escuela").spinner({
          min: -1,
        });
        $("#policia").spinner({
          min: -1,
        });
        $("#hospital").spinner({
          min: -1,
        });
        $("#area_verde").spinner({
          min: -1,
        });

        $('.ui-spinner-button').click(function() { // Permite accionar el evento change al usar los botones del spinner
          $(this).siblings('input').change();
          var spinner_input_id = $(this).siblings('input').attr('id');
          var spinner_value = $('#'+spinner_input_id).val();
          if (spinner_value == '-1') {
            $('#'+spinner_input_id).val('');
            $(this).siblings('input').change();
          };
        });



    });
});
