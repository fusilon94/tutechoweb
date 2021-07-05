$(document).ready(function(){
  jQuery(function($){

    $(".enviar_btn").on("click", function(){
        const pais_file = $("#pais_select option:selected").val();
        const tipo_cierre = $("#tipo_file_select option:selected").val();
        const id_file = $("#id_file").val();


        if (tipo_cierre !== "" && pais_file !== "" && id_file !== "") {

            $.ajax({
              type: "POST",
              url: "process-request-id-check-file-cierre.php",
              data: { pais_sent : pais_file, 
                      tipo_cierre_sent : tipo_cierre, 
                      id_file_sent : id_file },
            }).done(function(data){
              if (data == 'exito') {
                $("#form_cierre_file").submit();   
              } else {
                  console.log(data);
                $(".popup_content").html(`<h2>ID File invalido</h2>`);
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
