        // ### FUNCIONES GLOBAL SCOPE
        // Funcion que rellena el input y scrollea al lugar. Recibe 2 argumentos: pregunta(id), espacio(class)
        function focus_fill_input(pregunta, espacio){
            $(pregunta).on("input", function(){
                $(espacio).html($(pregunta).val()).trigger("change");
                
                if ($(pregunta).val() == "") {
                    $(espacio).html("______").trigger("change");
                };
            }).on("focusin", function(){   
                $(".contrato_contenedor").scrollTop(0);           

                let t = $(".contrato_contenedor").offset().top;
                
                $(".contrato_contenedor").scrollTop($(espacio).offset().top - t - 100);
                            
                $(espacio).addClass('focused');
            }).on("focusout", function(){
                $(espacio).removeClass('focused'); 
            });
        };

        function focus_fill_select(pregunta, espacio){
            $(pregunta).on("change", function(){
                const selector = pregunta + " option:selected";
                $(espacio).html($(selector).val()).trigger("change");
                
                if ($(pregunta).val() == "") {
                    $(espacio).html("______").trigger("change");
                };
            }).on("focusin", function(){              
                $(".contrato_contenedor").scrollTop(0);           

                let t = $(".contrato_contenedor").offset().top;
                
                $(".contrato_contenedor").scrollTop($(espacio).offset().top - t - 100);             
                $(espacio).addClass('focused');
            }).on("focusout", function(){
                $(espacio).removeClass('focused');
            });
        };

        function auto_grow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight + 8)+"px";
            }
        
        

jQuery(function($){

$(".contrato_contenedor").on("change", "span.completar", function(){
    const total = $(".contrato_contenedor span.completar").length;
    let avance = 0;
    $(".contrato_contenedor span.completar").each(function(){
            if ($(this).text() !== "______") {
                avance += 1;
            };
    });

    let progreso = (avance / total) * 100;

    $(".progreso").css("width", `calc(${progreso}% - 0.6em)`);
    $(".progreso_num").html(progreso.toFixed(0));

    if (progreso.toFixed(0) == 100) {
        $(".btn_imprimir").addClass("active");
    } else {
        $(".btn_imprimir").removeClass("active");
    };

});

$(".btn_imprimir").on("click", function(){
    if ($(this).hasClass("active")) {
        $(".overlay").css("visibility", "unset"); 
    };
    
});

$(".popup_cerrar").on("click", function(){
    $(".overlay").css("visibility", "hidden");
});



$(".paso_anterior").hover(function(){
    $(".paso_btn_icon_left").toggleClass("hovered", 300);
});

$(".paso_adelante").hover(function(){
    $(".paso_btn_icon_right").toggleClass("hovered", 300);
});

$(".paso_adelante").on("click", function(){
    
    let slide_jump = "-" + (etapa_actual * 100) + "%";
    $(".etapas_wrap").css("margin-left", slide_jump);

    etapa_actual += 1;

    if (etapa_actual > 1) {
        $(".paso_anterior").css("visibility", "unset");
    };
    if (etapa_actual == preguntas_grupos_cantidad) {
        $(".paso_adelante").css("visibility", "hidden");
    };
});

$(".paso_anterior").on("click", function(){
    
    let slide_jump = "-" + ((etapa_actual - 2) * 100) + "%";
    $(".etapas_wrap").css("margin-left", slide_jump);

    etapa_actual -= 1;

    if (etapa_actual == 1) {
        $(".paso_anterior").css("visibility", "hidden");
    };
    if (etapa_actual < preguntas_grupos_cantidad) {
        $(".paso_adelante").css("visibility", "unset");
    };

    
});

});

