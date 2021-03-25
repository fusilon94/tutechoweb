// ################################# BOTON TRASH DE SPONSORS ACTIVOS #######################################

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

// ################################# BOTON POWER ON DE SPONSORS INACTIVOS ###################################

  function confirmacion_reactivar(oObject){//Codigo que gobierna el boton Power-on para la reactivacion de sponsors inactivos
    var parent = oObject.parentNode.querySelector("div.boton_borrador_formulario_reactivar_confirmar");
    var trashicon = oObject.querySelector("i.fas");

    $(trashicon).toggleClass("fa-power-off fa-times");

    if ($(parent).is(":hidden")) {
      $(parent).show("slide", { direction: "left" }, 800);
    } else {
      $(parent).hide("slide", { direction: "left" }, 800);
    };
  };

//#################################### BOTON CONFIRMAR BORRAR DE SPONSORS ACTIVOS ##############################################

  function confirmacion_borrar(btn){//LO que sucede cuadno se valida y confirma la inactivacion de un sponsor

            var sponsor_inactivar = $(btn).parent().find("div.boton_borrador_formulario").attr("id");
            $.ajax({
                type: "POST",
                url: "process-request-sponsor_inactivar.php",
                data: { sponsor_para_inactivar : sponsor_inactivar },
            }).done(function(data){
              var referencia_fila_botones = $(btn).parent();
              $(referencia_fila_botones).css('display', 'none');

              $('.popup_success').css('visibility',  'visible');
              $('.popup_success_text').html(data);
            });

  };

//########################################### BOTON CONFIRMAR REACTIVAR SPONSORS INACTIVOS ##############################################

  function confirmacion_reactivar_validar(btn){// Lo que succede si se valida y confirma la reactivacion de un sponsor

      var sponsor_reactivar = $(btn).parent().find("div.boton_borrador_formulario").attr("id");
      $.ajax({
          type: "POST",
          url: "process-request-sponsor_inactivar.php",
          data: { sponsor_para_reactivar : sponsor_reactivar},
      }).done(function(data){
        var referencia_fila_botones = $(btn).parent();
        $(referencia_fila_botones).css('display', 'none');

        $('.popup_success').css('visibility',  'visible');
        $('.popup_success_text').html(data);

      });


  };
