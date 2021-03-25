$(document).ready(function(){
  jQuery(function($){

    $(".enviar_btn").on("click", function(){
        let tipo_file = $("#tipo_file_select option:selected").val();
        let pais_file = $("#pais_select option:selected").val();
        let id_file = $("#id_file").val();
        let id_agente = $("#id_agente").val();
        let borrador = 0;
        if ($("#borrador").prop('disabled') == false && $("#borrador").is(":checked") == true) {
            borrador = 1;
        };

        if (tipo_file !== "" && pais_file !== "" && id_file !== "" && id_agente !== "") {

            $.ajax({
                type: "POST",
                url: "process-request-id-check-file.php",
                data: { pais_sent : pais_file, tipo_file_sent : tipo_file, id_file_sent : id_file, id_agente_autorizacion : id_agente, borrador_sent : borrador },
            }).done(function(data){
                if (data == 'exito') {
                 $("#formulario_contratos_entry").submit();  
                }else{
                    $(".popup_content").html(data);
                    $(".popup_overlay").css("visibility", "unset");  
                };
            });

            
        }else{
            $(".popup_content").html("Por Favor, rellene todos los datos.");
            $(".popup_overlay").css("visibility", "unset");
        };
    });

    $(".popup_cerrar").on("click", function(){
        $(".popup_overlay").css("visibility", "hidden");
    });

    $(".tipo_file_select").on("change", function(){
      let tipo_file = $("#tipo_file_select option:selected").val();

      if (tipo_file == "inmueble"){
        $(".borrador_check_wrap").css('visibility', 'unset');
      }else if (tipo_file == "" || tipo_file == "personal"){
        $(".borrador_check_wrap").css('visibility', 'hidden');
      };
    });


  });
});
