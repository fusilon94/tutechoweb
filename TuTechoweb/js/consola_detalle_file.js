$(document).ready(function(){
  jQuery(function($){

    $(".enviar_btn").on("click", function(){
        let tipo_file = $("#tipo_file_select option:selected").val();
        let tipo_doc = $("#tipo_doc_select option:selected").val();
        let pais_file = $("#pais_select option:selected").val();

        if (tipo_file !== "" && pais_file !== "" && tipo_doc !== "") {
            $("#formulario_contratos_entry").submit();
        }else{
            $(".popup_content").html("Por Favor, rellene todos los datos.");
            $(".popup_overlay").css("visibility", "unset");
        };
    });

    $(".popup_cerrar").on("click", function(){
        $(".popup_overlay").css("visibility", "hidden");
    });

    $(".tipo_file_select").on("change", function(){
      const tipo_file = $(".tipo_file_select option:selected").val();
      
      $(".tipo_doc_select").attr("disabled", false);

      if (tipo_file == "personal") {

        $(".tipo_doc_select").html(`<option value=""></option>`);
        if (agente == 1 || agente == 11) {
          $(".tipo_doc_select").append(`
          <option value="jefe_agencia_central">Jefe de Agencia Central</option>
          <option value="agente_inversiones">Agente Inversiones</option>
          `);
        };
        if (agente == 1 || agente == 2 || agente == 11) {
            $(".tipo_doc_select").append(`
            <option value="jefe_agencia_local">Jefe de Agencia Local</option>
          `);
        };
        if (agente == 1 || agente == 2 || agente == 11 || agente == 12) {
          $(".tipo_doc_select").append(`
          <option value="agente_express">Agente Express</option>
          <option value="agente_sponsor">Agente Sponsor</option>
        `);
        };
        $(".tipo_doc_select").append(`
          <option value="agente_inmobiliario">Agente Inmobiliario</option>
          <option value="fotografo">Fotógrafo</option>
        `);

      }else if (tipo_file == "venta"){
        $(".tipo_doc_select").html(`
        <option value=""></option>
        <option value="casa">Casa</option>
        <option value="departamento">Departamento</option>
        <option value="local">Local</option>
        <option value="terreno">Terreno</option>
        `);
        
      }else if (tipo_file == "alquiler"){
        $(".tipo_doc_select").html(`
        <option value=""></option>
        <option value="casa">Casa</option>
        <option value="departamento">Departamento</option>
        <option value="local">Local</option>
        <option value="terreno">Terreno</option>
        `);
      }else if (tipo_file == ""){
        $(".tipo_doc_select").attr("disabled", true);
      };
    });


  });
});
