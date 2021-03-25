$(document).ready(function(){
  jQuery(function($){


    // COLOLAR EL MAPA
    const inmueble_lat = $("#mapa_lat").val();
    const inmueble_lng = $("#mapa_lng").val();
    const inmueble_zoom = $("#mapa_zoom").val();

    L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var mymap = L.map('mapid_config', {scrollWheelZoom: false })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([inmueble_lat, inmueble_lng], inmueble_zoom);

    var marcador_tutecho_popup = L.icon({ // Se define el tipo de marcador que se colocara
      iconUrl: '../../objetos/marcador_tutecho.svg',

      iconSize:     [45, 102], // size of the icon
      shadowSize:   [50, 64], // size of the shadow
      iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
      shadowAnchor: [4, 62],  // the same for the shadow
      popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    L.marker([inmueble_lat, inmueble_lng], {icon: marcador_tutecho_popup}).addTo(mymap);


    // CODIGO BOTON VISITA EXITO / FAIL
    $(".visita_fail_btn").on("click", function(){

      $(this).toggleClass('activo');
      $(".visita_exito_btn").removeClass('activo');
      let agente_selected = agente_id_default;
      let status_selected;

      if ($(".visita_fail_btn").hasClass("activo")) {
        status_selected = 0;
      }else if($(".visita_exito_btn").hasClass("activo")){
        status_selected = 1;
      }else{
        status_selected = "";
      };

      $.ajax({
        type: "POST",
        url: "process-request-visita-inmueble.php",
        data: { action_sent: 'update_status', agencia_tag_sent : agencia_tag_default, visita_key_sent: visita_key_default, agente_id_sent : agente_selected, status_sent : status_selected }
      }).done(function(data){

        if (data == 'error') {
          alert("Error de sistema");
        };
      
      });
      
    })

    $(".visita_exito_btn").on("click", function(){
      $(this).toggleClass('activo');
      $(".visita_fail_btn").removeClass('activo');
      
      let agente_selected = agente_id_default;
      let status_selected;

      if ($(".visita_fail_btn").hasClass("activo")) {
        status_selected = 0;
      }else if($(".visita_exito_btn").hasClass("activo")){
        status_selected = 1;
      }else{
        status_selected = "";
      };

      $.ajax({
        type: "POST",
        url: "process-request-visita-inmueble.php",
        data: { action_sent: 'update_status', agencia_tag_sent : agencia_tag_default, visita_key_sent: visita_key_default, agente_id_sent : agente_selected, status_sent : status_selected }
      }).done(function(data){

        if (data == 'error') {
          alert("Error de sistema");
        };
      
      });

    })


    // CODIGO ABRIR DETALLE ELEMENTO POPUP DIA

    $(".elemento_header").on("click", function(){

        const detalle_contenedor = $(this).parent().find(".elemento_detalle_wrap");
        const boton_detalle = $(this).find(".btn_elemento_detalle");

        if(boton_detalle.hasClass("activo")){
        if($(this).find("i.fas").hasClass("fa-chevron-circle-down")){

            $("i.fas.fa-chevron-circle-up").removeClass("fa-chevron-circle-up").addClass("fa-chevron-circle-down");
    
            $(this).find("i.fas").removeClass("fa-chevron-circle-down").addClass("fa-chevron-circle-up");
    
            $(".elemento_detalle_wrap").each(function(){
            $(this).hide().removeClass('activo');
            });
    
            detalle_contenedor.show("slide", { direction: "up" }, 200).toggleClass('activo');
    
        }else{
    
            $(this).find("i.fas").removeClass("fa-chevron-circle-up").addClass("fa-chevron-circle-down");
            detalle_contenedor.hide("slide", { direction: "up" }, 200).toggleClass('activo');
    
        };
        };

    });



    // CODIGO CHECK DEL TO-DO DETALLE
      $(".check_element_read").on("click", function(){

          let agente_selected = agente_id_default;
          
          const check_box = $(this).find(".check_list_box i");
          const key_to_do = $(this).parent().parent().parent().attr("key");
          const key_check = $(this).attr('key');
          const titulo = $(this).parent().parent().parent().attr("titulo");

          check_box.toggleClass("fas").toggleClass("far");
          
          let action;
          if (check_box.hasClass('far')) {
          action = 0;
          } else if (check_box.hasClass('fas')){
          action = 1;
          };


          $.ajax({
          type: "POST",
          url: "process-request-visita-inmueble.php",
          data: { action_sent: 'check_element', agente_id_sent : agente_selected, action_listened: action, key_check_sent : key_check, key_to_do_sent: key_to_do, titulo_sent: titulo, agencia_tag_sent : agencia_tag_default, visita_key_sent: visita_key_default }
          }).done(function(data){

          if (data == 'error') {
            alert("Error de check-list");
          };
          
          });
          

      });


      // CODIGO BTN ELIMINAR CHECK-LIST

      $(".eliminar_check_list_btn").on("click", function(){

        let agente_selected = agente_id_default;
          
        const contenedor = $(this).parent();
        const key_to_do = contenedor.attr("key");
        const titulo = contenedor.attr("titulo");
        

        $.ajax({
        type: "POST",
        url: "process-request-visita-inmueble.php",
        data: { action_sent: 'delete_check_list', agente_id_sent : agente_selected, key_to_do_sent: key_to_do, titulo_sent: titulo, agencia_tag_sent : agencia_tag_default, visita_key_sent: visita_key_default }
        }).done(function(data){

          if (data == 'error') {
            alert("Error de check-list");
          }else{
            contenedor.remove();
            if (!$(".elemento_popup").lenght) {
              $('.titulo_check_list').remove();
            };
          };
        
        });
        
      });

      // CODIGO BOTON EDITAR COMENTARIO

      $(".editar_comentarios_btn").on("click", function(){

        $(this).css("visibility", 'hidden');
        $(".guardar_comentarios_btn").css("visibility", 'unset');

        $(".comentarios_textarea").prop('readonly', false).toggleClass("edicion");

      });

      $(".guardar_comentarios_btn").on("click", function(){

        const comentario = $(".comentarios_textarea").val();
        const referencia = $('.btn_ficha_inmueble').parent().attr('referencia');
        const ficha_bien_tipo = $('.btn_ficha_inmueble').parent().attr('tabla');

        $(this).css("visibility", 'hidden');
        $(".editar_comentarios_btn").css("visibility", 'unset');
        $(".comentarios_textarea").prop('readonly', true).toggleClass("edicion");;

        $.ajax({
          type: "POST",
          url: "process-request-visita-inmueble.php",
          data: { action_sent: 'save_comentarios', comentario_sent: comentario, referencia_sent : referencia, tabla_sent: ficha_bien_tipo }
        }).done(function(data){

          if (data == 'error') {
            alert("Error al guardar comentario");
          };
        
        });

      });

      // CODIGO PARA TRAER LA FICHA BIEN DESPUES DE HACER CLICK EN UN THUMBNAIL BIEN INMUEBLE ####################################
      $('.btn_ficha_inmueble').on('click', function(){
        $('.ficha_bien_container').addClass('active');

        const contenedor = $(this).parent();

        const ficha_bien_clicked_referencia = contenedor.attr('referencia');
        const ficha_bien_tipo = contenedor.attr('tabla');
        const estado = contenedor.attr('estado');
        const agente_id = contenedor.attr('id_agente');

        $.ajax({
          type: "POST",
          url: "process-request-popup_ficha_bien_detalle.php",
          data: { ficha_bien_requested : ficha_bien_clicked_referencia, ficha_bien_tipo_requested : ficha_bien_tipo, estado : estado, agente_sent : agente_id },
        }).done(function(data){
          $('.popup_ficha_bien').html(data);
          $("body").addClass('ficha_active');
        });

      });


      // Codigo BORRAR CONTACTO EXTRA
      $('.eliminar_contacto_extra').on('click', function(){

        let agente_selected = agente_id_default;
        const contenedor = $(this).parent();
        const key = contenedor.attr('key');
        const telefono = contenedor.attr('telefono');
        
        $.ajax({
          type: "POST",
          url: "process-request-visita-inmueble.php",
          data: { action_sent: 'eliminar_contacto_extra', key_sent: key, telefono_sent : telefono, agencia_tag_sent : agencia_tag_default, visita_key_sent: visita_key_default, agente_id_sent : agente_selected }
        }).done(function(data){

          if (data == 'error') {
            alert("Error, no se pudo eliminar el contacto");
          }else{
            contenedor.remove();
          };
        
        });
        
      });





  });
});
