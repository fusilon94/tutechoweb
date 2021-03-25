$(document).ready(function(){
   jQuery(function($){


    // FUNCION PARA EL FORMATEO DEL NUMERO TELEFONO

    function getNumberFormat(numero) {

        if (numero !== '') {
            const digits = numero.replace(/\D/g, "").replace(/^0+/, '');

            return digits;
        }else{
            return '';
        };
            
    };

    
    // CODIGO PARA EL CAMBIO DE TABS

    $(".tab_agenda").on("click", function(){
        if($(this).hasClass("activo") == false){
            $(".tab_agenda").removeClass("activo");
            $(this).addClass("activo");
        };
    });



    // CODIGO CERRAR POPUP
    $(".cerrar_popup").on("click", function(){
        $(".popup_overlay").css('opacity', '0').css("visibility", "hidden");
        $(".menu_more").removeClass("activo");
    });


    // FIRST CHANGE

    $.ajax({
        type: "POST",
        url: "process-request-libreta-contactos.php",
        data: { action_sent : 'ver_contactos' }
    }).done(function(data){
        $(".resultados_wrap").html(data);
    });


    
    // CODIGO PARA EL POPUP AGREGAR CONTACTOS


    $(".agregar_btn").on("click", function(){//CODIGO PARA CREAR EL POPUP y POBLARLO
        $(".popup_contenido").html(`
        
        <span class="contacto_tag_edicion">Nuevo Contacto</span>
        <div class="popup_cabecera_edit">

            <span class="popup_cabecera_titulo">
                <img src="../../objetos/hombre_icono_min_gold.svg" alt="Foto">
                <input type="text" placeholder="Nombre y Apellidos" class="nombre_contacto_input">
            </span>
            <span class="popup_cabecera_actions">
                <span class="cabecera_male activo"><i class="fa fa-male"></i></span>
                <span class="cabecera_female"><i class="fa fa-female"></i></span>
            </span>

        </div>

        <hr class="cabecera_line">

        <div class="popup_contacto_contenido">
            <div class="popup_contacto_email">
                <i class="fas fa-at"></i>
                <input type="text" placeholder="Email" class="email_contacto_input">
            </div>
            <div class="popup_contacto_telefono">
                <i class="fa fa-phone"></i>
                <input type="text" placeholder="Telefono (+XXX) XXXXXXX" class="telefono_contacto_input">
                <span class="btn_whatsapp_edicion">
                    <span class="fa-stack icon_stacks_whatsapp">
                        <i class="fab fa-whatsapp fa-stack-2x"></i>
                        <i class="fa fa-circle"></i>
                    </span>
                    <p>WhatsApp?</p>
                </span>
            </div>
            <div class="popup_contacto_info">
                <i class="fa fa-info"></i>
                <textarea name="" placeholder="Descripción...(opcional)" class="info_contacto_input"></textarea>
            </div>

            <span class="error_wrap">Error de algun tipo</span>
            <span class="btn_guardar" modo="nuevo_contacto">Crear Contacto</span>
        </div>

        `);
        $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
    });


    $(".popup_overlay").on("click", ".cabecera_male:not(.activo)", function(e) {

        $(this).toggleClass("activo");
        $(".cabecera_female").toggleClass("activo");
        const new_src = $(".popup_cabecera_titulo img").prop("src").replace("mujer", "hombre");
        $(".popup_cabecera_titulo img").prop("src", new_src);

    });

    $(".popup_overlay").on("click", ".cabecera_female:not(.activo)", function(e) {

        $(this).toggleClass("activo");
        $(".cabecera_male").toggleClass("activo");
        const new_src = $(".popup_cabecera_titulo img").prop("src").replace("hombre", "mujer");
        $(".popup_cabecera_titulo img").prop("src", new_src);

    });

    $(".popup_overlay").on("click", ".btn_whatsapp_edicion", function(){
        $(this).toggleClass("activo");
    });

    $(".popup_overlay").on("input", ".nombre_contacto_input", function(){
        if ($(this).val().match(/^[\w\d\s áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
            if ($(this).val() !== '') {
                $(this).addClass("borde_rojo");
                $(".error_wrap").html("Sólo use caracteres permitidos").css("visibility", "unset");
            };
          } else {
            $(this).removeClass("borde_rojo");
            $(".error_wrap").html("").css("visibility", "hidden");
          };
    });

    $(".popup_overlay").on("input", ".email_contacto_input", function(){
        if ($(this).val().match(/^[\w\d\s .-/+@_&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
            if ($(this).val() !== '') {
                $(this).addClass("borde_rojo");
                $(".error_wrap").html("Sólo use caracteres permitidos").css("visibility", "unset");
            };
          } else {
            $(this).removeClass("borde_rojo");
            $(".error_wrap").html("").css("visibility", "hidden");
          };
    });

    $(".popup_overlay").on("input", ".telefono_contacto_input", function(){
        if ($(this).val().match(/^[+\-0-9().# \/]+$/g) == null) {//Si se ingrso un caracter no permitido
            if ($(this).val() !== '') {
                $(this).addClass("borde_rojo");
                $(".error_wrap").html("Sólo use caracteres permitidos").css("visibility", "unset");
            };
          } else {
            $(this).removeClass("borde_rojo");
            $(".error_wrap").html("").css("visibility", "hidden");
          };
    });

    $(".popup_overlay").on("input", ".info_contacto_input", function(){
        if ($(this).val().match(/^[\w\d\s+\- ,?!&._#@áÁéÉíÍóÓúÚñÑ\'\/]+$/) == null) {//Si se ingrso un caracter no permitido
            if ($(this).val() !== '') {
                $(this).addClass("borde_rojo");
                $(".error_wrap").html("Sólo use caracteres permitidos").css("visibility", "unset");
            };
          } else {
            $(this).removeClass("borde_rojo");
            $(".error_wrap").html("").css("visibility", "hidden");
          };
    });


    $(".popup_overlay").on("click", ".btn_guardar", function(){


        let modo = $(this).attr("modo");
        let contacto_nombre = $(".nombre_contacto_input").val();
        let contacto_genero;
        if ($(".cabecera_male").hasClass("activo")) {
            contacto_genero = "hombre";
        }else{
            contacto_genero = "mujer";
        };
        let contacto_email = $(".email_contacto_input").val();
        let contacto_telefono = $(".telefono_contacto_input").val();
        let contacto_whatsapp;
        if ($(".btn_whatsapp_edicion").hasClass("activo")) {
            contacto_whatsapp = 1;
        }else{
            contacto_whatsapp = 0;
        };
        let contacto_info = $(".info_contacto_input").val();
        let current_tab;
        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos")) {
            current_tab = 'mis_contactos';
        }else if($(".tab_agenda.activo").hasClass("tab_contactos_utiles")){
            current_tab = 'contactos_utiles';
        };

        let errores = '';

        if (contacto_nombre == '' || contacto_telefono == '') {
            errores = 'error';
        };

        $(".borde_rojo").each(function(){
            errores = "error";
        });

        if (errores !== '') {
            $(".error_wrap").html("Todos los campos deben llenarse correctamente").css("visibility", "unset");
        }else{

            if (modo == 'nuevo_contacto') {

                if (current_tab == 'mis_contactos') {

                    $.ajax({
                        type: "POST",
                        url: "process-request-libreta-contactos.php",
                        data: { action_sent : modo, nombre_contacto_sent : contacto_nombre, genero_contacto_sent : contacto_genero, contacto_email_sent : contacto_email, contacto_telefono_sent : contacto_telefono, contacto_whatsapp_sent : contacto_whatsapp, contacto_info_sent : contacto_info, tab_sent : current_tab}
                    }).done(function(data){
                        if (data == 'error') {
                            $(".popup_contenido").html(`Telefono en uso por otro Contacto`);
                            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                        }else{
                            $(".resultados_wrap").html(data);
                        };
                        
                    });

                }else if(current_tab == 'contactos_utiles'){

                    let agencia_selected = $("#agencia_select").find("option:selected").val();

                    if ($("#pais_select").length) {//si hay pais que especificar

                        let pais_selected = $("#pais_select option:selected").val();

                        $.ajax({
                            type: "POST",
                            url: "process-request-libreta-contactos.php",
                            data: { action_sent : modo, nombre_contacto_sent : contacto_nombre, genero_contacto_sent : contacto_genero, contacto_email_sent : contacto_email, contacto_telefono_sent : contacto_telefono, contacto_whatsapp_sent : contacto_whatsapp, contacto_info_sent : contacto_info, tab_sent : current_tab, agencia_sent : agencia_selected, pais_sent : pais_selected}
                        }).done(function(data){
                            if (data == 'error') {
                                $(".popup_contenido").html(`Telefono en uso por otro Contacto`);
                                $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                            }else{
                                $(".resultados_wrap").html(data);
                            };
                        });

                    }else{

                        $.ajax({
                            type: "POST",
                            url: "process-request-libreta-contactos.php",
                            data: { action_sent : modo, nombre_contacto_sent : contacto_nombre, genero_contacto_sent : contacto_genero, contacto_email_sent : contacto_email, contacto_telefono_sent : contacto_telefono, contacto_whatsapp_sent : contacto_whatsapp, contacto_info_sent : contacto_info, tab_sent : current_tab, agencia_sent : agencia_selected}
                        }).done(function(data){
                            if (data == 'error') {
                                $(".popup_contenido").html(`Telefono en uso por otro Contacto`);
                                $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                            }else{
                                $(".resultados_wrap").html(data);
                            };
                        });

                    };

                };

                

            }else if(modo == 'editar_contacto'){

                let contacto_index = $(".index_contacto_input").val();

                if (current_tab == 'mis_contactos') {

                    let valor_busqueda = $(".barra_busqueda_input").val();

                    $.ajax({
                        type: "POST",
                        url: "process-request-libreta-contactos.php",
                        data: { contacto_index_sent: contacto_index, action_sent : modo, nombre_contacto_sent : contacto_nombre, genero_contacto_sent : contacto_genero, contacto_email_sent : contacto_email, contacto_telefono_sent : contacto_telefono, contacto_whatsapp_sent : contacto_whatsapp, contacto_info_sent : contacto_info, busqueda_valor_sent : valor_busqueda, tab_sent : current_tab }
                    }).done(function(data){
                        if (data == 'error') {
                            $(".popup_contenido").html(`Telefono en uso por otro Contacto`);
                            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                        }else{
                            $(".resultados_wrap").html(data);
                        };
                    });

                }else if(current_tab == 'contactos_utiles'){

                    let agencia_selected = $("#agencia_select").find("option:selected").val();

                    if ($("#pais_select").length) {//si hay pais que especificar

                        let pais_selected = $("#pais_select option:selected").val();

                        $.ajax({
                            type: "POST",
                            url: "process-request-libreta-contactos.php",
                            data: { contacto_index_sent: contacto_index, action_sent : modo, nombre_contacto_sent : contacto_nombre, genero_contacto_sent : contacto_genero, contacto_email_sent : contacto_email, contacto_telefono_sent : contacto_telefono, contacto_whatsapp_sent : contacto_whatsapp, contacto_info_sent : contacto_info, tab_sent : current_tab, agencia_sent : agencia_selected, pais_sent : pais_selected }
                        }).done(function(data){
                            if (data == 'error') {
                                $(".popup_contenido").html(`Telefono en uso por otro Contacto`);
                                $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                            }else{
                                $(".resultados_wrap").html(data);
                            };
                        });

                    }else{

                        $.ajax({
                            type: "POST",
                            url: "process-request-libreta-contactos.php",
                            data: { contacto_index_sent: contacto_index, action_sent : modo, nombre_contacto_sent : contacto_nombre, genero_contacto_sent : contacto_genero, contacto_email_sent : contacto_email, contacto_telefono_sent : contacto_telefono, contacto_whatsapp_sent : contacto_whatsapp, contacto_info_sent : contacto_info, tab_sent : current_tab, agencia_sent : agencia_selected }
                        }).done(function(data){
                            if (data == 'error') {
                                $(".popup_contenido").html(`Telefono en uso por otro Contacto`);
                                $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                            }else{
                                $(".resultados_wrap").html(data);
                            };
                        });

                    };


                };

            };

            $(".popup_overlay").css('opacity', '0').css("visibility", "hidden");
            
        };


    });




    // CODIGO PARA EL POPUP EDITAR CONTACTO
    $(".agenda_contenido").on("click", ".elemento_edit", function(e) {

        const line_container = $(this).parent().parent();
        const genero = line_container.attr("gender");
        const icon_contacto = line_container.find(".elemento_nombre_foto").prop("src");
        const nombre_contacto = line_container.find(".elemento_nombre_text").text();
        function gender_selector(valor, comparacion){
            if (valor == comparacion) {
                return "activo";
            }else{
                return "";
            };
        };
        const email_contacto = line_container.find(".elemento_email").text();
        const telefono_contacto = line_container.find(".elemento_telefono_text").text();
        const whatsapp_contacto = ((line_container.find(".elemento_telefono_whatsapp").hasClass("activo")) ? "activo" : "");
        const info_contacto = line_container.find(".elemento_info").text();
        const index_contacto = line_container.attr("id");

        $(".popup_contenido").html(`
        
        <span class="contacto_tag_edicion">Edición Contacto</span>
        <div class="popup_cabecera_edit">

            <span class="popup_cabecera_titulo">
                <img src="${icon_contacto}" alt="Foto">
                <input type="text" placeholder="Nombre y Apellidos" class="nombre_contacto_input" value="${nombre_contacto}">
            </span>
            <span class="popup_cabecera_actions">
                <span class="cabecera_male ${gender_selector(genero, 'hombre')}"><i class="fa fa-male"></i></span>
                <span class="cabecera_female ${gender_selector(genero, 'mujer')}"><i class="fa fa-female"></i></span>
            </span>

        </div>

        <hr class="cabecera_line">

        <div class="popup_contacto_contenido">
            <div class="popup_contacto_email">
                <i class="fas fa-at"></i>
                <input type="text" placeholder="Email" class="email_contacto_input" value="${email_contacto}">
            </div>
            <div class="popup_contacto_telefono">
                <i class="fa fa-phone"></i>
                <input type="text" placeholder="Telefono (+XXX) XXXXXXX" class="telefono_contacto_input" value="${telefono_contacto}">
                <span class="btn_whatsapp_edicion ${whatsapp_contacto}">
                    <span class="fa-stack icon_stacks_whatsapp">
                        <i class="fab fa-whatsapp fa-stack-2x"></i>
                        <i class="fa fa-circle"></i>
                    </span>
                    <p>WhatsApp?</p>
                </span>
            </div>
            <div class="popup_contacto_info">
                <i class="fa fa-info"></i>
                <textarea name="" placeholder="Descripción...(opcional)" class="info_contacto_input">${info_contacto}</textarea>
            </div>

            <span class="error_wrap">Error de algun tipo</span>
            <span class="btn_guardar" modo="editar_contacto">Guardar Cambios</span>
            <input type="hidden" class="index_contacto_input" value="${index_contacto}">
        </div>

        `);
        $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
    });


    // CODIGO PARA EL POPUP EDITAR CONTACTO DESDE EL POPUP CONTACTO
    $(".popup_overlay").on("click", ".cabecera_edit", function(e) {

        const index_contacto = $(".index_contacto_input").val();

        const selector_container = "#" + index_contacto;

        const line_container = $(selector_container);
        const genero = line_container.attr("gender");
        const icon_contacto = line_container.find(".elemento_nombre_foto").prop("src");
        const nombre_contacto = line_container.find(".elemento_nombre_text").text();
        function gender_selector(valor, comparacion){
            if (valor == comparacion) {
                return "activo";
            }else{
                return "";
            };
        };
        const email_contacto = line_container.find(".elemento_email").text();
        const telefono_contacto = line_container.find(".elemento_telefono_text").text();
        const whatsapp_contacto = ((line_container.find(".elemento_telefono_whatsapp").hasClass("activo")) ? "activo" : "");
        const info_contacto = line_container.find(".elemento_info").text();

        $(".popup_contenido").html(`
        
        <span class="contacto_tag_edicion">Edición Contacto</span>
        <div class="popup_cabecera_edit">

            <span class="popup_cabecera_titulo">
                <img src="${icon_contacto}" alt="Foto">
                <input type="text" placeholder="Nombre y Apellidos" class="nombre_contacto_input" value="${nombre_contacto}">
            </span>
            <span class="popup_cabecera_actions">
                <span class="cabecera_male ${gender_selector(genero, 'hombre')}"><i class="fa fa-male"></i></span>
                <span class="cabecera_female ${gender_selector(genero, 'mujer')}"><i class="fa fa-female"></i></span>
            </span>

        </div>

        <hr class="cabecera_line">

        <div class="popup_contacto_contenido">
            <div class="popup_contacto_email">
                <i class="fas fa-at"></i>
                <input type="text" placeholder="Email" class="email_contacto_input" value="${email_contacto}">
            </div>
            <div class="popup_contacto_telefono">
                <i class="fa fa-phone"></i>
                <input type="text" placeholder="Telefono (+XXX) XXXXXXX" class="telefono_contacto_input" value="${telefono_contacto}">
                <span class="btn_whatsapp_edicion ${whatsapp_contacto}">
                    <span class="fa-stack icon_stacks_whatsapp">
                        <i class="fab fa-whatsapp fa-stack-2x"></i>
                        <i class="fa fa-circle"></i>
                    </span>
                    <p>WhatsApp?</p>
                </span>
            </div>
            <div class="popup_contacto_info">
                <i class="fa fa-info"></i>
                <textarea name="" placeholder="Descripción...(opcional)" class="info_contacto_input">${info_contacto}</textarea>
            </div>

            <span class="error_wrap">Error de algun tipo</span>
            <span class="btn_guardar" modo="editar_contacto">Guardar Cambios</span>
            <input type="hidden" class="index_contacto_input" value="${index_contacto}">
        </div>

        `);
        $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
    });


    // CODIGO PARA LA ESTRELLITA DE DESTACADOS

    $(".agenda_contenido").on("click", ".elemento_star", function(e) {

        const line_container = $(this).parent().parent();
        const index_contacto = line_container.attr("id");
        let contacto_destacado;

        if($(this).hasClass("activo")){
            contacto_destacado = 0;
        }else{
            contacto_destacado = 1; 
        };

        $(this).toggleClass("activo");

        let valor_busqueda = $(".barra_busqueda_input").val();


        $.ajax({
            type: "POST",
            url: "process-request-libreta-contactos.php",
            data: { contacto_index_sent: index_contacto, action_sent : 'editar_contacto', contacto_destacado_sent : contacto_destacado, busqueda_valor_sent : valor_busqueda}
        }).done(function(data){
            $(".resultados_wrap").html(data);
        });

    });


    $(".popup_overlay").on("click", ".cabecera_star", function(e) {

        const index_contacto = $(".index_contacto_input").val();
        let contacto_destacado;

        if($(this).hasClass("activo")){
            contacto_destacado = 0;
            let new_src = $(".popup_cabecera_titulo img").prop("src").replace("gold", "blue");
            $(".popup_cabecera_titulo img").prop("src", new_src);
            $(".popup_overlay").find(".popup_cabecera_titulo img").p
        }else{
            contacto_destacado = 1; 
            let new_src = $(".popup_cabecera_titulo img").prop("src").replace("blue", "gold");
            $(".popup_cabecera_titulo img").prop("src", new_src);
        };

        let valor_busqueda = $(".barra_busqueda_input").val();


        $.ajax({
            type: "POST",
            url: "process-request-libreta-contactos.php",
            data: { contacto_index_sent: index_contacto, action_sent : 'editar_contacto', contacto_destacado_sent : contacto_destacado, busqueda_valor_sent : valor_busqueda}
        }).done(function(data){
            $(".resultados_wrap").html(data);
        });

        $(this).toggleClass("activo");
        

    });

    // CODIGO PARA LOS 3 DOSTS MENU MORE TABLA

    $(".agenda_contenido").on("click", ".elemento_more", function(e) {

        const posicion = $(this).offset();
        const id_fila = $(this).parent().parent().attr('id');

        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos")) {
            $(".opcion_compartir").addClass("activo");
        }else{
            $(".opcion_compartir").removeClass("activo");
        };

        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos") || $(".tab_agenda.activo").hasClass("tab_contactos_utiles")) {
            $(".opcion_eliminar").addClass("activo");
        }else{
            $(".opcion_eliminar").removeClass("activo");
        };

        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos") || $(".tab_agenda.activo").hasClass("tab_contactos_utiles") || $(".tab_agenda.activo").hasClass("tab_tu_agencia")) {
            $(".opcion_visita").addClass("activo");
        }else{
            $(".opcion_visita").removeClass("activo");
        };

        $(".menu_more").css("top", (posicion['top'] + 30)).css('left', (posicion['left'] - 80)).toggleClass("activo").attr("data", id_fila);


    });

    // CODIGO PARA LOS 3 DOSTS MENU MORE DEL POPUP CONTACTO

    $(".popup_overlay").on("click", ".cabecera_more", function(e) {

        const posicion = $(this).offset();
        const id_fila = $(".index_contacto_input").val();

        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos")) {
            $(".opcion_compartir").addClass("activo");
        }else{
            $(".opcion_compartir").removeClass("activo");
        };

        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos") || $(".tab_agenda.activo").hasClass("tab_contactos_utiles")) {
            $(".opcion_eliminar").addClass("activo");
        }else{
            $(".opcion_eliminar").removeClass("activo");
        };

        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos") || $(".tab_agenda.activo").hasClass("tab_contactos_utiles") || $(".tab_agenda.activo").hasClass("tab_tu_agencia")) {
            $(".opcion_visita").addClass("activo");
        }else{
            $(".opcion_visita").removeClass("activo");
        };

        $(".menu_more").css("top", (posicion['top'] + 30)).css('left', (posicion['left'] - 80)).toggleClass("activo").attr("data", id_fila);


    });

    // CODIGO PARA MANTENER el MENU MORE ABIERTO

    $(".agenda_contenido").on("mouseover", ".elemento_agenda", function() {

        $(this).find(".elemento_actions").addClass("activo");
        
        if ($(".menu_more").hasClass("activo")) {

            const id_menu_more = $(".menu_more").attr("data");

            if ($(this).attr("id") !== id_menu_more) {

                $(".menu_more").css("top", 0).css('left', 0).removeClass("activo");

            };

        };
    });

    $(".agenda_contenido").on("mouseleave", ".elemento_agenda", function() {

        $(this).find(".elemento_actions").removeClass("activo");

        if ($('.menu_more:hover').length == 0) {
            $(".menu_more").css("top", 0).css('left', 0).removeClass("activo");
        };

        
        
    });


    $(".menu_more").on("mouseover", function() {
        
        if ($(".menu_more").hasClass("activo")) {

            const id_menu_more = "#" + $(".menu_more").attr("data");

            $(id_menu_more).find(".elemento_actions").addClass("activo");

        };
    });

    $(".menu_more").on("mouseleave", function() {
        
        if ($(".menu_more").hasClass("activo")) {

            const id_menu_more = "#" + $(".menu_more").attr("data");

            $(id_menu_more).find(".elemento_actions").removeClass("activo");

        };
    });

    $(".agenda_wrap").on("mouseleave", function() {
        
        if ($(".menu_more").hasClass("activo")) {

            if ($('.menu_more:hover').length == 0) {
                $(".menu_more").removeClass("activo");
            };  

        };

    });

    // CODIGO PARA BORRAR CONTACTO

    $(".opcion_eliminar").on("click", function(e) {

        const id_menu_more = $(".menu_more").attr("data");
        let current_tab;

        if ($(".tab_agenda.activo").hasClass("tab_mis_contactos")) {
            
            current_tab = 'mis_contactos';

            $.ajax({
                type: "POST",
                url: "process-request-libreta-contactos.php",
                data: { contacto_index_sent: id_menu_more, action_sent : 'borrar_contacto', tab_sent : current_tab}
            }).done(function(data){
                $(".resultados_wrap").html(data);
                $(".menu_more").removeClass("activo");
                $(".popup_overlay").css('opacity', '0').css("visibility", "hidden");
            });

        }else if($(".tab_agenda.activo").hasClass("tab_contactos_utiles")){
            current_tab = 'contactos_utiles';

            if ($("#pais_select").length) {

                let pais_selected = $("#pais_select").find("option:selected").val();
                let agencia_selected = $("#agencia_select").find("option:selected").val();

                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { contacto_index_sent: id_menu_more, action_sent : 'borrar_contacto', tab_sent : current_tab, agencia_sent : agencia_selected, pais_sent : pais_selected}
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                    $(".menu_more").removeClass("activo");
                    $(".popup_overlay").css('opacity', '0').css("visibility", "hidden");
                });

            }else{

                let agencia_selected = $("#agencia_select").find("option:selected").val();

                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { contacto_index_sent: id_menu_more, action_sent : 'borrar_contacto', tab_sent : current_tab, agencia_sent : agencia_selected}
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                    $(".menu_more").removeClass("activo");
                    $(".popup_overlay").css('opacity', '0').css("visibility", "hidden");
                });

            };

        };


        

    });


    // CODIGO PARA ABRIR POPUP CONTACTO

    $(".agenda_contenido").on("click", ".elemento_agenda", function(e) {
        if ($(e.target).hasClass('excluded') == false){//click en toda la linea menos en los accions buttons

            const line_container = $(this);
            const icon_contacto = line_container.find(".elemento_nombre_foto").prop("src");
            const nombre_contacto = line_container.find(".elemento_nombre_text").text();
            
            const email_contacto = line_container.find(".elemento_email").text();
            const telefono_contacto = line_container.find(".elemento_telefono_text").text();
            const whatsapp_contacto = ((line_container.find(".elemento_telefono_whatsapp").hasClass("activo")) ? "activo" : "");
            const info_contacto = line_container.find(".elemento_info").text();
            const index_contacto = line_container.attr("id");
            const star_destacado = ((line_container.find(".elemento_star").hasClass("activo")) ? "activo" : "");

            function show_mail_button(email_val){
                if (email_val !== '') {
                  return 'activo';  
                }else{
                  return '';
                };
            };
            
            let current_tab = $(".tab_agenda.activo");

            if (current_tab.hasClass("tab_mis_contactos")) {

                $(".popup_contenido").html(`
            
                    <span class="contacto_tag">Contacto</span>
                    <div class="popup_cabecera">

                        <span class="popup_cabecera_titulo">
                            <img src="${icon_contacto}" alt="Foto">
                            <p>${nombre_contacto}</p>
                        </span>
                        <span class="popup_cabecera_actions">
                            <span class="cabecera_star ${star_destacado}"><i class="fa fa-star"></i></span>
                            <span class="cabecera_edit"><i class="fa fa-edit"></i></span>
                            <span class="cabecera_more"><i class="fas fa-ellipsis-v"></i></span>
                        </span>

                    </div>

                    <hr class="cabecera_line">

                    <div class="popup_contacto_contenido">
                        <div class="popup_contacto_email">
                            <i class="fas fa-at"></i>
                            <p>${email_contacto}</p>
                            <span class="btns_popup_wrap">
                                <a href='mailto: ${email_contacto}' class="popup_contacto_mail_btn ${show_mail_button(email_contacto)}">
                                    <i class="fa fa-envelope"></i>
                                    <p>Mail</p>
                                </a>
                            </span>
                        </div>
                        <div class="popup_contacto_telefono">
                            <i class="fa fa-phone"></i>
                            <p>${telefono_contacto}</p>
                            <span class="btns_popup_wrap">
                                <a class="popup_contacto_call_btn" href="tel:${getNumberFormat(telefono_contacto)}"><p>Llamar</p></a>
                                <a href="https://api.whatsapp.com/send?phone=${getNumberFormat(telefono_contacto)}" class="popup_contacto_whatsapp_btn ${whatsapp_contacto}" target="_blank">
                                    <span class="fa-stack icon_stacks_whatsapp">
                                        <i class="fab fa-whatsapp fa-stack-2x"></i>
                                        <i class="fa fa-circle"></i>
                                    </span>
                                    <p>WhatsApp</p>
                                </a>
                            </span>
                        </div>
                        <div class="popup_contacto_info">
                            <i class="fa fa-info"></i>
                            <p>${info_contacto}</p>
                        </div>
                        <input type="hidden" class="index_contacto_input" value="${index_contacto}">
                    </div>
                
                `);
                
            }else if(current_tab.hasClass("tab_tu_agencia")){

                $(".popup_contenido").html(`
            
                    <span class="contacto_tag">Contacto</span>
                    <div class="popup_cabecera">

                        <span class="popup_cabecera_titulo">
                            <img src="${icon_contacto}" alt="Foto">
                            <p>${nombre_contacto}</p>
                        </span>
                        <span class="popup_cabecera_actions">
                            <span class="cabecera_more"><i class="fas fa-ellipsis-v"></i></span>
                        </span>

                    </div>

                    <hr class="cabecera_line">

                    <div class="popup_contacto_contenido">
                        <div class="popup_contacto_email">
                            <i class="fas fa-at"></i>
                            <p>${email_contacto}</p>
                            <span class="btns_popup_wrap">
                                <a href='mailto: ${email_contacto}' class="popup_contacto_mail_btn  ${show_mail_button(email_contacto)}">
                                    <i class="fa fa-envelope"></i>
                                    <p>Mail</p>
                                </a>
                                <span class="popup_contacto_mail_tutecho_btn">
                                    <img src="../../objetos/icono_tutecho.svg" alt="Tutecho">
                                    <p>Interno</p>
                                </span>
                            </span>
                        </div>
                        <div class="popup_contacto_telefono">
                            <i class="fa fa-phone"></i>
                            <p>${telefono_contacto}</p>
                            <span class="btns_popup_wrap">
                                <a class="popup_contacto_call_btn" href="tel:${getNumberFormat(telefono_contacto)}"><p>Llamar</p></a>
                                <a class="popup_contacto_whatsapp_btn ${whatsapp_contacto}" href="https://api.whatsapp.com/send?phone=${getNumberFormat(telefono_contacto)}" target="_blank" >
                                    <span class="fa-stack icon_stacks_whatsapp">
                                        <i class="fab fa-whatsapp fa-stack-2x"></i>
                                        <i class="fa fa-circle"></i>
                                    </span>
                                    <p>WhatsApp</p>
                                </a>
                            </span>
                        </div>
                        <div class="popup_contacto_info">
                            <i class="fa fa-info"></i>
                            <p>${info_contacto}</p>
                        </div>
                        <input type="hidden" class="index_contacto_input" value="${index_contacto}">
                    </div>
                
                `);                

            }else if(current_tab.hasClass("tab_contactos_utiles")){

                $(".popup_contenido").html(`
            
                    <span class="contacto_tag">Contacto</span>
                    <div class="popup_cabecera">

                        <span class="popup_cabecera_titulo">
                            <img src="${icon_contacto}" alt="Foto">
                            <p>${nombre_contacto}</p>
                        </span>
                        <span class="popup_cabecera_actions">
                            <span class="cabecera_edit"><i class="fa fa-edit"></i></span>
                            <span class="cabecera_more"><i class="fas fa-ellipsis-v"></i></span>
                        </span>

                    </div>

                    <hr class="cabecera_line">

                    <div class="popup_contacto_contenido">
                        <div class="popup_contacto_email">
                            <i class="fas fa-at"></i>
                            <p>${email_contacto}</p>
                            <span class="btns_popup_wrap">
                                <a href='mailto: ${email_contacto}' class="popup_contacto_mail_btn  ${show_mail_button(email_contacto)}">
                                    <i class="fa fa-envelope"></i>
                                    <p>Mail</p>
                                </a>
                            </span>
                        </div>
                        <div class="popup_contacto_telefono">
                            <i class="fa fa-phone"></i>
                            <p>${telefono_contacto}</p>
                            <span class="btns_popup_wrap">
                                <a class="popup_contacto_call_btn" href="tel:${getNumberFormat(telefono_contacto)}"><p>Llamar</p></a>
                                <a class="popup_contacto_whatsapp_btn ${whatsapp_contacto}" href="https://api.whatsapp.com/send?phone=${getNumberFormat(telefono_contacto)} "target="_blank">
                                    <span class="fa-stack icon_stacks_whatsapp">
                                        <i class="fab fa-whatsapp fa-stack-2x"></i>
                                        <i class="fa fa-circle"></i>
                                    </span>
                                    <p>WhatsApp</p>
                                </a>
                            </span>
                        </div>
                        <div class="popup_contacto_info">
                            <i class="fa fa-info"></i>
                            <p>${info_contacto}</p>
                        </div>
                        <input type="hidden" class="index_contacto_input" value="${index_contacto}">
                    </div>
                
                `);


            }else if(current_tab.hasClass("tab_hash")){



                if (line_container.hasClass("registrador")) {

                    $(".popup_contenido").html(`
            
                        <span class="contacto_tag">Contacto</span>
                        <div class="popup_cabecera">

                            <span class="popup_cabecera_titulo">
                                <img src="${icon_contacto}" alt="Foto">
                                <p>${nombre_contacto}</p>
                            </span>
                            

                        </div>

                        <hr class="cabecera_line">

                        <div class="popup_contacto_contenido">
                            <div class="popup_contacto_email">
                                <i class="fas fa-at"></i>
                                <p>${email_contacto}</p>
                                <span class="btns_popup_wrap">
                                    <a href='mailto: ${email_contacto}' class="popup_contacto_mail_btn  ${show_mail_button(email_contacto)}">
                                        <i class="fa fa-envelope"></i>
                                        <p>Mail</p>
                                    </a>
                                    <span class="popup_contacto_mail_tutecho_btn">
                                        <img src="../../objetos/icono_tutecho.svg" alt="Tutecho">
                                        <p>Interno</p>
                                    </span>
                                </span>
                            </div>
                            <div class="popup_contacto_telefono">
                                <i class="fa fa-phone"></i>
                                <p>${telefono_contacto}</p>
                                <span class="btns_popup_wrap">
                                    <a class="popup_contacto_call_btn" href="tel:${getNumberFormat(telefono_contacto)}"><p>Llamar</p></a>
                                    <a class="popup_contacto_whatsapp_btn ${whatsapp_contacto}" href="https://api.whatsapp.com/send?phone=${getNumberFormat(telefono_contacto)} "target="_blank">
                                        <span class="fa-stack icon_stacks_whatsapp">
                                            <i class="fab fa-whatsapp fa-stack-2x"></i>
                                            <i class="fa fa-circle"></i>
                                        </span>
                                        <p>WhatsApp</p>
                                    </a>
                                </span>
                            </div>
                            <div class="popup_contacto_info">
                                <i class="fa fa-info"></i>
                                <p>${info_contacto}</p>
                            </div>
                            <input type="hidden" class="index_contacto_input" value="${index_contacto}">
                        </div>
                    
                    `); 

                }else{

                    $(".popup_contenido").html(`
            
                        <span class="contacto_tag">Contacto</span>
                        <div class="popup_cabecera">

                            <span class="popup_cabecera_titulo">
                                <img src="${icon_contacto}" alt="Foto">
                                <p>${nombre_contacto}</p>
                            </span>
                            

                        </div>

                        <hr class="cabecera_line">

                        <div class="popup_contacto_contenido">
                            <div class="popup_contacto_email">
                                <i class="fas fa-at"></i>
                                <p>${email_contacto}</p>
                                <span class="btns_popup_wrap">
                                    <a href='mailto: ${email_contacto}' class="popup_contacto_mail_btn  ${show_mail_button(email_contacto)}">
                                        <i class="fa fa-envelope"></i>
                                        <p>Mail</p>
                                    </a>
                                </span>
                            </div>
                            <div class="popup_contacto_telefono">
                                <i class="fa fa-phone"></i>
                                <p>${telefono_contacto}</p>
                                <span class="btns_popup_wrap">
                                    <a class="popup_contacto_call_btn" href="tel:${getNumberFormat(telefono_contacto)}"><p>Llamar</p></a>
                                    <a class="popup_contacto_whatsapp_btn ${whatsapp_contacto}" href="https://api.whatsapp.com/send?phone=${getNumberFormat(telefono_contacto)} "target="_blank">
                                        <span class="fa-stack icon_stacks_whatsapp">
                                            <i class="fab fa-whatsapp fa-stack-2x"></i>
                                            <i class="fa fa-circle"></i>
                                        </span>
                                        <p>WhatsApp</p>
                                    </a>
                                </span>
                            </div>
                            <div class="popup_contacto_info">
                                <i class="fa fa-info"></i>
                                <p>${info_contacto}</p>
                            </div>
                            <input type="hidden" class="index_contacto_input" value="${index_contacto}">
                        </div>
                    
                    `);      
                };

                
            }else if(current_tab.hasClass("tab_agente")){

                $(".popup_contenido").html(`
            
                <span class="contacto_tag">Contacto</span>
                <div class="popup_cabecera">

                    <span class="popup_cabecera_titulo">
                        <img src="${icon_contacto}" alt="Foto">
                        <p>${nombre_contacto}</p>
                    </span>

                </div>

                <hr class="cabecera_line">

                <div class="popup_contacto_contenido">
                    <div class="popup_contacto_email">
                        <i class="fas fa-at"></i>
                        <p>${email_contacto}</p>
                        <span class="btns_popup_wrap">
                            <a href='mailto: ${email_contacto}' class="popup_contacto_mail_btn ${show_mail_button(email_contacto)}">
                                <i class="fa fa-envelope"></i>
                                <p>Mail</p>
                            </a>
                        </span>
                    </div>
                    <div class="popup_contacto_telefono">
                        <i class="fa fa-phone"></i>
                        <p>${telefono_contacto}</p>
                        <span class="btns_popup_wrap">
                            <a class="popup_contacto_call_btn" href="tel:${getNumberFormat(telefono_contacto)}"><p>Llamar</p></a>
                            <a class="popup_contacto_whatsapp_btn ${whatsapp_contacto}" href="https://api.whatsapp.com/send?phone=${getNumberFormat(telefono_contacto)} "target="_blank">
                                <span class="fa-stack icon_stacks_whatsapp">
                                    <i class="fab fa-whatsapp fa-stack-2x"></i>
                                    <i class="fa fa-circle"></i>
                                </span>
                                <p>WhatsApp</p>
                            </a>
                        </span>
                    </div>
                    <div class="popup_contacto_info">
                        <i class="fa fa-info"></i>
                        <p>${info_contacto}</p>
                    </div>
                    <input type="hidden" class="index_contacto_input" value="${index_contacto}">
                </div>
            
            `);

            };
               

            

            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
        };
    })


    // CODIGO PARA LA BUSQUEDA DE CONTACTOS

    $(".barra_busqueda_input").on("input", function(){

        const valor_busqueda = $(this).val();

        $.ajax({
            type: "POST",
            url: "process-request-libreta-contactos.php",
            data: { action_sent : 'busqueda_contacto', busqueda_valor_sent : valor_busqueda}
        }).done(function(data){
            $(".resultados_wrap").html(data);
        });

 
    });


    // CODIGO PARA EL TAB TUS CONTACTOS

    $(".tab_mis_contactos").on("click", function(){

        $(".barra_selects_agenda_wrap").css("visibility", "hidden");
        $(".barra_busqueda_wrap").css("visibility", "unset");
        $(".barra_selects_hash_inmuebles_wrap").css("visibility", "hidden");
        $(".agregar_btn").addClass("activo");
        $(".barra_selects_agente_wrap").css("visibility", 'hidden');
        
            $.ajax({
                type: "POST",
                url: "process-request-libreta-contactos.php",
                data: { action_sent : 'ver_contactos' }
            }).done(function(data){
                $(".resultados_wrap").html(data);
            });
        
    });


    // CODIGO PARA EL TAB TU AGENCIA

    $(".tab_tu_agencia").on("click", function(){

        $(".barra_busqueda_wrap").css("visibility", "hidden");
        $(".barra_selects_agenda_wrap").css("visibility", "unset");
        $(".barra_selects_hash_inmuebles_wrap").css("visibility", "hidden");
        $(".agregar_btn").removeClass("activo");
        $(".barra_selects_agente_wrap").css("visibility", 'hidden');

        if ($("#pais_select").length) {
            let pais_selected = $("#pais_select").find("option:selected").val();
            let agencia_selected = $("#agencia_select").find("option:selected").val();

            if (agencia_selected !== '') {
                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : 'ver_contactos_agencia', agencia_sent : agencia_selected, pais_sent : pais_selected }
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                });
            }else{
                $(".resultados_wrap").empty();
            };
        }else{

            let agencia_selected = $("#agencia_select").find("option:selected").val();

            if (agencia_selected !== '') {
                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : 'ver_contactos_agencia', agencia_sent : agencia_selected }
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                });
            }else{
                $(".resultados_wrap").empty();
            };;

        };
 
            
        
    });

    // CODIGO PARA POBLAR SELECT AGENCIAS SEGUN PAIS

    if($("#pais_select").length){

        $("#pais_select").on("change", function(){

            let pais_selected = $(this).find("option:selected").val();

            if (pais_selected == '') {
                
                $("#agencia_select").empty().prop("disabled", true);

            }else{

                $.ajax({
                    type: "POST",
                    url: "process-request-agencias-pais.php",
                    data: { paisChoice : pais_selected }
                }).done(function(data){
                    $("#agencia_select").html(data).prop("disabled", false);
                }); 

            };

        });

    };

     // CODIGO PARA EL TAB CONTACTOS UTILES

     $(".tab_contactos_utiles").on("click", function(){

        $(".barra_busqueda_wrap").css("visibility", "hidden");
        $(".barra_selects_agenda_wrap").css("visibility", "unset");
        $(".barra_selects_hash_inmuebles_wrap").css("visibility", "hidden");
        $(".agregar_btn").addClass("activo");
        $(".barra_selects_agente_wrap").css("visibility", 'hidden');

        if ($("#pais_select").length) {
            let pais_selected = $("#pais_select").find("option:selected").val();
            let agencia_selected = $("#agencia_select").find("option:selected").val();

            if (agencia_selected !== '') {
                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : 'ver_contactos_utiles', agencia_sent : agencia_selected, pais_sent : pais_selected }
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                });
            }else{
                $(".resultados_wrap").empty();
            };
        }else{

            let agencia_selected = $("#agencia_select").find("option:selected").val();

            if (agencia_selected !== '') {
                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : 'ver_contactos_utiles', agencia_sent : agencia_selected }
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                });
            }else{
                $(".resultados_wrap").empty();
            };;

        };

     });



     // CODIGO PARA TRAER CONTACTOS DE AGENCIA SEGUN AGENCIA ESPECIFICADA

    $("#agencia_select").on("change", function(){

        let agencia_selected = $(this).find("option:selected").val();

        let current_tab = $(".tab_agenda.activo");
        let action_mode;

        if (current_tab.hasClass("tab_tu_agencia")) {
            action_mode = "ver_contactos_agencia";
        }else if(current_tab.hasClass("tab_contactos_utiles")){
            action_mode = "ver_contactos_utiles";
        };

        if (agencia_selected !== "") {
            if ($("#pais_select").length) {//si hay pais que especificar

                let pais_selected = $("#pais_select option:selected").val();
    
                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : action_mode, agencia_sent : agencia_selected, pais_sent : pais_selected }
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                });
    
            }else{//si NO hay pais que especificar
    
                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : action_mode, agencia_sent : agencia_selected }
                }).done(function(data){
                    $(".resultados_wrap").html(data);
                });
    
            };
        }else{
            $(".resultados_wrap").empty();
        };

        


    });


    
    // CODIGO PARA EL TAB HASHTAG

    $(".tab_hash").on("click", function(){

        $(".barra_busqueda_wrap").css("visibility", "hidden");
        $(".barra_selects_agenda_wrap").css("visibility", "hidden");
        $(".agregar_btn").removeClass("activo");
        $(".barra_selects_hash_inmuebles_wrap").css("visibility", "unset");
        $(".barra_selects_agente_wrap").css("visibility", 'hidden');

        $(".resultados_wrap").empty();

    });

    if ($("#referencia").length) {
        $("#referencia").on("input", function(){
            if ($(this).val().match(/^[\w\d\s -/+_#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
                if ($(this).val() !== '') {
                    $(this).addClass("borde_rojo");
                };
              } else {
                $(this).removeClass("borde_rojo");
              };
        });
    };


    // CODIGO PARA BUSQUEDA DE BIENES EN EL TAB HASHTAG

    $(".buscar_referencia_btn").on("click", function(){

        let referencia = $("#referencia").val();
        if (referencia !== '') {
            if ($("#referencia").hasClass("borde_rojo")) {
                $(".popup_contenido").html(`Caracteres NO permitidos en Referencia`);
                $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
            }else{
                if ($("#pais_select_referencia").length) {

                    let pais_selected = $("#pais_select_referencia").val();
    
                    if (pais_selected !== '') {
                        $.ajax({
                            type: "POST",
                            url: "process-request-libreta-contactos.php",
                            data: { action_sent : 'referencia_search', referencia_sent : referencia, pais_sent :  pais_selected}
                        }).done(function(data){
                            $(".resultados_wrap").html(data);
                        });
                    }else{
    
                        $(".popup_contenido").html(`Debe ingresar un Pais`);
                        $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                    };
        
                }else{
        
                    $.ajax({
                        type: "POST",
                        url: "process-request-libreta-contactos.php",
                        data: { action_sent : 'referencia_search', referencia_sent : referencia }
                    }).done(function(data){
                        $(".resultados_wrap").html(data);
                    });
        
                };
            };
            
        }else{
            $(".popup_contenido").html(`Debe ingresar una #Referencia`);
            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
        };
        

    });


    // CODIGO TAB USER AGENTE

    $(".tab_agente").on("click", function(){
        $(".barra_busqueda_wrap").css("visibility", "hidden");
        $(".barra_selects_agenda_wrap").css("visibility", "hidden");
        $(".agregar_btn").removeClass("activo");
        $(".barra_selects_hash_inmuebles_wrap").css("visibility", "hidden");
        $(".barra_selects_agente_wrap").css("visibility", 'unset');

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

    // CODIGO PARA BUSCAR LSITA CONTACTOS DE UN AGENTE

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
                            url: "process-request-libreta-contactos.php",
                            data: { action_sent : 'agente_contactos_search', id_sent : agente_id, pais_sent :  pais_selected}
                        }).done(function(data){
                            $(".resultados_wrap").html(data);
                        });
                    }else{
    
                        $(".popup_contenido").html(`Debe ingresar un Pais`);
                        $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
                    };
        
                }else{
        
                    $.ajax({
                        type: "POST",
                        url: "process-request-libreta-contactos.php",
                        data: { action_sent : 'agente_contactos_search', id_sent : agente_id }
                    }).done(function(data){
                        $(".resultados_wrap").html(data);
                    });
        
                };
            };
            
        }else{
            $(".popup_contenido").html(`Debe ingresar un Id de Agente`);
            $(".popup_overlay").css('opacity', '1').css("visibility", "unset");
        };
        

    });


    // CODIGO ABRIR POPUP COMPARTIR

    $(".opcion_compartir").on("click", function(e) {

        const id_menu_more = "#" + $(".menu_more").attr("data");
        const id_line = $(".menu_more").attr("data");
        const line_container = $(id_menu_more);
        const icon_contacto = line_container.find(".elemento_nombre_foto").prop("src");
        const nombre_contacto = line_container.find(".elemento_nombre_text").text();

        $(".popup_contenido").html(`
            
            <span class="contacto_tag">Compartir</span>

            <div class="contacto_compartir_tag">

                <img src="${icon_contacto}" alt="Foto">
                <p>${nombre_contacto}</p>
                
            </div>

            <input type="hidden" name="index_compartir" id="index_compartir" value="${id_line}">

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

        if (destinatario_id == '' || $("#compartir_input").hasClass("borde_rojo")) {
            $(".error_wrap").html(`Ingrese los datos correctamente`).css("visibility", "unset");
        }else{

            $.ajax({
                type: "POST",
                url: "process-request-libreta-contactos.php",
                data: { action_sent : 'compartir_contacto', index_sent : index_compartir, destinatario_sent : destinatario_id}
            }).done(function(data){
                if (data == 'error') {
                    $(".error_wrap").html(`ID:Agente NO existe`).css("visibility", "unset");
                }else if(data == 'exito'){
                    $(".popup_contenido").html(`<h2 class="mensage_success">Contacto compartido Exitosamente</h2>`);
                };

            });

        }; 

    });

    // CODIGO ABRIR POPUP AGREGAR A VISITA

    $(".opcion_visita").on("click", function(e) {

        const id_menu_more = "#" + $(".menu_more").attr("data");
        const id_line = $(".menu_more").attr("data");
        const line_container = $(id_menu_more);
        const icon_contacto = line_container.find(".elemento_nombre_foto").prop("src");
        const nombre_contacto = line_container.find(".elemento_nombre_text").text();

        $(".popup_contenido").html(`
            
            <span class="contacto_tag">+ Visita</span>

            <div class="contacto_compartir_tag">

                <img src="${icon_contacto}" alt="Foto">
                <p>${nombre_contacto}</p>
                
            </div>

            <input type="hidden" name="index_compartir" id="index_compartir" value="${id_line}">

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

    // CODIGO PARA PODER MADAR CONTACTO A FICHA VISITA

    $(".popup_overlay").on("click", ".enviar_visita_btn", function() {

        const index_visita = $(".visita_select option:selected").attr("key");
        const agencia_tag_visita = $(".visita_select option:selected").attr("agencia_tag");
        const referencia_visita = $(".visita_select option:selected").attr("referencia");
        const index_contacto = $("#index_compartir").val();
        const tipo_contacto = $(".tab_agenda.activo").attr("tipo");

        if (index_visita == '' || agencia_tag_visita == '' || referencia_visita == '' || index_contacto == '' || tipo_contacto == '') {
            $(".error_wrap").html(`Visita contiene Errores`).css("visibility", "unset");
        }else{

            $.ajax({
                type: "POST",
                url: "process-request-libreta-contactos.php",
                data: { action_sent : 'agregar_a_visita', index_sent : index_visita, agencia_tag_sent : agencia_tag_visita, referencia_sent : referencia_visita, index_contacto_sent : index_contacto, tipo_contacto_sent : tipo_contacto}
            }).done(function(data){
                if (data == 'error') {
                    $(".error_wrap").html(`Error de Formulario`).css("visibility", "unset");
                }else if(data == 'exito'){
                    $(".popup_contenido").html(`<h2 class="mensage_success">Contacto Agregado a Visita Exitosamente</h2>`);
                };

                

            });

        }; 

    });


    // CODIGO PARA EL POPUP MENSAGE INTERNO

    $(".popup_overlay").on("click", ".popup_contacto_mail_tutecho_btn", function() {

        const agente_id = $(".index_contacto_input").val();
        const id_selector = "#" + agente_id;
        const line_container = $(id_selector);
        const icon_contacto = line_container.find(".elemento_nombre_foto").prop("src");
        const nombre_contacto = line_container.find(".elemento_nombre_text").text();

        $(".popup_contenido").html(`
            <span class="contacto_tag">Mensaje</span>


            <div class="contacto_compartir_tag">

                <p style="margin-right: 1.5em">A: </p>
                <img src="${icon_contacto}" alt="Foto">
                <p>${nombre_contacto}</p>
                
            </div>


            <input type="hidden" name="index_compartir" id="index_enviar_mensaje" value="${agente_id}">

            <label for="mensaje_interno_text" class="mensaje_interno_label">Mensage: </label>

            <textarea name="mensaje_interno_text" id="mensaje_interno_text" class="mensaje_interno_text"></textarea>
            
            <span class="error_wrap"></span>

            <span class="enviar_mensaje_btn">Enviar</span>
        
        `);

        $(".popup_overlay").on("input", "#mensaje_interno_text", function(){
            if ($(this).val().match(/^[\w\d\s -/+@_.,?¡¿*$€#!#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
                if ($(this).val() !== '') {
                    $(this).addClass("borde_rojo");
                    $(".error_wrap").html("Sólo use caracteres permitidos").css("visibility", "unset");
                };
              } else {
                $(this).removeClass("borde_rojo");
                $(".error_wrap").html("").css("visibility", "hidden");
              };
        });


    });


    // CODIGO PARA ENVIAR MENSAJES INTERNOS A AGENTES

    $(".popup_overlay").on("click", ".enviar_mensaje_btn", function() {

        const agente_id = $("#index_enviar_mensaje").val();
        const mensaje = $("#mensaje_interno_text").val();
        


        if (mensaje == '' || $("#mensaje_interno_text").hasClass("borde_rojo")) {
            $(".error_wrap").html(`Ingrese los datos correctamente`).css("visibility", "unset");
        }else{

            let pais_selected = '';

            if ($(".tab_agenda.activo").hasClass('tab_tu_agencia')) {
                if ($("#pais_select").length) {
                    pais_selected = $("#pais_select option:selected").val();
                };
            }else if($(".tab_agenda.activo").hasClass('tab_hash')){
                if ($("#pais_select_referencia").length) {
                    pais_selected = $("#pais_select_referencia option:selected").val();
                };
            };

            if (pais_selected == '') {

                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : 'mensaje_interno', agente_id_sent : agente_id, mensaje_sent : mensaje}
                }).done(function(data){
                    if (data == 'error') {
                        $(".error_wrap").html(`Hubo un Error`).css("visibility", "unset");
                    }else if(data == 'exito'){
                        $(".popup_contenido").html(`<h2 class="mensage_success">Mensaje enviado Exitosamente</h2>`);
                    };
    
                });

            }else{

                $.ajax({
                    type: "POST",
                    url: "process-request-libreta-contactos.php",
                    data: { action_sent : 'mensaje_interno', agente_id_sent : agente_id, mensaje_sent : mensaje, pais_sent : pais_selected}
                }).done(function(data){
                    console.log(data);
                    if (data == 'error') {
                        $(".error_wrap").html(`Hubo un Error`).css("visibility", "unset");
                    }else if(data == 'exito'){
                        $(".popup_contenido").html(`<h2 class="mensage_success">Mensaje enviado Exitosamente</h2>`);
                    };
    
                });

            }

            

        }; 

    });

   });

});
