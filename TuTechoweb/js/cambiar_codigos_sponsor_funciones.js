// ################################# BOTON TRASH DE SPONSORS ACTIVOS #######################################

  function confirmacion(oObject){//Codigo que gobierna el boton TRASH en inactivar Sponsors activos
    var parent = oObject.parentNode.querySelector("div.boton_borrador_formulario_borrar_confirmar");
    var trashicon = oObject.querySelector("i.fas");

    $(trashicon).toggleClass("fa-sync-alt fa-times");

    if ($(parent).is(":hidden")) {
      $(parent).show("slide", { direction: "left" }, 800);
    } else {
      $(parent).hide("slide", { direction: "left" }, 800);
    };
  };

//#################################### BOTON CONFIRMAR BORRAR DE SPONSORS ACTIVOS ##############################################

  function confirmacion_borrar(btn){//LO que sucede cuadno se valida y confirma la inactivacion de un sponsor

            var sponsor = $(btn).parent().find("div.boton_borrador_formulario").attr("id");
            $.ajax({
                type: "POST",
                url: "process-request-cambiar-codigos-sponsor.php",
                data: { sponsor_sent : sponsor },
            }).done(function(data){
              $('.popup_success').css('visibility',  'visible');
              $('.popup_success_text').html(data);
            });

  };
