$(document).ready(function(){
  jQuery(function($){

    $('.boton_borrador_formulario_borrar_confirmar').on("click", function(){
          var sponsor_activar = $(this).parent().find("div.boton_borrador_formulario").attr("id");
          var pais = $(this).parent().find(".boton_borrador_formulario").attr("name");
          $.ajax({
              type: "POST",
              url: "process-request-sponsor_activar.php",
              data: { sponsor_para_activar : sponsor_activar, pais_sent : pais  },
          }).done(function(data){
            var referencia_fila_botones = $(this).parent();
            $(referencia_fila_botones).css('display', 'none');

            $('.popup_success').css('visibility',  'visible');
            $('.popup_success_text').html(data);
          });
    });


    $(".boton_borrador_formulario_borrar").on("click", function(){
      var parent = $(this).parent().find("div.boton_borrador_formulario_borrar_confirmar");
      var trashicon = $(this).find("i.fas");

      $(trashicon).toggleClass("fa-power-off fa-times");

      if ($(parent).is(":hidden")) {
        $(parent).show("slide", { direction: "left" }, 800);
      } else {
        $(parent).hide("slide", { direction: "left" }, 800);
      };
    });

    

    $(".boton_borrador_formulario").on("click", function(){
      var sponsor_nombre = $(this).attr('id');
      var pais = $(this).attr('name');

      $.ajax({
          type: "POST",
          url: "process-request-sponsor_visualizar_admin.php",
          data: { nombre_sponsor_sent : sponsor_nombre, pais_sent : pais},
      }).done(function(data){
        var popup_sponsor_received = data;
        $('.overlay_sponsor_previsualizacion').toggleClass("active").html(popup_sponsor_received);

      });

    });


    $('.overlay_sponsor_previsualizacion').on('click',function(){

    $(".popup_sponsor").remove();
    $('.overlay_sponsor_previsualizacion').toggleClass("active");

  });

  $('.overlay_sponsor_previsualizacion').on('click', 'span.popup_sponsor_cerrar' ,function(){

    $(".popup_sponsor").remove();
    $('.overlay_sponsor_previsualizacion').toggleClass("active");

  });

  $('.overlay_sponsor_previsualizacion').on('click', '.previsualizacion_container' ,function(e){
          if($(e.target).not('span.popup_sponsor_cerrar')){
          e.stopPropagation();//evita que active el click event de su contenedor, la elemento sponsor de la ficha bien
        }

  });


  $('.popup_success_cerrar i.fa-times').on("click", function(){
    $('.popup_success').css('visibility',  'hidden');
  });

  });
});
