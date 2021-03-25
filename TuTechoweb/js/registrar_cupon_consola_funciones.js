// ################################# BOTON PLUS DE SPONSORS ACTIVOS #######################################

  function confirmacion(oObject){//Codigo que gobierna el boton TRASH en inactivar Sponsors activos
    var parent = oObject.parentNode.querySelector("div.boton_borrador_formulario_borrar_confirmar");
    var trashicon = oObject.querySelector("i.fas");

    $(trashicon).toggleClass("fa-plus fa-times");

    if ($(parent).is(":hidden")) {
      $(parent).show("slide", { direction: "left" }, 800);
    } else {
      $(parent).hide("slide", { direction: "left" }, 800);
    };
  };


//#################################### BOTON CONFIRMAR AGREGAR CUPON SPONSORS ##############################################

  function confirmacion_agregar(btn){//LO que sucede cuadno se valida y confirma la inactivacion de un sponsor

      var sponsor_inactivar = $(btn).parent().find("div.boton_borrador_formulario").attr("id");

      $('#cupon_agregar_nombre').val(sponsor_inactivar);
      $('#cupon_agregar_form').submit();
  };
