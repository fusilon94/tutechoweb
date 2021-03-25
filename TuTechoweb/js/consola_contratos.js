$(document).ready(function(){
  jQuery(function($){

    $(".enviar_btn").on("click", function(){
        let tipo_contrato = $("#tipo_select option:selected").val();
        let pais_contrato = $("#pais_select option:selected").val();

        if (tipo_contrato !== "" && pais_contrato !== "") {
            $("#formulario_contratos_entry").submit();
        }else{
            $(".popup_content").html("Por Favor, rellene todos los datos.");
            $(".popup_overlay").css("visibility", "unset");
        };
    });

    $(".popup_cerrar").on("click", function(){
        $(".popup_overlay").css("visibility", "hidden");
    });


  });
});
