$(document).ready(function(){
   jQuery(function($){

    $(".agente_id").on("input", function(){
        if ($(this).val().match(/^[\w\d\s#\']+$/) == null) {//Si se ingrso un caracter no permitido
            $(this).addClass("borde_rojo");
            alert('Simbolo/Caracter no permitido');
          } else {
              $(this).removeClass("borde_rojo");
          };
    });

    $(".agente_telefono").on("input", function(){
        if ($(this).val().match(/^[+\-0-9().# \/]+$/g) == null) {//Si se ingrso un caracter no permitido
            $(this).addClass("borde_rojo");
            alert('Simbolo/Caracter no permitido');
          } else {
              $(this).removeClass("borde_rojo");
          };
    });


    $(".habilitar_btn").on("click", function(){

        if ($(".agente_id").hasClass("borde_rojo") || $(".agente_telefono").hasClass("borde_rojo")) {
            alert("Datos incorrectos, por favor use sólo caracteres permitidos");
        }else{
            
            if ($(".agente_id").val() == '' || $(".agente_telefono").val() == '') {
                alert("Deben completarse todos los datos");
            }else{
                let agente_id = $(".agente_id").val();
                let agente_telefono = $(".agente_telefono").val();

                $.ajax({
                    type: "POST",
                    url: "process-request-habilitar_agente.php",
                    data: { agente_id_sent : agente_id, agente_telefono_sent : agente_telefono }
                }).done(function(data){
                    $(".popup_success_text").html(data);
                    $(".popup_success").css("visibility", "unset");
                });
            };

        };

    });

    $(".popup_success_cerrar").on("click", function(){
        $(".popup_success").css("visibility", "hidden");
    });


   })

});
