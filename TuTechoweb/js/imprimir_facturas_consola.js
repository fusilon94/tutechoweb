$(document).ready(function(){
  jQuery(function($){

    $(".boton_file").on("click", function(){

        const id_factura = $(this).attr("id");
        const tipo_factura = $(this).attr("tipo");
        const referencia_inmueble = $(this).attr("referencia");
        const fecha_factura = $(this).attr("fecha");

        if (id_factura == '' || tipo_factura == '' || fecha_factura == '') {
            alert('Error de sistema');
            return;
        };

        $("#id_factura").val(id_factura);
        $("#tipo_facrura").val(tipo_factura);
        $("#referencia_inmueble").val(referencia_inmueble);
        $("#fecha_factura").val(fecha_factura);

        $("#open_file").submit();

    });

  });
});
