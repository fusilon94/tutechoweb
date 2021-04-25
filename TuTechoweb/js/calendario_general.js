$(document).ready(function(){
  jQuery(function($){

    // CODIGO PARA EL CAMBIO DE PAIS PARA ADMIN

    $(".pais_selector_calendario").on("click", function(){

      $(".pais_list_overlay").css("visibility", 'unset');
            
    });


    $(".cerrar_pais_list").on("click", function(){
      $(".pais_list_overlay").css("visibility", 'hidden');
    });

    $(".pais_opcion").on("click", function(){
      let pais_selected = $(this).attr("id");

      $.ajax({
        type: "POST",
        url: "process-request-consola-admin-pais.php",
        data: { pais_sent : pais_selected },
      }).done(function(data){
        if (data == "Exito") {
          location.reload();
        }else{
          alert("Hubo un Error");
        };
          
      });


    });

    // FIST CHARGE WEEK DAYS LABELS

    $.ajax({
        type: "POST",
        url: "process-request-calendario.php",
        data: { titles_requested: 'titles_requested'}
    }).done(function(data){
      $(".titulos_semana").html(data);
      
    });

    // FISRT CHARGE CALENDAR

    const past_event_init = $("#past_event_check").val();

    $.ajax({
        type: "POST",
        url: "process-request-calendario.php",
        data: { fecha_tag_sent: 'hoy', agencia_tag_sent : agencia_tag_default, agente_id_sent : agente_id_default, past_events_sent : past_event_init }
    }).done(function(data){

      $(".calendario_contendor").html(data);

      $(".calendario_contendor").scrollTop(0);           

      let t = $(".calendario_contendor").offset().top;
      
      $(".calendario_contendor").scrollTop($(".today").offset().top - t - 5);// Sepone el scroll en el dia de hoy
      let titulo_first = $(".today").attr("mes") + " " + $(".today").attr("year");

      $(".cabecera_titulo").html(titulo_first).attr("data", $(".today").attr("id").slice(3));
    });

    // HOVER ON DAY CHANGES MONTH TITTLE AND HIGHLIGHT CURRENT MONTH

    $(".calendario_contendor").on("mouseover", ".day_wrap", function(){

      let titulo_new = $(this).attr("mes") + " " + $(this).attr("year");
      let data_string = $(this).attr("id").slice(3);

      $(".cabecera_titulo").html(titulo_new).attr("data", data_string);

      let days_out_of_month = ".day_wrap:not(." + $(this).attr("mes") + ")";
      let days_in_month = ".day_wrap." + $(this).attr("mes");

   
      $(days_out_of_month).addClass("fade");
    
      $(days_in_month).removeClass("fade");
   

      
    });


    // DEFINICION DE LOS EVENTLISTENER SOBRE EL SCROLL

    // SE DEFINE LA FUNCION ENCARGADA DE LIMITAR EL INTERVALO DE TIEMPO QUE PASA ANTES DE VERIFICAR SI EL SCROLL DE LA PAGINA LLAMA O NO A SUMAR MAS ELEMENTOS AL CALENDARIO
    function scroll_check_timer(fn, wait){//the call is at the en of the js
      var scroll_time = Date.now();
      return function(){
        if ((scroll_time + wait - Date.now()) < 0) {
          fn();//llama la funcion en param, que es scroll_check_position
          scroll_time = Date.now();
        };
      };
    };


    //FUNCION DE CHEQUEO DE LA POSICION DEL SCROLL
    function scroll_check_position(){

        if ((($(".calendario_contendor").offset().top)+80) > $(".calendario_contendor").scrollTop()) {//si se acerca al TOP

              let first_day = $(".day_wrap").first().attr("id");
              const past_event = $("#past_event_check").val();
              let agencia_selected = agencia_tag_default;
              let agente_selected = agente_id_default;

              if ($("#agencia_select").length) {
                if ($("#agencia_select option:selected").val() !== '') {
                  agencia_selected = $("#agencia_select option:selected").val();
                };
              };
        
              if ($("#agente_select").length) {
                if ($("#agente_select option:selected").val() !== '') {
                  agente_selected = $("#agente_select option:selected").val();
                };
              };
        
              
              $.ajax({
                type: "POST",
                url: "process-request-calendario.php",
                data: { fecha_tag_sent: 'before', date_tag_sent : first_day, agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, past_events_sent : past_event }
            }).done(function(data){
        
              $(".calendario_contendor").prepend(data);
              $(".week_wrap").last().remove();
              $(".week_wrap").last().remove();
              $(".week_wrap").last().remove();
        
            });

        };

        if ($(".calendario_contendor")[0].scrollHeight - $(".calendario_contendor").scrollTop() <= ($(".calendario_contendor").outerHeight() + 180)) {
          
          let last_day = $(".day_wrap").last().attr("id");
          const past_event = $("#past_event_check").val();
          let agencia_selected = agencia_tag_default;
          let agente_selected= agente_id_default;

          if ($("#agencia_select").length) {
            if ($("#agencia_select option:selected").val() !== '') {
              agencia_selected = $("#agencia_select option:selected").val();
            };
          };
    
          if ($("#agente_select").length) {
            if ($("#agente_select option:selected").val() !== '') {
              agente_selected = $("#agente_select option:selected").val();
            };
          };

          $.ajax({
              type: "POST",
              url: "process-request-calendario.php",
              data: { fecha_tag_sent: 'after', date_tag_sent : last_day, agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, past_events_sent : past_event }
          }).done(function(data){
      
            $(".calendario_contendor").append(data);
            $(".week_wrap").first().remove();
            $(".week_wrap").first().remove();
            $(".week_wrap").first().remove();
      
          });


        };//si se acerca al BOTTOM

     
    };

    $(".calendario_contendor").on('scroll', scroll_check_timer(scroll_check_position, 100));


    // CODIGO PARA EL BOTON HOY
    $(".hoy_bnt").on("click", function(){

      const past_event = $("#past_event_check").val();
      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;

      if ($("#agencia_select").length) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      $.ajax({
          type: "POST",
          url: "process-request-calendario.php",
          data: { fecha_tag_sent: 'hoy', agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, past_events_sent : past_event  }
      }).done(function(data){

        $(".calendario_contendor").html(data);

        $(".calendario_contendor").scrollTop(0);           

        let t = $(".calendario_contendor").offset().top;
        
        $(".calendario_contendor").scrollTop($(".today").offset().top - t - 5);// Sepone el scroll en el dia de hoy
        let titulo_first = $(".today").attr("mes") + " " + $(".today").attr("year");

        $(".cabecera_titulo").html(titulo_first).attr("data", $(".today").attr("id"));
      });

    });


    // CODIGO PARA EL BOTON BACK MES

    $(".mes_back_btn").on("click", function(){

      let fecha_tag = $(".cabecera_titulo").attr("data");

      let fecha_searched = "01-" + fecha_tag;
      
      const past_event = $("#past_event_check").val();
      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;

      if ($("#agencia_select").length) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      $.ajax({
          type: "POST",
          url: "process-request-calendario.php",
          data: { fecha_tag_sent: 'past_month', date_tag_sent : fecha_searched, agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, past_events_sent : past_event }
      }).done(function(data){

        $(".calendario_contendor").html(data);

        $(".calendario_contendor").scrollTop(0);           

        let t = $(".calendario_contendor").offset().top;

        let flag_day = "#" + $('.flag_day').val();
        
        $(".calendario_contendor").scrollTop($(flag_day).offset().top - t - 5);// Sepone el scroll en el dia de hoy

        let titulo_first = $(flag_day).attr("mes") + " " + $(flag_day).attr("year");

        $(".cabecera_titulo").html(titulo_first).attr("data", $('.flag_day').val().slice(3));
      });

    });

    // CODIGO PARA EL BOTON FOWARD MES

    $(".mes_foward_btn").on("click", function(){

      let fecha_tag = $(".cabecera_titulo").attr("data");

      let fecha_searched = "01-" + fecha_tag;
      
      const past_event = $("#past_event_check").val();
      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;

      if ($("#agencia_select").length) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      $.ajax({
          type: "POST",
          url: "process-request-calendario.php",
          data: { fecha_tag_sent: 'next_month', date_tag_sent : fecha_searched, agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, past_events_sent : past_event }
      }).done(function(data){

        $(".calendario_contendor").html(data);

        $(".calendario_contendor").scrollTop(0);           

        let t = $(".calendario_contendor").offset().top;

        let flag_day = "#" + $('.flag_day').val();
        
        $(".calendario_contendor").scrollTop($(flag_day).offset().top - t - 5);// Sepone el scroll en el dia de hoy

        let titulo_first = $(flag_day).attr("mes") + " " + $(flag_day).attr("year");

        $(".cabecera_titulo").html(titulo_first).attr("data", $('.flag_day').val().slice(3));
      });

    });


    // CODIGO CERRAR POPUP AGREGAR EVENTO/TAREA

    $(".popup_overlay").on("click", ".cerrar_popup", function(){
      $(".popup_overlay").css("opacity", 0).css("visibility", "hidden");
      window.history.back();
    });

    // CODIGO ABRIR FILTROS

    $(".filtros_btn").on("click", function(){
      $(".filtros_overlay").css("opacity", 1).css("visibility", "unset");
      history.pushState('popup_opened', null, "#filtros");
    });

    // CODIGP CERRAR FILTROS

    $(".filtros_cerrar_btn").on("click", function(){
      $(".filtros_overlay").css("opacity", 0).css("visibility", "hidden");
      window.history.back();
    });

    // CODIGO BOTON VER EVENTOS PASADOS

    $(".past_event_btn").on("click", function(){
      $(this).toggleClass("activo");

      if ($(this).hasClass('activo')) {
        $("#past_event_check").val(1);
      }else{
        $("#past_event_check").val(0);
      };

      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;
      const past_event = $("#past_event_check").val();

      if ($("#agencia_select").length) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };


      $.ajax({
          type: "POST",
          url: "process-request-calendario.php",
          data: { fecha_tag_sent: 'hoy', agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, past_events_sent : past_event }
      }).done(function(data){

        $(".calendario_contendor").html(data);

        $(".calendario_contendor").scrollTop(0);           

        let t = $(".calendario_contendor").offset().top;
        
        $(".calendario_contendor").scrollTop($(".today").offset().top - t - 5);// Sepone el scroll en el dia de hoy
        let titulo_first = $(".today").attr("mes") + " " + $(".today").attr("year");

        $(".cabecera_titulo").html(titulo_first).attr("data", $(".today").attr("id").slice(3));
      });

    });



    //CODIGO PARA CAMBIAR DE AGENCIA Y POBLAR SELECT AGENTE SI EXISTE
    
    if ($("#agencia_select").length) {
      
      $("#agencia_select").on("change", function(){

          const agencia_selected = $(this).find("option:selected").val();
          const past_event =  $("#past_event_check").val();
          const agencia_id = $(this).find("option:selected").attr('data');

          if ($("#agente_select").length) {

            if (agencia_selected == '') {
              $("#agente_select").html(`
                <option value=""></option>
                <option value="${agente_id_default}">ADMIN</option>
              `);
            }else{
              $.ajax({
                  type: "POST",
                  url: "process-request-calendario.php",
                  data: { fecha_tag_sent: 'get_agentes', agencia_id_sent : agencia_id }
              }).done(function(data){
                $("#agente_select").html(data);
              });
            };

        }else{

          $.ajax({
              type: "POST",
              url: "process-request-calendario.php",
              data: { fecha_tag_sent: 'hoy', agencia_tag_sent : agencia_selected, agente_id_sent : agente_id_default, past_events_sent : past_event }
          }).done(function(data){
      
            $(".calendario_contendor").html(data);
      
            $(".calendario_contendor").scrollTop(0);           
      
            let t = $(".calendario_contendor").offset().top;
            
            $(".calendario_contendor").scrollTop($(".today").offset().top - t - 5);// Sepone el scroll en el dia de hoy
            let titulo_first = $(".today").attr("mes") + " " + $(".today").attr("year");
      
            $(".cabecera_titulo").html(titulo_first).attr("data", $(".today").attr("id").slice(3));
          });

        };
        

      });

    };


    // CODIGO SELECT AGENTE

    if ($("#agente_select").length) {

      $("#agente_select").on("change", function(){

        let agente_selected = $(this).find("option:selected").val();
        
        if($(this).find("option:selected").val() == ''){
          agente_selected = agente_id_default;
        };
        const agencia_selected = $("#agencia_select option:selected").val();
        const past_event =  $("#past_event_check").val();

        $.ajax({
            type: "POST",
            url: "process-request-calendario.php",
            data: { fecha_tag_sent: 'hoy', agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, past_events_sent : past_event }
        }).done(function(data){
    
          $(".calendario_contendor").html(data);
    
          $(".calendario_contendor").scrollTop(0);           
    
          let t = $(".calendario_contendor").offset().top;
          
          $(".calendario_contendor").scrollTop($(".today").offset().top - t - 5);// Sepone el scroll en el dia de hoy
          let titulo_first = $(".today").attr("mes") + " " + $(".today").attr("year");
    
          $(".cabecera_titulo").html(titulo_first).attr("data", $(".today").attr("id").slice(3));

          
        });



      });

    };

    

    // CODIGO POPUP AGENDA DEL DIA

    $(".calendario_contendor").on("click", ".day_wrap", function(){//el evento no se propaga al boton hijo "agregar", por el stop_propagation
    
      const date_selected = $(this).attr('id');
      const day_num = date_selected.substring(0,2);
      const day_string = $(this).attr('dia');
      const mes_string = $(this).attr('mes');
      const year_num = $(this).attr('year');

      const titulo_h1 = day_string + ' ' + day_num;
      const titulo_h4 = mes_string + ' ' + year_num;

      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;

      if ($("#agencia_select").length) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      $.ajax({
        type: "POST",
        url: "process-request-calendario.php",
        data: { fecha_tag_sent: 'popup_dia', agencia_tag_sent : agencia_selected, agente_id_sent : agente_selected, fecha_selected_sent : date_selected, titulo_h1_sent : titulo_h1, titulo_h4_sent : titulo_h4 }
      }).done(function(data){

        $(".popup").html(data);

      });
      

      $(".popup_overlay").css("opacity", 1).css("visibility", "unset");
      
      history.pushState('popup_opened', null, "#detalle_dia");

    });

    // CODIGO PARA CERRAR EL POPUP AGENDA DIA
    $(".popup_overlay").on("click", ".cerrar_popup_dia", function(){
      
      $(".popup").empty();
      $(".popup_overlay").css("opacity", 0).css("visibility", "hidden");
      window.history.back();
      
    });
    

    // CODIGO PARA MOSTRAR LOS ACTIONS DE ELEMENTOS POPUP DIA

    $(".popup_overlay").on("mouseover", ".elemento_popup", function(){

      const actions_contenedor = $(this).find(".elemento_actions_wrap");

      $(".elemento_actions_wrap.activo").css('visibility', 'hidden');
      
      if(actions_contenedor.hasClass('activo')){

        actions_contenedor.css('visibility', 'unset');

      };
      
    });

    // CODIGO CLICK EN POPUP ELEMENTO ACTION BORRAR
    $(".popup_overlay").on("click", ".confirmar_borrar.activado", function(){
      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;

      if ($("#agencia_select").length) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      const contenedor = $(this).parent().parent().parent();
      const tipo_elemento = contenedor.attr('data');
      const key = contenedor.attr('key');
      const fecha_actual = $(".popup_date").val();
      const past_event =  $("#past_event_check").val();
      let data_extra = {
        elemento: tipo_elemento,
        key: key,
        fecha_actual: fecha_actual
      };

      if (tipo_elemento == 'anuncios_agencia') {
        data_extra['titulo'] = contenedor.attr('titulo');
        
      }else if(tipo_elemento == 'eventos_agencia'){
        data_extra['titulo'] = contenedor.attr('titulo');

      }else if(tipo_elemento == 'registros_agente'){
        data_extra['referencia'] = contenedor.attr('titulo');//dentro de 'titulo'
        data_extra['hora'] = contenedor.attr('hora');

      }else if(tipo_elemento == 'citas_jefe'){
        data_extra['hora'] = contenedor.attr('hora');

      }else if(tipo_elemento == 'visitas_agente'){
        data_extra['referencia'] = contenedor.attr('titulo'); // dentro de titulo
        data_extra['hora'] = contenedor.attr('hora');

      }else if(tipo_elemento == 'salidas_jefe'){
        data_extra['hora'] = contenedor.attr('hora');

      }else if(tipo_elemento == 'eventos_personal'){
        data_extra['titulo'] = contenedor.attr('titulo');
        data_extra['tipo'] = contenedor.attr('tipo');

      }else if(tipo_elemento == 'to_do_personal'){
        data_extra['titulo'] = contenedor.attr('titulo');
        data_extra['hora'] = contenedor.attr('hora');
      };

      $.ajax({
        type: "POST",
        url: "process-request-calendario.php",
        data: { fecha_tag_sent: 'borrar_elemento', agencia_tag_sent : agencia_selected, past_events_sent : past_event, agente_id_sent : agente_selected, extra_sent : data_extra }
      }).done(function(data){
        
        if (data == "error") {
          console.log(data);
          
        }else{
          const section_contenedor = contenedor.parent();
          contenedor.remove();

          if (section_contenedor.find('.elemento_popup').length == 0) {
            section_contenedor.remove();
          };

          const cuadro_id = "#" + fecha_actual;
      
          $(".calendario_contendor").html(data);

          $(".calendario_contendor").scrollTop(0);           

          const t = $(".calendario_contendor").offset().top;
          
          $(".calendario_contendor").scrollTop($(cuadro_id).offset().top - t - 5);// Sepone el scroll en el dia de hoy
          let titulo_first = $(cuadro_id).attr("mes") + " " + $(cuadro_id).attr("year");

          $(".cabecera_titulo").html(titulo_first).attr("data", $(cuadro_id).attr("id").slice(3));

        };

      });
      
    });

    // CODIGO CHECK DEL TODO POPUP DETALLE
    $(".popup_overlay").on("click", ".check_element_read", function(){

      let agente_selected = agente_id_default;

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };
      
      const check_box = $(this).find(".check_list_box i");
      const fecha = $(".popup_date").val();
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
        url: "process-request-calendario.php",
        data: { fecha_tag_sent: 'check_element', agente_id_sent : agente_selected, action_sent: action, key_check_sent : key_check, key_to_do_sent: key_to_do, fecha_sent : fecha, titulo_sent: titulo }
      }).done(function(data){

        $(".check_list_wrap.edit").html(data);
        
      })
        


    });

    // CODIGO EDITAR TODO POPUP DETALLE
    $(".popup_overlay").on("click", ".btn_editar_check_list", function(){
      $(this).parent().parent().find('.check_list_wrap.read').hide();
      $(this).parent().parent().find('.check_list_wrap.edit').css('display', 'flex');
      
    });
    
    // CODIGO PARA GUARDAR CAMBIOS AL TODO POPUP DETALLE 
    $(".popup_overlay").on("click", ".btn_guardar_cambios_check_list", function(){

      let agente_selected = agente_id_default;

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      const fecha = $(".popup_date").val();
      const key_to_do = $(this).parent().parent().parent().attr("key");
      const titulo = $(this).parent().parent().parent().attr("titulo");
      const check_element_parent = $(this).parent();

      let array_to_do = [];

      check_element_parent.find('.check_element').each(function(){

        const titulo_elemento = $(this).find('textarea').val();
        const checked_elemento = $(this).attr("estado");

        const new_element = {
          titulo: titulo_elemento,
          checked: checked_elemento
        };
        
        array_to_do.push(new_element);
      });


      const json_array_to_do = JSON.stringify(array_to_do);

      $.ajax({
        type: "POST",
        url: "process-request-calendario.php",
        data: { fecha_tag_sent: 'edit_to_do', agente_id_sent : agente_selected, key_to_do_sent: key_to_do, fecha_sent : fecha, titulo_sent: titulo, to_do_json_sent : json_array_to_do }
      }).done(function(data){

        if (data == 'error') {
          alert("error");
        }else{
          
          const day_selected =  "#" + $(".popup_date").val();

          $(".popup").empty();
          $(".popup_overlay").css("opacity", 0).css("visibility", "hidden");

          $(day_selected).trigger('click');
          
        };
        
      })

      
    });


    // CODIGO PARA EVITAR LA PROPAGACION DE CLICK ELEMENTO HEADER
    $(".popup_overlay").on("click", ".elemento_actions_wrap", function(event){
      event.stopPropagation();//para evitar lanzar los eventos de click en el elemento padre
    });

    // CODIGO ABRIR DETALLE ELEMENTO POPUP DIA

    $(".popup_overlay").on("click", ".elemento_header", function(){

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

    // CODIGO VER DETALLE DE INMUEBLE EN REGISTRO
    $(".popup_overlay").on("click", ".boton_info_inmueble", function(){
      var formulario_referencia = $(this).attr('id');
      var formulario_tabla = $(this).attr('name');

      $.ajax({
          type: "POST",
          url: "process-request-form-info-nuevo_form.php",
          data: { referencia_sent : formulario_referencia, tabla_sent : formulario_tabla}
      }).done(function(data){
          $('.overlay_datos_inmuebles').css('visibility', 'unset').html(data);
      });

    });

    //CODIGO QUE EVITA QUE SE ABRAN MAS POPUP AL HACER CLICK EN EL MISMO YA QUE ES UN ELEMENTO CHILD

    $('.overlay_datos_inmuebles').on('click',function(){

      $(".info_adicional_container").remove();
      $('.overlay_datos_inmuebles').css('visibility', 'hidden');

    });

    $('.overlay_datos_inmuebles').on('click', '.previsualizacion_container' ,function(e){
      e.stopPropagation();//evita que active el click event de su contenedor, la elemento sponsor de la ficha bien
    });


    // CODIGO BOTONES RESPUESTA FOTOGRAFO

    $('.popup_overlay').on('click', ".fotografo_option:not(.activo)", function(){
      const container = $(this).parent();
      container.find(".fotografo_option").removeClass("activo");
      $(this).addClass("activo");

      let respuesta_fotografo;
      if($(this).hasClass("btn_confirmar")){
        respuesta_fotografo = true;
      }else if($(this).hasClass("btn_rechazar")){
        respuesta_fotografo = false;
      };

      

      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;
      const past_event =  $("#past_event_check").val();

      if ($("#agencia_select").length) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").length) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      const contenedor = $(this).parent().parent().parent().parent();
      const referencia = contenedor.attr("titulo");
      const hora = contenedor.attr('hora');
      const agente_registrador = contenedor.attr('agente');
      const fecha_actual = $(".popup_date").val();
      
      
      $.ajax({
        type: "POST",
        url: "process-request-calendario.php",
        data: { fecha_tag_sent: 'respuesta_fotografo', agencia_tag_sent : agencia_selected, past_events_sent : past_event, agente_id_sent : agente_selected, respuesta_sent: respuesta_fotografo, registrador_sent : agente_registrador, fecha_sent : fecha_actual, hora_sent : hora, referencia_sent : referencia }
      }).done(function(data){

        
      })
        

    });

    // CODIGO AGREGAR EVENTO DESDE EL POPUP DIA

    $('.popup_overlay').on('click', ".popup_agregar_evento_btn", function(){

      const date = "#" + $(".popup_date").val();
      const agregarButton = $(".calendario_contendor").find(date).find(".day_agregar_btn");
      agregarButton.trigger('click');

    });


    // CODIGO PARA TRAER LA FICHA BIEN DESPUES DE HACER CLICK EN UN THUMBNAIL BIEN INMUEBLE ####################################
    $('.popup_overlay').on('click', '.boton_ficha_inmueble', function(){
      $('.ficha_bien_container').addClass('active');

      const contenedor_abuelo = $(this).parent().parent();

      const ficha_bien_clicked_referencia = contenedor_abuelo.attr('titulo');
      const ficha_bien_tipo = contenedor_abuelo.attr('tabla');
      const estado = contenedor_abuelo.attr('estado');
      const agente_id = $(".popup_agente_id").val();


      $.ajax({
        type: "POST",
        url: "process-request-popup_ficha_bien_detalle.php",
        data: { ficha_bien_requested : ficha_bien_clicked_referencia, ficha_bien_tipo_requested : ficha_bien_tipo, estado : estado, agente_sent : agente_id },
      }).done(function(data){
        $('.popup_ficha_bien').html(data);
        $("body").addClass('ficha_active');
      });

    });


    // ################### PUSH STATE PARA APERTURA DE POPUP AGREGAR EVENTO ##########################

    $(".calendario_contendor").on("click", ".day_agregar_btn", function(e){

      if (window.location.hash == '') {
        history.pushState('popup_opened', null, "#agregar_evento");
      }
      
    });
    
    //############## CODIGO PARA EL BOTON DE BORRAR #########################################

    $(".popup_overlay").on("click", ".elemento_actions_wrap.activo .borrar_trash", function(){
      const btn_confirmar = $(this).parent().find(".confirmar_borrar");

      if (btn_confirmar.hasClass('activado')) {
        btn_confirmar.hide("slide", { direction: "left" }, 800).toggleClass("activado");
        $(this).removeClass("fa-times-circle").addClass("fa-trash-alt");
      }else{
        btn_confirmar.show("slide", { direction: "left" }, 800).toggleClass("activado");
        $(this).removeClass("fa-trash-alt").addClass("fa-times-circle");
      };
    });



    // Event Listenner that adds a new check element
    $('.popup_overlay').on("click", ".agregar_check_element",  function(){
      const check_element = `
          <span class="check_element" estado="0">
            <textarea rows="1" oninput="auto_grow(this)" style="height: 29px;"></textarea>
            <span class="borrar_check_element"><i class="fas fa-times-circle"></i></span>
          </span>
      `;
      $(check_element).insertBefore('.agregar_check_element');
    })

    // Event Listenner that erases an existing check_element
    $('.popup_overlay').on("click", ".borrar_check_element",  function(){
      const elements = $('.check_element').length;
      if (elements > 1) {
        $(this).parent().remove();
      }
    })

    $('.popup_overlay').on("click", ".anniversario_opcion:not(.activo)",  function(){
      $(".anniversario_opcion").removeClass("activo");
      $(this).addClass("activo");
    });


    // ######################## BACK BUTTON MECHANICS ########################

    window.onpopstate = checkState; // cuando un nuevo state aparece, cuando se oprime el Back/Foward Browser button

    function checkState(e) {

      if(e.state == null) {// se cierran todos los popups posibles
        
        $(".filtros_overlay").css("opacity", 0).css("visibility", "hidden");
      
        $(".popup").empty();
        $(".popup_overlay").css("opacity", 0).css("visibility", "hidden");

      };

    };


  });
});
