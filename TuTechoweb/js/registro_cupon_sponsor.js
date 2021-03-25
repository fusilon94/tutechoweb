$(document).ready(function(){
  jQuery(function($){


// CODIGO ACTIVAR VISTA PRELIMINAR EN TABLET Y MOBILE ############################

    $('.switch_container').on('click', "span.boton_ver_vista_preliminar", function(){
      $(".popups_container").toggleClass('visible');
      $("span.boton_ver_vista_preliminar i").toggleClass('fa-eye-slash fa-eye');
      $(".boton_ver_vista_preliminar").toggleClass('active');
    });

// CODIGO QUE LLENA DINAMICAMENTE LA INFO INGRESADA EN LOS INPUT TYPE TEXT #####################


    $(".tipo_promocion").on("change", function(){
      var tipo_promocion = $(this).val();

      if (tipo_promocion == '1') {
        $('.configuracion_sponsor_paquete2').html("<div class=\"elemento_formulario_sponsor\"><label for=\"promo_var1\">Variable #1: </label><input id=\"promo_var1\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"promo_var1\" class=\"tipo1_promo_var1 input_obligatorio_spinner\"></div><div class=\"elemento_formulario_sponsor\"><label for=\"promo_var2\"> Variable #2: </label><select name=\"promo_var2\" id=\"promo_var2\" class=\"categoria input_obligatorio\"><option></option><option value=\"%\">'%' Porcentaje</option><option value=\"Bs\">'Bs' Bolivianos</option><option value=\"$us\">'$us' Dolares</option></select></div><div class=\"elemento_formulario_sponsor\"><label for=\"promo_var3\"> Variable #3: </label><select name=\"promo_var3\" id=\"promo_var3\" class=\"categoria input_obligatorio\"><option></option><option value=\"DESCUENTO\">DESCUENTO</option><option value=\"OFF\">OFF</option></select></div><div class=\"elemento_formulario_sponsor\"><label for=\"promo_var4\"> Variable #4: </label><div class=\"promo_var4_radio_container\"><span class=\"promo_var4_radio opcion_horizontal active\">Horizontal</span><span class=\"promo_var4_radio opcion_vertical\">Vertical</span></div><input type=\"hidden\" name=\"promo_var4\" id=\"promo_var4\" value=\"row\"></div><div class=\"elemento_formulario_sponsor\"><label for=\"font_size1_input\">Tamaño del Texto #1: </label><div id=\"opcion_font_size_input\"></div><input type=\"hidden\" id=\"font_size1_input\" name=\"font_size1_input\" value=\"1.875em\"></div><div class=\"elemento_formulario_sponsor\"><label for=\"font_size_2_input\">Tamaño del Texto #2: </label><div id=\"opcion_font_size_input2\"></div><input type=\"hidden\" id=\"font_size2_input\" name=\"font_size2_input\" value=\"1.875em\"></div>");

        $(".promo_tipo_2_x").empty();

      };
      if (tipo_promocion == '2') {
        $('.configuracion_sponsor_paquete2').html("<div class=\"elemento_formulario_sponsor\"><label for=\"promo_var1\">Variable #1: </label><input id=\"promo_var1\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"promo_var1\" class=\"tipo2_promo_var1 input_obligatorio_spinner\"></div><div class=\"elemento_formulario_sponsor\"><label for=\"promo_var2\">Variable #2: </label><input id=\"promo_var2\" type=\"text\" autocomplete=\"off\" readonly=\"readonly\" name=\"promo_var2\" class=\"tipo2_promo_var2 input_obligatorio_spinner\"></div><div class=\"elemento_formulario_sponsor\"><label for=\"font_size1_input\">Tamaño del Texto #1: </label><div id=\"opcion_font_size_input\"></div><input type=\"hidden\" id=\"font_size1_input\" name=\"font_size1_input\" value=\"1.875em\"></div>");

        $(".promo_tipo_2_x").html("x");

      };
      if (tipo_promocion == '3') {
        $('.configuracion_sponsor_paquete2').html("<div class=\"elemento_formulario_sponsor\"><label for=\"promo_var1\"> Variable #1: </label><select name=\"promo_var1\" id=\"promo_var1\" class=\"input_obligatorio\"><option></option><option value=\"GRATIS\">GRATIS</option><option value=\"FREE\">FREE</option><option value=\"REGALO\">REGALO</option></select></div><div class=\"elemento_formulario_sponsor\"><label for=\"font_size1_input\">Tamaño del Texto #1: </label><div id=\"opcion_font_size_input\"></div><input type=\"hidden\" id=\"font_size1_input\" name=\"font_size1_input\" value=\"1.875em\"></div>");

        $(".promo_tipo_2_x").empty();
      };

      $(".promo_cuadro1_texto1").empty();
      $(".promo_cuadro1_texto2").empty();
      $(".promo_cuadro2_texto1").empty();

      $('.configuracion_sponsor_paquete2').append("<div class=\"elemento_formulario_sponsor\"><label for=\"tipo_texto\">Tipo de Texto: </label><select name=\"tipo_texto\" id=\"tipo_texto\" class=\"tipo_texto input_obligatorio\"><option value=\"normal\">Normal</option><option value=\"italic\">Itálica</option><option value=\"bold\">Negrilla</option></select></div><div class=\"elemento_formulario_sponsor\"><label for=\"inclinacion_input\">Inclinación (en grados °): </label><div id=\"opcion_inclinacion_input\"></div><input type=\"hidden\" id=\"inclinacion_input\" name=\"inclinacion_input\" value=\"\"></div><div class=\"elemento_formulario_sponsor\"><label for=\"\">Posición:</label><div class=\"promo_btn_gran_container\"><div class=\"promo_btn_mini_container\"><span class=\"promo_btn_posicion\"><i class=\"fas fa-arrow-alt-circle-up\"></i></span><span class=\"promo_btn_posicion\"><i class=\"fas fa-arrow-alt-circle-down\"></i></span></div><div class=\"promo_btn_mini_container\"><span class=\"promo_btn_posicion\"><i class=\"fas fa-arrow-alt-circle-left\"></i></span><span class=\"promo_btn_posicion\"><i class=\"fas fa-arrow-alt-circle-right\"></i></span></div></div><input type=\"hidden\" name=\"promo_top_position\" id=\"promo_top_position\" class=\"promo_top_position\" value=\"\"><input type=\"hidden\" name=\"promo_left_position\" id=\"promo_left_position\" class=\"promo_left_position\" value=\"\"></div><div class=\"galeria_colores_contenedor_texto\"><p style=\"text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default\">Color del Texto</p><div class=\"galeria_colores\"><span class=\"color_texto_opcion\" style=\"background-color: rgba(142, 36, 170);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(94, 53, 177);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(57, 73, 171);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(30, 136, 229);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(3, 155, 229);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(0, 172, 193);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(0, 137, 123);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(67, 160, 71);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(124, 179, 66);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(251, 192, 45);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(255, 143, 0);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(239, 108, 0);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(216, 67, 21);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(211, 47, 47);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(216, 27, 96);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(208, 0, 0);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(93, 64, 55);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(158, 158, 158);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(97, 97, 97);\"></span><span class=\"color_texto_opcion\" style=\"background-color: rgba(0, 0, 0);\"></span></div><input type=\"hidden\" name=\"opcion_colores_input\" id=\"opcion_colores_input\" value=\"\"></div>");

      iniciar_spinners_y_sliders();

      if ($(this).val() !== '') {//si se escogio una fecha
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
      }else {
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
        $('.configuracion_sponsor_paquete2').empty();
    };

    });

// ############################### CODIGO RESPUESTA INPUTS PROPIOS DE LA PROMO SPONSOR ###########################################

 $(".configuracion_sponsor_paquete2").on('change', '#promo_var1', function(){
   var promo_var1 = $(this).val();
   $(".promo_cuadro1_texto1").html(promo_var1);

       if ($(this).val() !== '') {//si se escogio una fecha
         if ($(this).parent().parent().children("label").children('i').length) {//si el DOT de verif existe //si es un spinner
           $(this).parent().parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
         };
         if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe // si es un select
           $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
         };
       }else {
         if ($(this).parent().parent().children("label").children('i').length) {//si el DOT de verif existe //si es un spinner
           $(this).parent().parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
         };
         if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe //si es un select
           $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
         };
       };
 });

 $(".configuracion_sponsor_paquete2").on('change', '#promo_var2', function(){
   var promo_var2 = $(this).val();
   $(".promo_cuadro1_texto2").html(promo_var2);

   if ($(this).val() !== '') {//si se escogio una fecha
     if ($(this).parent().parent().children("label").children('i').length) {//si el DOT de verif existe //si es un spinner
       $(this).parent().parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
     };
     if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe //si es un select
       $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
     };
   }else {
     if ($(this).parent().parent().children("label").children('i').length) {//si el DOT de verif existe //si es un spinner
       $(this).parent().parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
     };
     if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe //si es un select
       $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
     };
   };
 });

 $(".configuracion_sponsor_paquete2").on('change', '#promo_var3', function(){
   var promo_var3 = $(this).val();
   $(".promo_cuadro2_texto1").html(promo_var3);

     if ($(this).val() !== '') {//si se escogio una fecha
       if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
         $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
       };
     }else {
       if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
         $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
       };
     };
 });

 $(".configuracion_sponsor_paquete2").on('click', '.promo_var4_radio:not(.active)', function(){

   if ($(this).hasClass('opcion_vertical')) {
     $(".info_promo_1_container").css("flex-direction", "column");
     $(".promo_cuadro1_texto2").css("margin-right", "0em");
     $("#promo_var4").attr("value", "column");
   }else {
     $(".info_promo_1_container").css("flex-direction", "row");
     $(".promo_cuadro1_texto2").css("margin-right", "0.3em")
     $("#promo_var4").attr("value", "row");
   }

 });

 $(".configuracion_sponsor_paquete2").on('change', '#font_size1_input', function(){
   var font_size_1 = (($(this).val()) / 16) + "em";
   $(".promo_cuadro1_texto1").css("font-size", font_size_1);
   $(".promo_cuadro1_texto2").css("font-size", font_size_1);
   $(".promo_tipo_2_x").css("font-size", font_size_1);
 });

 $(".configuracion_sponsor_paquete2").on('change', '#font_size2_input', function(){
   var font_size_2 = (($(this).val()) / 16) + "em";
   $(".promo_cuadro2_texto1").css("font-size", font_size_2);
 });

 $(".configuracion_sponsor_paquete2").on('change', '#tipo_texto', function(){
   var font_weight_all = $(this).val();
   if (font_weight_all == 'bold' || font_weight_all == 'normal') {
     $(".promo_cuadro1_texto1").css("font-weight", font_weight_all).css("font-style", 'normal');
     $(".promo_cuadro1_texto2").css("font-weight", font_weight_all).css("font-style", 'normal');
     $(".promo_cuadro2_texto1").css("font-weight", font_weight_all).css("font-style", 'normal');
   }else {
     $(".promo_cuadro1_texto1").css("font-weight", 'normal').css("font-style", font_weight_all);
     $(".promo_cuadro1_texto2").css("font-weight", 'normal').css("font-style", font_weight_all);
     $(".promo_cuadro2_texto1").css("font-weight", 'normal').css("font-style", font_weight_all);
   }

 });

 $(".configuracion_sponsor_paquete2").on('change', '#inclinacion_input', function(){
  var inclinacion = "rotate(" + $(this).val() + "deg)";
  $(".info_promo_1_container").css("transform", inclinacion);
 });

 $(".configuracion_sponsor_paquete2").on('click', '.promo_btn_posicion i.fa-arrow-alt-circle-up', function(){
  var top_current_position = parseInt($(".info_promo_1_container").css("top").replace('px', ''));
  var new_position = (top_current_position / 16) - 0.1;
  var top_new_position = new_position + 'em';
  if(new_position > 0){
    $(".info_promo_1_container").css("top", top_new_position);
    $("#promo_top_position").attr('value', top_new_position);
  };
  if(new_position < 0){
    $(".info_promo_1_container").css("top", "0em");
    $("#promo_top_position").attr('value', "0em");
  };
 });

 $(".configuracion_sponsor_paquete2").on('click', '.promo_btn_posicion i.fa-arrow-alt-circle-down', function(){
  var top_current_position = parseInt($(".info_promo_1_container").css("top").replace('px', ''));
  var top_new_position = ((top_current_position / 16) + 0.1) + 'em';
  $(".info_promo_1_container").css("top", top_new_position);
  $("#promo_top_position").attr('value', top_new_position);
 });

 $(".configuracion_sponsor_paquete2").on('click', '.promo_btn_posicion i.fa-arrow-alt-circle-left', function(){
  var left_current_position = parseInt($(".info_promo_1_container").css("left").replace('px', ''));
  var new_position = (left_current_position / 16) - 0.1;
  var left_new_position = new_position + 'em';
  if(new_position > 0){
    $(".info_promo_1_container").css("left", left_new_position);
    $("#promo_left_position").attr('value', left_new_position);
  };
  if(new_position < 0){
    $(".info_promo_1_container").css("left", "0em");
    $("#promo_left_position").attr('value', "0em");
  };

 });

 $(".configuracion_sponsor_paquete2").on('click', '.promo_btn_posicion i.fa-arrow-alt-circle-right', function(){
  var left_current_position = parseInt($(".info_promo_1_container").css("left").replace('px', ''));
  var left_new_position = ((left_current_position / 16) + 0.1) + 'em';
    $(".info_promo_1_container").css("left", left_new_position);
    $("#promo_left_position").attr('value', left_new_position);
 });

 $(".configuracion_sponsor_paquete2").on('click', '.color_texto_opcion', function(){
   var color_picked = $(this).css("background-color");

   $(".promo_cuadro1_texto1").css("color", color_picked);
   $(".promo_cuadro1_texto2").css("color", color_picked);
   $(".promo_tipo_2_x").css("color", color_picked);
   $(".promo_cuadro2_texto1").css("color", color_picked);

   $("span.color_texto_opcion").css('border', '2px solid rgb(255, 255, 255)');//se reinicializa el borde de los thumbs
   $(this).css('border', '2px solid rgb(153, 153, 152)');//se marca como elegido solo al que se le dio click
   $("#opcion_colores_input").val(color_picked);//se guarda el valor clickeado en el input hidden
 });

 $(".configuracion_sponsor_paquete3").on('input', '#promo_info1', function(){
   var info_texto1 = $(this).val();
   $(".promo_info_texto1").html(info_texto1);

   if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
     if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
       $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
     };
     if ($(this).val().match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&#%*:@!?¡¿\/,.\']+$/) == null) {//Si se ingrso un caracter no permitido
       alert('Simbolo/Caracter no permitido');
       $(this).css('border-color', 'rgb(255, 0, 0) ')
     }else {
       $(this).css('border-color', 'initial')
     }
   }else {
     if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
       $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
     };
   }
 });

 $(".configuracion_sponsor_paquete3").on('input', '#promo_info2', function(){
   var info_texto2 = $(this).val();
   $(".promo_info_texto2").html(info_texto2);

   if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
     if ($(this).val().match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&#%*:@!?¡¿\/,.\']+$/) == null) {//Si se ingrso un caracter no permitido
       alert('Simbolo/Caracter no permitido');
       $(this).css('border-color', 'rgb(255, 0, 0) ')
     }else {
       $(this).css('border-color', 'initial')
     };
   };
 });

 $(".galeria_ilustraciones").on("click", "span.ilustracion", function(){
     var ilustracion_picked = $(this).children("img").attr('src');
     var ilustracion_url = "url(" + ilustracion_picked + ")";

     $("span.ilustracion").css('border', '3px solid rgb(255, 255, 255)');//se reinicializa el borde de los thumbs
     $(this).css('border', '3px solid rgb(153, 153, 152)');//se marca como elegido solo al que se le dio click
     $("#galeria_ilustraciones_input").val(ilustracion_picked);//se guarda el valor clickeado en el input hidden

     $(".ilustracion_fondo").css("background-image", ilustracion_url);

     if ($(".galeria_ilustraciones_contenedor p i").length) {//si el DOT de verif existe
     $(".galeria_ilustraciones_contenedor p i").css('color', 'rgb(68, 235, 54)');
     };
 });


