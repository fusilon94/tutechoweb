$(document).ready(function(){
  jQuery(function($){


    const hashVal = window.location.hash;
    const referencia = hashVal.replace(/%20/g, " ").trim().split("~")[0]; // SE ALAMCENAN LOS DATOS DEL STATE (url) DENTRO DE UN ARRAY


    $.ajax({
        type: "POST",
        url: "process-request-propietario-consola.php",
        data: { referencia_sent : referencia },
      }).done(function(data){
        $(".contenido").html(data)
      });


  });
});
