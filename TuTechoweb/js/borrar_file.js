$(document).ready(function(){
  jQuery(function($){

    $(".enviar_btn").on("click", function(){
        const pais_file = $("#pais_select option:selected").val();
        const tipo_file = $("#tipo_file_select option:selected").val();
        const id_file = $("#id_file").val();


        if (tipo_file !== "" && pais_file !== "" && id_file !== "") {

            $.ajax({
              type: "POST",
              url: "process-request-id-check-file.php",
              data: { pais_sent : pais_file, 
                      tipo_file_sent : tipo_file, 
                      id_file_sent : id_file },
            }).done(function(data){
              if (data == 'exito') {
                $("#form_borrar_file").submit();   
              } else {
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


  });
});
