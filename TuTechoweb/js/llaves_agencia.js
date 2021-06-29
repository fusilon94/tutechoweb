$(document).ready(function () {
  jQuery(function ($) {


  const pais_cookie = Cookies.get('tutechopais');

  let datos_pais;
  $.ajax({
    type: "POST",
    url: "../../contenido/m5/process-request-coordenadas-paises.php",
    dataType: 'json',
    async: false,
  }).done(function(data){
    datos_pais = data;
  });    
  
  $(".departamento_label").html(`${datos_pais['org_territorial']} :`);


  // Abrir llaves detalles
  $('.show_all_keys').on('click', function(){
    const agencia_id = $("#agencia").val();
    const agencia_nombre = $("#agencia option:selected").text();

    $('.popup_content').html(`
      <div class="llavero_wrap">
        <h2 class="lavero_titulo">Llavero <br> Agencia: ${agencia_nombre}</h2>
        <div class="llaves_resultados_contenedor"></div>
      </div>
    `);

    find_inmueble_llavero(agencia_id);

  })


  // #### Retirar Llaves
  $('.resultados_container').on('click', '.action_key', function(){
    const referencia_key = $(this).parent().parent().find(".boton_borrador_formulario").attr("id");
    $('.popup_content').html(`
      <div class="popup_wrap_1">
        <h2>Desea devolver la(s) llave(s) al Propietario?</h2>
        <span class="popup_boton btn_devolucion_continuar">Continuar</span>
      </div>
      <div class="popup_wrap_2">
        <h2>Devolución de Llave(s) del Inmueble ${referencia_key}</h2>
        <h3>-- Cargar Conformidad firmada (solo PDF) --</h3>
        <form id="formulario_llaves_agencia" method="post" action="llaves_agencia.php" enctype="multipart/form-data">
          <input type="file" id="conformidad_retirar" name="conformidad_retirar" accept="application/pdf">
          <input type="hidden" name="referencia_form" id="referencia_form" value="${referencia_key}">
          <input type="hidden" name="modo" id="modo" value="retirar">
        </form>
          <span class="popup_boton btn_devolucion_confirmar" referencia="${referencia_key}">Continuar</span>
      </div>
      
    `);
    $('.overlay_popup').addClass('opened');
    $("body").toggleClass("popup_active");
  });

  // RETIRAR LLAVES - LLAVERO
  $('.popup').on('click', '.action_key', function(){
    const referencia_key = $(this).parent().parent().find(".boton_borrador_formulario").attr("id");
    $('.popup_content').html(`
      <div class="popup_wrap_1">
        <h2>Desea devolver la(s) llave(s) al Propietario?</h2>
        <span class="popup_boton btn_devolucion_continuar">Continuar</span>
      </div>
      <div class="popup_wrap_2">
        <h2>Devolución de Llave(s) del Inmueble ${referencia_key}</h2>
        <h3>-- Cargar Conformidad firmada (solo PDF) --</h3>
        <form id="formulario_llaves_agencia" method="post" action="llaves_agencia.php" enctype="multipart/form-data">
          <input type="file" id="conformidad_retirar" name="conformidad_retirar" accept="application/pdf">
          <input type="hidden" name="referencia_form" id="referencia_form" value="${referencia_key}">
          <input type="hidden" name="modo" id="modo" value="retirar">
        </form>
        <span class="popup_boton btn_devolucion_confirmar">Continuar</span>
      </div>
      
    `);
    $('.overlay_popup').addClass('opened');
    $("body").toggleClass("popup_active");
  });

  $(".overlay_popup").on("click", ".btn_devolucion_continuar", function(){
    $(this).parent().toggleClass("continuar", 400);

  });



  $(".overlay_popup").on("click", ".btn_devolucion_confirmar", function(){
      $("#formulario_llaves_agencia").submit();
  });



  // #### Agregar Llaves

  $('.resultados_container').on('click', '.no_action', function(){
    const referencia_key = $(this).parent().parent().find(".boton_borrador_formulario").attr("id");
    $('.popup_content').html(`
      <div class="popup_wrap_1">
        <h2>Desea agregar llave(s) de este Inmueble?</h2>
        <span class="popup_boton btn_agregar_continuar">Continuar</span>
      </div>
      <div class="popup_wrap_2">
        <h2>Agregar Llave(s) del Inmueble ${referencia_key}</h2>
        <h3>-- Cargar Conformidad firmada --</h3>
        <form id="formulario_llaves_agencia" method="post" action="llaves_agencia.php" enctype="multipart/form-data">
          <input type="file" id="conformidad_agregar" name="conformidad_agregar" accept="application/pdf">
          <input type="hidden" name="referencia_form" id="referencia_form" value="${referencia_key}">
          <input type="hidden" name="modo" id="modo" value="agregar">
        </form>
        <span class="popup_boton btn_agregar_confirmar">Continuar</span>
      </div>
      
    `);
    $('.overlay_popup').toggleClass('opened');
    $("body").toggleClass("popup_active");
  });

  $(".overlay_popup").on("click", ".btn_agregar_continuar", function(){
    $(this).parent().toggleClass("continuar", 400);

  });



  $(".overlay_popup").on("click", ".btn_agregar_confirmar", function(){
    $("#formulario_llaves_agencia").submit();
  });


//CODIGO PARA POBLAR SELECT CIUDADES SEGUN EL DEPARTAMENTO ESCOGIDO ###############################################

$("select.departamento").change(function(){
  var departamentoSelected = $(".departamento option:selected").val();
  if (departamentoSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

      $.ajax({
          type: "POST",
          url: "process-request-ciudades.php",
          data: { departamentoChoice : departamentoSelected }
      }).done(function(data){
          $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
          $("#barrio").empty().prop('disabled', true);//se vacia y bloquea el select barrios si tenia algo
          $('.resultados_container').empty();
      });

  }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
    $("#ciudad").empty().prop('disabled', true).val('');
    $("#barrio").empty().prop('disabled', true).val('');
    $('.resultados_container').empty();
  };
});


//CODIGO PARA POBLAR SELECT BARRIOS SEGUN LA CIUDAD ESCOGIDA  Y MOSTRAR RESULTADOS SPONSORS###############################################

$("select.ciudad").change(function(){
  var ciudadSelected = $(".ciudad option:selected").val();

  if (ciudadSelected !== '') { // si hubo seleccion se cargan los barrios de la db
    $.ajax({
        type: "POST",
        url: "process-request-barrios.php",
        data: { ciudadesChoice : ciudadSelected }
    }).done(function(data){
        if (data !== '<option></option>') {//si hubo resultados entonces se pobla y activa  el select barrios
          $("#barrio").prop('disabled', false).html(data);
          $('.resultados_container').empty();
        }else { // si no hubo resultados se desactiva y vacia el select barrios
          $("#barrio").empty().prop('disabled', true);
          $('.resultados_container').empty();//se vacian los resultados
        };
    });

    $.ajax({
        type: "POST",
        url: "process-request-ciudad_poblado_check.php",
        data: { ciudad_sent : ciudadSelected }
    }).done(function(data){
      if (data == 'poblado') {
        $('.resultados_container').empty();
        find_bien_inmueble(ciudadSelected);
      };
    });

  }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
    $("#barrio").empty().prop('disabled', true).val('');
    $('.resultados_container').empty();
  };
});


  //CODIGO PARA MOSTRAR RESULTADOS SEGUN EL BARRIO ESCOGIDO ###############################################

  $("select.barrio").change(function(){
    var barrioSelected = $(".barrio option:selected").val();

    if (barrioSelected !== '') { //
      $('.resultados_container').empty();
      find_bien_inmueble(barrioSelected);
    }else {//si se escogio barrio vacio a proposito se vacian los resultados sponsor
      $('.resultados_container').empty();
    };
  });

  // CODIGO PARA BUSQUEDA SEGUN REFERENCIA ############################################################

  $(".input_referencia_btn").on("click", function(){
    var referencia_picked = $("#input_referencia").val();
    $("#input_direccion").val('');
    $("#departamento").val('');
    $("#ciudad").empty().prop('disabled', true).val('');
    $("#barrio").empty().prop('disabled', true).val('');
    find_bien_inmueble_by_reference(referencia_picked);
  });

  // CODIGO PARA BUSCAR SEGUN DIRECCION ##########################################################################

  $("#input_direccion").on("input", function(){
    var direccion_key = $("#input_direccion").val();
    if (direccion_key !== '') {
    $("#input_referencia").val('');
    $("#departamento").val('');
    $("#ciudad").empty().prop('disabled', true).val('');
    $("#barrio").empty().prop('disabled', true).val('');
    find_bien_inmueble_by_direccion(direccion_key);
    }else{
    $('.resultados_container').empty();
    };

  });


  $("#agencia").selectmenu();//SE INITIALIZA EL SELECTMENU

  $("#agencia").on("selectmenuchange", function(){//SE TRAE LOS ITEM AL CAMBIAR DE AGENCIA

  });

  $(".popup_cerrar").on("click", function(){
    $(".overlay_popup").toggleClass("opened");
    $("body").toggleClass("popup_active");
    $(".popup_content").empty();
    $('.popup').css('background-color', 'rgb(255, 255, 255)');
  });




  
  // SE DEFINE LA FUNCION QUE PERMITE TRAER LOS RESULTADOS DE LA BASE DE DATOS #####################################

  function action_selector(llave, holder){
    if (llave == 1) {
        return `<i class='fas fa-key action_key' aria-hidden='true'></i>`;
    }else{
      return '<span class="no_action"><i class="far fa-square"></i></span>';
    };
  };

  function show_holders(holder){
    if (holder !== '') {
      return `<img src="../../agentes/${pais_cookie}/${holder}/foto_plomo_min.jpg" alt="" title="ID: ${holder}">`
    }else{
      return '<span class="no_holder">-</span>';;
    };

  };

  function find_inmueble_llavero(agencia_id){
    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves-resultados.php",
        data: { agencia_llavero_sent: agencia_id },
        dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(data){
      const bienes_array = data;

      if (Object.entries(bienes_array).length > 0) {//si no es un array vacio

        bienes_array.forEach(function(bien){
          if (bien['visibilidad'] == 'visible') {
            $('.llaves_resultados_contenedor').append(`
              <div class='boton_borrador_mini_contenedor'>
                <div id='${bien['referencia']}' name='${bien['estado']}' class='boton_borrador_formulario'>
                  <i class='fas fa-search' aria-hidden='true'></i>
                  <p><span class='nombre'>${bien['referencia']} - ${bien['location_tag']}</span></p>
                </div>
                <div class='action_container'>
                  ${action_selector(bien['llave'], bien['llave_holder'])}
                </div>
              </div>
            `);
          };
        });

      };

      if ($(".llaves_resultados_contenedor .boton_borrador_mini_contenedor").length == 0) {
        $('.llaves_resultados_contenedor').append("- LLAVERO VACÍO -");
      };

      $('.overlay_popup').toggleClass('opened');
      $("body").toggleClass("popup_active");

    });

  };

  function find_bien_inmueble(barrio_param){
    const agencia_id = $("#agencia").val();
    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves-resultados.php",
        data: { barrio_sent : barrio_param, agencia_id_sent: agencia_id },
        dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(data){
      const bienes_array = data;
      const location_tag = barrio_param;
      $('.resultados_container').empty();//se vacian los resultados

      if (Object.entries(bienes_array).length > 0) {//si no es un array vacio

        $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + barrio_param + "</span>");

        bienes_array.forEach(function(bien){
          if (bien['visibilidad'] == 'visible') {
            $('.resultados_container').append(`
              <div class='boton_borrador_mini_contenedor'>
                <div id='${bien['referencia']}' name='${bien['estado']}' class='boton_borrador_formulario'>
                  <i class='fas fa-search' aria-hidden='true'></i>
                  <p><span class='nombre'>${bien['referencia']} - ${location_tag}</span></p>
                </div>
                <div class='action_container'>
                  ${action_selector(bien['llave'], bien['llave_holder'])}
                </div>
              </div>
            `);
          };
        });

      };

      if ($(".resultados_container .boton_borrador_mini_contenedor").length == 0) {
        $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
      };

    });

  };

  function find_bien_inmueble_by_reference(reference_param){
    const agencia_id = $("#agencia").val()
    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves-resultados.php",
        data: { reference_sent : reference_param, agencia_id_sent: agencia_id },
        dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(bienes_array){

      $('.resultados_container').empty();//se vacian los resultados

      if (bienes_array.length > 0) {//si no es vacio
        $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + bienes_array[0]['location_tag'] + "</span>");

          if (bienes_array[0]['visibilidad'] == 'visible') {
            $('.resultados_container').append(`
              <div class='boton_borrador_mini_contenedor'>
                <div id='${bienes_array[0]['referencia']}' name='${bienes_array[0]['estado']}' class='boton_borrador_formulario'>
                  <i class='fas fa-search' aria-hidden='true'></i>
                  <p><span class='nombre'>${bienes_array[0]['referencia']} - ${bienes_array[0]['location_tag']}</span></p>
                </div>
                <div class='action_container'>
                  ${action_selector(bienes_array[0]['llave'], bienes_array[0]['llave_holder'])}
                </div>
              </div>
            `);
          };

      };

      if ($(".resultados_container .boton_borrador_mini_contenedor").length == 0) {
        $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
      };

    });
  };

  function find_bien_inmueble_by_direccion(direccion_param){
    const agencia_id = $("#agencia").val()
    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves-resultados.php",
        data: { direccion_sent : direccion_param, agencia_id_sent: agencia_id },
        dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(bienes_array){

      $('.resultados_container').empty();//se vacian los resultados

      if (Object.entries(bienes_array).length > 0) {//si no es un array vacio

        bienes_array.forEach(function(bien){
          if (bien['visibilidad'] == 'visible') {
            $('.resultados_container').append(`
              <div class='boton_borrador_mini_contenedor'>
                <div id='${bien['referencia']}' name='${bien['estado']}' class='boton_borrador_formulario'>
                  <i class='fas fa-search' aria-hidden='true'></i>
                  <p><span class='nombre'>${bien['referencia']} - ${bien['location_tag']}</span></p>
                </div>
                <div class='action_container'>
                  ${action_selector(bien['llave'], bien['llave_holder'])}
                </div>
              </div>
            `);
          };
        });

      };

      if ($(".resultados_container .boton_borrador_mini_contenedor").length == 0) {
        $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
      };

    });
  };




  });
});
