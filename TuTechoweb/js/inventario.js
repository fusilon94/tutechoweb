$(document).ready(function () {
  jQuery(function ($) {

    $("#agencia").selectmenu();//SE INITIALIZA EL SELECTMENU

    function refresh_inventario(agencia, modo) {//SE DEFINE LA FUNCION PARA TRAER LOS ITEM DE UNA AGENCIA
      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { agenciaChoice: agencia, modoChoice: modo }
      }).done(function (data) {
        $(".item_list").html(data);
      });
    };

    refresh_inventario(agencia_first, first_mode); //FIRST ENTRY CHARGE OF RESULTS

    function refresh_items_en_uso(agencia){
      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { agenciaItems: agencia}
      }).done(function (data) {
        $(".item_usados_list").html(data);
      });
    };

    $("#agencia").on("selectmenuchange", function(){//SE TRAE LOS ITEM AL CAMBIAR DE AGENCIA
      let modo_val = $("#agencia option:selected").attr("acceso");
      let modo_selected;
      if (modo_val == 1) {
        modo_selected = 'edit';
      }else{
        modo_selected = 'read';
      }
      let agencia_selected = $("#agencia option:selected").val();

      refresh_inventario(agencia_selected, modo_selected);
      refresh_items_en_uso(agencia_selected);
    });


    $(".tab_choice").on("click", function () {//CODIGO AL CAMBIAR DE TAB
      if (!$(this).hasClass("active")) {
        $(".tab_choice").toggleClass("active");
        $(".resultados_inventario").toggleClass("visible");
        $(".resultados_items_usados").toggleClass("visible");
      };

      let agencia_actual = $("#agencia option:selected").val();

      refresh_items_en_uso(agencia_actual);
    });


    $(".item_list").on("click", ".escoger_btn", function(){
      if (!$(this).hasClass("apagado")) {
        

        let group_item = $(this).parent().parent().attr("name");

        $.ajax({
          type: "POST",
          url: "process-request-refresh-inventario.php",
          data: { group_info_requested: group_item }
        }).done(function (data) {
          $(".popup_content").html(data);
          $(".overlay_popup").toggleClass("opened");
          $("body").toggleClass("popup_active");
        });
      };

    });

    $(".popup_content").on("click", ".opcion_btn.escoger", function(){

      const option_clicked = $(this).attr("name");
      $(".id_option_title").html(option_clicked);
      $(".referencia_localizacion").val("");
      $(".opciones_container").toggleClass("choice_done", 300);      
      
    });

    $(".popup_content").on("click", ".back_btn_retiro_form", function(){
      $(".opciones_container").toggleClass("choice_done", 300);
    });

    $(".popup_content").on("click", ".retirar_confirmar_btn", function(){
      const id = $(".id_option_title").text();
      const referencia_bien = $('.referencia_localizacion').val();

      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { 
          item_id: id,
          referencia_bien: referencia_bien,
          agente_id: agente_id
        }
      }).done(function (data) {
        if (data == "ERROR REFERENCIA") {
          alert("REFERENCIA INEXISTENTE");
        };
        if(data == "EXITO"){
          alert("Exito");
          $(".overlay_popup").toggleClass("opened");
          $("body").toggleClass("popup_active");
          $(".popup_content").empty();
          window.location.reload();
        };
      });

    });

    $(".item_list").on("click", ".editar_btn", function(){

      const group_code_ask = $(this).parent().parent().attr("name");

      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { group_edition_requested: group_code_ask }
      }).done(function (data) {
        $(".popup_content").html(data);
        $(".overlay_popup").toggleClass("opened");
        $("body").toggleClass("popup_active");
      });

    });

    $(".popup_content").on("click", ".opcion_btn.edicion", function(){

      const option_clicked = $(this).attr("name");
      $(".id_option_title").html(option_clicked);
      if($(this).hasClass("out")){
        $(".prestar_btn").addClass("inactive");
      };
      $(".opciones_container").toggleClass("choice_edicion1", 300);      
      
    });

    $(".popup_content").on("click", ".back_btn_edicion_list", function(){
      $(".opciones_container").toggleClass("choice_edicion1", 300);
      $(".remover_confirmar_btn").removeClass("active");
      $(".prestar_btn").removeClass("inactive");
    });

    $(".popup_content").on("click", ".back_btn_edicion_options", function(){
      $(".opciones_container").toggleClass("choice_edicion2", 300);
      $(".remover_confirmar_btn").removeClass("active");
    });

    $(".popup_content").on("click", ".remover_btn", function(){
      const btn_trash = $(this).parent().find(".remover_confirmar_btn");
      btn_trash.toggleClass("active", 300);
    });

    $(".popup_content").on("click", ".prestar_btn", function(){
      if ($(this).hasClass("inactive") == false) {
        $(".referencia_localizacion").val("");
        $(".id_prestatario").val("");
        $(".opciones_container").toggleClass("choice_edicion2", 300);
      };
    });

    $(".popup_content").on("click", ".back_btn_prestamo", function(){
      $(".opciones_container").toggleClass("choice_edicion3", 300);
    });

    $(".popup_content").on("click", ".verificar_prestamo_btn", function(){
      const referencia_verificar = $(".referencia_localizacion").val();
      const agente_verificar = $(".id_prestatario").val();

      if (referencia_verificar == '' || agente_verificar == '') {
        alert("Quedan datos por llenar")
      } else {
        $.ajax({
          type: "POST",
          url: "process-request-refresh-inventario.php",
          data: { referencia_check: referencia_verificar, agente_check: agente_verificar }
        }).done(function (data) {
          if (data == "ERROR DE DATOS") {
            alert(data);
          }else{
            $(".info_prestatario_lista").html(data);
            $(".opciones_container").toggleClass("choice_edicion3", 300);
          };
        });
      };

    });

    $(".popup_content").on("click", ".confirmar_prestamo_btn", function(){
      const item_prestamo = $(".id_option_title.unique").text();
      const referencia_prestamo = $(".referencia_localizacion").val();
      const agente_prestamo = $(".id_prestatario").val();

      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { item_prestamo: item_prestamo, referencia_prestamo: referencia_prestamo, agente_prestamo: agente_prestamo }
      }).done(function (data) {
        if(data == "EXITO"){
          alert("Exito");
          $(".overlay_popup").toggleClass("opened");
          $("body").toggleClass("popup_active");
          $(".popup_content").empty();
          window.location.reload();
        };
      });

    });

    $(".popup_content").on("click", ".remover_confirmar_btn", function(){
      const item_remover = $(".id_option_title.unique").text();

      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { item_remover: item_remover }
      }).done(function (data) {
        if(data == "EXITO"){
          alert("Item Removido");
          $(".overlay_popup").toggleClass("opened");
          $("body").toggleClass("popup_active");
          $(".popup_content").empty();
          window.location.reload();
        };
      });

    });

    $(".item_list").on("click", ".agregar_btn", function(){
      
      const group_code_ask = $(this).parent().parent().attr("name");

      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { group_add_code_requested: group_code_ask }
      }).done(function (data) {
        $(".popup_content").html(data);
        $(".overlay_popup").toggleClass("opened");
        $("body").toggleClass("popup_active");
      });

    });

    $(".popup_content").on("click", ".agregar_item_suplementario_confirmar", function(){

      if ($(".nuevo_id_costo").val() !== '') {
        const item_new_code = $(".nuevo_id_item").val();
        const item_new_group = $(".hidden_group_item").val();
        const item_new_agencia = $("#agencia option:selected").val();
        const item_new_costo = $(".nuevo_id_costo").val();

        $.ajax({
          type: "POST",
          url: "process-request-refresh-inventario.php",
          data: { id_new_item: item_new_code, id_new_group: item_new_group, id_new_agencia: item_new_agencia, id_new_costo: item_new_costo }
        }).done(function (data) {
          alert(data);
            $(".overlay_popup").toggleClass("opened");
            $("body").toggleClass("popup_active");
            $(".popup_content").empty();
            window.location.reload();
        });
      }else{
        alert("Debe especificar un costo");
      };
      
    });

    $(".item_usados_list").on("click", ".extra_info_btn", function(){
      
      const id_item = $(this).parent().parent().attr("name");
    
      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { id_info_requested: id_item }
      }).done(function (data) {
        $(".popup_content").html(data);
        $(".overlay_popup").toggleClass("opened");
        $("body").toggleClass("popup_active");
      });

    });

    $(".agregar_nuevo_item_btn").on("click", function(){
      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { new_item_requested_agencia: "YES" }
      }).done(function (data) {
        $(".popup_content").html(data);
        $(".overlay_popup").toggleClass("opened");
        $("body").toggleClass("popup_active");
      });
    });

    $(".popup_content").on("click", ".agregar_nuevo_item_confirmar", function(){
      if ($(".nuevo_id_costo").val() !== '' &&  $(".nuevo_id_descipcion").val() !== '' && $(".nuevo_id_dimensiones").val() !== '') {
        const item_new_code = $(".nuevo_id_item").val();
        const item_new_group = $(".hidden_group_item").val();
        const item_new_agencia = $("#agencia option:selected").val();
        const item_new_costo = $(".nuevo_id_costo").val();
        const item_new_descripcion = $(".nuevo_id_descipcion").val();
        const item_new_dimensiones = $(".nuevo_id_dimensiones").val();

        $.ajax({
          type: "POST",
          url: "process-request-refresh-inventario.php",
          data: { new_item_id: item_new_code, new_item_group: item_new_group, new_item_agencia: item_new_agencia, new_item_costo: item_new_costo, new_item_descripcion: item_new_descripcion, new_item_dimensiones: item_new_dimensiones }
        }).done(function (data) {
            alert(data);
            $(".overlay_popup").toggleClass("opened");
            $("body").toggleClass("popup_active");
            $(".popup_content").empty();
            window.location.reload();
        });

      }else{
        alert("Quedan campos por llenar");
      };
    });

    $(".retornar_btn").on("click", function(){

      $.ajax({
        type: "POST",
        url: "process-request-refresh-inventario.php",
        data: { retornar_popup_request: "YES" }
      }).done(function (data) {
          $(".popup_content").html(data);
          $(".overlay_popup").toggleClass("opened");
          $("body").toggleClass("popup_active");
      });
      
    });

    $(".popup_content").on("click", ".retornar_item_confirmar", function(){
      if ($(".id_item_retornar").val() !== '' && $(".retorno_estado").val() !== '') {
        const id_retornar = $(".id_item_retornar").val();
        const estado_retornar = $(".retorno_estado option:selected").val();
        const comentario_retornar = $(".retorno_comentario").val();

        $.ajax({
          type: "POST",
          url: "process-request-refresh-inventario.php",
          data: { retornar_id: id_retornar, retornar_estado: estado_retornar, retornar_comentario: comentario_retornar, agente_retorno: agente_id }
        }).done(function (data) {
          alert(data);
          $(".overlay_popup").toggleClass("opened");
          $("body").toggleClass("popup_active");
          $(".popup_content").empty();
          window.location.reload();
        });

      } else {
        alert("Quedan campos por llenar");
      };
    });

    $(".popup_cerrar").on("click", function(){
      $(".overlay_popup").toggleClass("opened");
      $("body").toggleClass("popup_active");
      $(".popup_content").empty();
    });


  });
});
