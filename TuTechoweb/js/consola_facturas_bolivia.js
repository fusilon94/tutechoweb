$(document).ready(function(){
  jQuery(function($){


    function load_facturas(agencia_id){

        $.ajax({
            type: "POST",
            url: "process-request-cargar-facturas_bolivia.php",
            data: { 'agencia_id': agencia_id, 'modo': modo },
        }).done(function(data){
            $(".list_container").html(data);
        });

    };

    const agencia_first = $(".agencia_select option:selected").val();
    if (agencia_first !== '') {
        load_facturas(agencia_first);
    };


    $(".agencia_select").on("change", function(){
        const agencia_id = $(".agencia_select option:selected").val();
        if (agencia_id == '') {
            $(".list_container").empty();
        }else{
            load_facturas(agencia_id);
        }
    });
    


  });
});