// CODIGO DATEPICKER ###########################################

    var fecha_vencimiento_contrato = $("#fecha_vencimiento_contrato_sponsor").val();

    $( "#fecha_vencimiento" ).datepicker({//esto funciona con jqueryUI a tener en el header del .view
        changeMonth: true,
        changeYear: true,
        maxDate: fecha_vencimiento_contrato,
        dateFormat: "yy/mm/dd",
        onSelect: function(dateText, datePicker) {
       $(this).attr('value', dateText);
       $(".validez_cupon span").html(dateText);
       if ($(this).val() !== '') {//si se escogio una fecha
         if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
           $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
         };
       }else {
         if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
           $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
         };
     };
    }
    });

// SPINNER Y SLIDERS #####################################################

    function iniciar_spinners_y_sliders(){
      if ($(".tipo1_promo_var1").length > 0) {
        $(".tipo1_promo_var1").spinner({
          min: 5,
          step: 5
        });
      };

      if ($(".tipo2_promo_var1").length > 0) {
        $(".tipo2_promo_var1").spinner({
          min: 2,
          step: 1
        });
      };

      if ($(".tipo2_promo_var2").length > 0) {
        $(".tipo2_promo_var2").spinner({
          min: 1,
          step: 1
        });
      };

      if ($("#opcion_font_size_input").length > 0) {
        $("#opcion_font_size_input").slider({
            range: "min",
            value: 30,
            min: 30,
            max: 100,
            slide: function( event, ui ) {
              $( "#font_size1_input" ).val( ui.value ).change();
            }
          });

          var font_size_1_default = (($("#opcion_font_size_input").slider("value")) / 16) + "em";
          $(".promo_cuadro1_texto1").css("font-size", font_size_1_default);
          $(".promo_cuadro1_texto2").css("font-size", font_size_1_default);
          $(".promo_tipo_2_x").css("font-size", font_size_1_default);
      };

      if ($("#opcion_font_size_input2").length > 0) {
        $("#opcion_font_size_input2").slider({
            range: "min",
            value: 30,
            min: 20,
            max: 100,
            slide: function( event, ui ) {
              $( "#font_size2_input" ).val( ui.value ).change();
            }
          });

          var font_size_2_default = (($("#opcion_font_size_input2").slider("value")) / 16) + "em";
          $(".promo_cuadro2_texto1").css("font-size", font_size_2_default);
      };

      if ($("#opcion_inclinacion_input").length > 0) {
        $("#opcion_inclinacion_input").slider({
            range: "min",
            value: 0,
            min: -90,
            max: 90,
            slide: function( event, ui ) {
              $( "#inclinacion_input" ).val( ui.value ).change();
            }
          });
          var inclinacion_default = "rotate(" + $("#opcion_inclinacion_input").slider("value") + "deg)";
          $(".info_promo_1_container").css("transform", inclinacion_default);
      };

      $('.ui-spinner-button').click(function() {
         $(this).siblings('input').change();
      });

    };

