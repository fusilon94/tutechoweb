
  let datos_pais;
  $.ajax({
    type: "POST",
    url: "../../contenido/m5/process-request-coordenadas-paises.php",
    data: {pais_selected : pais_selected},
    dataType: 'json',
    async: false,
  }).done(function(data){
    datos_pais = data;
  });


  function check(element){//pone check verde cuando se carga un pdf
    var document_loaded = $(element).val();
    var id = $(element).data('id');
    if (document_loaded == '') {
      $("label[for='" + id + "'] p").css('display', 'block');
      $("label[for='" + id + "'] i").css('display', 'none');
    }else {
      var fileExtension = ['pdf'];
      if ($.inArray($(element).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        $(".popup_success_text").html("Solo se admiten archivos PDF");
        $(".popup_success").css("visibility", "unset");
      }else{
        $("label[for='" + id + "'] p").css('display', 'none');
        $("label[for='" + id + "'] i").css('display', 'block');
      };
      
    };

  };


  function check_jpg(element){//se carga la previsualizacion al cargar foto (event change)
    var fileExtension = ['jpg'];
    if($(element).val() !== ""){
      if ($.inArray($(element).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        $(".popup_success_text").html("Solo se admiten imagenes .jpg");
        $(".popup_success").css("visibility", "unset");
        $(element).val("").trigger("change");
      };
    };
  };


  function thumb_click_operator(oObject, tipo){
    if (tipo == 'pdf') {
      var thumbfoto = oObject.parentNode.querySelector("span.thumb_pdf_container");
    }else if(tipo == 'image'){
      var thumbfoto = oObject.parentNode.querySelector("img.thumb_foto_normal");
    };
    var fotocampo = oObject.parentNode.querySelector("input[type=file]");
    var thumbfoto_p = oObject.parentNode.querySelector("div.thumb_foto_normal_p_container");
    var foto_return_button = oObject.parentNode.querySelector("i.return_change_foto");

    $(thumbfoto).hide();
    $(thumbfoto_p).hide();
    $(foto_return_button).css('visibility', 'visible');
    $(fotocampo).prop('disabled', false);
  };

  function return_foto_click_operator(oObject, tipo){
    if (tipo == 'pdf') {
      var thumbfoto = oObject.parentNode.querySelector("span.thumb_pdf_container");
    }else if(tipo == 'image'){
      var thumbfoto = oObject.parentNode.querySelector("img.thumb_foto_normal");
    };
    var fotocampo = oObject.parentNode.querySelector("input[type=file]");
    var thumbfoto_p = oObject.parentNode.querySelector("div.thumb_foto_normal_p_container");
    var foto_return_button = oObject.parentNode.querySelector("i.return_change_foto");

    $(thumbfoto).show();
    $(thumbfoto_p).show();
    $(foto_return_button).css('visibility', 'hidden');
    $(fotocampo).prop('disabled', true).val("").trigger("change");

  }

  function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight + 8)+"px";
  }

jQuery(function($){

// ##########################################################################################
// ###############################DRAG AND DROP FEATURES ####################################
// ##########################################################################################

  $(".drags_contenedor").on('dragenter', '.campo_foto', function (e){  // lo que pasa cuando drag por encima, y cuando te vas
      $(this).css('border', '3px dashed #007fff');
  });

  $(".drags_contenedor").on('dragover', '.campo_foto', function (e){
      e.preventDefault();
      e.stopPropagation();
      $(this).css('border', '1px solid #007fff');
      return false;
  });

  $(".drags_contenedor").on('dragleave', '.campo_foto', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).css('border', '3px dashed gray');
  });

  $(".drags_contenedor").on('drop', '.campo_foto', function(e) {
    $(this).css('border', '3px dashed gray');
  });


// CODIGO POPUP SUCCESS ####################################################################################

  $('.popup_success_cerrar i.fa-times').on("click", function(){
    $('.popup_success').css('visibility',  'hidden');
  });

// CODIGO BOTON CREAR AGENCIA ########################################################

$(".boton_crear_file").on('click', function(){

  let errores = '';
  $(".input_wrap:not(.opcional) input:not(:disabled)").each(function(){
    if (!$(this).parent().hasClass('opcional')) {
      if ($(this).val() == '') {
        errores = "error";
      };
    };
  });

  $(".input_wrap:not(.opcional) textarea:not(:disabled)").each(function(){
    if (!$(this).parent().hasClass('opcional')) {
      if ($(this).val() == '') {
        errores = "error";
      };
    };
  });

  $(".input_wrap:not(.opcional) select:not(:disabled)").each(function(){
    if ($(this).find("option:selected").val() == '') {
      errores = "error";
    };
  });

  $(".contenedor_foto:not(.opcional) input:not(:disabled)").each(function(){
    if ($(this).val() == '') {
      errores = "error";
    };
  });

  $(".borde_rojo").each(function(){
    errores = "error";
  });

  if (errores !== "") {
    $(".popup_success_text").html("Todos los campos deben llenarse correctamente");
    $(".popup_success").css("visibility", "unset");
  }else{
    $("#nueva_file_form").submit();
  };

});

});

