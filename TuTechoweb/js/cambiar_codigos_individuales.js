$(document).ready(function(){
   jQuery(function($){

    $(".codigo_actual").on("input", function(){
        if ($(this).val().match(/^[\w\d\s#&@\']+$/) == null) {//Si se ingrso un caracter no permitido
            $(this).addClass("borde_rojo");
            alert('Simbolo/Caracter no permitido');
          } else {
              $(this).removeClass("borde_rojo");
          };
    });

    $(".new_codigo").on("input", function(){
        if ($(this).val().match(/^[\w\d\s#&@\']+$/) == null) {//Si se ingrso un caracter no permitido
            $(this).addClass("borde_rojo");
            alert('Simbolo/Caracter no permitido');
          } else {
              $(this).removeClass("borde_rojo");
          };
    });

    $(".new_codigo2").on("input", function(){
        if ($(this).val().match(/^[\w\d\s#&@\']+$/) == null) {//Si se ingrso un caracter no permitido
            $(this).addClass("borde_rojo");
            alert('Simbolo/Caracter no permitido');
          } else {
              $(this).removeClass("borde_rojo");
          };
    });


    $(".btn_cambiar_codigos").on("click", function(){

        if ($(".codigo_actual").hasClass("borde_rojo") || $(".new_codigo").hasClass("borde_rojo") || $(".new_codigo2").hasClass("borde_rojo")) {
            
            $(".popup_success_text").html("Datos incorrectos, por favor use sólo caracteres permitidos");
            $(".popup_success").css("visibility", "unset");

        }else{
            
            if ($(".codigo_actual").val() == '' || $(".new_codigo").val() == '' || $(".new_codigo2").val() == '') {

                $(".popup_success_text").html("Deben completarse todos los datos");
                $(".popup_success").css("visibility", "unset");

            }else{

                let codigo_actual = $(".codigo_actual").val();
                let new_codigo = $(".new_codigo").val();
                let new_codigo2 = $(".new_codigo2").val();

                if (codigo_actual == new_codigo) {

                    $(".popup_success_text").html("La nueva contraseña debe ser distinta de la actual");
                    $(".popup_success").css("visibility", "unset");

                }else{
                    if(new_codigo !== new_codigo2){

                        $(".popup_success_text").html("Las nuevas contraseñas no son iguales");
                        $(".popup_success").css("visibility", "unset");


                    }else{

                        if (new_codigo.length < 6) {

                            $(".popup_success_text").html("La nueva contraseña debe ser de almenos 6 caracteres");
                            $(".popup_success").css("visibility", "unset");

                        }else{
                            if (/[0-9]+/.test(new_codigo) !== true) {

                                $(".popup_success_text").html("La nueva contraseña debe tener almenos un digito numérico");
                                $(".popup_success").css("visibility", "unset");

                            }else{

                                if (/[A-Z]+/.test(new_codigo) !== true) {

                                    $(".popup_success_text").html("La nueva contraseña debe tener almenos una letra mayuscula");
                                    $(".popup_success").css("visibility", "unset");

                                }else{

                                    $.ajax({
                                        type: "POST",
                                        url: "process-request-cambiar-codigos-individual.php",
                                        data: { codigo_actual_sent : codigo_actual, new_codigo_sent : new_codigo }
                                    }).done(function(data){
                                        $(".popup_success_text").html(data);
                                        $(".popup_success").css("visibility", "unset");
                                        $(".codigo_actual").val('');//reset
                                        $(".new_codigo").val('');//reset
                                        $(".new_codigo2").val('');//reset
                                    });

                                };

                                

                            };
                        };

                        

                    };
                };
                
            };

        };

    });



    $(".popup_success_cerrar").on("click", function(){
        $(".popup_success").css("visibility", "hidden");
    });


    $(".sin_codigos").on("click", function(){
        $(".popup_success_text").html("Contactese con el Administrador de la Web para solucionar su problema");
        $(".popup_success").css("visibility", "unset");
    });


    


   })

});
