$(document).ready(function(){
  jQuery(function($){

    const modo = (agencia_id == '') ? 'new' : 'edit';

    console.log(modo)
    $.ajax({
        type: "POST",
        url: "process-request-crear-agencia.php",
        data: { modo_sent: modo, agencia_id_sent: agencia_id },
        async: false
    }).done(function(data){

      $(".datos_contenedor").html(data);

    });

// CODIGO VERIFICACION REGEX #########################################################

  $("#direccion").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

  $("#direccion_complemento").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

  $("#telefono").on('input', function(){
    if ($(this).val().match(/^[+\-0-9().# \/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

  $("#nit").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&#\/,.\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

// CODIGO BOTON CREAR AGENCIA ########################################################

  $(".boton_crear_agencia").on('click', function(){
    const departamento = $(".departamento option:selected").val();
    const ciudad = $(".ciudad option:selected").val();
    const direccion = $("#direccion").val();
    const complemento = $("#direccion_complemento").val();
    const telefono = $("#telefono").val();
    const nit = $("#nit").val();
    const lat = $("#mapa_coordenada_lat").val();
    const lng = $("#mapa_coordenada_lng").val();
    const zoom = $("#mapa_zoom").val();
    const foto = $("#foto").val();
    const foto2 = $("#foto2").val();

    if (departamento == '' || ciudad == '' || direccion == '' || complemento == '' || telefono == '' || nit == '' || lat == '' || lng == '' || zoom == '' || (foto == '' && foto2 == '')) {
      $(".popup_success_text").html('Todos los campos deben llenarse');
      $(".popup_success").css('visibility', 'unset');
    }else {
      if (direccion.match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null || complemento.match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null || telefono.match(/^[+\-0-9().# \/]+$/g) == null || nit.match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&#\/,.\']+$/) == null) {
        $(".popup_success_text").html('Caracteres no permitidos en algun campo');
        $(".popup_success").css('visibility', 'unset');
      }else {
        $("#nueva_agencia_form").submit();
      };

    };

  });



  $.uploadPreview({
      input_field: "#foto",   // Default: .image-upload
      preview_box: "#campo_foto",  // Default: .image-preview
      label_field: "#foto_label",    // Default: .image-label
      label_default: "<p>Sube una foto de la Agencia<br><span>Click or Drop</span></p>",   // Default: Choose File
      label_selected: "Cambia esta imagen",  // Default: Change File
      no_label: false                 // Default: false
  });

  $.uploadPreview({
    input_field: "#foto2",   // Default: .image-upload
    preview_box: "#campo_foto2",  // Default: .image-preview
    label_field: "#foto_label2",    // Default: .image-label
    label_default: "<p>Sube una foto de la Agencia<br><span>Click or Drop</span></p>",   // Default: Choose File
    label_selected: "Cambia esta imagen",  // Default: Change File
    no_label: false                 // Default: false
  });



  });
});
