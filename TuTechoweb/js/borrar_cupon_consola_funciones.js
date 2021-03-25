// ################################# BOTON PLUS DE SPONSORS ACTIVOS #######################################

  function confirmacion(oObject){//Codigo que gobierna el boton TRASH en inactivar Sponsors activos
    var parent = oObject.parentNode.querySelector("div.boton_borrador_formulario_borrar_confirmar");
    var trashicon = oObject.querySelector("i.fas");

    $(trashicon).toggleClass("fa-trash-alt fa-times");

    if ($(parent).is(":hidden")) {
      $(parent).show("slide", { direction: "left" }, 800);
    } else {
      $(parent).hide("slide", { direction: "left" }, 800);
    };
  };


//#################################### BOTON CONFIRMAR AGREGAR CUPON SPONSORS ##############################################

  function confirmacion_agregar(btn){//LO que sucede cuadno se valida y confirma la inactivacion de un sponsor

      var cupon_borrar = $(btn).parent().find("div.boton_borrador_formulario").attr("id");

      $.ajax({
          type: "POST",
          url: "process-request-cupon-borrar.php",
          data: { cupon_borrar_sent : cupon_borrar },
      }).done(function(data){
        $('.popup_success_text').html(data);
        $('.popup_success').css('visibility', 'visible');
        var referencia_fila_botones = $(btn).parent();
        $(referencia_fila_botones).css('display', 'none');
      });
  };