// SLIDER y BOTONES FIJOS DEL FORMULARIO  PARA CAMBIAR LOS TEXTOS INFO DEL CUPON#########################################

  // SLIDER FONT SIZE TEXTO INFO
  $("#promo_info_font_size").slider({
      range: "min",
      value: 16,
      min: 10,
      max: 30,
      slide: function( event, ui ) {
        $( "#promo_info_font_size_input" ).val( ui.value ).change();
      }
    });

    var font_size_info_default = (($("#promo_info_font_size").slider("value")) / 16) + "em";
    $(".promo_info_texto1").css("font-size", font_size_info_default);
    $(".promo_info_texto2").css("font-size", font_size_info_default);

    $(".configuracion_sponsor_paquete3").on('change', '#promo_info_font_size_input', function(){
      var font_size_info = (($(this).val()) / 16) + "em";
      $(".promo_info_texto1").css("font-size", font_size_info);
      $(".promo_info_texto2").css("font-size", font_size_info);
    });


    // BOTONES DE POSICION DEL TEXTO INFO
    $(".configuracion_sponsor_paquete3").on('click', '.promo_info_btn_posicion i.fa-arrow-alt-circle-left', function(){
     var left_padding_current_position = parseInt($(".info_promo_2_container").css("padding-left").replace('px', ''));
     var new_padding = (left_padding_current_position / 16) - 0.1;
     var left_padding_new_position = new_padding + 'em';
     if(new_padding > 0){
       $(".info_promo_2_container").css("padding-left", left_padding_new_position);
       $("#promo_info_posicion").attr('value', left_padding_new_position);
     };
     if(new_padding < 0){
       $(".info_promo_2_container").css("padding-left", '0em');
       $("#promo_info_posicion").attr('value', '0em');
     };
    });

    $(".configuracion_sponsor_paquete3").on('click', '.promo_info_btn_posicion i.fa-arrow-alt-circle-right', function(){
     var left_padding_current_position = parseInt($(".info_promo_2_container").css("padding-left").replace('px', ''));
     var new_padding = (left_padding_current_position / 16) + 0.1;
     var left_padding_new_position = new_padding + 'em';
     if(new_padding > 0){
       $(".info_promo_2_container").css("padding-left", left_padding_new_position);
       $("#promo_info_posicion").attr('value', left_padding_new_position);
     };
     if(new_padding < 0){
       $(".info_promo_2_container").css("padding-left", '0em');
       $("#promo_info_posicion").attr('value', '0em');
     };
    });

    $(".configuracion_sponsor_paquete1").on('click', '.promo_info_sponsor_btn_posicion i.fa-arrow-alt-circle-right', function(){
     var right_padding_current_position = parseInt($(".popup_sponsor_info").css("right").replace('px', ''));
     var new_right = (right_padding_current_position / 16) - 0.1;
     var right_padding_new_position = new_right + 'em';
     if(new_right > 0){
       $(".popup_sponsor_info").css("right", right_padding_new_position);
       $("#promo_info_sponsor_posicion").attr('value', right_padding_new_position);
     };
     if(new_right < 0){
       $(".popup_sponsor_info").css("right", '0em');
       $("#promo_info_sponsor_posicion").attr('value', '0em');
     };
    });

    $(".configuracion_sponsor_paquete1").on('click', '.promo_info_sponsor_btn_posicion i.fa-arrow-alt-circle-left', function(){
     var right_padding_current_position = parseInt($(".popup_sponsor_info").css("right").replace('px', ''));
     var new_right = (right_padding_current_position / 16) + 0.1;
     var right_padding_new_position = new_right + 'em';
     if(new_right > 0){
       $(".popup_sponsor_info").css("right", right_padding_new_position);
       $("#promo_info_sponsor_posicion").attr('value', right_padding_new_position);
     };
     if(new_right < 0){
       $(".popup_sponsor_info").css("right", '0em');
       $("#promo_info_sponsor_posicion").attr('value', '0em');
     };
    });



