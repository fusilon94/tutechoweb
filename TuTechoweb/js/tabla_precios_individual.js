// Sitio del plugin para mas info: woocommerce.com/flexslider/  //

$(document).ready(function(){
  jQuery(function($){
  
    var hashVal = window.location.hash.substr(1);
    var hashArray = hashVal.replace(/%20/g, " ").trim().split("&"); // SE ALAMCENAN LOS DATOS DEL STATE (url) DENTRO DE UN ARRAY

    $.ajax({
        type: "POST",
        url: "process-request-agencia-tabla-precios-previsualizacion.php",
        data: { pais_sent : hashArray[1],
                agencia_sent : hashArray[0] }
    }).done(function(data){
        
        $(".preview_contenido").html(data);
        
        let count = 0;
        $(".tabla_venta_gris tr").each(function(){
            
            if (count == 0) {
                count += 1;
            } else if (count % 2 == 0){//si es numero
                    
                    count += 1;
            } else {// si es impar
                    $(this).addClass('fondo_gris');
                    count += 1;
            };
            
        });
  
        count = 0;
        $(".tabla_alquiler_gris tr").each(function(){
            
            if (count == 0) {
                count += 1;
            } else if (count % 2 == 0){//si es numero
                    
                    count += 1;
            } else {// si es impar
                    $(this).addClass('fondo_gris');
                    count += 1;
            };
            
        });
    });

    });
  })
