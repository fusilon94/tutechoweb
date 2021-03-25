$(document).ready(function(){
  jQuery(function($){

    $(".boton_borrador_formulario").on("click", function(){
        const key_selected = $(this).attr("key");
        const agencia_tag_selected = $(this).attr("agencia_tag");
        const referencia_selected = $(this).attr("referencia");

        $("#key_selected").val(key_selected);
        $("#agencia_tag_selected").val(agencia_tag_selected);
        $("#referencia_selected").val(referencia_selected);

        $("#open_file").submit();
    });

  });
});