// CODIGO PARA CAMBIAR DINAMICAMENTE EL COLOR DEL BORDE DE LAS PREVISUALISACIONES ##################

  $(".galeria_colores").on("click", "span.color_borde", function(){
      var color_picked = $(this).css("background-color");

      $(".popup_sponsor").css("background-color", color_picked);//se carga el borde a las previsualizaciones
      $("span.color_borde").css('border', '3px solid rgb(255, 255, 255)');//se reinicializa el borde de los thumbs
      $(this).css('border', '3px solid rgb(153, 153, 152)');//se marca como elegido solo al que se le dio click
      $("#galeria_colores_input").val(color_picked);//se guarda el valor clickeado en el input hidden

  });

// CODIGO PARA CONTROLAR CHECKBOXRADIO CASERO ###########################################

  $(".configuracion_sponsor_paquete2").on("click", ".promo_var4_radio", function(){
    $(".promo_var4_radio.active").removeClass('active');
    $(this).addClass('active');
  });

// CODIGO PARA EL BOTON DE EXTRA SEGURIDAD ###############################################

  $(".seguridad_extra_btn").on("click", function(){
    $(".seguridad_extra_btn").toggleClass('active');
    if ($(this).hasClass('active')) {
      $("#seguridad_extra").attr('value', 'SI');
    }else {
      $("#seguridad_extra").attr('value', '') ;
    }

  });

