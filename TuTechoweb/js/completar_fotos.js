$(document).ready(function(){
  jQuery(function($){

// CODIGO QUE COLOCA EL TAG THUMBAIL EN EL PRIMER BORDE FOTO######################

  $(".borde_foto:first").append("<span class=\"tag_thumbnail\"><p>PORTADA</p></span>");

// CODIGO QUE PERMITE EL COMPORTAMIENTO SORTABLE DE LOS CAMPOS FOTOS #################

  const lista = document.getElementById('lista');

  Sortable.create(lista, {
    animation: 200,
    handle: ".drag_handler",
    invertSwap: true,
    swapThreshold: 1,
    forceFallback: true,
    fallbackTolerance: 8,
    direction: 'horizontal',
    filter: ".ignore-elements"
  });

// CODIGO PARA AGREGAR O QUITAR CAMPOS DE FOTOS ##############################
  var modo_consola = $("#modo_consola").val();
  var min_fotos = parseInt($("#contador_min").val());
  var max_fotos = parseInt($("#contador_max").val());
  var exclusivo_360 = parseInt($("#exclusivo_360").val());
  var activo_360 = parseInt($("#activo_360").val());
  var contador_edit = parseInt($("#contador_edit").val());
  var contador_fotos;
  var vr_exist = $("#vr_exist").val();

  if (modo_consola == 'first entry') {
    if ($("#contador_min").val() == 2) {
      contador_fotos = 3
    }else {
      contador_fotos = $("#contador_min").val();
    };
  };
  if (modo_consola == 'edicion') {
    if ($("#contador_min").val() == 2) {
      contador_fotos = contador_edit;
      if (contador_fotos == 2) {
        if(!$(".quitar_campo_btn").hasClass('limit_reached')){
          $(".quitar_campo_btn").addClass('limit_reached');
        };
      };
    }else {
      contador_fotos = contador_edit;
      if (vr_exist == 'SI') {
        if(!$(".quitar_campo_btn").hasClass('limit_reached')){
          $(".quitar_campo_btn").addClass('limit_reached');
        };
      };
    };
  };


  $(".quitar_campo_btn").on("click", function(){

        if (contador_fotos > min_fotos) {
          contador_fotos--;

          if (contador_fotos == min_fotos) {
            $(".cuenta_de_campos").html("MIN");
          }else {
            $(".cuenta_de_campos").html(contador_fotos + " Fotos");
          };

          if ($(".agregar_campo_btn").hasClass('limit_reached')) {
            $(".agregar_campo_btn").removeClass('limit_reached');
          };

          $('#lista div.borde_foto_trans.campo_borrable:last').remove();
          $('.marcos_contenedor div.borde_foto:last').remove();
        };

        if (contador_fotos == min_fotos) {
          $(this).addClass('limit_reached');
        };
  });

  $(".agregar_campo_btn").on("click", function(){

      if (contador_fotos < max_fotos) {

        contador_fotos++;

        if (contador_fotos == max_fotos) {
          $(".cuenta_de_campos").html("MAX");
        }else {
          $(".cuenta_de_campos").html(contador_fotos + " Fotos");
        };

        var rdm_string = '';
        var characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz012456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < 8; i++ ) {
           rdm_string += characters.charAt(Math.floor(Math.random() * charactersLength));
        };

        if (activo_360 == 0 || (activo_360 == 0 && exclusivo_360 == 1 && max_fotos == 3)) {
          $("#lista").append(`
            <div id="contenedor_foto${rdm_string}" class="borde_foto_trans campo_editable campo_borrable campo_nuevo">
              <span class="drag_handler"><i class="fas fa-arrows-alt"></i></span>
              <div id="campo_foto${rdm_string}" class="campo_foto">
                <label for="foto${rdm_string}" id="foto${rdm_string}_label"><p> Sube la Fotografía</br><span>Click or Drop</span></p></label>
                <input type="file" id="foto${rdm_string}" accept="image/jpeg" name="foto${rdm_string}" onchange="check_jpg(this)">
              </div>           
              <div class="contenedor_foto_360_titulo">
                <div class="subtitulo_container">
                  <label for="titulo_foto${rdm_string}">Titulo de la foto</label>
                  <input type="text" id="titulo_foto${rdm_string}" name="titulo_foto${rdm_string}" class="titulo_foto" value="">
                  <input type="hidden" id="titulo_foto${rdm_string}_original" name="titulo_foto${rdm_string}_original" value="original_empty">
                </div>
                <div class="campo_foto_360" id="campo_foto${rdm_string}_360" style="visibility: hidden"></div>
              </div>
            </div>
          `);
        };

        if ((activo_360 == 1 && exclusivo_360 == 0) || (activo_360 == 1 && exclusivo_360 == 1 && max_fotos == 15)) {
          $("#lista").append(`
          <div id="contenedor_foto${rdm_string}" class="borde_foto_trans campo_editable campo_borrable campo_nuevo">
            <span class="drag_handler"><i class="fas fa-arrows-alt"></i></span>
            <div id="campo_foto${rdm_string}" class="campo_foto">
              <label for="foto${rdm_string}" id="foto${rdm_string}_label"><p> Sube la Fotografía</br><span>Click or Drop</span></p></label>
              <input type="file" id="foto${rdm_string}" accept="image/jpeg" name="foto${rdm_string}" onchange="check_jpg(this)">
            </div>
            <div class="contenedor_foto_360_titulo">
              <div class="subtitulo_container">
                <label for="titulo_foto${rdm_string}">Titulo de la foto</label>
                <input type="text" id="titulo_foto${rdm_string}" name="titulo_foto${rdm_string}" class="titulo_foto" value="">
                <input type="hidden" id="titulo_foto${rdm_string}_original" name="titulo_foto${rdm_string}_original" value="original_empty">
              </div>
              <div class="campo_foto_360" id="campo_foto${rdm_string}_360">
                <label for="foto${rdm_string}_360"><p>360°</p><i class="far fa-check-circle"></i></label>
                <input type="file" id="foto${rdm_string}_360" accept="image/jpeg" name="foto${rdm_string}_360" data-id="foto${rdm_string}_360" onchange = "check(this)">
              </div>
            </div>
          </div>`);
        };

        refresh_campos_mechanism();

        $.uploadPreview({
            input_field: "#foto" + rdm_string,   // Default: .image-upload
            preview_box: "#campo_foto" + rdm_string,  // Default: .image-preview
            label_field: "#foto" + rdm_string + "_label",    // Default: .image-label
            label_default: "<p>SUBE LA FOTOGRAFÍA<br><span>Click or Drop</span></p>",   // Default: Choose File
            label_selected: "Cambia esta imagen",  // Default: Change File
            no_label: false                 // Default: false
        });

        $(".marcos_contenedor").append("<div class=\"borde_foto\"><span class=\"tag_borde\"><p>" + contador_fotos + "</p></span></div>");

        if ($(".quitar_campo_btn").hasClass('limit_reached')) {
          $(".quitar_campo_btn").removeClass('limit_reached');
        };

      };
      if (contador_fotos == max_fotos) {
        $(this).addClass('limit_reached');
      };



  });

// Esto permite que al cargar una foto 360 complementaria el icono cambie a checked verde

    function check(element){
      var foto360 = $(element).val();
      var id = $(element).data('id');
      if (foto360 == '') {
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

    function check_jpg(element){
      var fileExtension = ['jpg'];
      if($(element).val() !== ""){
        if ($.inArray($(element).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
          $(".popup_success_text").html("Solo se admiten imagenes .jpg");
          $(".popup_success").css("visibility", "unset");
          $(element).val("").trigger("change");
        };
      };
    };

// ##########################################################################################
// ###############################DRAG AND DROP FEATURES ####################################
// ##########################################################################################

 function refresh_campos_mechanism(){
   // Dinamica de estilos general (PARA FOTO PRINCIPAL)#########################
           $(".campo_foto").on('dragenter', function (e){  // lo que pasa cuando drag por encima, y cuando te vas
               $(this).css('border', '3px dashed #007fff');
           });

           $(".campo_foto").on('dragover', function (e){
               e.preventDefault();
               e.stopPropagation();
               $(this).css('border', '1px solid #007fff');
               return false;
           });

           $(".campo_foto").on('dragleave', function(e) {
               e.preventDefault();
               e.stopPropagation();
               $(this).css('border', '3px dashed gray');
           });

           $(".campo_foto").on('drop', function(e) {
            $(this).css('border', '3px dashed gray');
        });

   // Dinamica de estilos general (PARA FOTO 360 COMPLEMENTARIA)#########################
           $(".campo_foto_360").on('dragenter', function (e){  // lo que pasa cuando drag por encima, y cuando te vas
               $(this).css('border', '3px dashed #007fff');
           });

           $(".campo_foto_360").on('dragover', function (e){
               e.preventDefault();
               e.stopPropagation();
               $(this).css('border', '1px solid #007fff');
               return false;
           });

           $(".campo_foto_360").on('dragleave', function(e) {
               e.preventDefault();
               e.stopPropagation();
               $(this).css('border', '3px dashed gray');
           });

           $(".campo_foto_360").on('drop',function(e){
               e.preventDefault();
               alert("360° Box isn't droppable");
             });
 };

 refresh_campos_mechanism() //se lo llama para la carga inicial


// Lista de Drag-and-Drop elements  FIRST CHARGE OF PAGE !!!##################################
      var modo_consola = $("#modo_consola").val();

      if (modo_consola == "first entry") {
        $.uploadPreview({
            input_field: "#foto1",   // Default: .image-upload
            preview_box: "#campo_foto1",  // Default: .image-preview
            label_field: "#foto1_label",    // Default: .image-label
            label_default: "<p>SUBE LA FOTOGRAFÍA<br><span>Click or Drop</span></p>",   // Default: Choose File
            label_selected: "Cambia esta imagen",  // Default: Change File
            no_label: false                 // Default: false
        });

        $.uploadPreview({
            input_field: "#foto2",   // Default: .image-upload
            preview_box: "#campo_foto2",  // Default: .image-preview
            label_field: "#foto2_label",    // Default: .image-label
            label_default: "<p>SUBE LA FOTOGRAFÍA<br><span>Click or Drop</span></p>",   // Default: Choose File
            label_selected: "Cambia esta imagen",  // Default: Change File
            no_label: false                 // Default: false
        });

        $.uploadPreview({
            input_field: "#foto3",   // Default: .image-upload
            preview_box: "#campo_foto3",  // Default: .image-preview
            label_field: "#foto3_label",    // Default: .image-label
            label_default: "<p>SUBE LA FOTOGRAFÍA<br><span>Click or Drop</span></p>",   // Default: Choose File
            label_selected: "Cambia esta imagen",  // Default: Change File
            no_label: false                 // Default: false
        });

      };

      if (modo_consola == "edicion") {
        var i = 1;
        while (i <= contador_edit) {

          $.uploadPreview({
              input_field: "#foto" + i,   // Default: .image-upload
              preview_box: "#campo_foto" + i,  // Default: .image-preview
              label_field: "#foto" + i + "_label",    // Default: .image-label
              label_default: "<p>SUBE LA FOTOGRAFÍA<br><span>Click or Drop</span></p>",   // Default: Choose File
              label_selected: "Cambia esta imagen",  // Default: Change File
              no_label: false                 // Default: false
          });

          i++
        };

      };

// LIVE CHECK ERROR CORREECTION ON INPUT fill ########################################

  $('#formulario_registro_fotos').on("change", ".campo_foto input[type='file']", function(){
    var foto_val = $(this).val();
    if (foto_val !== '' && $(this).parent().parent().hasClass('incomplete')) {
      validar_fotos();
    };
  });

  $('#formulario_registro_fotos').on("change", ".campo_foto_360 input[type='file']", function(){
    var foto360_val = $(this).val();
    if (foto360_val !== '' && $(this).parent().parent().parent().hasClass('incomplete')) {
      validar_fotos();
    };
  });

  $('#formulario_registro_fotos').on("input", ".titulo_foto", function(){
    var titulo_val = $(this).val();
    if (titulo_val.match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&\/,.\']+$/) == null) {//si se ingreso un caracter NO permitido
      alert('Carácter NO permitido');
      if ($(this).css('border-color') !== "rgb(255, 0, 0)") {
        $(this).css('border-color', 'rgb(255, 0, 0)');
      };
    }else {//si se ingreso un caracter permitido
        $(this).css('border-color', 'initial');
    };
    if (titulo_val !== '' && $(this).parent().parent().parent().hasClass('incomplete')) {//si se esta corrigiendo un error de validacion
      validar_titulos();
    };
  });

// FUNCION DE VALIDACION DE LLENADO DE ESPACIOS ######################################

function validar_fotos(){
  $('.campo_editable').each(function(){

    var this_foto = $(this).find('.campo_foto input[type="file"]').val();//check si no esta vacio
    var this_foto360 = $(this).find('.campo_foto_360 input[type="file"]').val();//check si no esta vacio
    var this_titulo_borde = $(this).find('.titulo_foto').css("border-color");//check si se escribio correctamente

    if (this_foto == '' || this_foto360 == '' || this_titulo_borde == 'rgb(255, 0, 0)') {
      $(this).addClass('incomplete');
      error = 'error';
    }else {
      if ($(this).hasClass('incomplete')) {
        $(this).removeClass('incomplete');
      };
    };
  });

};

function validar_titulos(){

  $('.borde_foto_trans').each(function(){
    var this_titulo = $(this).find('.titulo_foto').val();//check si no esta vacio
    var this_titulo_borde = $(this).find('.titulo_foto').css("border-color");//check si se escribio correctamente

    if (this_titulo == '' || this_titulo_borde == 'rgb(255, 0, 0)') {
      $(this).addClass('incomplete');
      error = 'error';
    }else {
      if ($(this).hasClass('incomplete')) {
        $(this).removeClass('incomplete');
      };
    };
  });

};

function validar_final(){
  var error = 'clear';
  $('.campo_editable').each(function(){

    var this_foto = $(this).find('.campo_foto input[type="file"]').val();//check si no esta vacio
    var this_foto360 = $(this).find('.campo_foto_360 input[type="file"]').val();//check si no esta vacio
    var this_titulo_borde = $(this).find('.titulo_foto').css("border-color");//check si se escribio correctamente

    if (this_foto == '' || this_foto360 == '' || this_titulo_borde == 'rgb(255, 0, 0)') {
      $(this).addClass('incomplete');
      error = 'error';
    };
  });

  $('.borde_foto_trans').each(function(){
    var this_titulo = $(this).find('.titulo_foto').val();//check si no esta vacio
    var this_titulo_borde = $(this).find('.titulo_foto').css("border-color");//check si se escribio correctamente

    if (this_titulo == '' || this_titulo_borde == 'rgb(255, 0, 0)') {
      $(this).addClass('incomplete');
      error = 'error';
    };
  });

  function getOccurrence(array, value) {
    return array.filter((v) => (v === value)).length;
  }

  var all_titulos_array = [];
  $('.titulo_foto').each(function(){
    var titulo_val = $(this).val();
    all_titulos_array.push(titulo_val);
  });

  $('.titulo_foto').each(function(){
    var titulo_occurence = getOccurrence(all_titulos_array, $(this).val());
    if ($(this).css('border-color') == 'rgb(255, 31, 0)') {
      $(this).css('border-color', 'initial');
    };
    if (titulo_occurence > 1) {
      $(this).css('border-color', 'rgb(255, 31, 0)');
      error = 'error';
      $('.popup_success').css('visibility', 'visible');
    };
  });

  return error;

};

// CODIGO PARA MANEJAR EL BOTON VALIDACION ##########################################

$(".btn_validar").on("click", function(){
  var validacion = validar_final();
  if (validacion == 'clear') {
    $(".btn_registrar").css("display", "flex");
  }else {
    $(".btn_registrar").css("display", "none");
  };

});

// CODIGO PARA MANEJAR EL BOTON REGISTRAR ###########################################

 $(".btn_fin_container").on("click", ".btn_registrar", function(){
   var validacion = validar_final();
   if (validacion == 'clear') {
     $("#lista input[type='file']").prop("disabled", false);//se reactivan todos los input file disabled en el modo edcion antes de enviar la info
     $("#formulario_registro_fotos").submit();
   }else {
     $(".btn_registrar").css("display", "none");
   };

 });

 //################ CODIGO PARA CERRAR POPUP SUCCESS #########################################
       $('span.popup_success_cerrar').on('click', function(){
         $('.popup_success').css('visibility', 'hidden');
       });

 });
})
