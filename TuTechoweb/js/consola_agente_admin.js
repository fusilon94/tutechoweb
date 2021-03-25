$(document).ready(function(){
  jQuery(function($){

// CODIGO VENTANA DIALOGO PARA MENSAJES DE EXITO EN FORMULARIOS PREVIOS #####################


    $(".pais_selector").on("click", function(){

      $(".pais_list_overlay").css("visibility", 'unset');
            
    });


    $(".cerrar_pais_list").on("click", function(){
      $(".pais_list_overlay").css("visibility", 'hidden');
    });

    $(".pais_opcion").on("click", function(){
      let pais_selected = $(this).attr("id");

      $.ajax({
        type: "POST",
        url: "process-request-consola-admin-pais.php",
        data: { pais_sent : pais_selected },
      }).done(function(data){
        if (data == "Exito") {
          location.reload();
        }else{
          alert("Hubo un Error");
        };
          
      });


    });

    $( "#dialog_exito" ).dialog({
          autoOpen: true,
          buttons: {
            Ok: function() {
              $( this ).dialog( "close" );
            }
          },
          show: {
            effect: "blind",
            duration: 500
          },
          hide: {
            effect: "fade",
            duration: 500
          }
    });


  });
});
