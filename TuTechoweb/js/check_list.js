function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight + 8)+"px";
  }

$(document).ready(function(){
    jQuery(function($){

        function refresh_inputs() {
            $(".fecha_edit").each(function(){
                $(this).datepicker({
                    dateFormat: "dd-mm-yy"
                });
            });

            $(".hora_edit").each(function(){
                $(this).clockTimePicker({
                    autosize:true,
                    vibrate:false,
                    alwaysSelectHoursFirst:true
                  });
            });

            $(".titulo_text_edit").on("input", function(){
                if ($(this).val().match(/^[\w\d\s -/+_,.!?*$€()@#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
                    if ($(this).val() !== '') {
                        $(this).addClass("borde_rojo");
                    };
                } else {
                    $(this).removeClass("borde_rojo");
                };
            });

            $( ".edit_elements_wrap" ).each(function(){

                Sortable.create(this, {
                    animation: 200,
                    handle: ".handler_element",
                    invertSwap: true,
                    swapThreshold: 1,
                    forceFallback: true,
                    fallbackTolerance: 8,
                    direction: 'vertical',
                  });

            });

            
            
        };
        
        // CODIGO PARA EL CAMBIO DE TABS

        $(".tab").on("click", function(){
            if($(this).hasClass("activo") == false){
                $(".tab").removeClass("activo");
                $(this).addClass("activo");
            };
        });


        // CODIGO CERRAR POPUP
        $(".popup_overlay").on("click", ".cerrar_popup", function(){
            $(".popup_overlay").css('opacity', '0').css("visibility", "hidden");
            $(".menu_more").removeClass("activo");
        });

        // CODIGO PARA LOS 3 DOSTS MENU MORE TABLA

        $(".resultados_wrap").on("click", ".elemento_more", function(e) {

            const posicion = $(this).offset();
            const key = $(this).parent().parent().parent().attr('key');
            
            $(".menu_more").css("top", (posicion['top'] + 30)).css('left', (posicion['left'] - 80)).toggleClass("activo").attr("data", key);

        });

        // CODIGO PARA MANTENER el MENU MORE ABIERTO

        $(".resultados_wrap").on("mouseover", ".elemento_popup", function(){

            const actions_contenedor = $(this).find(".elemento_actions_wrap");
    
            $(".elemento_actions_wrap.activo").css('visibility', 'hidden');
            
            if(actions_contenedor.hasClass('activo')){
    
            actions_contenedor.css('visibility', 'unset');
    
            };

            if ($(".menu_more").hasClass("activo")) {

                const key = $(".menu_more").attr("data");

                if ($(this).attr("key") !== key) {

                    $(".menu_more").css("top", 0).css('left', 0).removeClass("activo");

                };

            };
            
        });

        $(".resultados_wrap").on("mouseleave", ".elemento_popup", function() {

            if ($('.menu_more:hover').length == 0) {
                $(".menu_more").css("top", 0).css('left', 0).removeClass("activo");
            };
            
        });

        $(".sub_global_wrap").on("mouseleave", function() {
            
            if ($(".menu_more").hasClass("activo")) {

                if ($('.menu_more:hover').length == 0) {
                    $(".menu_more").removeClass("activo");
                };  

            };

        });


        // FIRST CHARGE #########################

        $.ajax({
            type: "POST",
            url: "process-request-check-list.php",
            data: { agente_id_sent : agente_id_default, pais_sent : '', action_sent : 'refresh' }
        }).done(function(data){

            $(".resultados_wrap").html(data);
            refresh_inputs();

        });


        // CODIGO ABRIR DETALLE ELEMENTO POPUP DIA

        $(".resultados_wrap").on("click", ".elemento_header", function(){

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

        // CODIGO PARA GUARDAR CAMBIOS AL TO-DO DETALLE 
        $(".resultados_wrap").on("click", ".btn_guardar_cambios_check_list", function(){

            let agente_selected = agente_id_default;

            const new_titulo = $(this).parent().parent().parent().parent().find('.titulo_text_edit').val();
            const new_fecha = $(this).parent().parent().parent().parent().find('.fecha_edit').val();
            const new_hora = $(this).parent().parent().parent().parent().find('.hora_edit').val();

            const key_to_do = $(this).parent().parent().parent().attr("key");
            const titulo = $(this).parent().parent().parent().attr("titulo");

            const check_element_parent = $(this).parent();
    
            let array_to_do = [];

            console.log(new_titulo+new_fecha+new_hora);

            if ($(this).parent().parent().parent().find('.titulo_text_edit').hasClass('borde_rojo') == false && new_titulo !== '') {
               
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
                url: "process-request-check-list.php",
                data: { agente_id_sent : agente_selected, key_to_do_sent: key_to_do, titulo_sent: titulo, to_do_json_sent : json_array_to_do, action_sent : 'edit', new_titulo_sent : new_titulo, new_fecha_sent : new_fecha, new_hora_sent : new_hora}
                }).done(function(data){
        
                if (data == 'error') {
                    alert("error");
                }else{
                    
                    $(".resultados_wrap").html(data);
                    refresh_inputs();
                };
                
                })

            }else{
                alert("Titulo con caracteres prohibidos");
            };
    
            
        });
    
    
        // CODIGO PARA EVITAR LA PROPAGACION DE CLICK ELEMENTO HEADER
        $(".resultados_wrap").on("click", ".elemento_actions_wrap", function(event){
            event.stopPropagation();//para evitar lanzar los eventos de click en el elemento padre
        });

         // CODIGO PARA EVITAR LA PROPAGACION DE CLICK ELEMENTO HEADER
         $(".resultados_wrap").on("click", ".titulo_edit", function(event){
            event.stopPropagation();//para evitar lanzar los eventos de click en el elemento padre
        });

        // CODIGO CHECK DEL TO-DO DETALLE
        $(".resultados_wrap").on("click", ".check_element_read", function(){

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
            url: "process-request-check-list.php",
            data: { action_sent: 'check_element', agente_id_sent : agente_selected, action_listened: action, key_check_sent : key_check, key_to_do_sent: key_to_do, titulo_sent: titulo }
            }).done(function(data){

            $(".check_list_wrap.edit").html(data);
            
            })
            


        });

        // CODIGO EDITAR TO_DO DETALLE
        $(".resultados_wrap").on("click", ".btn_editar_check_list", function(){

            $(this).parent().parent().find('.check_list_wrap.read').hide();
            $(this).parent().parent().find('.check_list_wrap.edit').css('display', 'flex');

            $(this).parent().parent().parent().find('.titulo_read').hide();
            $(this).parent().parent().parent().find('.titulo_edit').css('display', 'flex');
            
        });


        // CODIGO PARA EL TAB TUS CONTACTOS

        $(".tab_mis_listas").on("click", function(){

            $(".agregar_btn").addClass("activo");
            $(".barra_selects_wrap").css("visibility", 'hidden');
            
            $.ajax({
                type: "POST",
                url: "process-request-check-list.php",
                data: { agente_id_sent : agente_id_default, pais_sent : '', action_sent : 'refresh' }
            }).done(function(data){

                $(".resultados_wrap").html(data);
                refresh_inputs();
            });
            
        });

        // CODIGO TAB USER AGENTE

        $(".tab_agente").on("click", function(){
            $(".agregar_btn").removeClass("activo");
            $(".barra_selects_wrap").css("visibility", 'unset');

            $(".resultados_wrap").empty();


        });

        if ($("#agente_id").length) {
            $("#agente_id").on("input", function(){
                if ($(this).val().match(/^[\w\d\s -/+_#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
                    if ($(this).val() !== '') {
                        $(this).addClass("borde_rojo");
                    };
                } else {
                    $(this).removeClass("borde_rojo");
                };
            });
        };

        // CODIGO PARA BUSCAR LISTAS DE UN AGENTE

        $(".buscar_agente_btn").on("click", function(){

            const agente_id = $("#agente_id").val();
            if (agente_id !== '') {
                if ($("#agente_id").hasClass("borde_rojo")) {
                    $(".popup_contenido").html(`Caracteres NO permitidos en Referencia`);
                    $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                }else{
                    if ($("#pais_select_agente").length) {

                        const pais_selected = $("#pais_select_agente").val();
        
                        if (pais_selected !== '') {
                            $.ajax({
                                type: "POST",
                                url: "process-request-check-list.php",
                                data: { action_sent : 'agente_search', agente_id_sent : agente_id, pais_sent :  pais_selected}
                            }).done(function(data){
                                $(".resultados_wrap").html(data);
                                refresh_inputs();
                            });
                        }else{
        
                            $(".popup_contenido").html(`Debe ingresar un Pais`);
                            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                        };
            
                    };
                };
                
            }else{
                $(".popup_contenido").html(`Debe ingresar un Id de Agente`);
                $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
            };
            

        });


        // CODIGO ABRIR POPUP COMPARTIR

        $(".opcion_compartir").on("click", function() {

            const id_menu_more = "#" + $(".menu_more").attr("data");
            const key_to_do = $(".menu_more").attr("data");
            const line_container = $(id_menu_more);
            const titulo = line_container.attr("titulo");
            const hora = line_container.attr("hora");
            const fecha = line_container.attr("fecha");

            $(".popup_contenido").html(`
                
                <span class="contacto_tag">Compartir</span>

                <div class="check_list_compartir_tag">
                    <p>Check-List: ${titulo} ${fecha} - ${hora}</p>     
                </div>

                <input type="hidden" name="index_compartir" id="index_compartir" value="${key_to_do}">
                <input type="hidden" name="index_titulo" id="index_titulo" value="${titulo}">

                <div class="contacto_compartir_input_wrap">

                    <input type="text" name="compartir_input" id="compartir_input" placeholder="Compartir con: #ID-Agente">
                    
                </div>

                <span class="error_wrap"></span>

                <span class="compartir_btn">Enviar</span>
            
            `);

            $(".popup_overlay").on("input", "#compartir_input", function(){
                if ($(this).val().match(/^[\w\d\s -/+@_#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
                    if ($(this).val() !== '') {
                        $(this).addClass("borde_rojo");
                        $(".error_wrap").html("Sólo use caracteres permitidos").css("visibility", "unset");
                    };
                } else {
                    $(this).removeClass("borde_rojo");
                    $(".error_wrap").html("").css("visibility", "hidden");
                };
            });

            $(".menu_more").removeClass("activo");

            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");


        });


        // CODIGO PARA PODER COMPARTIR CONTACTOS ENTRE AGENTES

        $(".popup_overlay").on("click", ".compartir_btn", function() {

            let index_compartir = $("#index_compartir").val();
            let destinatario_id =  $("#compartir_input").val();
            const titulo = $("#index_titulo").val();

            if (destinatario_id == '' || $("#compartir_input").hasClass("borde_rojo")) {
                $(".error_wrap").html(`Ingrese los datos correctamente`).css("visibility", "unset");
            }else{

                $.ajax({
                    type: "POST",
                    url: "process-request-check-list.php",
                    data: { action_sent : 'compartir', index_sent : index_compartir, destinatario_sent : destinatario_id, titulo_sent: titulo, agente_id_sent : agente_id_default}
                }).done(function(data){
                    if (data == 'error') {
                        alert(`ID:Agente NO existe`);
                    }else{
                        $(".popup_contenido").html(`<h2 class="mensage_success">Check-List compartido Exitosamente</h2>`);
                    };
                });

            }; 

        });


        // CODIGO ABRIR POPUP AGREGAR A VISITA

        $(".opcion_visita").on("click", function() {

            const id_menu_more = "#" + $(".menu_more").attr("data");
            const key_to_do = $(".menu_more").attr("data");
            const line_container = $(id_menu_more);
            const titulo = line_container.attr("titulo");
            const hora = line_container.attr("hora");
            const fecha = line_container.attr("fecha");

            $(".popup_contenido").html(`
                
                <span class="contacto_tag">+ Visita</span>

                <div class="check_list_compartir_tag">
                    <p>Check-List: ${titulo} ${fecha} - ${hora}</p>     
                </div>

                <input type="hidden" name="index_to_do" id="index_to_do" value="${key_to_do}">
                <input type="hidden" name="index_titulo" id="index_titulo" value="${titulo}">

                <div class="contacto_visita_select_wrap">
                    <label for="visita_select">Selecciona la Visita:</label>
                    <select name="visita_select" class="visita_select">

                    </select>
                    
                </div>

                <span class="error_wrap"></span>

                <span class="enviar_visita_btn">Enviar</span>
            
            `);

            $.ajax({
                type: "POST",
                url: "process-request-libreta-contactos.php",
                data: { action_sent : 'get_visitas'}
            }).done(function(data){
                if (data == 'error') {
                    $(".error_wrap").html(`Error de Formulario`).css("visibility", "unset");
                }else{
                    $(".visita_select").html(data);
                };

            });

            $(".menu_more").removeClass("activo");

            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");


        });


        // CODIGO PARA PODER MADAR CHECK-LIST A FICHA VISITA

        $(".popup_overlay").on("click", ".enviar_visita_btn", function() {

            const index_visita = $(".visita_select option:selected").attr("key");
            const agencia_tag_visita = $(".visita_select option:selected").attr("agencia_tag");
            const referencia_visita = $(".visita_select option:selected").attr("referencia");
            const index_to_do = $("#index_to_do").val();
            const titulo = $("#index_titulo").val();

            if (index_visita == '' || agencia_tag_visita == '' || referencia_visita == '' || index_to_do == '' || titulo == '') {
                $(".error_wrap").html(`Visita contiene Errores`).css("visibility", "unset");
            }else{

                $.ajax({
                    type: "POST",
                    url: "process-request-check-list.php",
                    data: { action_sent : 'agregar_a_visita', index_sent : index_visita, agencia_tag_sent : agencia_tag_visita, referencia_sent : referencia_visita, index_to_do_sent : index_to_do, titulo_sent : titulo}
                }).done(function(data){
                    if (data == 'error') {
                        $(".error_wrap").html(`Error de Formulario`).css("visibility", "unset");
                    }else if(data == 'exito'){
                        $(".popup_contenido").html(`<h2 class="mensage_success">Check-List Agregado a Visita Exitosamente</h2>`);
                    };

                    console.log(data);

                });

            }; 

        });    


        //############## CODIGO PARA EL BOTON DE BORRAR #########################################

        $(".resultados_wrap").on("click", ".elemento_actions_wrap.activo .borrar_trash", function(){
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
        $('.resultados_wrap').on("click", ".agregar_check_element",  function(){
            const check_element = `
                <span class="check_element" estado="0">
                <i class="fas fa-arrows-alt handler_element"></i>
                <textarea rows="1" oninput="auto_grow(this)" style="height: 29px;"></textarea>
                <span class="borrar_check_element"><i class="fas fa-times-circle"></i></span>
                </span>
            `;

            $(this).parent().find(".edit_elements_wrap").find(".check_element").last().after(check_element);
            
        })

        // Event Listenner that adds a new check element
        $(".popup_overlay").on("click", ".agregar_check_element_popup",  function(){
            const check_element = `
                <span class="check_element_popup" estado="0">
                <textarea rows="1" oninput="auto_grow(this)" style="height: 29px;"></textarea>
                <span class="borrar_check_element_popup"><i class="fas fa-times-circle"></i></span>
                </span>
            `;

            $(check_element).insertBefore('.agregar_check_element_popup');
        })
    
        // Event Listenner that erases an existing check_element
        $('.resultados_wrap').on("click", ".borrar_check_element",  function(){
            const elements = $('.check_element').length;
            if (elements > 1) {
            $(this).parent().remove();
            }
        })

        // Event Listenner that erases an existing check_element
        $(".popup_overlay").on("click", ".borrar_check_element_popup",  function(){
            const elements = $('.check_element_popup').length;
            if (elements > 1) {
            $(this).parent().remove();
            }
        })


        // CODIGO CLICK EN POPUP ELEMENTO ACTION BORRAR
        $(".resultados_wrap").on("click", ".confirmar_borrar", function(){
            let agente_selected = agente_id_default;
    
            const contenedor = $(this).parent().parent().parent();
            const key = contenedor.attr('key');
            const titulo = contenedor.attr('titulo');
            
            $.ajax({
                type: "POST",
                url: "process-request-check-list.php",
                data: { action_sent: 'borrar', agente_id_sent : agente_selected, key_sent : key, titulo_sent : titulo }
            }).done(function(data){
                if (data == "error") {
                    console.log(data);
                    
                }else{
                    
                $(".resultados_wrap").html(data);
                refresh_inputs();   
        
                };
            });
        });



        $(".agregar_btn").on("click", function(event){
      
            $(".popup").html(`
      
            <span class="cerrar_popup"><i class="fas fa-times-circle"></i></span>
            <span class="popup_contenido">
            
              <div class="popup_cabecera">
                <span class="tab_popup tab_evento activo" style="width: 100%">Evento</span>
              </div>
      
              <div class="contenido_evento activo">
                
                <input type="text" class="titulo_evento" placeholder="Titulo">

                <input type="text" class="fecha_evento" placeholder="Fecha (opcional)" value="">
      
                <input type="text" class="hora_evento" placeholder="Hora (opcional)" value="">
      
                <div class="check_list_wrap_popup">
                    <span class="check_element_popup">
                    <textarea rows="1" oninput="auto_grow(this)" style="height: 29px;"></textarea>
                    <span class="borrar_check_element_popup"><i class="fas fa-times-circle"></i></span>
                    </span>
                    
                    <span class="agregar_check_element_popup">
                    <i class="fas fa-plus-circle"></i>
                    <i class="fas fa-caret-right"></i>
                    </span>
                </div>
      
                <span class="error_wrap_evento"></span>
      
                <span class="btn_guardar_evento">Guardar</span>
      
              </div>
      
              </span>
      
            `);
      
            $(".popup_overlay").css("opacity", 1).css("visibility", "unset");
      
            $('.hora_evento').clockTimePicker({
              autosize:true,
              vibrate:false,
              alwaysSelectHoursFirst:true
            });

            $( ".fecha_evento" ).datepicker({
                dateFormat: "dd-mm-yy"
            });
      
        });


        // CODIGO GUARDAR EVENTO

        $(".popup_overlay").on("click", ".btn_guardar_evento", function(){
            const fecha = $(".fecha_evento").val();
            const titulo = $(".titulo_evento").val();
            const hora = $(".hora_evento").val();
    
            let agente_selected = agente_id_default;
    
            let check_list = [];
    
            $(".check_element_popup").each(function(){
    
                if ($(this).find("textarea").val() !== '') {
                const new_element = {'titulo': $(this).find("textarea").val(), 'checked' : 0};
                check_list.push(new_element);
                };
    
            });
    
            let check_list_json = JSON.stringify(check_list);
    
            if (titulo == '') {
            
            $(".error_wrap_evento").html("El formulario debe llenarse correctamente").css("visibility", "unset");
    
            }else{
    
                $.ajax({
                    type: "POST",
                    url: "process-request-check-list.php",
                    data: { action_sent: 'agregar', fecha_sent : fecha, hora_sent : hora, titulo_sent : titulo, check_list_sent: check_list_json, agente_id_sent : agente_id_default }
                }).done(function(data){
            
                    if (data == 'error') {
                        $(".error_wrap_evento").html("Error de Formulario").css("visibility", "unset");
                    }else{

                    $(".resultados_wrap").html(data);
                    refresh_inputs();
            
                    $(".popup_overlay").css("opacity", 0).css("visibility", "hidden");

                    
            
                    };
            
                    
                });
    
            
    
        };
            
    
        });


    });

});