// CODIGO PARA CERRAR EL POPUP DE ERRORES##########################################################################

  $(".popup_errores_cerrar").on("click", function(){
    $(".popup_errores").css('visibility', 'hidden');
  });

// CODIGO QUE CONTROLA LOS DATOS DESPUES DE PRESIONAR "GUARDAR BORRADOR" o "VALIDAR DATOS" o "REGISTRAR DATOS"###################
  function check_empty_inputs(param){
        var errores = '';

        $(".input_obligatorio").each(function(){//CHECK si no existen campos mal rellenados
          var input_chars_filter = $(this).css('border-color');

          if (input_chars_filter == 'rgb(255, 0, 0)') {
            errores = "error";
          };

        });

        $(".input_obligatorio").each(function() {//CHECK si todos los campos obligatorios contienen valores
          var input_val = $(this).val();
          if (input_val == '') {
            errores = "error";
            if ($(this).parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
              $(this).parent().children("label").prepend("<i class='fa fa-circle'></i>");
            };
          };

        });

        $(".input_obligatorio_spinner").each(function() {//CHECK si todos los campos obligatorios contienen valores
          var input_val = $(this).val();
          if (input_val == '') {
            errores = "error";
            if ($(this).parent().parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
              $(this).parent().parent().children("label").prepend("<i class='fa fa-circle'></i>");
            };
          };

        });


        if ($(".input_ilustracion_obligatorio").val() == '') {//CHECK si se eligio una ilustracion
          errores = "error";
          if ($(".galeria_ilustraciones_contenedor p i").length == 0) {//si el DOT de verif NO existe
            $(".galeria_ilustraciones_contenedor p").prepend("<i class='fa fa-circle'></i>");
          };
        };

        if($("#fecha_vencimiento").val() == ''){//CHECK si se eligio una fecha de vencimiento
          errores = "error";
          if ($("#fecha_vencimiento").parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
            $("#fecha_vencimiento").parent().children("label").prepend("<i class='fa fa-circle'></i>");
          };
        }else {//CHECK SI EL AÑO NO SUPERA AL CONTRATO
          var fecha_nueva = $("#fecha_vencimiento").val().split("/");
          var fecha_contrato = $("#fecha_vencimiento_contrato_sponsor").val().split("/");


          if (parseInt(fecha_nueva[0]) > parseInt(fecha_contrato[0])) {
            errores = "error";
            if ($("#fecha_vencimiento").parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
              $("#fecha_vencimiento").parent().children("label").prepend("<i class='fa fa-circle'></i>");
            };
          }else {//CHECK SI EL MES NO SUPERA AL CONTRATO
            if (parseInt(fecha_nueva[1]) > parseInt(fecha_contrato[1])) {
              errores = "error";
              if ($("#fecha_vencimiento").parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
                $("#fecha_vencimiento").parent().children("label").prepend("<i class='fa fa-circle'></i>");
              };
            }else {//CHECK SI EL DIA NO SUPERA AL CONTRATO
              if (parseInt(fecha_nueva[2]) > parseInt(fecha_contrato[2])) {
                errores = "error";
                if ($("#fecha_vencimiento").parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
                  $("#fecha_vencimiento").parent().children("label").prepend("<i class='fa fa-circle'></i>");
                };
            };
          };
        };

      };



        if (errores !== '') {//LAST CHECK - si durante los check hubo reporte de errores
          $(".popup_errores").css('visibility', 'visible');
          $("#boton_validar_form").css('display', 'flex');
          $("#boton_submit_form").css('display', 'none');
        }else {//SI NO HUBO NINGUN ERROR
          $("#boton_validar_form").css('display', 'none');
          $("#boton_submit_form").css('display', 'flex');
          if (param == 'registrar') {
            $("#formulario_registro_cupon_sponsor").submit();
          };
        };
  };



  $("#boton_fin_formulario_contenedor").on("click", "button.boton_fin_formulario", function(){
    var button_pushed = $(this).val();

      if (button_pushed == 'validar_datos') {// si se apretó en Validar datos
        check_empty_inputs('validar');//lanzar el chequeo total
      };

      if (button_pushed == 'registar_datos') {// si se apretó en Registrar Sponsor
        check_empty_inputs('registrar');//lanzar el chequeo total
      };


    });


  });
});
