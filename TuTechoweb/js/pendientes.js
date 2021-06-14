$(document).ready(function(){
  jQuery(function($){

// #################################### SWITCH #################################################

$('.switch').on('click', '.switch_btn', function(){
  if ($(this).hasClass('active') == false) {

      $(".switch_nuevos").toggleClass("active");
      $(".switch_borrados").toggleClass("active");

      var agente_id = $('#id_agente').val();

      if ($(this).hasClass('switch_nuevos')) {

        $.ajax({
            type: "POST",
            url: "process-request-pendientes.php",
            data: { tipo_pendiente_sent : 'nuevos', agente_id_sent : agente_id }
        }).done(function(data){
            $('.pendientes_contenedor').html(data);
        });


      };

      if ($(this).hasClass('switch_borrados')) {

        $.ajax({
            type: "POST",
            url: "process-request-pendientes.php",
            data: { tipo_pendiente_sent : 'borrados', agente_id_sent : agente_id }
        }).done(function(data){
            $('.pendientes_contenedor').html(data);
        });

      };

  };
});

// ############################ POPUP ###########################################################

$('.popup_cerrar').on('click', function(){
  $('.popup_pendiente_container').css('visibility', 'hidden');
  $('body').removeClass('popup_active');
});

$('.pendientes_contenedor').on('click', '.pendiente_wrapper', function(){
  var codigo = $(this).parent().attr('id');
  var tipo = $(this).find('.etiqueta').text();
  var color_tipo = $(this).find('.etiqueta').css('background-color');
  var fecha_creacion = $(this).find('.pais_tag').text() + $(this).find('.fecha_creacion').text();
  var contenido = $(this).find('.pendiente_contenido').html();
  var pendiente_wrapper = $(this);
  var pais_tag = $(this).find('.pais_tag').text();

  $('.popup_contenido').html(`
    <span class="popup_titulo" style="background-color: ${color_tipo}">${tipo}</span>
    <span class="popup_info_header">
      <span class="popup_codigo">Código: ${codigo}</span>
      <span class="popup_fecha">${fecha_creacion}</span>
    </span>
    <span class="popup_texto">${contenido}</span>
    `);

  if ($(this).find('.etiqueta').hasClass("contacto_compartido")) {
    $('.popup_contenido').append(`
    <span class="agregar_contacto_btn btn_action" data="${codigo}">
    <i class="fas fa-plus-circle"></i>
    <p>Agregar Contacto</p>
    </span>
    `);
  };

  if ($(this).find('.etiqueta').hasClass("check_list_compartido")) {
    $('.popup_contenido').append(`
    <span class="agregar_check_list_btn btn_action" data="${codigo}">
    <i class="fas fa-plus-circle"></i>
    <p>Guardar Check-List</p>
    </span>
    `);
  };

  if ($(this).find('.etiqueta').hasClass("transferencia_llave")) {
    $('.popup_contenido').append(`
    <span class="btn_group">
      <span class="negar_trans_llave_btn btn_action respuesta_trans_llave" data="${codigo}" respuesta="0">
      <p>Refutar</p>
      </span>

      <span class="confirmar_trans_llave_btn btn_action respuesta_trans_llave" data="${codigo}"  respuesta="1">
      <p>Aceptar</p>
      </span>
    </span>
    `);
  };

  $('.popup_pendiente_container').css('visibility', 'unset');
  $('body').addClass('popup_active');

  if (pendiente_wrapper.hasClass('no_leido')) {

    $.ajax({
        type: "POST",
        url: "process-request-pendientes.php",
        data: { codigo_sent : codigo, pais_sent : pais_tag }
    }).done(function(data){
        pendiente_wrapper.removeClass('no_leido');
    });

  };

});

$('.popup_contenido').on('click', '.respuesta_trans_llave', function() {
  
  const codigo = $(this).attr('data');
  const respuesta = $(this).attr('respuesta');

  $.ajax({
    type: "POST",
    url: "process-request-pendientes.php",
    data: { codigo_pendiente : codigo, transferencia_llave_respuesta : respuesta }
  }).done(function(data){
    $('.popup_info_contenido').html(data);
    $('.pop_up_info_container').css('visibility', 'unset');
    $('.popup_pendiente_container').css('visibility', 'hidden');

    var agente_id = $('#id_agente').val();
    $.ajax({
        type: "POST",
        url: "process-request-pendientes.php",
        data: { tipo_pendiente_sent : 'nuevos', agente_id_sent : agente_id }
    }).done(function(data){
        $('.pendientes_contenedor').html(data);
    });


  });
})

$('.popup_contenido').on('click', '.agregar_contacto_btn', function() {
  
  const codigo = $(this).attr('data');

  $.ajax({
    type: "POST",
    url: "process-request-pendientes.php",
    data: { codigo_contacto_sent : codigo }
  }).done(function(data){
    $('.popup_info_contenido').html(data);
    $('.pop_up_info_container').css('visibility', 'unset');
    $('.popup_pendiente_container').css('visibility', 'hidden');
  });
})

$('.popup_contenido').on('click', '.agregar_check_list_btn', function() {
  
  const codigo = $(this).attr('data');

  $.ajax({
    type: "POST",
    url: "process-request-pendientes.php",
    data: { codigo_check_list_sent : codigo }
  }).done(function(data){
    $('.popup_info_contenido').html(data);
    $('.pop_up_info_container').css('visibility', 'unset');
    $('.popup_pendiente_container').css('visibility', 'hidden');
  });
})

// ############################## BOTONES DE PENDIENTES ##########################################

  $('.popup_info_cerrar').on('click', function(){
    $('.pop_up_info_container').css('visibility', 'hidden');
    $('body').removeClass('popup_active');
  });

  $('.pendientes_contenedor').on('click', '.pendiente_btn i', function(){
    if ($(this).hasClass('fa-times-circle')) {
        var codigo_borrar = $(this).parent().parent().attr('id');
        var pendiente_wrapper = $(this).parent().parent();
        var pais_tag = pendiente_wrapper.find('.pais_tag').text();

        $.ajax({
            type: "POST",
            url: "process-request-pendientes.php",
            data: { codigo_borrar_sent : codigo_borrar, pais_sent : pais_tag }
        }).done(function(data){
            pendiente_wrapper.css('display', 'none');
        });
    };


    if ($(this).hasClass('fa-redo-alt')) {
        var codigo_rehacer = $(this).parent().parent().attr('id');
        var pendiente_wrapper = $(this).parent().parent();
        var pais_tag = pendiente_wrapper.find('.pais_tag').text();

        $.ajax({
            type: "POST",
            url: "process-request-pendientes.php",
            data: { codigo_rehacer_sent : codigo_rehacer, pais_sent : pais_tag }
        }).done(function(data){
            pendiente_wrapper.css('display', 'none');
        });
    };

    if ($(this).hasClass('fa-question-circle')) {
        var etiqueta = $(this).parent().parent().find('.etiqueta');

        if (etiqueta.hasClass('reclamo')) {
          $('.popup_info_contenido').html('ESTE PENDIENTE DESAPARECERÁ AUTOMÁTICAMENTE UNA VEZ SE HAYAN CORREGIDO LOS ERRORES EXPUESTOS');
        };

        $('.pop_up_info_container').css('visibility', 'unset');
    };


  });



  });
});
