$(document).ready(function(){
  jQuery(function($){

    $(".boton_file").on("click", function(){
        const id_selected = $(this).attr("id");
        const tipo_doc_selected = $(this).attr("name");
        const pais_selected = $(this).attr("data");
        const tipo_file_selected = $(this).attr("data2");

        $("#id_selected").val(id_selected);
        $("#tipo_doc_selected").val(tipo_doc_selected);
        $("#pais_selected").val(pais_selected);
        $("#tipo_file_selected").val(tipo_file_selected);

        $("#open_file").submit();
    });

  });
});
